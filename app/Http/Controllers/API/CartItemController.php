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

    // Kiểm tra xem sản phẩm đã tồn tại trong giỏ hàng chưa, bao gồm cả các mục đã bị xóa mềm
    $cartItem = CartItem::withTrashed()
      ->where('cart_id', $cart->id)
      ->where('product_id', $product->id)
      ->first();

    $newQuantity = $request->quantity;

    if ($cartItem) {
      $newQuantity += $cartItem->quantity;

      // Kiểm tra số lượng sản phẩm còn lại
      if ($product->quantity < $newQuantity) {
        return response()->json(['message' => 'Insufficient product quantity'], 400);
      }
      if ($cartItem->trashed()) {
        // Khôi phục lại mục giỏ hàng nếu đã bị xóa mềm
        $cartItem->restore();
      }
      // Tăng số lượng sản phẩm trong giỏ hàng nếu đã tồn tại
      $cartItem->quantity = $newQuantity;
      $cartItem->amount = $cartItem->quantity * $product->price;
      $cartItem->save();
    } else {
      // Thêm sản phẩm vào giỏ hàng nếu chưa tồn tại
      $cartItem = CartItem::create([
        'cart_id' => $cart->id,
        'product_id' => $product->id,
        'name' => $product->name,
        'description' => $product->description,
        'quantity' => $request->quantity,
        'price' => $product->price,
        'amount' => $request->quantity * $product->price,
      ]);
    }

    // Cập nhật tổng giá trị giỏ hàng
    $cart->total_prices += $request->quantity * $product->price;
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

  public function increaseQuantityOfProductInCart(Request $request, $cart_id)
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
      return response()->json(['message' => 'Cannot modify an inactive cart'], 400);
    }

    // Kiểm tra sản phẩm
    $cartItem = CartItem::where('cart_id', $cart->id)
      ->where('product_id', $request->product_id)
      ->first();

    if (!$cartItem) {
      return response()->json(['message' => 'Product not found in cart'], 404);
    }

    // Kiểm tra số lượng sản phẩm còn lại
    $product = Product::find($request->product_id);
    if ($product->quantity < ($cartItem->quantity + $request->quantity)) {
      return response()->json(['message' => 'Insufficient product quantity'], 400);
    }

    // Tăng số lượng sản phẩm trong giỏ hàng
    $cartItem->quantity += $request->quantity;
    $cartItem->amount = $cartItem->quantity * $product->price;
    $cartItem->save();

    // Cập nhật tổng giá trị giỏ hàng
    $cart->total_prices += $request->quantity * $product->price;
    $cart->save();

    return response()->json([
      'message' => 'Product quantity increased successfully',
      'status' => 200,
      'cart_item' => $cartItem,
    ], 200);
  }

  public function decreaseQuantityOfProductInCart(Request $request, $cart_id)
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
      return response()->json(['message' => 'Cannot modify an inactive cart'], 400);
    }

    // Kiểm tra sản phẩm
    $cartItem = CartItem::where('cart_id', $cart->id)
      ->where('product_id', $request->product_id)
      ->first();

    if (!$cartItem) {
      return response()->json(['message' => 'Product not found in cart'], 404);
    }

    // Kiểm tra nếu số lượng giảm vượt quá số lượng hiện tại
    if ($cartItem->quantity < $request->quantity) {
      return response()->json(['message' => 'Insufficient quantity to decrease'], 400);
    }

    // Giảm số lượng sản phẩm trong giỏ hàng
    $cartItem->quantity -= $request->quantity;
    $cartItem->amount = $cartItem->quantity * $cartItem->price;

    // Nếu số lượng sản phẩm bằng 0, xóa sản phẩm khỏi giỏ hàng
    if ($cartItem->quantity == 0) {
      $cartItem->delete();
    } else {
      $cartItem->save();
    }

    // Cập nhật tổng giá trị giỏ hàng
    $cart->total_prices -= $request->quantity * $cartItem->price;
    $cart->save();

    return response()->json([
      'message' => 'Product quantity decreased successfully',
      'status' => 200,
      'cart_item' => $cartItem,
    ], 200);
  }
}
