<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\RatingProduct;
use App\Models\SubOrder;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

  public function getSales(Request $request)
  {
    // Lấy shop_id của shop hiện tại (giả sử shop_id được lưu trong session hoặc lấy từ authenticated user)
    $shop_id = auth()->user()->shop->id;

    if (!$shop_id) {
      return response()->json([
        'message' => 'Shop not found for the current user!',
        'status' => 404,
      ], 404);
    }

    // Lấy thời gian hiện tại và thời gian 7 ngày trước
    $endDate = Carbon::now();
    $startDate = $endDate->copy()->subDays(7);

    // Khởi tạo mảng chứa doanh thu theo ngày
    $salesData = [];

    // Lấy doanh thu theo từng ngày trong khoảng thời gian 7 ngày
    for ($date = $startDate; $date <= $endDate; $date->addDay()) {
      // Tính tổng doanh thu trong ngày
      $totalSales = SubOrder::where('shop_id', $shop_id)
        ->whereDate('created_at', $date)
        ->sum('sub_total_prices');

      // Lấy tên của thứ trong tuần
      $dayName = $date->format('D'); // 'D' format sẽ trả về tên thứ ngắn (Mon, Tue, Wed, ...)

      // Thêm dữ liệu vào mảng
      $salesData[] = [
        'name' => $dayName,
        'sale' => $totalSales,
      ];
    }

    // Tính tổng doanh thu của tất cả các ngày trong mảng
    $totalSales = array_sum(array_column($salesData, 'sale'));

    return response()->json([
      'message' => 'Sales fetched successfully!',
      'status' => 200,
      'total_sales' => $totalSales,
      'data' => $salesData,
    ], 200);
  }

  public function getRevenue(Request $request)
  {
    $shop_id = auth()->user()->shop->id;
    $year = $request->get('year', Carbon::now()->year); // Lấy năm từ request hoặc sử dụng năm hiện tại

    // Khởi tạo mảng để chứa dữ liệu theo tháng
    $salesData = array_fill(0, 12, ['name' => '', 'revenue' => 0]);

    // Tên các tháng
    $monthNames = [
      'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun',
      'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'
    ];

    foreach ($monthNames as $index => $month) {
      $monthStart = Carbon::create($year, $index + 1, 1)->startOfMonth();
      $monthEnd = $monthStart->copy()->endOfMonth();

      // Lấy tổng doanh thu từ sub_orders
      $revenueData = SubOrder::where('shop_id', $shop_id)
        ->whereBetween('created_at', [$monthStart, $monthEnd])
        ->sum('sub_total_prices');

      $salesData[$index] = [
        'name' => $month,
        'revenue' => (float)$revenueData, // Ép kiểu về số thực
      ];
    }

    return response()->json([
      'message' => 'Revenue retrieved successfully!',
      'status' => 200,
      'data' => $salesData
    ]);
  }

  public function getSelled(Request $request)
  {
    $shop_id = auth()->user()->shop->id;
    $year = $request->get('year', Carbon::now()->year); // Lấy năm từ request hoặc sử dụng năm hiện tại

    // Khởi tạo mảng để chứa dữ liệu theo tháng
    $salesData = array_fill(0, 12, ['name' => '', 'sold' => 0]);

    // Tên các tháng
    $monthNames = [
      'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun',
      'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'
    ];

    foreach ($monthNames as $index => $month) {
      $monthStart = Carbon::create($year, $index + 1, 1)->startOfMonth();
      $monthEnd = $monthStart->copy()->endOfMonth();

      // Lấy tổng số lượng đã bán từ cart_items của các sản phẩm thuộc shop đó
      $soldData = CartItem::whereHas('product', function ($query) use ($shop_id) {
        $query->where('shop_id', $shop_id);
      })
        ->whereHas('cart.order', function ($query) use ($monthStart, $monthEnd) {
          $query->whereBetween('created_at', [$monthStart, $monthEnd]);
        })
        ->sum('quantity');

      $salesData[$index] = [
        'name' => $month,
        'sold' => (int)$soldData // Ép kiểu về số nguyên
      ];
    }

    return response()->json([
      'message' => 'Selled product retrieved successfully!',
      'status' => 200,
      'data' => $salesData
    ]);
  }

  public function getCategory(Request $request)
  {
    $shop_id = auth()->user()->shop->id;

    if (!$shop_id) {
      return response()->json([
        'message' => 'Shop not found for the current user!',
        'status' => 404,
      ], 404);
    }

    // Lấy danh sách các loại sản phẩm và tổng số lượng sản phẩm của từng loại
    $categories = Product::join('product_categories', 'products.product_category_id', '=', 'product_categories.id')
      ->select('product_categories.type', DB::raw('COUNT(products.id) as total_quantity'))
      ->where('products.shop_id', $shop_id)
      ->groupBy('product_categories.type')
      ->get();

    // Chuẩn bị dữ liệu để trả về
    $data = [];
    foreach ($categories as $category) {
      $data[] = [
        'type' => $category->type,
        'total_quantity' => $category->total_quantity,
      ];
    }

    return response()->json([
      'message' => 'Categories fetched successfully!',
      'status' => 200,
      'data' => $data,
    ], 200);
  }

  public function getRecentOrder(Request $request)
  {
    // Lấy id của shop hiện tại
    $shop_id = auth()->user()->shop->id;

    // Lấy 15 sub_order gần nhất của shop đó
    $recentOrders = SubOrder::where('shop_id', $shop_id)
      ->orderBy('created_at', 'desc')
      ->take(15)
      ->with(['order.customer.account']) // Eager load mối quan hệ
      ->get();

    // Chuyển đổi dữ liệu thành định dạng mong muốn
    $data = $recentOrders->map(function ($subOrder) {
      return [
        'id' => $subOrder->id,
        'sub_total_prices' => $subOrder->sub_total_prices,
        'status' => $subOrder->status,
        'order_id' => $subOrder->order_id,
        'shop_id' => $subOrder->shop_id,
        'created_at' => $subOrder->created_at,
        'updated_at' => $subOrder->updated_at,
        'customer_id' => $subOrder->order->customer->id,
        'customer_username' => $subOrder->order->customer->account->username,
        'customer_name' => $subOrder->order->customer->name,
        'address' => $subOrder->order->address,
      ];
    });

    // Trả về JSON response với danh sách các sub_order
    return response()->json([
      'message' => 'Recent orders retrieved successfully!',
      'status' => 200,
      'data' => $data
    ]);
  }

  public function getPopularProduct(Request $request)
  {
    $shop_id = auth()->user()->shop->id;

    // Truy vấn để lấy 10 sản phẩm bán chạy nhất
    $popularProducts = Product::where('shop_id', $shop_id)
      ->orderBy('sold_quantity', 'desc')
      ->take(10)
      ->get();

    // Trả về kết quả dưới dạng JSON
    return response()->json([
      'message' => 'Popular products fetched successfully!',
      'status' => 200,
      'data' => $popularProducts
    ]);
  }
}
