<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\HistoryDiagnosis;
use App\Models\HistoryVaccine;
use App\Models\MedicalCenter;
use App\Models\Role;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class DoctorController extends Controller
{
  public function searchDoctor(Request $request)
  {
    $medicalCenterId = auth()->user()->medicalCenter->id;
    $page_number = intval($request->query('page_number', 1));
    $num_of_page = intval($request->query('num_of_page', 10));
    $searchTerm = $request->query('search_term', '');

    $query = Doctor::with(['account']) // Eager load the account relationship
      ->where('medical_center_id', $medicalCenterId)
      ->where(function ($query) use ($searchTerm) {
        $query->where('full_name', '=', $searchTerm)
          ->orWhere('phone', '=', $searchTerm)
          ->orWhere('CMND', '=', $searchTerm)
          ->orWhereHas('account', function ($accountQuery) use ($searchTerm) {
            $accountQuery->where('email', 'LIKE', "%{$searchTerm}%");
          });
      });

    $doctors = $query->paginate($num_of_page, ['*'], 'page', $page_number);

    return response()->json([
      'message' => 'Doctors retrieved successfully!',
      'status' => 200,
      'pagination' => [
        'loz' => 'Test',
        'total' => $doctors->total(),
        'per_page' => $doctors->perPage(),
        'current_page' => $doctors->currentPage(),
        'last_page' => $doctors->lastPage(),
        'from' => $doctors->firstItem(),
        'to' => $doctors->lastItem(),
      ],
      'data' => $doctors->items(),
    ], 200);
  }

  public function searchDeletedDoctor(Request $request)
  {
    $medicalCenterId = auth()->user()->medicalCenter->id;
    $page_number = intval($request->query('page_number', 1));
    $num_of_page = intval($request->query('num_of_page', 10));
    $searchTerm = $request->query('search_term', '');

    $query = Doctor::onlyTrashed()
      ->with(['account']) // Eager load the account relationship
      ->where('medical_center_id', $medicalCenterId)
      ->where(function ($query) use ($searchTerm) {
        $query->where('full_name', '=', $searchTerm)
          ->orWhere('phone', '=', $searchTerm)
          ->orWhere('CMND', '=', $searchTerm)
          ->orWhereHas('account', function ($accountQuery) use ($searchTerm) {
            $accountQuery->where('email', 'LIKE', "%{$searchTerm}%");
          });
      });

    $doctors = $query->paginate($num_of_page, ['*'], 'page', $page_number);

    return response()->json([
      'message' => 'Deleted doctors retrieved successfully!',
      'status' => 200,
      'pagination' => [
        'total' => $doctors->total(),
        'per_page' => $doctors->perPage(),
        'current_page' => $doctors->currentPage(),
        'last_page' => $doctors->lastPage(),
        'from' => $doctors->firstItem(),
        'to' => $doctors->lastItem(),
      ],
      'data' => $doctors->items(),
    ], 200);
  }

  public function restoreDoctor($doctor_id)
  {
    $medical_center_id = auth()->user()->medicalCenter->id;

    $doctor = Doctor::onlyTrashed()
      ->where('id', $doctor_id)
      ->where('medical_center_id', $medical_center_id)
      ->first();

    if (!$doctor) {
      return response()->json([
        'message' => 'Doctor not found or does not belong to the medical center',
        'status' => 404,
      ], 404);
    }

    $doctor->restore();

    return response()->json([
      'message' => 'Restore doctor successfully!',
      'status' => 200,
    ], 200);
  }

  // Lấy danh sách toàn bộ các bác sĩ
  public function getAllDoctors(Request $request)
  {
    // Lấy tham số phân trang từ request
    $page_number = intval($request->input('page_number', 1));
    $num_of_page = intval($request->input('num_of_page', 10));

    // Lấy số lượng doctor
    $total_doctors = Doctor::count();
    $total_pages = ceil($total_doctors / $num_of_page);

    // Tính toán offset
    $offset = ($page_number - 1) * $num_of_page;

    $doctors = Doctor::whereNull('deleted_at')
      ->offset($offset)
      ->limit($num_of_page)
      ->get();

    $formatted_doctors = [];
    foreach ($doctors as $doctor) {
      $ratingData = $doctor->calculateDoctorRating();

      $formatted_doctors[] = [
        'doctor_id' => $doctor->id,
        'account_id' => $doctor->account->id,
        'full_name' => $doctor->full_name,
        'email' => $doctor->account->email,
        'gender' => $doctor->gender,
        'birthdate' => $doctor->birthdate,
        'description' => $doctor->description,
        'CMND' => $doctor->CMND,
        'phone' => $doctor->phone,
        'address' => $doctor->address,
        'image' => $doctor->image,
        'certificate' => $doctor->certificate,
        'rating' => $ratingData['average'],
        'rating_count' => $ratingData['count'],
      ];
    }

    return response()->json([
      'message' => 'Fetch doctors successfully!',
      'status' => 200,
      'page_number' => $page_number,
      'num_of_page' => $num_of_page,
      'total_pages' => $total_pages,
      'total_doctors' => $total_doctors,
      'data' => $formatted_doctors,
    ], 200);
  }

  public function getDoctorsOfMedicalCenter(Request $request)
  {
    $medical_center_id = auth()->user()->medicalCenter->id;

    if (!MedicalCenter::find($medical_center_id)) {
      return response()->json([
        'message' => 'Medical Center not found!',
        'status' => 404
      ], 404);
    }

    $page_number = intval($request->query('page_number', 1));
    $num_of_page = intval($request->query('num_of_page', 10));

    // Lấy số lượng doctor
    $total_doctors = Doctor::where('medical_center_id', $medical_center_id)->count();
    $total_pages = ceil($total_doctors / $num_of_page);

    // Tính toán offset
    $offset = ($page_number - 1) * $num_of_page;

    $doctors = Doctor::where('medical_center_id', $medical_center_id)->whereNull('deleted_at')
      ->offset($offset)
      ->limit($num_of_page)
      ->get();

    $formatted_doctors = [];
    foreach ($doctors as $doctor) {
      $ratingData = $doctor->calculateDoctorRating();

      $formatted_doctors[] = [
        'doctor_id' => $doctor->id,
        'account_id' => $doctor->account->id,
        'full_name' => $doctor->full_name,
        'email' => $doctor->account->email,
        'gender' => $doctor->gender,
        'birthdate' => $doctor->birthdate,
        'description' => $doctor->description,
        'CMND' => $doctor->CMND,
        'phone' => $doctor->phone,
        'address' => $doctor->address,
        'image' => $doctor->image,
        'certificate' => $doctor->certificate,
        'rating' => $ratingData['average'],
        'rating_count' => $ratingData['count'],
      ];
    }

    return response()->json([
      'message' => 'Fetch doctors successfully!',
      'status' => 200,
      'page_number' => $page_number,
      'num_of_page' => $num_of_page,
      'total_pages' => $total_pages,
      'total_doctors' => $total_doctors,
      'data' => $formatted_doctors,
    ], 200);
  }

  public function show($doctor_id)
  {
    $doctor = Doctor::with(['account', 'medicalCenter'])
      ->withTrashed()
      ->find($doctor_id);

    if (!$doctor) {
      return response()->json([
        'message' => 'Doctor not found',
        'status' => 404,
      ], 404);
    }

    $ratingData = $doctor->calculateDoctorRating();
    $formatted_doctor = [
      'doctor_id' => $doctor->id,
      'account_id' => $doctor->account->id,
      'full_name' => $doctor->full_name,
      'email' => $doctor->account->id,
      'gender' => $doctor->gender,
      'birthdate' => $doctor->birthdate,
      'description' => $doctor->description,
      'CMND' => $doctor->CMND,
      'phone' => $doctor->phone,
      'address' => $doctor->address,
      'image' => $doctor->image,
      'certificate' => $doctor->certificate,
      'rating' => $ratingData['average'],
      'rating_count' => $ratingData['count'],
      'medical_center' => [
        'id' => $doctor->medicalCenter->id,
        'name' => $doctor->medicalCenter->name,
        'email' => $doctor->medicalCenter->email,
        'description' => $doctor->medicalCenter->description,
        'phone' => $doctor->medicalCenter->phone,
        'address' => $doctor->medicalCenter->address,
        'website' => $doctor->medicalCenter->website,
        'fanpage' => $doctor->medicalCenter->fanpage,
        'work_time' => $doctor->medicalCenter->work_time,
        'establish_year' => $doctor->medicalCenter->establish_year,
        'certificate' => $doctor->medicalCenter->certificate,
      ],
    ];

    return response()->json([
      'message' => 'Fetch doctor detail successfully!',
      'status' => 200,
      'data' => $formatted_doctor,
    ], 200);
  }

  public function getDeletedDoctorOfMedicalCenter(Request $request)
  {
    $medical_center_id = auth()->user()->medicalCenter->id;

    if (!MedicalCenter::find($medical_center_id)) {
      return response()->json([
        'message' => 'Medical Center not found!',
        'status' => 404
      ], 404);
    }

    $page_number = intval($request->query('page_number', 1));
    $num_of_page = intval($request->query('num_of_page', 10));

    // Lấy số lượng doctor
    $total_deleted_doctors = Doctor::onlyTrashed('medical_center_id', $medical_center_id)->count();
    $total_pages = ceil($total_deleted_doctors / $num_of_page);

    // Tính toán offset
    $offset = ($page_number - 1) * $num_of_page;

    $deleted_doctors = Doctor::onlyTrashed('medical_center_id', $medical_center_id)
      ->offset($offset)
      ->limit($num_of_page)
      ->get();

    $formatted_deleted_doctors = [];
    foreach ($deleted_doctors as $deleted_doctor) {
      $ratingData = $deleted_doctor->calculateDoctorRating();

      $formatted_deleted_doctors[] = [
        'doctor_id' => $deleted_doctor->id,
        'account_id' => $deleted_doctor->account->id,
        'full_name' => $deleted_doctor->full_name,
        'email' => $deleted_doctor->account->email,
        'gender' => $deleted_doctor->gender,
        'birthdate' => $deleted_doctor->birthdate,
        'description' => $deleted_doctor->description,
        'CMND' => $deleted_doctor->CMND,
        'phone' => $deleted_doctor->phone,
        'address' => $deleted_doctor->address,
        'image' => $deleted_doctor->image,
        'certificate' => $deleted_doctor->certificate,
        'rating' => $ratingData['average'],
        'rating_count' => $ratingData['count'],
      ];
    }

    return response()->json([
      'message' => 'Fetch deleted doctors successfully!',
      'status' => 200,
      'page_number' => $page_number,
      'num_of_page' => $num_of_page,
      'total_pages' => $total_pages,
      'total_deleted_doctors' => $total_deleted_doctors,
      'data' => $formatted_deleted_doctors,
    ], 200);
  }

  /**
   * Tạo một bác sĩ mới.
   */
  public function createDoctor(Request $request)
  {
    $medical_center_id = auth()->user()->medicalCenter->id;

    // Validate input
    $validatedData = $request->validate([
      'full_name' => 'required|string|max:255',
      'gender' => 'required|string|max:10',
      'birthdate' => 'required|date',
      'description' => 'nullable|string',
      'CMND' => 'required|string|max:20|unique:doctors,CMND',
      'phone' => 'required|string|max:15|unique:doctors,phone',
      'address' => 'required|string|max:255',
      'image' => 'nullable|string|max:255',
      'certificate' => 'nullable|string|max:255',
      'username' => 'required|string|max:50',
      'email' => 'required|string|email|max:255|unique:accounts,email',
      'password' => 'required|string',
    ]);

    $validatedData['medical_center_id'] = $medical_center_id;

    // Tạo account mới cho bác sĩ
    $account = Account::create([
      'username' => $validatedData['username'],
      'email' => $validatedData['email'],
      'password' => Hash::make($validatedData['password']),
      'role_id' => Role::where('role_name', 'ROLE_DOCTOR')->first()->id,
      'enabled' => true,
      'is_approved' => true,
    ]);

    // Tạo bác sĩ mới
    $doctor = Doctor::create([
      'full_name' => $validatedData['full_name'],
      'gender' => $validatedData['gender'],
      'birthdate' => $validatedData['birthdate'],
      'description' => $validatedData['description'],
      'CMND' => $validatedData['CMND'],
      'phone' => $validatedData['phone'],
      'address' => $validatedData['address'],
      'image' => $validatedData['image'],
      'certificate' => $validatedData['certificate'],
      'account_id' => $account->id,
      'medical_center_id' => $validatedData['medical_center_id'],
    ]);

    return response()->json([
      'status_code' => 201,
      'message' => 'Doctor created successfully',
      'doctor' => $doctor,
    ], 201);
  }

  /**
   * Xóa bác sĩ theo ID.
   */
  public function deleteDoctor($doctor_id)
  {
    $doctor = Doctor::find($doctor_id);

    if (!$doctor) {
      return response()->json([
        'status_code' => 404,
        'message' => 'Doctor not found',
      ], 404);
    }

    $doctor->delete();

    return response()->json([
      'status_code' => 200,
      'message' => 'Doctor deleted successfully',
    ], 200);
  }

  public function getAllAppointmentsOfDoctor(Request $request, $doctor_id)
  {
    $page_number = intval($request->query('page_number', 1));
    $num_of_page = intval($request->query('num_of_page', 10));

    // Lấy tất cả các cuộc hẹn của bác sĩ
    $query = Appointment::where('doctor_id', $doctor_id)->whereNull('deleted_at');

    // Tính tổng số cuộc hẹn
    $total_appointments = $query->count();
    $total_pages = ceil($total_appointments / $num_of_page);

    // Tính toán offset
    $offset = ($page_number - 1) * $num_of_page;

    // Lấy danh sách các cuộc hẹn với phân trang
    $appointments = $query->orderBy('start_time', 'desc')
      ->offset($offset)
      ->limit($num_of_page)
      ->get();

    // Format lại dữ liệu trả về
    $formatted_appointments = [];
    foreach ($appointments as $appointment) {
      $formatted_appointments[] = [
        'appointment_id' => $appointment->id,
        'start_time' => $appointment->start_time,
        'message' => $appointment->message,
        'done' => $appointment->done,
        'created_at' => $appointment->created_at,
        'updated_at' => $appointment->updated_at,
        'deleted_at' => $appointment->deleted_at,
        'customer' => [
          'customer_id' => $appointment->customer->id,
          'full_name' => $appointment->customer->full_name,
          'gender' => $appointment->customer->gender,
          'birthdate' => $appointment->customer->birthdate,
          'CMND' => $appointment->customer->CMND,
          'address' => $appointment->customer->address,
          'phone' => $appointment->customer->phone,
          'ranking_point' => $appointment->customer->ranking_point,
        ],
        'pet' => [
          'pet_id' => $appointment->pet->id,
          'name' => $appointment->pet->name,
          'type' => $appointment->pet->type,
          'age' => $appointment->pet->age,
          'gender' => $appointment->pet->gender,
          'description' => $appointment->pet->description,
          'image' => $appointment->pet->image,
          'is_purebred' => $appointment->pet->image,
          'status' => $appointment->pet->image,
          'breed' => [
            'breed_id' => $appointment->pet->breed->id,
            'name' => $appointment->pet->breed->name,
            'type' => $appointment->pet->breed->type,
            'description' => $appointment->pet->breed->description,
            'image' => $appointment->pet->breed->image,
            'origin' => $appointment->pet->breed->origin,
            'lifespan' => $appointment->pet->breed->lifespan,
            'average_size' => $appointment->pet->breed->average_size,
          ],
        ],
      ];
    }

    return response()->json([
      'message' => 'Fetch appointments successfully!',
      'status' => 200,
      'page_number' => $page_number,
      'num_of_page' => $num_of_page,
      'total_pages' => $total_pages,
      'total_appointments' => $total_appointments,
      'data' => $formatted_appointments,
    ], 200);
  }

  public function getFreetimeOfDoctor(Request $request, $doctor_id)
  {
    $date = $request->query('date');

    if (!$date) {
      return response()->json([
        'message' => 'Date is required',
        'status' => 400,
      ], 400);
    }

    // Chuyển đổi date thành khoảng thời gian bắt đầu và kết thúc của ngày đó
    $start_date = Carbon::parse($date)->startOfDay();
    $end_date = Carbon::parse($date)->endOfDay();

    // Lấy medical center của bác sĩ
    $doctor = Doctor::find($doctor_id);
    if (!$doctor) {
      return response()->json([
        'message' => 'Doctor not found',
        'status' => 404,
      ], 404);
    }
    $medical_center = $doctor->medicalCenter;

    // Lấy thời gian làm việc từ medical center
    $work_times = $medical_center->getWorkTimes();
    $work_start_time = Carbon::parse($date . ' ' . $work_times['start']);
    $work_end_time = Carbon::parse($date . ' ' . $work_times['end']);

    // Lấy danh sách appointment của bác sĩ trong khoảng thời gian
    $appointments = Appointment::where('doctor_id', $doctor_id)
      ->whereBetween('start_time', [$start_date, $end_date])
      ->orderBy('start_time', 'asc')
      ->get();

    // Tạo danh sách các khoảng thời gian trong ngày từ thời gian làm việc
    $all_time_slots = [];
    for ($time = $work_start_time->copy(); $time->lt($work_end_time); $time->addHour()) {
      $all_time_slots[] = $time->format('Y-m-d H:i:s');
    }

    // Chuyển đổi các khoảng thời gian đã đặt lịch thành các mốc thời gian Carbon
    $booked_times = $appointments->pluck('start_time')->map(function ($time) {
      return Carbon::parse($time)->format('Y-m-d H:i:s');
    })->toArray();

    // Loại bỏ các mốc thời gian đã có lịch hẹn khỏi danh sách thời gian có sẵn
    $free_times = array_diff($all_time_slots, $booked_times);

    // Chuyển đổi $free_times thành mảng để trả về dưới dạng JSON
    $free_times = array_values($free_times);

    // Trả về các mốc thời gian rảnh dưới dạng JSON
    return response()->json([
      'message' => 'Fetch free times successfully!',
      'status' => 200,
      'data' => $free_times,
    ], 200);
  }

  // public function createVaccineHistory(Request $request)
  // {
  //   $doctor_id = auth()->user()->doctor->id;

  //   $validatedData = $request->validate([
  //     'vaccine' => 'required|string',
  //     'note' => 'nullable|string',
  //     'pet_id' => 'required|exists:pets,id',
  //   ]);

  //   $validatedData['doctor_id'] = $doctor_id;

  //   $vaccineHistory = HistoryVaccine::create([
  //     'vaccine' => $validatedData['vaccine'],
  //     'note' => $validatedData['note'],
  //     'doctor_id' => $validatedData['doctor_id'],
  //     'pet_id' => $validatedData['pet_id'],
  //   ]);

  //   return response()->json($vaccineHistory, 201);
  // }

  // public function createDiagnosisHistory(Request $request)
  // {
  //   $doctor_id = auth()->user()->doctor->id;

  //   $validatedData = $request->validate([
  //     'reason' => 'required',
  //     'diagnosis' => 'required',
  //     'treatment' => 'required',
  //     'health_condition' => 'required',
  //     'note' => 'nullable',
  //     'pet_id' => 'required|exists:pets,id',
  //   ]);

  //   $validatedData['doctor_id'] = $doctor_id;

  //   $diagnosisHistory = HistoryDiagnosis::create([
  //     'reason' => $validatedData['reason'],
  //     'diagnosis' => $validatedData['diagnosis'],
  //     'treatment' => $validatedData['treatment'],
  //     'health_condition' => $validatedData['health_condition'],
  //     'note' => $validatedData['note'],
  //     'doctor_id' => $validatedData['doctor_id'],
  //     'pet_id' => $validatedData['pet_id'],
  //   ]);

  //   return response()->json($diagnosisHistory, 201);
  // }

  public function updateVaccineHistory(Request $request, $vaccine_history_id)
  {
    $doctor_id = auth()->user()->doctor->id;

    $validatedData = $request->validate([
      'vaccine' => 'required|string',
      'note' => 'nullable|string',
      'pet_id' => 'required|exists:pets,id',
    ]);

    $vaccineHistory = HistoryVaccine::where('id', $vaccine_history_id)
      ->where('doctor_id', $doctor_id)
      ->first();

    if (!$vaccineHistory) {
      return response()->json(['message' => 'Vaccine history not found or unauthorized'], 404);
    }

    $vaccineHistory->update([
      'vaccine' => $validatedData['vaccine'],
      'note' => $validatedData['note'],
      'pet_id' => $validatedData['pet_id'],
    ]);

    return response()->json($vaccineHistory, 200);
  }

  // Phương thức cập nhật lịch sử chẩn đoán
  public function updateDiagnosisHistory(Request $request, $diagnosis_history_id)
  {
    $doctor_id = auth()->user()->doctor->id;

    $validatedData = $request->validate([
      'reason' => 'required',
      'diagnosis' => 'required',
      'treatment' => 'required',
      'health_condition' => 'required',
      'note' => 'nullable|string',
      'pet_id' => 'required|exists:pets,id',
    ]);

    $diagnosisHistory = HistoryDiagnosis::where('id', $diagnosis_history_id)
      ->where('doctor_id', $doctor_id)
      ->first();

    if (!$diagnosisHistory) {
      return response()->json(['message' => 'Diagnosis history not found or unauthorized'], 404);
    }

    $diagnosisHistory->update([
      'reason' => $validatedData['reason'],
      'diagnosis' => $validatedData['diagnosis'],
      'treatment' => $validatedData['treatment'],
      'health_condition' => $validatedData['health_condition'],
      'note' => $validatedData['note'],
      'pet_id' => $validatedData['pet_id'],
    ]);

    return response()->json($diagnosisHistory, 200);
  }

  public function getProfile()
  {
    $account_id = auth()->user()->id;

    $doctor = Doctor::where('account_id', $account_id)->first();

    // Kiểm tra xem doctor có tồn tại không
    if (!$doctor) {
      return response()->json(['message' => 'Doctor center not found'], 404);
    }

    $formattedDoctor = [
      'id' => $doctor->id,
      'account_id' => $doctor->account->id,
      'name' => $doctor->full_name,
      'username' => $doctor->account->username,
      'email' => $doctor->account->email,
      'role' => $doctor->account->role->role_name,
      'gender' => $doctor->gender,
      'birthdate' => $doctor->birthdate,
      'description' => $doctor->description,
      'image' => $doctor->image,
      'avatar' => $doctor->account->avatar,
      'phone' => $doctor->phone,
      'address' => $doctor->address,
      'CMND' => $doctor->CMND,
      'certificate' => $doctor->certificate,
      'created_at' => $doctor->created_at,
      'updated_at' => $doctor->updated_at,
    ];

    return response()->json([
      'message' => 'Get doctor profile successfully',
      'status' => 200,
      'data' => $formattedDoctor
    ], 200);
  }

  public function updateProfile(Request $request)
  {
    $doctor_id = auth()->user()->doctor->id;

    try {
      // Lấy doctor hiện tại
      $doctor = Doctor::findOrFail($doctor_id);
    } catch (ModelNotFoundException $e) {
      return response()->json([
        'message' => 'Doctor not found!',
        'status' => 404
      ], 404);
    }

    // Xác thực dữ liệu
    $validatedData = $request->validate([
      'full_name' => 'required|string',
      'gender' => 'required|string',
      'birthdate' => 'required|string',
      'description' => 'nullable|string',
      'CMND' => 'required|string',
      'phone' => 'required|string',
      'address' => 'required|string',
      'image' => 'nullable|string',
      'certificate' => 'nullable|string',
      'avatar' => 'nullable|string',
      'username' => 'required|string',
    ]);

    // Cập nhật doctor
    $doctor->update([
      'full_name' => $validatedData['full_name'],
      'gender' => $validatedData['gender'],
      'birthdate' => $validatedData['birthdate'],
      'description' => $validatedData['description'],
      'CMND' => $validatedData['CMND'],
      'phone' => $validatedData['phone'],
      'address' => $validatedData['address'],
      'image' => $validatedData['image'],
      'certificate' => $validatedData['certificate'],
    ]);

    // Cập nhật thông tin tài khoản
    $account = Account::findOrFail($doctor->account_id);
    $account->update([
      'username' => $validatedData['username'],
      'avatar' => $validatedData['avatar'],
    ]);

    return response()->json([
      'message' => 'Doctor updated successfully.',
      'status' => 200,
      'data' => $doctor
    ], 200);
  }
}
