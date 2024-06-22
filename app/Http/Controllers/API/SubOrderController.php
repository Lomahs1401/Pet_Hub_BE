<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\SubOrder;
use Illuminate\Http\Request;

class SubOrderController extends Controller
{
  public function getDoneSubOrder(Request $request)
  {
    // Lấy thông tin customer đang đăng nhập
    $customer = auth()->user()->customer;

    if (!$customer) {
      return response()->json([
        'message' => 'Customer not found!',
        'status' => 404,
      ], 404);
    }

    // Lấy tham số page_number và num_of_page từ request
    $page_number = intval($request->query('page_number', 1));
    $num_of_page = intval($request->query('num_of_page', 10));

    // Truy vấn danh sách các suborders có trạng thái là 'Done' và thuộc về customer đang đăng nhập
    $query = SubOrder::with(['order.cart.cartItem.product.shop'])
      ->whereHas('order', function ($query) use ($customer) {
        $query->where('customer_id', $customer->id);
      })
      ->where('status', 'Done');

    // Tính tổng số suborders và tổng số trang
    $total_suborders = $query->count();
    $total_pages = ceil($total_suborders / $num_of_page);

    // Phân trang
    $doneSubOrders = $query->orderBy('created_at', 'desc')
      ->offset(($page_number - 1) * $num_of_page)
      ->limit($num_of_page)
      ->get();

    // Chuẩn bị dữ liệu trả về
    $result = $doneSubOrders->map(function ($subOrder) {
      // Lấy shop_id của sub_order
      $shopId = $subOrder->order->cart->cartItem->first()->product->shop_id;

      // Lấy các cart_items thuộc cùng shop với sub_order
      $cartItems = $subOrder->order->cart->cartItem->filter(function ($item) use ($shopId) {
        return $item->product->shop_id === $shopId;
      })->map(function ($item) {
        return [
          'id' => $item->id,
          'name' => $item->name,
          'description' => $item->description,
          'quantity' => $item->quantity,
          'price' => $item->price,
          'amount' => $item->amount,
          'product_id' => $item->product_id,
          'created_at' => $item->created_at,
          'updated_at' => $item->updated_at,
        ];
      });

      return [
        'sub_order_id' => $subOrder->id,
        'sub_total_prices' => $subOrder->sub_total_prices,
        'status' => $subOrder->status,
        'order_id' => $subOrder->order->id,
        'address' => $subOrder->order->address,
        'payment_method' => $subOrder->order->payment_method,
        'transaction_order_id' => $subOrder->order->transaction_order_id,
        'created_at' => $subOrder->created_at,
        'updated_at' => $subOrder->updated_at,
        'cart_items' => $cartItems->values()->all(),
      ];
    });

    return response()->json([
      'message' => 'Query successfully!',
      'status' => 200,
      'page_number' => $page_number,
      'num_of_page' => $num_of_page,
      'total_pages' => $total_pages,
      'total_suborders' => $total_suborders,
      'data' => $result,
    ]);
  }
}
