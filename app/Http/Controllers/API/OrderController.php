<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Order;
use App\Models\SubOrder;
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
      $formatted_orders[] = [
        'id' => $order->id,
        'total_prices' => $order->total_prices,
        'address' => $order->address,
        'payment_method' => $order->payment_method,
        'transaction_order_id' => $order->transaction_order_id,
        'customer_id' => $order->customer_id,
        'cart_id' => $order->cart_id,
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
    $order = Order::with(['subOrder.shop', 'cart.cartItem.product'])->find($order_id);

    if (!$order) {
      return response()->json([
        'message' => 'Order not found',
        'status' => 404,
      ], 404);
    }

    $formatted_order = [
      'id' => $order->id,
      'total_prices' => $order->total_prices,
      'address' => $order->address,
      'payment_method' => $order->payment_method,
      'transaction_order_id' => $order->transaction_order_id,
      'customer_id' => $order->customer_id,
      'created_at' => $order->created_at,
      'updated_at' => $order->updated_at,
      'cart' => [
        'id' => $order->cart->id,
        'total_prices' => $order->cart->total_prices,
        'cart_items' => $order->cart->cartItem->map(function ($cartItem) {
          $ratingData = $cartItem->product->calculateProductRating();

          return [
            'id' => $cartItem->id,
            'name' => $cartItem->name,
            'description' => $cartItem->description,
            'quantity' => $cartItem->quantity,
            'amount' => $cartItem->amount,
            'price' => $cartItem->price,
            'product_id' => $cartItem->product_id,
            'product_image' => $cartItem->product->image,
            'rating' => $ratingData['average'],
            'rating_count' => $ratingData['count'],
          ];
        }),
      ],
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
      'transaction_order_id' => 'required|unique:orders,transaction_order_id',
      'cart_id' => 'required|exists:carts,id',
    ], [
      'address.required' => 'Address is required',
      'payment_method.required' => 'Payment method is required',
      'transaction_order_id.required' => 'Transaction order ID is required',
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

      // Tạo đơn hàng
      $order = Order::create([
        'total_prices' => $totalPrices,
        'address' => $validatedData['address'],
        'payment_method' => $validatedData['payment_method'],
        'transaction_order_id' => $validatedData['transaction_order_id'],
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

        // Tạo subOrder cho từng shop
        SubOrder::create([
          'sub_total_prices' => $subOrderTotalPrice,
          'status' => 'pending', // Có thể thay đổi trạng thái nếu cần
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
}
