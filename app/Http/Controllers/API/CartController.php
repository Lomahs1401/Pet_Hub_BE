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
    $cartItems = $cart->cartItem()->with('product.shop.account')->get();

    // Tạo một danh sách các shop
    $shops = [];

    foreach ($cartItems as $cart_item) {
      $shop = $cart_item->product->shop;
      $ratingShop = $shop->calculateShopRating();
      $ratingProduct = $cart_item->product->calculateProductRating();

      $formatted_cart_item = [
        'id' => $cart_item->id,
        'name' => $cart_item->name,
        'description' => $cart_item->description,
        'quantity' => $cart_item->quantity,
        'price' => $cart_item->price,
        'amount' => $cart_item->amount,
        'product_id' => $cart_item->product_id,
        'product_image' => $cart_item->product->image,
        'rating' => $ratingProduct['average'],
        'rating_count' => $ratingProduct['count'],
      ];

      // Kiểm tra xem shop đã tồn tại trong danh sách chưa
      if (!isset($shops[$shop->id])) {
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
          'cart_items' => [],
        ];
      }

      // Thêm sản phẩm vào shop tương ứng
      $shops[$shop->id]['cart_items'][] = $formatted_cart_item;
    }

    // Chuyển danh sách các shop từ associative array sang indexed array
    $shops = array_values($shops);

    return response()->json([
      'message' => 'Query cart successfully',
      'status' => 200,
      'cart' => [
        'id' => $cart->id,
        'total_prices' => $cart->total_prices,
        'is_active' => $cart->is_active,
      ],
      'shops' => $shops,
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
