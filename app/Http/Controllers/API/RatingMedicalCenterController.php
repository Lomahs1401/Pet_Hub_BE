<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\MedicalCenter;
use App\Models\RatingMedicalCenter;
use App\Models\RatingMedicalCenterInteract;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RatingMedicalCenterController extends Controller
{
  public function getCustomerRatingsOfMedicalCenterId(Request $request, $medical_center_id)
  {
    // Kiểm tra xem medical center có tồn tại hay không
    $medicalCenterExists = DB::table('medical_centers')->where('id', $medical_center_id)->exists();

    if (!$medicalCenterExists) {
      return response()->json([
        'message' => 'Medical center not found!',
        'status' => 404
      ], 404);
    }

    $user = auth()->user();

    $ratings = RatingMedicalCenter::with(['customer.account', 'customer.ratings', 'interacts.account'])
      ->where('medical_center_id', $medical_center_id)
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

  public function getDetailRating($shop_id)
  {
    $shop = MedicalCenter::withTrashed()->find($shop_id);
    if (!$shop) {
      return response()->json([
        'message' => 'Medical center not found!',
        'status' => 404
      ], 404);
    }

    // Lấy số lượng rating cho từng mức điểm từ 1 đến 5
    $ratings = RatingMedicalCenter::select('rating', DB::raw('count(*) as count'))
      ->where('medical_center_id', $shop_id)
      ->groupBy('rating')
      ->orderBy('rating', 'desc')
      ->get();

    // Tạo một mảng để lưu số lượng các rating từ 1 đến 5 sao
    $ratingCounts = [
      'five_star' => 0,
      'four_star' => 0,
      'three_star' => 0,
      'two_star' => 0,
      'one_star' => 0,
    ];

    // Cập nhật số lượng rating vào mảng
    foreach ($ratings as $rating) {
      switch ($rating->rating) {
        case 5:
          $ratingCounts['five_star'] = $rating->count;
          break;
        case 4:
          $ratingCounts['four_star'] = $rating->count;
          break;
        case 3:
          $ratingCounts['three_star'] = $rating->count;
          break;
        case 2:
          $ratingCounts['two_star'] = $rating->count;
          break;
        case 1:
          $ratingCounts['one_star'] = $rating->count;
          break;
      }
    }

    // Trả về kết quả dưới dạng JSON
    return response()->json([
      'message' => 'Query successfully!',
      'status' => 200,
      'data' => $ratingCounts
    ]);
  }

  public function getDetailRatingMedicalCenterOfCustomer($medical_center_id)
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
    $rating = RatingMedicalCenter::with(['medicalCenter', 'medicalCenter.account'])
      ->where('medical_center_id', $medical_center_id)
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
      'medical_center_id' => $rating->medicalCenter->id,
      'name' => $rating->medicalCenter->name,
      'username' => $rating->medicalCenter->account->username,
      'email' => $rating->medicalCenter->account->email,
      'avatar' => $rating->medicalCenter->account->avatar,
      'medical_center_description' => $rating->medicalCenter->description,
      'image' => $rating->medicalCenter->image,
      'phone' => $rating->medicalCenter->phone,
      'address' => $rating->medicalCenter->address,
      'website' => $rating->medicalCenter->website,
      'fanpage' => $rating->medicalCenter->fanpage,
      'work_time' => $rating->medicalCenter->work_time,
      'establish_year' => $rating->medicalCenter->establish_year,
    ];

    return response()->json([
      'message' => 'Query successfully!',
      'status' => 200,
      'data' => $result,
    ]);
  }

  // ====================================     For Customer     ====================================
  public function createRatingMedicalCenter(Request $request, $medical_center_id)
  {
    // Kiểm tra xem medical center có tồn tại hay không
    $medicalCenterExists = DB::table('medical_centers')->where('id', $medical_center_id)->exists();

    if (!$medicalCenterExists) {
      return response()->json([
        'message' => 'Medical center not found!',
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

    $rating = new RatingMedicalCenter();
    $rating->rating = $validatedData['rating'];
    $rating->description = $validatedData['description'];
    $rating->customer_id = auth()->user()->id;
    $rating->medical_center_id = $medical_center_id;
    $rating->save();

    return response()->json([
      'message' => 'Medical center rated successfully!',
      'data' => $rating,
    ], 201);
  }

  public function updateRatingMedicalCenter(Request $request, $rating_id)
  {
    // Kiểm tra xem rating có tồn tại hay không
    $rating = RatingMedicalCenter::find($rating_id);

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

  public function deleteRatingMedicalCenter($rating_id)
  {
    // Kiểm tra xem rating có tồn tại hay không
    $rating = RatingMedicalCenter::find($rating_id);

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

  // ====================================     For Medical Center     ====================================
  public function getCustomerRatings(Request $request)
  {
    $medical_center_id = auth()->user()->medicalCenter->id;

    // Kiểm tra xem medical center có tồn tại hay không
    $medicalCenterExists = DB::table('medical_centers')->where('id', $medical_center_id)->exists();

    if (!$medicalCenterExists) {
      return response()->json([
        'message' => 'Medical center not found!',
        'status' => 404
      ], 404);
    }

    $user = auth()->user();

    $ratings = RatingMedicalCenter::with(['customer.account', 'customer.ratings', 'interacts.account'])
      ->where('medical_center_id', $medical_center_id)
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
            'medical_center_liked' => $user_liked,
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

  public function getRatingDetail($rating_medical_center_id)
  {
    // Lấy rating theo id
    $rating = RatingMedicalCenter::with(['customer.account', 'interacts.account'])
      ->find($rating_medical_center_id);

    // Kiểm tra nếu không tìm thấy rating
    if (!$rating) {
      return response()->json([
        'message' => 'Rating not found!',
        'status' => 404,
      ], 404);
    }

    // Lấy thông tin chi tiết về rating
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

    return response()->json([
      'message' => 'Rating detail retrieved successfully!',
      'status' => 200,
      'data' => [
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
          'details' => $likes,
        ]
      ]
    ]);
  }

  public function likeRatingMedicalCenter($rating_medical_center_id)
  {
    $medical_center_account_id = auth()->user()->id;

    // Kiểm tra xem đã tồn tại một record với rating_medical_center_id và account_id hay chưa
    $existingLike = RatingMedicalCenterInteract::withTrashed()
      ->where('rating_medical_center_id', $rating_medical_center_id)
      ->where('account_id', $medical_center_account_id)
      ->first();

    if ($existingLike) {
      // Nếu bản ghi đã bị xóa (unliked), khôi phục lại nó
      if ($existingLike->trashed()) {
        $existingLike->restore();
        $totalLikes = RatingMedicalCenterInteract::where('rating_medical_center_id', $rating_medical_center_id)
          ->count();

        return response()->json([
          'message' => 'Rating medical center liked successfully.',
          'total_likes' => $totalLikes,
          'data' => $existingLike,
        ], 200);
      }

      return response()->json([
        'message' => 'You have already liked this rating medical center.',
      ], 409); // 409 Conflict
    }

    // Tạo một record mới trong bảng rating_medical_center_interacts
    $like = RatingMedicalCenterInteract::create([
      'rating_medical_center_id' => $rating_medical_center_id,
      'account_id' => $medical_center_account_id,
    ]);

    $totalLikes = RatingMedicalCenterInteract::where('rating_medical_center_id', $rating_medical_center_id)
      ->count();

    return response()->json([
      'message' => 'Rating medical center liked successfully.',
      'total_likes' => $totalLikes,
      'data' => $like,
    ], 201);
  }

  public function unlikeRatingMedicalCenter($rating_medical_center_id)
  {
    $medical_center_account_id = auth()->user()->id;

    // Tìm bản ghi tồn tại với rating_medical_center_id và account_id
    $existingLike = RatingMedicalCenterInteract::where('rating_medical_center_id', $rating_medical_center_id)
      ->where('account_id', $medical_center_account_id)
      ->first();

    if (!$existingLike) {
      return response()->json([
        'message' => 'You have not liked this rating medical center yet.',
      ], 404); // 404 Not Found
    }

    // Xóa mềm bản ghi
    $existingLike->delete();

    $totalLikes = RatingMedicalCenterInteract::where('rating_medical_center_id', $rating_medical_center_id)
      ->count();

    return response()->json([
      'message' => 'Rating medical center unliked successfully.',
      'total_likes' => $totalLikes,
    ], 200);
  }

  public function replyToRatingMedicalCenter(Request $request, $rating_medical_center_id)
  {
    // Tìm rating medical center theo ID
    $ratingMedicalCenter = RatingMedicalCenter::find($rating_medical_center_id);

    if (!$ratingMedicalCenter) {
      return response()->json([
        'message' => 'Rating medical center not found.',
      ], 404); // 404 Not Found
    }

    // Cập nhật phần phản hồi và ngày phản hồi
    $ratingMedicalCenter->reply = $request->input('reply');
    $ratingMedicalCenter->reply_date = Carbon::now();
    $ratingMedicalCenter->save();

    return response()->json([
      'message' => 'Reply added successfully.',
      'data' => $ratingMedicalCenter,
    ], 200); // 200 OK
  }

  public function updateReplyToRatingMedicalCenter(Request $request, $rating_medical_center_id)
  {
    // Tìm rating medical center theo ID
    $ratingMedicalCenter = RatingMedicalCenter::find($rating_medical_center_id);

    if (!$ratingMedicalCenter) {
      return response()->json([
        'message' => 'Rating medical center not found.',
      ], 404); // 404 Not Found
    }

    // Cập nhật phần phản hồi và ngày phản hồi
    $ratingMedicalCenter->reply = $request->input('reply');
    $ratingMedicalCenter->reply_date = Carbon::now();
    $ratingMedicalCenter->save();

    return response()->json([
      'message' => 'Reply updated successfully.',
      'data' => $ratingMedicalCenter,
    ], 200); // 200 OK
  }

  public function deleteReplyToRatingMedicalCenter($rating_medical_center_id)
  {
    // Tìm rating medical center theo ID
    $ratingMedicalCenter = RatingMedicalCenter::find($rating_medical_center_id);

    if (!$ratingMedicalCenter) {
      return response()->json([
        'message' => 'Rating medical center not found.',
      ], 404); // 404 Not Found
    }

    // Xóa phần phản hồi và ngày phản hồi
    $ratingMedicalCenter->reply = null;
    $ratingMedicalCenter->reply_date = null;
    $ratingMedicalCenter->save();

    return response()->json([
      'message' => 'Reply deleted successfully.',
      'data' => $ratingMedicalCenter,
    ], 200); // 200 OK
  }
}
