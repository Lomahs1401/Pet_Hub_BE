<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\RatingProduct;
use App\Models\RatingProductInteract;
use App\Models\Shop;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RatingProductController extends Controller
{
  // ====================================     For Customer     ====================================
  public function createRatingProduct(Request $request, $product_id)
  {
    // Kiểm tra xem product có tồn tại hay không
    $productExists = DB::table('products')->where('id', $product_id)->exists();

    if (!$productExists) {
      return response()->json([
        'message' => 'Product not found!',
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

    $rating = new RatingProduct();
    $rating->rating = $validatedData['rating'];
    $rating->description = $validatedData['description'];
    $rating->customer_id = auth()->user()->id;
    $rating->product_id = $product_id;
    $rating->save();

    return response()->json([
      'message' => 'Product rated successfully!',
      'data' => $rating,
    ], 201);
  }

  public function updateRatingProduct(Request $request, $rating_id)
  {
    // Kiểm tra xem rating có tồn tại hay không
    $rating = RatingProduct::find($rating_id);

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

  public function deleteRatingProduct(Request $request, $rating_id)
  {
    // Kiểm tra xem rating có tồn tại hay không
    $rating = RatingProduct::find($rating_id);

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

  public function getCustomerRatingsOfProductId(Request $request, $product_id)
  {
    $user = auth()->user();
    $role_user = DB::table('roles')->where('id', '=', $user->role_id)->value('role_name');

    // Lấy thông tin shop của user nếu role là shop
    if ($role_user === 'ROLE_SHOP') {
      $auth_shop_id = DB::table('shops')->where('account_id', '=', $user->id)->value('id');
    }

    // Kiểm tra sản phẩm thuộc về shop nào
    $productShopId = DB::table('products')->where('id', $product_id)->value('shop_id');

    // Nếu role là shop và sản phẩm không thuộc shop của user, trả về lỗi
    if ($role_user === 'ROLE_SHOP' && $auth_shop_id !== $productShopId) {
      return response()->json([
        'message' => 'You do not have access to this product.',
      ], 403); // 403 Forbidden
    }

    // Lấy rating của sản phẩm cụ thể
    $ratings = RatingProduct::with(['customer.account', 'customer.ratings', 'interacts.account'])
      ->where('product_id', $product_id)
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

        // Kiểm tra xem shop có nằm trong danh sách likes hay không
        $shop_liked = $rating->interacts->contains('account_id', $user->id);

        return [
          'rating_id' => $rating->id,
          'rating_score' => $rating->rating,
          'description' => $rating->description,
          'reply' => $rating->reply,
          'reply_date' => $rating->reply_date,
          'rating_date' => $rating->created_at,
          'customer_username' => $account->username,
          'customer_avatar' => $account->avatar,
          'account_creation_date' => $account->created_at,
          'customer_rating_count' => $customer->ratings->count(),
          'customer_ranking_point' => $customer->ranking_point ?? 0,
          'likes' => [
            'total_likes' => $rating->interacts->count(),
            'shop_liked' => $shop_liked,
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

  public function getDetailRating($product_id)
  {
    $product = Product::find($product_id);
    if (!$product) {
      return response()->json([
        'message' => 'Product not found!',
        'status' => 404
      ], 404);
    }

    // Lấy số lượng rating cho từng mức điểm từ 1 đến 5
    $ratings = RatingProduct::select('rating', DB::raw('count(*) as count'))
      ->where('product_id', $product_id)
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

  // ====================================     For Shop     ====================================
  // Hàm để shop like một rating product
  public function likeRatingProduct($rating_product_id)
  {
    $shop_account_id = auth()->user()->id;

    // Kiểm tra xem đã tồn tại một record với rating_product_id và account_id hay chưa
    $existingLike = RatingProductInteract::withTrashed()
      ->where('rating_product_id', $rating_product_id)
      ->where('account_id', $shop_account_id)
      ->first();

    if ($existingLike) {
      // Nếu bản ghi đã bị xóa (unliked), khôi phục lại nó
      if ($existingLike->trashed()) {
        $existingLike->restore();
        $totalLikes = RatingProductInteract::where('rating_product_id', $rating_product_id)
          ->count();

        return response()->json([
          'message' => 'Rating product liked successfully.',
          'total_likes' => $totalLikes,
          'data' => $existingLike,
        ], 200);
      }

      return response()->json([
        'message' => 'You have already liked this rating product.',
      ], 409); // 409 Conflict
    }

    // Tạo một record mới trong bảng rating_product_interacts
    $like = RatingProductInteract::create([
      'rating_product_id' => $rating_product_id,
      'account_id' => $shop_account_id,
    ]);

    $totalLikes = RatingProductInteract::where('rating_product_id', $rating_product_id)
      ->count();

    return response()->json([
      'message' => 'Rating product liked successfully.',
      'total_likes' => $totalLikes,
      'data' => $like,
    ], 201);
  }

  // Hàm để shop bỏ lượt like của một rating product
  public function unlikeRatingProduct($rating_product_id)
  {
    $shop_account_id = auth()->user()->id;

    // Tìm bản ghi tồn tại với rating_product_id và account_id
    $existingLike = RatingProductInteract::where('rating_product_id', $rating_product_id)
      ->where('account_id', $shop_account_id)
      ->first();

    if (!$existingLike) {
      return response()->json([
        'message' => 'You have not liked this rating product yet.',
      ], 404); // 404 Not Found
    }

    // Xóa mềm bản ghi
    $existingLike->delete();

    $totalLikes = RatingProductInteract::where('rating_product_id', $rating_product_id)
      ->count();

    return response()->json([
      'message' => 'Rating product unliked successfully.',
      'total_likes' => $totalLikes,
    ], 200);
  }

  public function replyToRatingProduct(Request $request, $rating_product_id)
  {
    // Tìm rating product theo ID
    $ratingProduct = RatingProduct::find($rating_product_id);

    if (!$ratingProduct) {
      return response()->json([
        'message' => 'Rating product not found.',
      ], 404); // 404 Not Found
    }

    // Cập nhật phần phản hồi và ngày phản hồi
    $ratingProduct->reply = $request->input('reply');
    $ratingProduct->reply_date = Carbon::now();
    $ratingProduct->save();

    return response()->json([
      'message' => 'Reply added successfully.',
      'data' => $ratingProduct,
    ], 200); // 200 OK
  }

  public function updateReplyToRatingProduct(Request $request, $rating_product_id)
  {
    // Tìm rating product theo ID
    $ratingProduct = RatingProduct::find($rating_product_id);

    if (!$ratingProduct) {
      return response()->json([
        'message' => 'Rating product not found.',
      ], 404); // 404 Not Found
    }

    // Cập nhật phần phản hồi và ngày phản hồi
    $ratingProduct->reply = $request->input('reply');
    $ratingProduct->reply_date = Carbon::now();
    $ratingProduct->save();

    return response()->json([
      'message' => 'Reply updated successfully.',
      'data' => $ratingProduct,
    ], 200); // 200 OK
  }

  public function deleteReplyToRatingProduct($rating_product_id)
  {
    // Tìm rating product theo ID
    $ratingProduct = RatingProduct::find($rating_product_id);

    if (!$ratingProduct) {
      return response()->json([
        'message' => 'Rating product not found.',
      ], 404); // 404 Not Found
    }

    // Xóa phần phản hồi và ngày phản hồi
    $ratingProduct->reply = null;
    $ratingProduct->reply_date = null;
    $ratingProduct->save();

    return response()->json([
      'message' => 'Reply deleted successfully.',
      'data' => $ratingProduct,
    ], 200); // 200 OK
  }
}
