<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\MedicalCenter;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DoctorController extends Controller
{
  public function getDoctorsOfMedicalCenter(Request $request, $medical_center_id)
  {
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
    $doctor = Doctor::with(['account', 'medicalCenter'])->find($doctor_id);

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
}
