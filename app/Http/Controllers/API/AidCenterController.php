<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\HistoryAdopt;
use App\Models\Pet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AidCenterController extends Controller
{
  public function getUnadoptedPetsOfAidCenter(Request $request, $aid_center_id)
  {
    // Lấy tham số page_number và num_of_page từ request
    $page_number = intval($request->query('page_number', 1));
    $num_of_page = intval($request->query('num_of_page', 10));

    $query = Pet::where('status', 0)
      ->where('aid_center_id', $aid_center_id)
      ->whereNull('customer_id');

    // Tính tổng số pets và tổng số trang
    $total_pets = $query->count();
    $total_pages = ceil($total_pets / $num_of_page);

    // Phân trang
    $pets = $query->with(['aidCenter', 'aidCenter.account'])
      ->offset(($page_number - 1) * $num_of_page)
      ->limit($num_of_page)
      ->get();

    // Extract aid center information from the first pet
    $aid_center_info = [];
    if ($pets->isNotEmpty()) {
      $firstPet = $pets->first();
      $aid_center_info = [
        'id' => $firstPet->aidCenter->id,
        'name' => $firstPet->aidCenter->name,
        'username' => $firstPet->aidCenter->account->username,
        'email' => $firstPet->aidCenter->account->email,
        'avatar' => $firstPet->aidCenter->account->avatar,
        'image' => $firstPet->aidCenter->image,
        'phone' => $firstPet->aidCenter->phone,
        'address' => $firstPet->aidCenter->address,
        'website' => $firstPet->aidCenter->website,
        'fanpage' => $firstPet->aidCenter->fanpage,
        'work_time' => $firstPet->aidCenter->work_time,
        'establish_year' => $firstPet->aidCenter->establish_year,
        'certificate' => $firstPet->aidCenter->certificate,
      ];
    }

    // Format pets data
    $formatted_pets = $pets->map(function ($pet) {
      return [
        'id' => $pet->id,
        'name' => $pet->name,
        'type' => $pet->type,
        'breed' => $pet->breed->name,
        'age' => $pet->age,
        'gender' => $pet->gender,
        'image' => $pet->image,
        'status' => $pet->status,
        'customer_id' => $pet->customer_id,
        'created_at' => $pet->created_at,
        'updated_at' => $pet->updated_at,
      ];
    });

    // Construct response with aid center information at the top level
    return response()->json([
      'message' => 'Query successfully!',
      'status' => 200,
      'page_number' => $page_number,
      'num_of_page' => $num_of_page,
      'total_pages' => $total_pages,
      'total_pets' => $total_pets,
      'data' => [ // Use data block to include both aid_center and pets
        'aid_center' => $aid_center_info, // Aid center info here
        'pets' => $formatted_pets, // Only pets data here
      ]
    ]);
  }

  public function getUnadoptedPets(Request $request)
  {
    // Lấy tham số page_number và num_of_page từ request
    $page_number = intval($request->query('page_number', 1));
    $num_of_page = intval($request->query('num_of_page', 10));

    $query = Pet::where('status', 0)->whereNull('customer_id');

    // Tính tổng số pets và tổng số trang
    $total_pets = $query->count();
    $total_pages = ceil($total_pets / $num_of_page);

    // Phân trang
    $pets = $query->with('aidCenter')
      ->offset(($page_number - 1) * $num_of_page)
      ->limit($num_of_page)
      ->get();

    // Định dạng dữ liệu trả về
    $formatted_pets = $pets->map(function ($pet) {
      $pet_data = [
        'id' => $pet->id,
        'name' => $pet->name,
        'type' => $pet->type,
        'breed' => $pet->breed->name,
        'age' => $pet->age,
        'gender' => $pet->gender,
        'image' => $pet->image,
        'status' => $pet->status,
        'customer_id' => $pet->customer_id,
        'aid_center' => $pet->aidCenter ? [
          'name' => $pet->aidCenter->name,
        ] : null,
        'created_at' => $pet->created_at,
        'updated_at' => $pet->updated_at
      ];

      return $pet_data;
    });

    // Trả về JSON response
    return response()->json([
      'message' => 'Query successfully!',
      'status' => 200,
      'page_number' => $page_number,
      'num_of_page' => $num_of_page,
      'total_pages' => $total_pages,
      'total_pets' => $total_pets,
      'data' => $formatted_pets,
    ]);
  }

  public function getDetailUnadpotedPet($pet_id)
  {
    // Lấy thông tin chi tiết của pet chưa được adopt
    $pet = Pet::with('aidCenter')
      ->whereNull('customer_id')
      ->where('status', 0)
      ->where('id', $pet_id)
      ->first();

    if (!$pet) {
      return response()->json([
        'message' => 'Pet not found or already adopted!',
        'status' => 404
      ], 404);
    }

    // Định dạng dữ liệu trả về
    $pet_data = [
      'id' => $pet->id,
      'name' => $pet->name,
      'type' => $pet->type,
      'age' => $pet->age,
      'gender' => $pet->gender,
      'description' => $pet->description,
      'image' => $pet->image,
      'breed_name' => $pet->breed->name,
      'breed_type' => $pet->breed->type,
      'breed_description' => $pet->breed->description,
      'breed_image' => $pet->breed->image,
      'breed_origin' => $pet->breed->origin,
      'breed_lifespan' => $pet->breed->lifespan,
      'breed_average_size' => $pet->breed->average_size,
      'aid_center' => [
        'id' => $pet->aidCenter->id,
        'name' => $pet->aidCenter->name,
        'description' => $pet->aidCenter->description,
        'image' => $pet->aidCenter->image,
        'phone' => $pet->aidCenter->phone,
        'address' => $pet->aidCenter->address,
        'website' => $pet->aidCenter->website,
        'fanpage' => $pet->aidCenter->fanpage,
        'work_time' => $pet->aidCenter->work_time,
        'establish_year' => $pet->aidCenter->establish_year,
        'certificate' => $pet->aidCenter->certificate,
        'created_at' => $pet->aidCenter->created_at,
        'updated_at' => $pet->aidCenter->updated_at,
      ],
      'created_at' => $pet->created_at,
      'updated_at' => $pet->updated_at,
    ];

    return response()->json([
      'message' => 'Query successfully!',
      'status' => 200,
      'data' => $pet_data,
    ]);
  }

  public function getMyAdoptedPets(Request $request)
  {
    $user = auth()->user();

    // Lấy tham số page_number và num_of_page từ request
    $page_number = intval($request->query('page_number', 1));
    $num_of_page = intval($request->query('num_of_page', 10));

    // Truy vấn danh sách các pets đã được nhận nuôi bởi customer
    $query = HistoryAdopt::where('customer_id', $user->id)
      ->with('pet.breed', 'pet.aidCenter')
      ->orderBy('created_at', 'desc');

    // Tính tổng số pets đã nhận nuôi và tổng số trang
    $total_pets = $query->count();
    $total_pages = ceil($total_pets / $num_of_page);

    // Phân trang
    $adoptions = $query->offset(($page_number - 1) * $num_of_page)
      ->limit($num_of_page)
      ->get();

    // Định dạng dữ liệu trả về
    $formatted_adoptions = $adoptions->map(function ($adoption) {
      $pet = $adoption->pet;

      if (!$pet) {
        return null; // Tránh lỗi khi pet không tồn tại
      }

      $breed_name = optional($pet->breed)->name; // Lấy tên breed (nếu tồn tại)

      return [
        'id' => $pet->id,
        'name' => $pet->name,
        'type' => $pet->type,
        'breed' => $breed_name, // Sử dụng null-safe operator
        'age' => $pet->age,
        'gender' => $pet->gender,
        'description' => $pet->description,
        'image' => $pet->image,
        'adopted_at' => $adoption->created_at,
        'updated_at' => $pet->updated_at,
      ];
    });

    // Loại bỏ các pet không tồn tại
    $formatted_adoptions = $formatted_adoptions->filter();

    // Trả về JSON response
    return response()->json([
      'message' => 'Query successfully!',
      'status' => 200,
      'page_number' => $page_number,
      'num_of_page' => $num_of_page,
      'total_pages' => $total_pages,
      'total_adoptions' => $total_pets,
      'data' => $formatted_adoptions->values(), // Đảm bảo trả về mảng chỉ số
    ]);
  }

  public function getDetailMyAdoptedPets($pet_id)
  {
    $pet = Pet::with('aidCenter')
      ->where('id', $pet_id)
      ->whereHas('historyAdoptions', function ($query) {
        $query->where('customer_id', auth()->user()->id);
      })
      ->where('status', 1)
      ->first();


    if (!$pet) {
      return response()->json([
        'message' => 'Pet not found or not belong to customer',
        'status' => 404
      ], 404);
    }

    // Đảm bảo quan hệ đã được load
    if (!$pet->aidCenter) {
      return response()->json([
        'message' => 'Aid Center not found for this pet',
        'status' => 404
      ], 404);
    }

    // Định dạng dữ liệu trả về
    $pet_data = [
      'id' => $pet->id,
      'name' => $pet->name,
      'type' => $pet->type,
      'age' => $pet->age,
      'gender' => $pet->gender,
      'description' => $pet->description,
      'image' => $pet->image,
      'breed_name' => $pet->breed->name,
      'breed_type' => $pet->breed->type,
      'breed_description' => $pet->breed->description,
      'breed_image' => $pet->breed->image,
      'breed_origin' => $pet->breed->origin,
      'breed_lifespan' => $pet->breed->lifespan,
      'breed_average_size' => $pet->breed->average_size,
      'aid_center' => optional($pet->aidCenter)->only([
        'id', 'name', 'description', 'image', 'phone', 'address', 'website', 'fanpage',
        'work_time', 'establish_year', 'certificate', 'created_at', 'updated_at'
      ]),
      'created_at' => $pet->created_at,
      'updated_at' => $pet->updated_at,
    ];

    return response()->json([
      'message' => 'Query successfully!',
      'status' => 200,
      'data' => $pet_data,
    ]);
  }

  public function adoptPet($pet_id)
  {
    // Xác thực người dùng là khách hàng
    $customer = auth()->user();

    // Kiểm tra xem pet có tồn tại và chưa được nhận nuôi
    $pet = Pet::where('id', $pet_id)
      ->whereNull('customer_id') // Chưa có người nhận nuôi
      ->where('status', 0) // Còn trong danh sách pets chưa được nhận nuôi
      ->first();

    if (!$pet) {
      return response()->json(['message' => 'Pet not found or already adopted.'], 404);
    }

    // Bắt đầu transaction để đảm bảo tính toàn vẹn dữ liệu
    DB::beginTransaction();

    try {
      // Tạo bản ghi trong history_adoptions
      $adoption = new HistoryAdopt();
      $adoption->customer_id = $customer->id;
      $adoption->pet_id = $pet->id;
      $adoption->save();

      // Cập nhật trường status của pet thành true
      $pet->status = true;
      $pet->save();

      // Commit transaction nếu thành công
      DB::commit();

      return response()->json([
        'message' => 'Pet adopted successfully!',
        'data' => [
          'pet_id' => $pet->id,
          'pet_name' => $pet->name,
          'customer_id' => $customer->id,
          'customer_name' => $customer->name,
          'adoption_date' => $adoption->created_at,
        ],
      ], 200);
    } catch (\Exception $e) {
      // Rollback transaction nếu có lỗi
      DB::rollback();

      return response()->json(['message' => 'Failed to adopt pet.'], 500);
    }
  }
}
