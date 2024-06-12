<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\RatingProduct;
use App\Models\SubOrder;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ShopDashboardController extends Controller
{
  public function getReviewsComparison(Request $request)
  {
    // Nhận tùy chọn thời gian từ request, mặc định là Last Month
    $option = strtolower($request->query('option')) ?? 'last month';

    // Lấy shop_id của shop hiện tại (giả sử shop_id được lưu trong session hoặc lấy từ authenticated user)
    $shop_id = auth()->user()->shop->id;

    // Xác định khoảng thời gian hiện tại và khoảng thời gian trước đó
    $endDate = Carbon::now();
    switch ($option) {
      case 'last day':
        $startDate = Carbon::today();
        $previousStartDate = Carbon::yesterday();
        $previousEndDate = Carbon::today()->subSecond();
        break;
      case 'last week':
        $startDate = Carbon::now()->startOfWeek();
        $previousStartDate = Carbon::now()->subWeek()->startOfWeek();
        $previousEndDate = Carbon::now()->subWeek()->endOfWeek();
        break;
      case 'last year':
        $startDate = Carbon::now()->startOfYear();
        $previousStartDate = Carbon::now()->subYear()->startOfYear();
        $previousEndDate = Carbon::now()->subYear()->endOfYear();
        break;
      case 'last month':
      default:
        $startDate = Carbon::now()->startOfMonth();
        $previousStartDate = Carbon::now()->subMonth()->startOfMonth();
        $previousEndDate = Carbon::now()->subMonth()->endOfMonth();
        break;
    }

    // Lấy tổng số lượng rating_product trong khoảng thời gian hiện tại
    $currentPeriodCount = RatingProduct::whereHas('product', function ($query) use ($shop_id) {
      $query->where('shop_id', $shop_id);
    })
      ->whereBetween('created_at', [$startDate, $endDate])
      ->count();

    // Lấy tổng số lượng rating_product trong khoảng thời gian trước đó
    $previousPeriodCount = RatingProduct::whereHas('product', function ($query) use ($shop_id) {
      $query->where('shop_id', $shop_id);
    })
      ->whereBetween('created_at', [$previousStartDate, $previousEndDate])
      ->count();

    // Tính toán tỷ lệ phần trăm chênh lệch
    if ($previousPeriodCount == 0) {
      $percentageChange = $currentPeriodCount > 0 ? 100 : 0;
    } else {
      $percentageChange = (($currentPeriodCount - $previousPeriodCount) / $previousPeriodCount) * 100;
    }

    return response()->json([
      'message' => 'Reviews comparison retrieved successfully',
      'status' => 200,
      'data' => [
        'current_period_count' => $currentPeriodCount,
        'previous_period_count' => $previousPeriodCount,
        'percentage_change' => $percentageChange,
      ],
    ], 200);
  }

  public function getRepliesComparison(Request $request)
  {
    $option = strtolower($request->query('option')) ?? 'last month';

    // Lấy shop_id của shop hiện tại (giả sử shop_id được lưu trong session hoặc lấy từ authenticated user)
    $shop_id = auth()->user()->shop->id;

    // Xác định khoảng thời gian hiện tại và khoảng thời gian trước đó
    $endDate = Carbon::now();
    switch ($option) {
      case 'last day':
        $startDate = Carbon::today();
        $previousStartDate = Carbon::yesterday();
        $previousEndDate = Carbon::today()->subSecond();
        break;
      case 'last week':
        $startDate = Carbon::now()->startOfWeek();
        $previousStartDate = Carbon::now()->subWeek()->startOfWeek();
        $previousEndDate = Carbon::now()->subWeek()->endOfWeek();
        break;
      case 'last year':
        $startDate = Carbon::now()->startOfYear();
        $previousStartDate = Carbon::now()->subYear()->startOfYear();
        $previousEndDate = Carbon::now()->subYear()->endOfYear();
        break;
      case 'last month':
      default:
        $startDate = Carbon::now()->startOfMonth();
        $previousStartDate = Carbon::now()->subMonth()->startOfMonth();
        $previousEndDate = Carbon::now()->subMonth()->endOfMonth();
        break;
    }

    // Lấy tổng số lượng rating_product đã reply trong khoảng thời gian hiện tại
    $currentPeriodCount = RatingProduct::whereHas('product', function ($query) use ($shop_id) {
      $query->where('shop_id', $shop_id);
    })
      ->whereNotNull('reply')
      ->whereBetween('created_at', [$startDate, $endDate])
      ->count();

    // Lấy tổng số lượng rating_product đã reply trong khoảng thời gian trước đó
    $previousPeriodCount = RatingProduct::whereHas('product', function ($query) use ($shop_id) {
      $query->where('shop_id', $shop_id);
    })
      ->whereNotNull('reply')
      ->whereBetween('created_at', [$previousStartDate, $previousEndDate])
      ->count();

    // Tính toán tỷ lệ phần trăm chênh lệch
    if ($previousPeriodCount == 0) {
      $percentageChange = $currentPeriodCount > 0 ? 100 : 0;
    } else {
      $percentageChange = (($currentPeriodCount - $previousPeriodCount) / $previousPeriodCount) * 100;
    }

    return response()->json([
      'message' => 'Replies comparison retrieved successfully',
      'status' => 200,
      'data' => [
        'current_period_count' => $currentPeriodCount,
        'previous_period_count' => $previousPeriodCount,
        'percentage_change' => $percentageChange,
      ],
    ], 200);
  }

  public function getProductsComparison(Request $request)
  {
    // Nhận tùy chọn thời gian từ request, mặc định là Last Month
    $option = strtolower($request->query('option')) ?? 'last month';

    // Lấy shop_id của shop hiện tại (giả sử shop_id được lưu trong session hoặc lấy từ authenticated user)
    $shop_id = auth()->user()->shop->id;

    // Xác định khoảng thời gian hiện tại và khoảng thời gian trước đó
    $endDate = Carbon::now();
    switch ($option) {
      case 'last day':
        $startDate = Carbon::today();
        $previousStartDate = Carbon::yesterday();
        $previousEndDate = Carbon::today()->subSecond();
        break;
      case 'last week':
        $startDate = Carbon::now()->startOfWeek();
        $previousStartDate = Carbon::now()->subWeek()->startOfWeek();
        $previousEndDate = Carbon::now()->subWeek()->endOfWeek();
        break;
      case 'last year':
        $startDate = Carbon::now()->startOfYear();
        $previousStartDate = Carbon::now()->subYear()->startOfYear();
        $previousEndDate = Carbon::now()->subYear()->endOfYear();
        break;
      case 'last month':
      default:
        $startDate = Carbon::now()->startOfMonth();
        $previousStartDate = Carbon::now()->subMonth()->startOfMonth();
        $previousEndDate = Carbon::now()->subMonth()->endOfMonth();
        break;
    }

    // Lấy tổng số lượng sản phẩm trong khoảng thời gian hiện tại
    $currentPeriodCount = Product::where('shop_id', $shop_id)
      ->whereBetween('created_at', [$startDate, $endDate])
      ->count();

    // Lấy tổng số lượng sản phẩm trong khoảng thời gian trước đó
    $previousPeriodCount = Product::where('shop_id', $shop_id)
      ->whereBetween('created_at', [$previousStartDate, $previousEndDate])
      ->count();

    // Tính toán tỷ lệ phần trăm chênh lệch
    if ($previousPeriodCount == 0) {
      $percentageChange = $currentPeriodCount > 0 ? 100 : 0;
    } else {
      $percentageChange = (($currentPeriodCount - $previousPeriodCount) / $previousPeriodCount) * 100;
    }

    return response()->json([
      'message' => 'Products comparison retrieved successfully',
      'status' => 200,
      'data' => [
        'current_period_count' => $currentPeriodCount,
        'previous_period_count' => $previousPeriodCount,
        'percentage_change' => $percentageChange,
      ],
    ], 200);
  }

  public function getOrdersComparison(Request $request)
  {
    // Nhận tùy chọn thời gian từ request, mặc định là Last Month
    $option = strtolower($request->query('option')) ?? 'last month';

    // Lấy shop_id của shop hiện tại (giả sử shop_id được lưu trong session hoặc lấy từ authenticated user)
    $shop_id = auth()->user()->shop->id;

    // Xác định khoảng thời gian hiện tại và khoảng thời gian trước đó
    $endDate = Carbon::now();
    switch ($option) {
      case 'last day':
        $startDate = Carbon::today();
        $previousStartDate = Carbon::yesterday();
        $previousEndDate = Carbon::today()->subSecond();
        break;
      case 'last week':
        $startDate = Carbon::now()->startOfWeek();
        $previousStartDate = Carbon::now()->subWeek()->startOfWeek();
        $previousEndDate = Carbon::now()->subWeek()->endOfWeek();
        break;
      case 'last year':
        $startDate = Carbon::now()->startOfYear();
        $previousStartDate = Carbon::now()->subYear()->startOfYear();
        $previousEndDate = Carbon::now()->subYear()->endOfYear();
        break;
      case 'last month':
      default:
        $startDate = Carbon::now()->startOfMonth();
        $previousStartDate = Carbon::now()->subMonth()->startOfMonth();
        $previousEndDate = Carbon::now()->subMonth()->endOfMonth();
        break;
    }

    // Lấy tổng số lượng sub_order trong khoảng thời gian hiện tại
    $currentPeriodCount = SubOrder::where('shop_id', $shop_id)
      ->whereBetween('created_at', [$startDate, $endDate])
      ->count();

    // Lấy tổng số lượng sub_order trong khoảng thời gian trước đó
    $previousPeriodCount = SubOrder::where('shop_id', $shop_id)
      ->whereBetween('created_at', [$previousStartDate, $previousEndDate])
      ->count();

    // Tính toán tỷ lệ phần trăm chênh lệch
    if ($previousPeriodCount == 0) {
      $percentageChange = $currentPeriodCount > 0 ? 100 : 0;
    } else {
      $percentageChange = (($currentPeriodCount - $previousPeriodCount) / $previousPeriodCount) * 100;
    }

    return response()->json([
      'message' => 'Orders comparison retrieved successfully',
      'status' => 200,
      'data' => [
        'current_period_count' => $currentPeriodCount,
        'previous_period_count' => $previousPeriodCount,
        'percentage_change' => $percentageChange,
      ],
    ], 200);
  }
}
