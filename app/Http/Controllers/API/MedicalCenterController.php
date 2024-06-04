<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\MedicalCenter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MedicalCenterController extends Controller
{
  public function show($medical_center_id)
  {
    if (!MedicalCenter::find($medical_center_id)) {
      return response()->json([
        'message' => 'Medical Center not found!',
        'status' => 404
      ], 404);
    }

    $medical_center = MedicalCenter::query()->whereNull('deleted_at')->find($medical_center_id);

    $ratingMedicalCenterData = $medical_center->calculateMedicalCenterRating();

    // Format lại dữ liệu bác sĩ
    $formatted_doctors = [];
    foreach ($medical_center->doctors as $doctor) {
      $ratingDoctorData = $doctor->calculateDoctorRating();

      $formatted_doctors[] = [
        'doctor_id' => $doctor->id,
        'account_id' => $doctor->account_id,
        'username' => $doctor->account->username,
        'email' => $doctor->account->email,
        'avatar' => $doctor->account->avatar,
        'full_name' => $doctor->full_name,
        'gender' => $doctor->gender,
        'birthdate' => $doctor->birthdate,
        'description' => $doctor->description,
        'CMND' => $doctor->CMND,
        'phone' => $doctor->phone,
        'address' => $doctor->address,
        'image' => $doctor->image,
        'certificate' => $doctor->certificate,
        'rating' => $ratingDoctorData['average'],
        'rating_count' => $ratingDoctorData['count'],
      ];
    }

    $formatted_medical_center = [
      'medical_center_id' => $medical_center->id,
      'account_id' => $medical_center->account->id,
      'username' => $medical_center->account->username,
      'email' => $medical_center->account->email,
      'avatar' => $medical_center->account->avatar,
      'name' => $medical_center->name,
      'description' => $medical_center->description,
      'image' => $medical_center->image,
      'phone' => $medical_center->phone,
      'address' => $medical_center->address,
      'website' => $medical_center->website,
      'fanpage' => $medical_center->fanpage,
      'work_time' => $medical_center->work_time,
      'establish_year' => $medical_center->establish_year,
      'created_at' => $medical_center->created_at,
      'updated_at' => $medical_center->updated_at,
      'rating' => $ratingMedicalCenterData['average'],
      'rating_count' => $ratingMedicalCenterData['count'],
      'doctors' => $formatted_doctors,
    ];

    return response()->json([
      'message' => 'Query successfully!',
      'status' => 200,
      'data' => $formatted_medical_center,
    ], 200);
  }

  public function paging(Request $request)
  {
    $page_number = intval($request->query('page_number', 1));
    $num_of_page = intval($request->query('num_of_page', 10));

    // Lấy số lượng trung tâm y tế
    $total_medical_centers = MedicalCenter::count();
    $total_pages = ceil($total_medical_centers / $num_of_page);

    // Tính toán offset
    $offset = ($page_number - 1) * $num_of_page;

    // Lấy dữ liệu trung tâm y tế dựa trên trang hiện tại và số lượng trung tâm y tế trên mỗi trang
    $medical_centers = MedicalCenter::whereNull('deleted_at')
      ->offset($offset)
      ->limit($num_of_page)
      ->get();

    // Format lại dữ liệu trung tâm y tế
    $formatted_medical_centers = [];
    foreach ($medical_centers as $medical_center) {
      // Tính trung bình rating và số lượng rating
      $ratingMedicalCenterData = $medical_center->calculateMedicalCenterRating();

      $formatted_medical_centers[] = [
        'medical_center_id' => $medical_center->id,
        'account_id' => $medical_center->account->id,
        'username' => $medical_center->account->username,
        'email' => $medical_center->account->email,
        'avatar' => $medical_center->account->avatar,
        'name' => $medical_center->name,
        'description' => $medical_center->description,
        'image' => $medical_center->image,
        'phone' => $medical_center->phone,
        'address' => $medical_center->address,
        'website' => $medical_center->website,
        'fanpage' => $medical_center->fanpage,
        'work_time' => $medical_center->work_time,
        'establish_year' => $medical_center->establish_year,
        'created_at' => $medical_center->created_at,
        'updated_at' => $medical_center->updated_at,
        'rating' => $ratingMedicalCenterData['average'],
        'rating_count' => $ratingMedicalCenterData['count'],
      ];
    }

    // Trả về JSON response
    return response()->json([
      'message' => 'Query successfully!',
      'status' => 200,
      'page_number' => $page_number,
      'num_of_page' => $num_of_page,
      'total_pages' => $total_pages,
      'total_medical_centers' => $total_medical_centers,
      'data' => $formatted_medical_centers,
    ]);
  }

  public function searchMedicalCenter(Request $request)
  {
    $name = $request->query('name');

    // Phân trang mặc định
    $page_number = intval($request->query('page_number', 1));
    $num_of_page = intval($request->query('num_of_page', 8));

    $query = MedicalCenter::query()->whereNull('deleted_at');

    if ($name) {
      $query->where('name', 'like', '%' . $name . '%');
    }

    // CHECK FOR ROLE_MEDICAL_CENTER
    $user = auth()->user();
    $role_user = DB::table('roles')->where('id', '=', $user->role_id)->value('role_name');
    if ($role_user === 'ROLE_MEDICAL_CENTER') {
      $medical_centers = DB::table('medical_centers')
        ->where('account_id', '=', $user->id)
        ->paginate($num_of_page, ['*'], 'page', $page_number);
    } else {
      $medical_centers = $query->paginate($num_of_page, ['*'], 'page', $page_number);
    }

    // Format lại dữ liệu trung tâm y tế
    $formatted_medical_centers = [];
    foreach ($medical_centers as $medical_center) {
      // Tính trung bình rating và số lượng rating
      $ratingMedicalCenterData = $medical_center->calculateMedicalCenterRating();

      $formatted_medical_centers[] = [
        'medical_center_id' => $medical_center->id,
        'account_id' => $medical_center->account->id,
        'username' => $medical_center->account->username,
        'email' => $medical_center->account->email,
        'avatar' => $medical_center->account->avatar,
        'name' => $medical_center->name,
        'description' => $medical_center->description,
        'image' => $medical_center->image,
        'phone' => $medical_center->phone,
        'address' => $medical_center->address,
        'website' => $medical_center->website,
        'fanpage' => $medical_center->fanpage,
        'work_time' => $medical_center->work_time,
        'establish_year' => $medical_center->establish_year,
        'created_at' => $medical_center->created_at,
        'updated_at' => $medical_center->updated_at,
        'rating' => $ratingMedicalCenterData['average'],
        'rating_count' => $ratingMedicalCenterData['count'],
      ];
    }

    // Trả về JSON response
    return response()->json([
      'message' => 'Search medical center successfully!',
      'status' => 200,
      'pagination' => [
        'total' => $medical_centers->total(),
        'per_page' => $medical_centers->perPage(),
        'current_page' => $medical_centers->currentPage(),
        'last_page' => $medical_centers->lastPage(),
        'from' => $medical_centers->firstItem(),
        'to' => $medical_centers->lastItem(),
      ],
      'data' => $formatted_medical_centers,
    ]);
  }

  public function getHighestRatingMedicalCenter(Request $request)
  {
    $page_number = intval($request->query('page_number', 1));
    $num_of_page = intval($request->query('num_of_page', 10));

    $medical_center_query = MedicalCenter::query()->whereNull('deleted_at');

    $total_medical_centers = $medical_center_query->count();
    $total_pages = ceil($total_medical_centers / $num_of_page);
    $offset = ($page_number - 1) * $num_of_page;

    $averageRatings = DB::table('rating_medical_centers')
      ->select('medical_center_id', DB::raw('AVG(rating) as average_rating'))
      ->groupBy('medical_center_id');

    $medical_centers = $medical_center_query
      ->leftJoinSub($averageRatings, 'average_ratings', function ($join) {
        $join->on('medical_centers.id', '=', 'average_ratings.medical_center_id');
      })
      ->select('medical_centers.*', 'average_ratings.average_rating')
      ->orderBy('average_rating', 'desc')
      ->offset($offset)
      ->limit($num_of_page)
      ->get();

    // Format lại dữ liệu trung tâm y tế
    $formatted_medical_centers = [];
    foreach ($medical_centers as $medical_center) {
      // Tính trung bình rating và số lượng rating
      $ratingMedicalCenterData = $medical_center->calculateMedicalCenterRating();

      $formatted_medical_centers[] = [
        'medical_center_id' => $medical_center->id,
        'account_id' => $medical_center->account->id,
        'username' => $medical_center->account->username,
        'email' => $medical_center->account->email,
        'avatar' => $medical_center->account->avatar,
        'name' => $medical_center->name,
        'description' => $medical_center->description,
        'image' => $medical_center->image,
        'phone' => $medical_center->phone,
        'address' => $medical_center->address,
        'website' => $medical_center->website,
        'fanpage' => $medical_center->fanpage,
        'work_time' => $medical_center->work_time,
        'establish_year' => $medical_center->establish_year,
        'created_at' => $medical_center->created_at,
        'updated_at' => $medical_center->updated_at,
        'rating' => $ratingMedicalCenterData['average'],
        'rating_count' => $ratingMedicalCenterData['count'],
      ];
    }

    // Trả về JSON response
    return response()->json([
      'message' => 'Search medical center successfully!',
      'status' => 200,
      'page_number' => $page_number,
      'num_of_page' => $num_of_page,
      'total_pages' => $total_pages,
      'total_products' => $total_medical_centers,
      'data' => $formatted_medical_centers,
    ]);
  }
}
