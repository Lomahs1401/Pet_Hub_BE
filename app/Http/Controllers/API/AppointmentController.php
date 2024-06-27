<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\MedicalCenter;
use App\Models\Pet;
use App\Models\RatingDoctor;
use App\Models\RatingMedicalCenter;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
  public function paging(Request $request)
  {
    $customer_id = auth()->user()->id;

    $page_number = intval($request->query('page_number', 1));
    $num_of_page = intval($request->query('num_of_page', 10));
    $sort_by = $request->query('sort_by', 'newest');

    $query = Appointment::query()->withTrashed()->where('customer_id', $customer_id);

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
      $doctor = $appointment->doctor;
      $medicalCenter = $doctor->medicalCenter;

      // Kiểm tra đánh giá của bác sĩ
      $doctorReview = RatingDoctor::where('customer_id', $customer_id)
        ->where('doctor_id', $doctor->id)
        ->first();

      $doctorIsReviewed = $doctorReview !== null;
      $doctorShopResponsed = $doctorIsReviewed && $doctorReview->reply !== null;
      $doctorRatingInfo = $doctorIsReviewed ? [
        'rated' => true,
        'rating' => $doctorReview->rating,
        'description' => $doctorReview->description,
        'reply' => $doctorReview->reply,
        'reply_date' => $doctorReview->reply_date,
      ] : null;

      // Kiểm tra đánh giá của trung tâm y tế
      $medicalCenterReview = RatingMedicalCenter::where('customer_id', $customer_id)
        ->where('medical_center_id', $medicalCenter->id)
        ->first();

      $medicalCenterIsReviewed = $medicalCenterReview !== null;
      $medicalCenterShopResponsed = $medicalCenterIsReviewed && $medicalCenterReview->reply !== null;
      $medicalCenterRatingInfo = $medicalCenterIsReviewed ? [
        'rated' => true,
        'rating' => $medicalCenterReview->rating,
        'description' => $medicalCenterReview->description,
        'reply' => $medicalCenterReview->reply,
        'reply_date' => $medicalCenterReview->reply_date,
      ] : null;

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
          'id' => $doctor->id,
          'full_name' => $doctor->full_name,
          'email' => $doctor->account->email,
          'image' => $doctor->image,
          'avatar' => $doctor->account->avatar,
          'phone' => $doctor->phone,
          'is_reviewed' => $doctorIsReviewed,
          'doctor_responsed' => $doctorShopResponsed,
          'rating_info' => $doctorRatingInfo
        ],
        'medical_center' => [
          'id' => $medicalCenter->id,
          'name' => $medicalCenter->name,
          'email' => $medicalCenter->email,
          'image' => $medicalCenter->image,
          'avatar' => $medicalCenter->account->avatar,
          'phone' => $medicalCenter->phone,
          'address' => $medicalCenter->address,
          'is_reviewed' => $medicalCenterIsReviewed,
          'medical_center_responsed' => $medicalCenterShopResponsed,
          'rating_info' => $medicalCenterRatingInfo
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
      $doctor = $appointment->doctor;
      $medicalCenter = $doctor->medicalCenter;

      // Kiểm tra đánh giá của bác sĩ
      $doctorReview = RatingDoctor::where('customer_id', $customer_id)
        ->where('doctor_id', $doctor->id)
        ->first();

      $doctorIsReviewed = $doctorReview !== null;
      $doctorShopResponsed = $doctorIsReviewed && $doctorReview->reply !== null;
      $doctorRatingInfo = $doctorIsReviewed ? [
        'rated' => true,
        'rating' => $doctorReview->rating,
        'description' => $doctorReview->description,
        'reply' => $doctorReview->reply,
        'reply_date' => $doctorReview->reply_date,
      ] : null;

      // Kiểm tra đánh giá của trung tâm y tế
      $medicalCenterReview = RatingMedicalCenter::where('customer_id', $customer_id)
        ->where('medical_center_id', $medicalCenter->id)
        ->first();

      $medicalCenterIsReviewed = $medicalCenterReview !== null;
      $medicalCenterShopResponsed = $medicalCenterIsReviewed && $medicalCenterReview->reply !== null;
      $medicalCenterRatingInfo = $medicalCenterIsReviewed ? [
        'rated' => true,
        'rating' => $medicalCenterReview->rating,
        'description' => $medicalCenterReview->description,
        'reply' => $medicalCenterReview->reply,
        'reply_date' => $medicalCenterReview->reply_date,
      ] : null;

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
          'id' => $doctor->id,
          'full_name' => $doctor->full_name,
          'email' => $doctor->account->email,
          'image' => $doctor->image,
          'avatar' => $doctor->account->avatar,
          'phone' => $doctor->phone,
          'is_reviewed' => $doctorIsReviewed,
          'doctor_esponsed' => $doctorShopResponsed,
          'rating_info' => $doctorRatingInfo,
        ],
        'medical_center' => [
          'id' => $medicalCenter->id,
          'name' => $medicalCenter->name,
          'email' => $medicalCenter->email,
          'image' => $medicalCenter->image,
          'avatar' => $medicalCenter->account->avatar,
          'phone' => $medicalCenter->phone,
          'address' => $medicalCenter->address,
          'is_reviewed' => $medicalCenterIsReviewed,
          'medical_center_responsed' => $medicalCenterShopResponsed,
          'rating_info' => $medicalCenterRatingInfo,
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
      $doctor = $appointment->doctor;
      $medicalCenter = $doctor->medicalCenter;

      // Kiểm tra đánh giá của bác sĩ
      $doctorReview = RatingDoctor::where('customer_id', $customer_id)
        ->where('doctor_id', $doctor->id)
        ->first();

      $doctorIsReviewed = $doctorReview !== null;
      $doctorShopResponsed = $doctorIsReviewed && $doctorReview->reply !== null;
      $doctorRatingInfo = $doctorIsReviewed ? [
        'rated' => true,
        'rating' => $doctorReview->rating,
        'description' => $doctorReview->description,
        'reply' => $doctorReview->reply,
        'reply_date' => $doctorReview->reply_date,
      ] : null;

      // Kiểm tra đánh giá của trung tâm y tế
      $medicalCenterReview = RatingMedicalCenter::where('customer_id', $customer_id)
        ->where('medical_center_id', $medicalCenter->id)
        ->first();

      $medicalCenterIsReviewed = $medicalCenterReview !== null;
      $medicalCenterShopResponsed = $medicalCenterIsReviewed && $medicalCenterReview->reply !== null;
      $medicalCenterRatingInfo = $medicalCenterIsReviewed ? [
        'rated' => true,
        'rating' => $medicalCenterReview->rating,
        'description' => $medicalCenterReview->description,
        'reply' => $medicalCenterReview->reply,
        'reply_date' => $medicalCenterReview->reply_date,
      ] : null;

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
          'id' => $doctor->id,
          'full_name' => $doctor->full_name,
          'email' => $doctor->account->email,
          'image' => $doctor->image,
          'avatar' => $doctor->account->avatar,
          'phone' => $doctor->phone,
          'is_reviewed' => $doctorIsReviewed,
          'doctor_responsed' => $doctorShopResponsed,
          'rating_info' => $doctorRatingInfo,
        ],
        'medical_center' => [
          'id' => $medicalCenter->id,
          'name' => $medicalCenter->name,
          'email' => $medicalCenter->email,
          'image' => $medicalCenter->image,
          'avatar' => $medicalCenter->account->avatar,
          'phone' => $medicalCenter->phone,
          'address' => $medicalCenter->address,
          'is_reviewed' => $medicalCenterIsReviewed,
          'medical_center_responsed' => $medicalCenterShopResponsed,
          'rating_info' => $medicalCenterRatingInfo,
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
      $doctor = $appointment->doctor;
      $medicalCenter = $doctor->medicalCenter;

      // Kiểm tra đánh giá của bác sĩ
      $doctorReview = RatingDoctor::where('customer_id', $customer_id)
        ->where('doctor_id', $doctor->id)
        ->first();

      $doctorIsReviewed = $doctorReview !== null;
      $doctorShopResponsed = $doctorIsReviewed && $doctorReview->reply !== null;
      $doctorRatingInfo = $doctorIsReviewed ? [
        'rated' => true,
        'rating' => $doctorReview->rating,
        'description' => $doctorReview->description,
        'reply' => $doctorReview->reply,
        'reply_date' => $doctorReview->reply_date,
      ] : null;

      // Kiểm tra đánh giá của trung tâm y tế
      $medicalCenterReview = RatingMedicalCenter::where('customer_id', $customer_id)
        ->where('medical_center_id', $medicalCenter->id)
        ->first();

      $medicalCenterIsReviewed = $medicalCenterReview !== null;
      $medicalCenterShopResponsed = $medicalCenterIsReviewed && $medicalCenterReview->reply !== null;
      $medicalCenterRatingInfo = $medicalCenterIsReviewed ? [
        'rated' => true,
        'rating' => $medicalCenterReview->rating,
        'description' => $medicalCenterReview->description,
        'reply' => $medicalCenterReview->reply,
        'reply_date' => $medicalCenterReview->reply_date,
      ] : null;

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
          'id' => $doctor->id,
          'full_name' => $doctor->full_name,
          'email' => $doctor->account->email,
          'image' => $doctor->image,
          'avatar' => $doctor->account->avatar,
          'phone' => $doctor->phone,
          'is_reviewed' => $doctorIsReviewed,
          'doctor_responsed' => $doctorShopResponsed,
          'rating_info' => $doctorRatingInfo,
        ],
        'medical_center' => [
          'id' => $medicalCenter->id,
          'name' => $medicalCenter->name,
          'email' => $medicalCenter->email,
          'image' => $medicalCenter->image,
          'avatar' => $medicalCenter->account->avatar,
          'phone' => $medicalCenter->phone,
          'address' => $medicalCenter->address,
          'is_reviewed' => $medicalCenterIsReviewed,
          'medical_center_responsed' => $medicalCenterShopResponsed,
          'rating_info' => $medicalCenterRatingInfo,
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

  // ==================================     FOR MEDICAL CENTER     ==================================
  public function getListDoneAppointments(Request $request)
  {
    $medical_center_id = auth()->user()->medicalCenter->id;

    $page_number = intval($request->query('page_number', 1));
    $num_of_page = intval($request->query('num_of_page', 10));
    $sort_by = $request->query('sort_by', 'newest');
    $date = $request->query('date');

    // Tính toán offset
    $offset = ($page_number - 1) * $num_of_page;

    // Lấy số lượng các cuộc hẹn đã hoàn thành
    $query = Appointment::withTrashed()
      ->whereHas('doctor', function ($doctorQuery) use ($medical_center_id) {
        $doctorQuery->where('medical_center_id', $medical_center_id);
      })
      ->with(['doctor.account', 'doctor.medicalCenter', 'pet.breed', 'customer.account'])
      ->where('done', true);

    // Thêm bộ lọc theo ngày nếu tham số 'date' được cung cấp
    if ($date) {
      $query->whereDate('start_time', '=', $date);
    }

    $total_appointments = $query->count();
    $total_pages = ceil($total_appointments / $num_of_page);

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
      $doctor = $appointment->doctor;
      $medicalCenter = $doctor->medicalCenter;

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
          'age' => $appointment->pet->age,
          'breed' => $appointment->pet->breed->name,
          'image' => $appointment->pet->breed->image,
        ],
        'doctor' => [
          'id' => $doctor->id,
          'full_name' => $doctor->full_name,
          'email' => $doctor->account->email,
          'image' => $doctor->image,
          'avatar' => $doctor->account->avatar,
          'phone' => $doctor->phone,
        ],
        'medical_center' => [
          'id' => $medicalCenter->id,
          'name' => $medicalCenter->name,
          'email' => $medicalCenter->email,
          'image' => $medicalCenter->image,
          'avatar' => $medicalCenter->account->avatar,
          'phone' => $medicalCenter->phone,
          'address' => $medicalCenter->address,
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
    ]);
  }

  public function getListWaitingAppointments(Request $request)
  {
    $medical_center_id = auth()->user()->medicalCenter->id;

    $page_number = intval($request->query('page_number', 1));
    $num_of_page = intval($request->query('num_of_page', 10));
    $sort_by = $request->query('sort_by', 'newest');
    $date = $request->query('date');

    // Tính toán offset
    $offset = ($page_number - 1) * $num_of_page;

    // Lấy số lượng các cuộc hẹn đang chờ
    $query = Appointment::withTrashed()
      ->whereHas('doctor', function ($doctorQuery) use ($medical_center_id) {
        $doctorQuery->where('medical_center_id', $medical_center_id);
      })
      ->with(['doctor.account', 'doctor.medicalCenter', 'pet.breed', 'customer.account'])
      ->where('done', false);

    // Thêm bộ lọc theo ngày nếu tham số 'date' được cung cấp
    if ($date) {
      $query->whereDate('start_time', '=', $date);
    }

    $total_appointments = $query->count();
    $total_pages = ceil($total_appointments / $num_of_page);

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
      $doctor = $appointment->doctor;
      $medicalCenter = $doctor->medicalCenter;

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
          'age' => $appointment->pet->age,
          'breed' => $appointment->pet->breed->name,
          'image' => $appointment->pet->breed->image,
        ],
        'doctor' => [
          'id' => $doctor->id,
          'full_name' => $doctor->full_name,
          'email' => $doctor->account->email,
          'image' => $doctor->image,
          'avatar' => $doctor->account->avatar,
          'phone' => $doctor->phone,
        ],
        'medical_center' => [
          'id' => $medicalCenter->id,
          'name' => $medicalCenter->name,
          'email' => $medicalCenter->email,
          'image' => $medicalCenter->image,
          'avatar' => $medicalCenter->account->avatar,
          'phone' => $medicalCenter->phone,
          'address' => $medicalCenter->address,
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
    ]);
  }

  public function getAppointmentDetail($appointment_id)
  {
    $appointment = Appointment::query()
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

  public function restore($appointment_id)
  {
    $doctor_id = auth()->user()->doctor->id;

    $appointment = Appointment::onlyTrashed()
      ->where('id', $appointment_id)
      ->where('doctor_id', $doctor_id)
      ->first();

    if (!$appointment) {
      return response()->json([
        'message' => 'Appointment not found or does not belong to the doctor',
        'status' => 404,
      ], 404);
    }

    $appointment->restore();

    return response()->json([
      'message' => 'Restore appointment successfully!',
      'status' => 200,
    ], 200);
  }

  // ==================================     FOR DOCTOR     ==================================
  public function getListDoneAppointmentsByDoctor(Request $request)
  {
    $doctorId = auth()->user()->doctor->id;

    $pageNumber = intval($request->query('page_number', 1));
    $numOfPage = intval($request->query('num_of_page', 10));
    $sortBy = $request->query('sort_by', 'newest');
    $date = $request->query('date');

    // Tính toán offset
    $offset = ($pageNumber - 1) * $numOfPage;

    // Lấy số lượng các cuộc hẹn đã hoàn thành
    $query = Appointment::where('doctor_id', $doctorId)
      ->where('done', true)
      ->with(['customer.account', 'pet']);

    // Thêm bộ lọc theo ngày nếu tham số 'date' được cung cấp
    if ($date) {
      $query->whereDate('start_time', '=', $date);
    }

    $totalAppointments = $query->count();
    $totalPages = ceil($totalAppointments / $numOfPage);

    if ($sortBy === 'oldest') {
      $appointments = $query->orderBy('start_time', 'asc')
        ->offset($offset)
        ->limit($numOfPage)
        ->get();
    } else {
      $appointments = $query->orderBy('start_time', 'desc')
        ->offset($offset)
        ->limit($numOfPage)
        ->get();
    }

    return response()->json([
      'message' => 'Fetch done appointments successfully!',
      'status' => 200,
      'page_number' => $pageNumber,
      'num_of_page' => $numOfPage,
      'total_pages' => $totalPages,
      'total_appointments' => $totalAppointments,
      'data' => $appointments,
    ]);
  }

  public function getListWaitingAppointmentsByDoctor(Request $request)
  {
    $doctorId = auth()->user()->doctor->id;

    $pageNumber = intval($request->query('page_number', 1));
    $numOfPage = intval($request->query('num_of_page', 10));
    $sortBy = $request->query('sort_by', 'newest');
    $date = $request->query('date');

    // Tính toán offset
    $offset = ($pageNumber - 1) * $numOfPage;

    // Lấy số lượng các cuộc hẹn đang chờ
    $query = Appointment::where('doctor_id', $doctorId)
      ->where('done', false)
      ->with(['customer.account', 'pet']);

    // Thêm bộ lọc theo ngày nếu tham số 'date' được cung cấp
    if ($date) {
      $query->whereDate('start_time', '=', $date);
    }

    $totalAppointments = $query->count();
    $totalPages = ceil($totalAppointments / $numOfPage);

    if ($sortBy === 'oldest') {
      $appointments = $query->orderBy('start_time', 'asc')
        ->offset($offset)
        ->limit($numOfPage)
        ->get();
    } else {
      $appointments = $query->orderBy('start_time', 'desc')
        ->offset($offset)
        ->limit($numOfPage)
        ->get();
    }

    return response()->json([
      'message' => 'Fetch waiting appointments successfully!',
      'status' => 200,
      'page_number' => $pageNumber,
      'num_of_page' => $numOfPage,
      'total_pages' => $totalPages,
      'total_appointments' => $totalAppointments,
      'data' => $appointments,
    ]);
  }
}
