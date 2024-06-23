<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\RatingDoctor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RatingDoctorController extends Controller
{
  public function getCustomerRatingsOfDoctorId(Request $request, $doctor_id)
  {
    // Kiểm tra xem doctor có tồn tại hay không
    $doctorExists = DB::table('doctors')->where('id', $doctor_id)->exists();

    if (!$doctorExists) {
      return response()->json([
        'message' => 'Doctor not found!',
        'status' => 404
      ], 404);
    }

    $user = auth()->user();

    $ratings = RatingDoctor::with(['customer.account', 'customer.ratings', 'interacts.account'])
      ->where('doctor_id', $doctor_id)
      ->orderBy('created_at', 'desc')
      ->get()
      ->map(function ($rating) use ($user) {
        $customer = $rating->customer;
        $account = $customer->account;

        // Lấy thông tin về lượt like
        $likes = $rating->interacts->map(function ($interact) {
          return [
            'account_id' => $interact->account_id,
            'username' => $interact->account->username,
            'avatar' => $interact->account->avatar,
          ];
        });

        // Kiểm tra xem user hiện tại có nằm trong danh sách likes hay không
        $user_liked = $rating->interacts->contains('account_id', $user->id);

        return [
          'rating_id' => $rating->id,
          'rating_score' => $rating->rating,
          'description' => $rating->description,
          'reply' => $rating->reply,
          'reply_date' => $rating->reply_date,
          'rating_date' => $rating->created_at,
          'customer_id' => $customer->id,
          'customer_username' => $account->username,
          'customer_avatar' => $account->avatar,
          'account_creation_date' => $account->created_at,
          'customer_rating_count' => $customer->ratings->count(),
          'customer_ranking_point' => $customer->ranking_point ?? 0,
          'likes' => [
            'total_likes' => $rating->interacts->count(),
            'user_liked' => $user_liked,
            'details' => $likes,
          ]
        ];
      });

    // Tính tổng số ratings và tổng số trang
    $totalRatings = $ratings->count();
    $num_of_page = intval($request->query('num_of_page', 5));
    $total_pages = ceil($totalRatings / $num_of_page);
    $page_number = intval($request->query('page_number', 1));

    // Phân trang dữ liệu ratings
    $paginatedRatings = $ratings->forPage($page_number, $num_of_page)->values();

    return response()->json([
      'message' => 'Query successfully!',
      'status' => 200,
      'page_number' => $page_number,
      'num_of_page' => $num_of_page,
      'total_pages' => $total_pages,
      'total_ratings' => $totalRatings,
      'data' => $paginatedRatings,
    ]);
  }

  public function getDetailRatingDoctorOfCustomer($doctor_id)
  {
    // Lấy thông tin khách hàng đang đăng nhập
    $customer = auth()->user()->customer;

    if (!$customer) {
      return response()->json([
        'message' => 'Customer not found!',
        'status' => 404,
      ], 404);
    }

    // Lấy thông tin đánh giá sản phẩm dựa trên rating_id và customer_id
    $rating = RatingDoctor::with(['doctor', 'doctor.account'])
      ->where('doctor_id', $doctor_id)
      ->where('customer_id', $customer->id)
      ->first();

    if (!$rating) {
      return response()->json([
        'message' => 'Rating not found!',
        'status' => 404,
      ], 404);
    }

    // Chuẩn bị dữ liệu trả về
    $result = [
      'rating_id' => $rating->id,
      'rating' => $rating->rating,
      'rating_description' => $rating->description,
      'reply' => $rating->reply,
      'reply_date' => $rating->reply_date,
      'created_at' => $rating->created_at,
      'updated_at' => $rating->updated_at,
      'doctor_id' => $rating->doctor->id,
      'full_name' => $rating->doctor->full_name,
      'username' => $rating->doctor->account->username,
      'email' => $rating->doctor->account->email,
      'avatar' => $rating->doctor->account->avatar,
      'gender' => $rating->doctor->gender,
      'birthdate' => $rating->doctor->birthdate,
      'doctor_description' => $rating->doctor->description,
      'CMND' => $rating->doctor->CMND,
      'phone' => $rating->doctor->phone,
      'address' => $rating->doctor->address,
      'image' => $rating->doctor->image,
      'certificate' => $rating->doctor->certificate,
    ];

    return response()->json([
      'message' => 'Query successfully!',
      'status' => 200,
      'data' => $result,
    ]);
  }

  // ====================================     For Customer     ====================================
  public function createRatingDoctor(Request $request, $doctor_id)
  {
    // Kiểm tra xem doctor có tồn tại hay không
    $doctorExists = DB::table('doctors')->where('id', $doctor_id)->exists();

    if (!$doctorExists) {
      return response()->json([
        'message' => 'Doctor not found!',
        'status' => 404
      ], 404);
    }

    $validatedData = $request->validate([
      'rating' => 'required|integer|min:1|max:5',
      'description' => 'required|string',
    ], [
      'rating.required' => 'Please provide a rating.',
      'rating.integer' => 'The rating must be a valid integer.',
      'rating.min' => 'The rating must be at least 1.',
      'rating.max' => 'The rating must be between 1 and 5.',
      'description.required' => 'Please provide a description.',
      'description.string' => 'The description must be a valid string.',
    ]);

    $rating = new RatingDoctor();
    $rating->rating = $validatedData['rating'];
    $rating->description = $validatedData['description'];
    $rating->customer_id = auth()->user()->id;
    $rating->doctor_id = $doctor_id;
    $rating->save();

    return response()->json([
      'message' => 'Doctor rated successfully!',
      'data' => $rating,
    ], 201);
  }

  public function updateRatingDoctor(Request $request, $rating_id)
  {
    // Kiểm tra xem rating có tồn tại hay không
    $rating = RatingDoctor::find($rating_id);

    if (!$rating) {
      return response()->json([
        'message' => 'Rating not found!',
        'status' => 404
      ], 404);
    }

    // Kiểm tra xem người dùng có quyền cập nhật rating này hay không
    if ($rating->customer_id !== auth()->user()->id) {
      return response()->json([
        'message' => 'You do not have permission to update this rating.',
        'status' => 403
      ], 403);
    }

    $validatedData = $request->validate([
      'rating' => 'sometimes|required|integer|min:1|max:5',
      'description' => 'sometimes|required|string',
    ], [
      'rating.required' => 'Please provide a rating.',
      'rating.integer' => 'The rating must be a valid integer.',
      'rating.min' => 'The rating must be at least 1.',
      'rating.max' => 'The rating must be between 1 and 5.',
      'description.required' => 'Please provide a description.',
      'description.string' => 'The description must be a valid string.',
    ]);

    // Cập nhật rating
    $rating->update($validatedData);

    return response()->json([
      'message' => 'Rating updated successfully!',
      'data' => $rating,
    ], 200);
  }

  public function deleteRatingDoctor($rating_id)
  {
    // Kiểm tra xem rating có tồn tại hay không
    $rating = RatingDoctor::find($rating_id);

    if (!$rating) {
      return response()->json([
        'message' => 'Rating not found!',
        'status' => 404
      ], 404);
    }

    // Kiểm tra xem người dùng có quyền xóa rating này hay không
    if ($rating->customer_id !== auth()->user()->id) {
      return response()->json([
        'message' => 'You do not have permission to delete this rating.',
        'status' => 403
      ], 403);
    }

    // Xóa rating
    $rating->delete();

    return response()->json([
      'message' => 'Rating deleted successfully!',
      'status' => 200
    ], 200);
  }
}
