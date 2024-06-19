<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\RatingShop;
use App\Models\Shop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RatingShopController extends Controller
{
  public function getDetailRating($shop_id)
  {
    $shop = Shop::withTrashed()->find($shop_id);
    if (!$shop) {
      return response()->json([
        'message' => 'Shop not found!',
        'status' => 404
      ], 404);
    }

    // Lấy số lượng rating cho từng mức điểm từ 1 đến 5
    $ratings = RatingShop::select('rating', DB::raw('count(*) as count'))
      ->where('shop_id', $shop_id)
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
}
