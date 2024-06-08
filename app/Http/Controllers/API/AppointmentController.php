<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\MedicalCenter;
use App\Models\Pet;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
  public function paging(Request $request)
  {
    $customer_id = auth()->user()->id;

    $page_number = intval($request->query('page_number', 1));
    $num_of_page = intval($request->query('num_of_page', 10));
    $sort_by = $request->query('sort_by', 'newest');

    $query = Appointment::query()->where('customer_id', $customer_id)->whereNull('deleted_at');

    // Tính tổng số trang
    $total_appointments = $query->count();
    $total_pages = ceil($total_appointments / $num_of_page);

    // Tính toán offset
    $offset = ($page_number - 1) * $num_of_page;

    if ($sort_by === 'oldest') {
      $appointments = $query->orderBy('start_time', 'asc')
        ->offset($offset)
        ->limit($num_of_page)
        ->get();
    } else {
      $appointments = $query->orderBy('start_time', 'desc')
        ->offset($offset)
        ->limit($num_of_page)
        ->get();
    }

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
        'pet' => [
          'pet_id' => $appointment->pet->id,
          'name' => $appointment->pet->name,
        ],
        'doctor' => [
          'full_name' => $appointment->doctor->full_name,
          'email' => $appointment->doctor->account->email,
          'phone' => $appointment->doctor->phone,
        ],
        'medical_center' => [
          'name' => $appointment->doctor->medicalCenter->name,
          'email' => $appointment->doctor->medicalCenter->email,
          'image' => $appointment->doctor->medicalCenter->image,
          'phone' => $appointment->doctor->medicalCenter->phone,
          'address' => $appointment->doctor->medicalCenter->address,
        ]
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

  public function show($appointment_id)
  {
    $customer_id = auth()->user()->id;

    $appointment = Appointment::query()
      ->where('customer_id', $customer_id)
      ->where('id', $appointment_id)
      ->whereNull('deleted_at')
      ->first();

    if (!$appointment) {
      return response()->json([
        'message' => 'Appointment not found or does not belong to the customer',
        'status' => 404,
      ], 404);
    }

    $formatted_appointment = [
      'appointment_id' => $appointment->id,
      'start_time' => $appointment->start_time,
      'message' => $appointment->message,
      'done' => $appointment->done,
      'created_at' => $appointment->created_at,
      'updated_at' => $appointment->updated_at,
      'deleted_at' => $appointment->deleted_at,
      'pet' => [
        'pet_id' => $appointment->pet->id,
        'name' => $appointment->pet->name,
        'type' => $appointment->pet->type,
        'age' => $appointment->pet->age,
        'gender' => $appointment->pet->gender,
        'description' => $appointment->pet->description,
        'image' => $appointment->pet->image,
        'is_purebred' => $appointment->pet->is_purebred,
        'status' => $appointment->pet->status,
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
      'doctor' => [
        'doctor_id' => $appointment->doctor->id,
        'account_id' => $appointment->doctor->account->id,
        'full_name' => $appointment->doctor->full_name,
        'email' => $appointment->doctor->account->email,
        'gender' => $appointment->doctor->gender,
        'birthdate' => $appointment->doctor->birthdate,
        'description' => $appointment->doctor->description,
        'CMND' => $appointment->doctor->CMND,
        'phone' => $appointment->doctor->phone,
        'address' => $appointment->doctor->address,
        'image' => $appointment->doctor->image,
        'certificate' => $appointment->doctor->certificate,
      ],
      'medical_center' => [
        'medical_center_id' => $appointment->doctor->medicalCenter->id,
        'account_id' => $appointment->doctor->medicalCenter->account->id,
        'name' => $appointment->doctor->medicalCenter->name,
        'email' => $appointment->doctor->medicalCenter->account->email,
        'description' => $appointment->doctor->medicalCenter->description,
        'image' => $appointment->doctor->medicalCenter->image,
        'phone' => $appointment->doctor->medicalCenter->phone,
        'address' => $appointment->doctor->medicalCenter->address,
        'website' => $appointment->doctor->medicalCenter->website,
        'fanpage' => $appointment->doctor->medicalCenter->fanpage,
        'work_time' => $appointment->doctor->medicalCenter->work_time,
        'establish_year' => $appointment->doctor->medicalCenter->establish_year,
        'certificate' => $appointment->doctor->medicalCenter->certificate,
      ],
      'customer' => [
        'customer_id' => $appointment->customer->id,
        'account_id' => $appointment->customer->account->id,
        'name' => $appointment->customer->full_name,
        'email' => $appointment->customer->account->email,
        'gender' => $appointment->customer->gender,
        'birthdate' => $appointment->customer->birthdate,
        'CMND' => $appointment->customer->CMND,
        'address' => $appointment->customer->address,
        'phone' => $appointment->customer->phone,
        'ranking_point' => $appointment->customer->ranking_point,
        'ranking_id' => $appointment->customer->ranking_id,
      ]
    ];

    return response()->json([
      'message' => 'Fetch appointment detail successfully!',
      'status' => 200,
      'data' => $formatted_appointment,
    ], 200);
  }

  public function getAppointmentBeforeCurrentDate(Request $request)
  {
    $customer_id = auth()->user()->id;

    $page_number = intval($request->query('page_number', 1));
    $num_of_page = intval($request->query('num_of_page', 10));

    $query = Appointment::query()->where('customer_id', $customer_id)
      ->where('start_time', '<', now())
      ->whereNull('deleted_at');

    // Tính tổng số trang
    $total_appointments = $query->count();
    $total_pages = ceil($total_appointments / $num_of_page);

    // Tính toán offset
    $offset = ($page_number - 1) * $num_of_page;

    $appointments = $query->orderBy('start_time', 'desc')
      ->offset($offset)
      ->limit($num_of_page)
      ->get();

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
        'pet' => [
          'pet_id' => $appointment->pet->id,
          'name' => $appointment->pet->name,
        ],
        'doctor' => [
          'full_name' => $appointment->doctor->full_name,
          'email' => $appointment->doctor->account->email,
          'phone' => $appointment->doctor->phone,
        ],
        'medical_center' => [
          'name' => $appointment->doctor->medicalCenter->name,
          'email' => $appointment->doctor->medicalCenter->email,
          'image' => $appointment->doctor->medicalCenter->image,
          'phone' => $appointment->doctor->medicalCenter->phone,
          'address' => $appointment->doctor->medicalCenter->address,
        ]
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

  public function getAppointmentAfterCurrentDate(Request $request)
  {
    $customer_id = auth()->user()->id;

    $page_number = intval($request->query('page_number', 1));
    $num_of_page = intval($request->query('num_of_page', 10));

    $query = Appointment::query()->where('customer_id', $customer_id)
      ->where('start_time', '>', now())
      ->whereNull('deleted_at');

    // Tính tổng số trang
    $total_appointments = $query->count();
    $total_pages = ceil($total_appointments / $num_of_page);

    // Tính toán offset
    $offset = ($page_number - 1) * $num_of_page;

    $appointments = $query->orderBy('start_time', 'asc')
      ->offset($offset)
      ->limit($num_of_page)
      ->get();

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
        'pet' => [
          'pet_id' => $appointment->pet->id,
          'name' => $appointment->pet->name,
        ],
        'doctor' => [
          'full_name' => $appointment->doctor->full_name,
          'email' => $appointment->doctor->account->email,
          'phone' => $appointment->doctor->phone,
        ],
        'medical_center' => [
          'name' => $appointment->doctor->medicalCenter->name,
          'email' => $appointment->doctor->medicalCenter->email,
          'image' => $appointment->doctor->medicalCenter->image,
          'phone' => $appointment->doctor->medicalCenter->phone,
          'address' => $appointment->doctor->medicalCenter->address,
        ]
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

  public function getDoneAppointment(Request $request)
  {
    $customer_id = auth()->user()->id;

    $page_number = intval($request->query('page_number', 1));
    $num_of_page = intval($request->query('num_of_page', 10));

    $query = Appointment::where('customer_id', $customer_id)
      ->where('done', true)
      ->whereNull('deleted_at');

    // Tính tổng số trang
    $total_appointments = $query->count();
    $total_pages = ceil($total_appointments / $num_of_page);

    // Tính toán offset
    $offset = ($page_number - 1) * $num_of_page;

    $appointments = $query->offset($offset)
      ->limit($num_of_page)
      ->get();

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
        'pet' => [
          'pet_id' => $appointment->pet->id,
          'name' => $appointment->pet->name,
        ],
        'doctor' => [
          'full_name' => $appointment->doctor->full_name,
          'email' => $appointment->doctor->account->email,
          'phone' => $appointment->doctor->phone,
        ],
        'medical_center' => [
          'name' => $appointment->doctor->medicalCenter->name,
          'email' => $appointment->doctor->medicalCenter->email,
          'image' => $appointment->doctor->medicalCenter->image,
          'phone' => $appointment->doctor->medicalCenter->phone,
          'address' => $appointment->doctor->medicalCenter->address,
        ]
      ];
    }

    return response()->json([
      'message' => 'Fetch done appointments successfully!',
      'status' => 200,
      'page_number' => $page_number,
      'num_of_page' => $num_of_page,
      'total_pages' => $total_pages,
      'total_appointments' => $total_appointments,
      'data' => $formatted_appointments,
    ], 200);
  }

  public function getDeletedAppointment(Request $request)
  {
    $customer_id = auth()->user()->id;

    $page_number = intval($request->query('page_number', 1));
    $num_of_page = intval($request->query('num_of_page', 10));

    $query = Appointment::where('customer_id', $customer_id)->onlyTrashed();

    // Tính tổng số trang
    $total_deleted_appointments = $query->count();
    $total_pages = ceil($total_deleted_appointments / $num_of_page);

    // Tính toán offset
    $offset = ($page_number - 1) * $num_of_page;

    $deleted_appointements = $query->offset($offset)
      ->limit($num_of_page)
      ->get();

    $formatted_deleted_appointements = [];
    foreach ($deleted_appointements as $deleted_appointment) {

      $formatted_deleted_appointements[] = [
        'appointment_id' => $deleted_appointment->id,
        'start_time' => $deleted_appointment->start_time,
        'message' => $deleted_appointment->message,
        'done' => $deleted_appointment->done,
        'created_at' => $deleted_appointment->created_at,
        'updated_at' => $deleted_appointment->updated_at,
        'deleted_at' => $deleted_appointment->deleted_at,
        'pet' => [
          'pet_id' => $deleted_appointment->pet->id,
          'name' => $deleted_appointment->pet->name,
        ],
        'doctor' => [
          'full_name' => $deleted_appointment->doctor->full_name,
          'email' => $deleted_appointment->doctor->account->email,
          'phone' => $deleted_appointment->doctor->phone,
        ],
        'medical_center' => [
          'name' => $deleted_appointment->doctor->medicalCenter->name,
          'email' => $deleted_appointment->doctor->medicalCenter->email,
          'image' => $deleted_appointment->doctor->medicalCenter->image,
          'phone' => $deleted_appointment->doctor->medicalCenter->phone,
          'address' => $deleted_appointment->doctor->medicalCenter->address,
        ]
      ];
    }

    return response()->json([
      'message' => 'Fetch deleted appointments successfully!',
      'status' => 200,
      'page_number' => $page_number,
      'num_of_page' => $num_of_page,
      'total_pages' => $total_pages,
      'total_appointments' => $total_deleted_appointments,
      'data' => $formatted_deleted_appointements,
    ], 200);
  }

  public function store(Request $request)
  {
    $customer_id = auth()->user()->id;

    $validatedData = $request->validate([
      'message' => 'nullable|string',
      'start_time' => 'required|date',
      'doctor_id' => 'required|exists:doctors,id',
      'pet_id' => 'required|exists:pets,id',
    ]);
    $validatedData['customer_id'] = $customer_id;

    $pet_id = $validatedData['pet_id'];
    $doctor_id = $validatedData['doctor_id'];
    $start_time = $validatedData['start_time'];

    // Kiểm tra xem pet_id có nằm trong danh sách các pet mà khách hàng nhận nuôi hoặc tạo ra hay không
    $isPetValid = Pet::where('id', $pet_id)
      ->where(function ($query) use ($customer_id) {
        $query->where('customer_id', $customer_id)
          ->orWhereHas('historyAdoptions', function ($query) use ($customer_id) {
            $query->where('customer_id', $customer_id);
          });
      })
      ->exists();

    if (!$isPetValid) {
      return response()->json([
        'message' => 'Pet not found or does not belong to the customer',
        'status' => 404,
      ], 404);
    }

    // Kiểm tra xem doctor_id có thuộc medical_center_id hay không
    $isDoctorInMedicalCenter = Doctor::where('id', $doctor_id)->exists();

    if (!$isDoctorInMedicalCenter) {
      return response()->json([
        'message' => 'Doctor not found',
        'status' => 404,
      ], 404);
    }

    // Kiểm tra xem đã có cuộc hẹn nào vào thời gian đó với bác sĩ đó chưa
    $isAppointmentConflict = Appointment::where('doctor_id', $doctor_id)
      ->where('start_time', $start_time)
      ->exists();

    if ($isAppointmentConflict) {
      return response()->json([
        'message' => 'The doctor already has an appointment at this time',
        'status' => 409,
      ], 409);
    }

    $appointment = Appointment::create($validatedData);

    return response()->json([
      'message' => 'Create appointment successfully!',
      'status' => 201,
      'data' => $appointment,
    ], 201);
  }

  public function update(Request $request, $appointment_id)
  {
    $customer_id = auth()->user()->id;

    $appointment = Appointment::findOrFail($appointment_id);

    if ($appointment->customer_id != $customer_id) {
      return response()->json([
        'message' => 'Appointment not found or does not belong to the customer',
        'status' => 404,
      ], 404);
    }

    $validatedData = $request->validate([
      'message' => 'nullable|string',
      'start_time' => 'required|date',
      'doctor_id' => 'required|exists:doctors,id',
      'pet_id' => 'required|exists:pets,id',
    ]);
    $validatedData['customer_id'] = $customer_id;

    $pet_id = $validatedData['pet_id'];
    $doctor_id = $validatedData['doctor_id'];

    // Kiểm tra xem pet_id có nằm trong danh sách các pet mà khách hàng nhận nuôi hoặc tạo ra hay không
    $isPetValid = Pet::where('id', $pet_id)
      ->where(function ($query) use ($customer_id) {
        $query->where('customer_id', $customer_id)
          ->orWhereHas('historyAdoptions', function ($query) use ($customer_id) {
            $query->where('customer_id', $customer_id);
          });
      })
      ->exists();

    if (!$isPetValid) {
      return response()->json([
        'message' => 'Pet not found or does not belong to the customer',
        'status' => 404,
      ], 404);
    }

    // Kiểm tra xem doctor_id có thuộc medical_center_id hay không
    $isDoctorInMedicalCenter = Doctor::where('id', $doctor_id)->exists();

    if (!$isDoctorInMedicalCenter) {
      return response()->json([
        'message' => 'Doctor not found',
        'status' => 404,
      ], 404);
    }

    $appointment->update($validatedData);

    return response()->json([
      'message' => 'Update appointment successfully!',
      'status' => 200,
      'data' => $appointment,
    ], 200);
  }

  public function destroy($appointment_id)
  {
    $customer_id = auth()->user()->id;

    $appointment = Appointment::where('id', $appointment_id)
      ->where('customer_id', $customer_id)
      ->first();

    if (!$appointment) {
      return response()->json([
        'message' => 'Appointment not found or does not belong to the customer',
        'status' => 404,
      ], 404);
    }

    if ($appointment->done) {
      return response()->json([
        'message' => 'This appointment has been completed and cannot be deleted',
        'status' => 400,
      ], 400);
    }

    $appointment->delete();

    return response()->json([
      'message' => 'Delete appointment successfully!',
      'status' => 200,
    ], 200);
  }
}
