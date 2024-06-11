<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;

class CartItemController extends Controller
{
  public function addProductIntoCart(Request $request, $cart_id)
  {
    $request->validate([
      'product_id' => 'required|integer',
      'quantity' => 'required|integer|min:1',
    ]);

    $customer_id = auth()->user()->customer->id;

    // Kiểm tra giỏ hàng
    $cart = Cart::where('id', $cart_id)
      ->where('customer_id', $customer_id)
      ->first();

    if (!$cart) {
      return response()->json(['message' => 'Cart not found or does not belong to the current customer'], 404);
    }

    if (!$cart->is_active) {
      return response()->json(['message' => 'Cannot add product to an inactive cart'], 400);
    }

    // Kiểm tra sản phẩm
    $product = Product::find($request->product_id);
    if (!$product) {
      return response()->json(['message' => 'Product not found'], 404);
    }

    // Kiểm tra số lượng sản phẩm còn lại
    if ($product->quantity < $request->quantity) {
      return response()->json(['message' => 'Insufficient product quantity'], 400);
    }

    // Tính toán giá
    $quantity = $request->quantity;
    $amount = $product->price * $quantity;

    // Thêm sản phẩm vào giỏ hàng
    $cartItem = CartItem::create([
      'cart_id' => $cart->id,
      'product_id' => $product->id,
      'name' => $product->name,
      'description' => $product->description,
      'quantity' => $quantity,
      'price' => $product->price,
      'amount' => $amount,
    ]);

    // Cập nhật tổng giá trị giỏ hàng
    $cart->total_prices += $amount;
    $cart->save();

    return response()->json([
      'message' => 'Product added to cart successfully',
      'status' => 200,
      'cart_item' => $cartItem,
    ], 200);
  }

  public function removeProductFromCart($cart_item_id)
  {
    // Tìm cart item dựa trên cart_item_id
    $cartItem = CartItem::withTrashed()->find($cart_item_id);

    if (!$cartItem) {
      return response()->json(['message' => 'Cart item not found'], 404);
    }

    if ($cartItem->deleted_at !== null) {
      return response()->json(['message' => 'Cart item has already been deleted'], 400);
    }

    // Lấy cart và kiểm tra quyền sở hữu
    $cart = Cart::find($cartItem->cart_id);
    $customer_id = auth()->user()->customer->id;

    if (!$cart || $cart->customer_id != $customer_id) {
      return response()->json(['message' => 'Cart not found or does not belong to the current customer'], 404);
    }

    // Xóa cart item (soft delete)
    $cartItem->delete();

    // Cập nhật tổng giá trị giỏ hàng
    $cart->total_prices -= $cartItem->price;
    $cart->save();

    return response()->json([
      'message' => 'Product removed from cart successfully',
    ], 200);
  }
}
