<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use Illuminate\Http\Request;

class CartController extends Controller
{
  public function getCurrentCart()
  {
    $customer_id = auth()->user()->customer->id;

    // Tìm giỏ hàng hiện tại của khách hàng (giả sử chỉ có một giỏ hàng đang hoạt động tại một thời điểm)
    $cart = Cart::where('customer_id', $customer_id)
      ->where('is_active', true)
      ->first();

    if (!$cart) {
      return response()->json([
        'message' => 'No active cart found for the current customer',
      ], 404);
    }

    // Lấy tất cả các sản phẩm trong giỏ hàng
    $cartItems = $cart->cartItem()->with('product')->get();

    $formatted_cart_items = [];

    foreach ($cartItems as $cart_item) {
      $ratingData = $cart_item->product->calculateProductRating();

      $formatted_cart_items[] = [
        'id' => $cart_item->id,
        'name' => $cart_item->name,
        'description' => $cart_item->description,
        'quantity' => $cart_item->quantity,
        'amount' => $cart_item->amount,
        'price' => $cart_item->price,
        'product_id' => $cart_item->product_id,
        'product_id' => $cart_item->product->image,
        'rating' => $ratingData['average'],
        'rating_count' => $ratingData['count'],
      ];
    }

    return response()->json([
      'message' => 'Query cart successfully',
      'status' => 200,
      'cart' => $cart,
      'cart_items' => $formatted_cart_items,
    ], 200);
  }

  public function createNewCart(Request $request)
  {
    $customer_id = auth()->user()->customer->id;

    // Tạo mới một giỏ hàng
    $cart = Cart::create([
      'customer_id' => $customer_id,
      'total_prices' => 0,
      'is_active' => true,
    ]);

    return response()->json([
      'message' => 'Cart created successfully',
      'status' => 201,
      'cart' => $cart,
    ], 201);
  }

  /**
   * Cập nhật thông tin của giỏ hàng.
   */
  public function updateCart(Request $request, $cart_id)
  {
    // Validate dữ liệu đầu vào
    $validatedData = $request->validate([
      'total_prices' => 'sometimes|required|numeric|min:0',
      'is_active' => 'sometimes|required|boolean',
    ]);

    // Tìm giỏ hàng theo ID
    $cart = Cart::find($cart_id);

    if (!$cart) {
      return response()->json([
        'message' => 'Cart not found',
      ], 404);
    }

    // Cập nhật thông tin giỏ hàng
    $cart->update($validatedData);

    return response()->json([
      'message' => 'Cart updated successfully',
      'cart' => $cart,
    ]);
  }
}
