<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\Role;
use App\Models\SubOrder;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
  public function getOrders()
  {
    $customer_id = auth()->user()->customer->id;

    $orders = Order::where('customer_id', $customer_id)
      ->orderBy('created_at', 'desc')
      ->get();

    $formatted_orders = [];

    foreach ($orders as $order) {
      // Format sub orders
      $formatted_sub_orders = [];
      foreach ($order->subOrder as $subOrder) {
        $formatted_sub_orders[] = [
          'sub_total_amount' => $subOrder->sub_total_prices,
          'status' => $subOrder->status,
        ];
      }

      $formatted_orders[] = [
        'id' => $order->id,
        'total_prices' => $order->total_prices,
        'address' => $order->address,
        'payment_method' => $order->payment_method,
        'transaction_order_id' => $order->transaction_order_id,
        'customer_id' => $order->customer_id,
        'cart_id' => $order->cart_id,
        'sub_orders' => $formatted_sub_orders,
        'created_at' => $order->created_at,
        'updated_at' => $order->updated_at,
      ];
    }

    return response()->json([
      'message' => 'Query order successfully',
      'status' => 200,
      'data' => $formatted_orders,
    ], 200);
  }

  public function getOrderDetail($order_id)
  {
    $order = Order::with(['subOrder.shop', 'cart.cartItem.product.shop.account', 'customer.account'])->find($order_id);

    if (!$order) {
      return response()->json([
        'message' => 'Order not found',
        'status' => 404,
      ], 404);
    }

    $shops = [];
    $cartItems = $order->cart->cartItem;

    foreach ($cartItems as $cartItem) {
      $shop = $cartItem->product->shop;
      $ratingShop = $shop->calculateShopRating();
      $ratingProduct = $cartItem->product->calculateProductRating();

      $formatted_cart_item = [
        'id' => $cartItem->id,
        'name' => $cartItem->name,
        'description' => $cartItem->description,
        'quantity' => $cartItem->quantity,
        'price' => $cartItem->price,
        'amount' => $cartItem->amount,
        'product_id' => $cartItem->product_id,
        'product_image' => $cartItem->product->image,
        'rating' => $ratingProduct['average'],
        'rating_count' => $ratingProduct['count'],
      ];

      // Kiểm tra xem shop đã tồn tại trong danh sách chưa
      if (!isset($shops[$shop->id])) {
        // Lấy sub_order của shop này
        $subOrder = $order->subOrder->where('shop_id', $shop->id)->first();

        $shops[$shop->id] = [
          'shop_id' => $shop->id,
          'shop_name' => $shop->name,
          'shop_username' => $shop->account->username,
          'shop_image' => $shop->image,
          'shop_avatar' => $shop->account->avatar,
          'shop_description' => $shop->description,
          'shop_phone' => $shop->phone,
          'shop_address' => $shop->address,
          'shop_website' => $shop->website,
          'shop_fanpage' => $shop->fanpage,
          'shop_work_time' => $shop->work_time,
          'shop_establish_year' => $shop->establish_year,
          'shop_certificate' => $shop->certificate,
          'rating' => $ratingShop['average'],
          'rating_count' => $ratingShop['count'],
          'sub_total_amount' => $subOrder ? $subOrder->sub_total_prices : null,
          'status' => $subOrder ? $subOrder->status : null,
          'cart_items' => [],
        ];
      }

      // Thêm sản phẩm vào shop tương ứng
      $shops[$shop->id]['cart_items'][] = $formatted_cart_item;
    }

    // Chuyển danh sách các shop từ associative array sang indexed array
    $shops = array_values($shops);

    $formatted_order = [
      'order_id' => $order->id,
      'cart_id' => $order->cart->id,
      'total_prices' => $order->total_prices,
      'address' => $order->address,
      'payment_method' => $order->payment_method,
      'transaction_order_id' => $order->transaction_order_id,
      'customer_id' => $order->customer_id,
      'full_name' => $order->customer->full_name,
      'username' => $order->customer->account->username,
      'email' => $order->customer->account->email,
      'avatar' => $order->customer->account->avatar,
      'customer_id' => $order->customer_id,
      'created_at' => $order->created_at,
      'updated_at' => $order->updated_at,
      'shops' => $shops,
    ];

    return response()->json([
      'message' => 'Order details retrieved successfully',
      'status' => 200,
      'data' => $formatted_order,
    ], 200);
  }

  public function createOrder(Request $request)
  {
    $customer_id = auth()->user()->customer->id;

    $validatedData = $request->validate([
      'address' => 'required|string',
      'payment_method' => 'required|string',
      'transaction_order_id' => 'sometimes|unique:orders,transaction_order_id',
      'cart_id' => 'required|exists:carts,id',
    ], [
      'address.required' => 'Address is required',
      'payment_method.required' => 'Payment method is required',
      'transaction_order_id.unique' => 'Transaction order ID is already taken',
      'cart_id.required' => 'Cart ID is required',
    ]);

    DB::beginTransaction();

    try {
      // Lấy giỏ hàng
      $cart = Cart::with('cartItem.product.shop')->findOrFail($validatedData['cart_id']);

      // Kiểm tra trạng thái giỏ hàng
      if (!$cart->is_active) {
        return response()->json([
          'message' => 'Cart is not active. Order already exists for this cart.',
          'status' => 400,
        ], 400);
      }

      // Tính tổng giá giỏ hàng
      $totalPrices = $cart->cartItem->sum(function ($item) {
        return $item->quantity * $item->price;
      });

      // Kiểm tra và tạo transaction_order_id nếu không có
      if (isset($validatedData['transaction_order_id'])) {
        $transactionOrderId = $validatedData['transaction_order_id'];
      } else {
        $transactionOrderId = strtoupper(uniqid($customer_id . $cart->id . now()->format('YmdHis')));
      }

      // Tạo đơn hàng
      $order = Order::create([
        'total_prices' => $totalPrices,
        'address' => $validatedData['address'],
        'payment_method' => $validatedData['payment_method'],
        'transaction_order_id' => $transactionOrderId,
        'customer_id' => $customer_id,
        'cart_id' => $cart->id,
      ]);

      // Lấy danh sách các shop khác nhau từ giỏ hàng
      $shops = $cart->cartItem->pluck('product.shop_id')->unique();

      foreach ($shops as $shopId) {
        // Tính tổng giá cho từng shop
        $subOrderTotalPrice = $cart->cartItem->where('product.shop_id', $shopId)->sum(function ($item) {
          return $item->quantity * $item->price;
        });

        // Xác định trạng thái dựa trên phương thức thanh toán
        if ($validatedData['payment_method'] == 'Paypal' || $validatedData['payment_method'] == 'paypal') {
          $status = 'Paid';
        } else {
          $status = 'Created';
        }

        // Tạo subOrder cho từng shop
        SubOrder::create([
          'sub_total_prices' => $subOrderTotalPrice,
          'status' => $status,
          'order_id' => $order->id,
          'shop_id' => $shopId,
        ]);

        // Gắn sản phẩm vào subOrder và cập nhật số lượng sản phẩm trong kho
        foreach ($cart->cartItem->where('product.shop_id', $shopId) as $cartItem) {
          $product = $cartItem->product;
          $product->quantity -= $cartItem->quantity; // Trừ số lượng sản phẩm
          $product->sold_quantity += $cartItem->quantity; // Cộng số lượng sản phẩm đã bán
          if ($product->quantity < 0) {
            throw new Exception('Product quantity not sufficient for product ID ' . $product->id);
          }
          $product->save();
        }
      }

      // Vô hiệu hoá giỏ hàng sau khi tạo đơn hàng
      $cart->update(['is_active' => false]);

      DB::commit();

      return response()->json([
        'message' => 'Order created successfully',
        'status' => 201,
        'data' => $order,
      ], 201);
    } catch (Exception $e) {
      DB::rollBack();

      return response()->json([
        'message' => 'Failed to create order',
        'status' => 500,
        'error' => $e->getMessage(),
      ], 500);
    }
  }

  public function pagingOrders(Request $request)
  {
    $shop_id = auth()->user()->shop->id;

    // Lấy tham số start_date và end_date từ request, nếu không có thì sử dụng ngày hiện tại
    $startDate = $request->query('start_date', Carbon::today()->toDateString());
    $endDate = $request->query('end_date', Carbon::today()->toDateString());

    // Chuyển đổi start_date và end_date thành đối tượng Carbon
    $startDate = Carbon::parse($startDate);
    $endDate = Carbon::parse($endDate);

    // Kiểm tra sự chênh lệch giữa start_date và end_date
    if ($startDate->greaterThan($endDate)) {
      return response()->json([
        'message' => 'Start date cannot be greater than end date',
        'status' => 400,
      ], 400);
    }

    // Lấy các tham số search_term và status từ request
    $searchTerm = $request->query('search_term', '');
    $status = $request->query('status', 'All');

    // Lấy danh sách sub_orders theo shop_id trong khoảng thời gian
    $subOrdersQuery = SubOrder::join('orders', 'sub_orders.order_id', '=', 'orders.id')
      ->join('customers', 'orders.customer_id', '=', 'customers.id')
      ->join('accounts', 'customers.account_id', '=', 'accounts.id')
      ->where('sub_orders.shop_id', $shop_id)
      ->whereBetween('orders.created_at', [$startDate, $endDate]);

    // Thêm điều kiện tìm kiếm theo search_term nếu có
    if (!empty($searchTerm)) {
      $subOrdersQuery = $subOrdersQuery->where(function ($query) use ($searchTerm) {
        $query->where('customers.full_name', 'like', '%' . $searchTerm . '%')
          ->orWhere('accounts.email', 'like', '%' . $searchTerm . '%');
      });
    }

    // Thêm điều kiện lọc theo status nếu có
    if ($status !== 'All') {
      $subOrdersQuery = $subOrdersQuery->where('sub_orders.status', $status);
    }

    // Lấy số lượng sub_orders và tính tổng số trang
    $totalSubOrders = $subOrdersQuery->count();
    $pageNumber = intval($request->query('page_number', 1));
    $numOfPage = intval($request->query('num_of_page', 10));
    $totalPages = ceil($totalSubOrders / $numOfPage);
    $offset = ($pageNumber - 1) * $numOfPage;

    // Lấy dữ liệu sub_orders dựa trên trang hiện tại và số lượng sub_orders trên mỗi trang
    $subOrders = $subOrdersQuery
      ->select(
        'sub_orders.*',
        'orders.customer_id',
        'orders.address',
        'orders.total_prices',
        'orders.payment_method',
        'customers.full_name',
        'accounts.username',
        'accounts.email',
        'accounts.avatar'
      )
      ->offset($offset)
      ->limit($numOfPage)
      ->get();

    // Định dạng dữ liệu sub_orders để trả về
    $formattedSubOrders = [];
    foreach ($subOrders as $subOrder) {
      $formattedSubOrders[] = [
        'id' => $subOrder->id,
        'shop_id' => $subOrder->shop_id,
        'sub_total_prices' => $subOrder->sub_total_prices,
        'status' => $subOrder->status,
        'payment_method' => $subOrder->payment_method,
        'address' => $subOrder->address,
        'customer_id' => $subOrder->customer_id,
        'full_name' => $subOrder->full_name,
        'username' => $subOrder->username,
        'email' => $subOrder->email,
        'avatar' => $subOrder->avatar,
        'created_at' => $subOrder->created_at,
        'updated_at' => $subOrder->updated_at,
      ];
    }

    // Trả về JSON response
    return response()->json([
      'message' => 'Query successfully!',
      'status' => 200,
      'page_number' => $pageNumber,
      'num_of_page' => $numOfPage,
      'total_pages' => $totalPages,
      'total_sub_orders' => $totalSubOrders,
      'data' => $formattedSubOrders,
    ]);
  }

  public function getRevenueByOrder(Request $request, $product_id)
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

      // Lấy tổng doanh thu và số lượng bán được của sản phẩm từ cart_items
      $revenueData = SubOrder::join('orders', 'sub_orders.order_id', '=', 'orders.id')
        ->join('carts', 'orders.cart_id', '=', 'carts.id')
        ->join('cart_items', 'carts.id', '=', 'cart_items.cart_id')
        ->where('sub_orders.shop_id', $shop_id)
        ->where('cart_items.product_id', $product_id)
        ->whereBetween('sub_orders.created_at', [$monthStart, $monthEnd])
        ->selectRaw('SUM(cart_items.amount) as total_revenue')
        ->first();

      $salesData[$index] = [
        'name' => $month,
        'revenue' => (float)$revenueData->total_revenue,
      ];
    }

    return response()->json([
      'message' => 'Revenue product retrieved successfully!',
      'status' => 200,
      'data' => $salesData
    ]);
  }

  public function getSellingByOrder(Request $request, $product_id)
  {
    $shop_id = auth()->user()->shop->id;
    $year = $request->get('year', Carbon::now()->year); // Lấy năm từ request hoặc sử dụng năm hiện tại

    // Khởi tạo mảng để chứa dữ liệu theo tháng
    $salesData = array_fill(0, 12, ['name' => '', 'quantity' => 0]);

    // Tên các tháng
    $monthNames = [
      'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun',
      'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'
    ];

    foreach ($monthNames as $index => $month) {
      $monthStart = Carbon::create($year, $index + 1, 1)->startOfMonth();
      $monthEnd = $monthStart->copy()->endOfMonth();

      // Lấy tổng doanh thu và số lượng bán được của sản phẩm từ cart_items
      $sellingData = SubOrder::join('orders', 'sub_orders.order_id', '=', 'orders.id')
        ->join('carts', 'orders.cart_id', '=', 'carts.id')
        ->join('cart_items', 'carts.id', '=', 'cart_items.cart_id')
        ->where('sub_orders.shop_id', $shop_id)
        ->where('cart_items.product_id', $product_id)
        ->whereBetween('sub_orders.created_at', [$monthStart, $monthEnd])
        ->selectRaw('SUM(cart_items.quantity) as total_quantity')
        ->first();

      $salesData[$index] = [
        'name' => $month,
        'selled' => (int)$sellingData->total_quantity,
      ];
    }

    return response()->json([
      'message' => 'Selling product retrieved successfully!',
      'status' => 200,
      'data' => $salesData
    ]);
  }

  public function getSubOrders($sub_order_id)
  {
    $shop_id = auth()->user()->shop->id;

    // Lấy thông tin sub_order
    $subOrder = SubOrder::join('orders', 'sub_orders.order_id', '=', 'orders.id')
      ->join('customers', 'orders.customer_id', '=', 'customers.id')
      ->join('accounts', 'customers.account_id', '=', 'accounts.id')
      ->where('sub_orders.id', $sub_order_id)
      ->where('sub_orders.shop_id', $shop_id)
      ->select(
        'sub_orders.id',
        'sub_orders.sub_total_prices',
        'sub_orders.status',
        'sub_orders.order_id',
        'sub_orders.shop_id',
        'sub_orders.created_at as sub_order_created_at',
        'sub_orders.updated_at as sub_order_updated_at',
        'orders.payment_method',
        'customers.full_name',
        'customers.ranking_point',
        'accounts.username',
        'accounts.email',
        'accounts.avatar',
        'accounts.created_at as account_creation_date'
      )
      ->first();

    // Nếu không tìm thấy sub_order, trả về lỗi
    if (!$subOrder) {
      return response()->json([
        'message' => 'Sub Order not found',
        'status' => 404,
      ], 404);
    }

    // Chuyển đổi ngày giờ sang định dạng ISO 8601
    $subOrder->account_creation_date = Carbon::parse($subOrder->account_creation_date)->toIso8601String();
    $subOrder->sub_order_created_at = Carbon::parse($subOrder->sub_order_created_at)->toIso8601String();
    $subOrder->sub_order_updated_at = Carbon::parse($subOrder->sub_order_updated_at)->toIso8601String();

    // Lấy danh sách các cart_items
    $cartItems = CartItem::join('products', 'cart_items.product_id', '=', 'products.id')
      ->join('carts', 'cart_items.cart_id', '=', 'carts.id')
      ->join('orders', 'carts.id', '=', 'orders.cart_id')
      ->join('customers', 'orders.customer_id', '=', 'customers.id')
      ->join('accounts', 'customers.account_id', '=', 'accounts.id')
      ->leftJoin('rating_products', function ($join) {
        $join->on('rating_products.product_id', '=', 'products.id')
          ->where('rating_products.customer_id', '=', DB::raw('customers.id'));
      })
      ->where('orders.id', $subOrder->order_id)
      ->where('products.shop_id', $shop_id)
      ->select(
        'cart_items.*',
        'products.image',
      )
      ->groupBy(
        'cart_items.id',
        'cart_items.cart_id',
        'cart_items.name',
        'cart_items.description',
        'cart_items.quantity',
        'cart_items.price',
        'cart_items.amount',
        'cart_items.product_id',
        'cart_items.created_at',
        'cart_items.updated_at',
        'cart_items.deleted_at',
        'products.image'
      )
      ->get();

    // Định dạng dữ liệu cart_items để trả về
    $formattedCartItems = $cartItems->map(function ($cartItem) {
      return [
        'id' => $cartItem->id,
        'name' => $cartItem->name,
        'description' => $cartItem->description,
        'quantity' => $cartItem->quantity,
        'price' => $cartItem->price,
        'amount' => $cartItem->amount,
        'product_id' => $cartItem->product_id,
        'product_image' => $cartItem->image,
      ];
    })->toArray();

    // Trả về JSON response
    return response()->json([
      'message' => 'Sub Order retrieved successfully!',
      'status' => 200,
      'data' => [
        'sub_order' => [
          'id' => $subOrder->id,
          'sub_total_prices' => $subOrder->sub_total_prices,
          'payment_method' => $subOrder->payment_method,
          'status' => $subOrder->status,
          'order_id' => $subOrder->order_id,
          'shop_id' => $subOrder->shop_id,
          'ranking_point' => $subOrder->ranking_point,
          'full_name' => $subOrder->full_name,
          'username' => $subOrder->username,
          'email' => $subOrder->email,
          'avatar' => $subOrder->avatar,
          'account_creation_date' => $subOrder->account_creation_date,
          'created_at' => $subOrder->sub_order_created_at,
          'updated_at' => $subOrder->sub_order_updated_at,
        ],
        'cart_items' => $formattedCartItems,
      ],
    ]);
  }
}
