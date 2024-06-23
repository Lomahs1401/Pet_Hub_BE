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
    $subOrders = SubOrder::with(['order.cart.cartItem.product.shop', 'order.cart.cartItem.product.ratings'])
      ->whereHas('order', function ($query) use ($customer) {
        $query->where('customer_id', $customer->id);
      })
      ->where('status', 'Done')
      ->orderBy('created_at', 'desc')
      ->offset(($page_number - 1) * $num_of_page)
      ->limit($num_of_page)
      ->get();

    // Chuẩn bị dữ liệu trả về
    $result = [];

    foreach ($subOrders as $subOrder) {
      // Lấy danh sách các cart_items của subOrder hiện tại
      $cartItems = $subOrder->order->cart->cartItem;

      // Lọc ra các cart_items của cùng shop với subOrder
      $filteredCartItems = $cartItems->filter(function ($item) use ($subOrder) {
        return $item->product->shop_id === $subOrder->shop_id;
      })->map(function ($item) use ($customer) {
        $ratingInfo = null;

        // Kiểm tra xem khách hàng đã đánh giá sản phẩm này chưa
        $rating = $item->product->ratings->where('customer_id', $customer->id)->first();
        $isReviewed = false;
        $shopResponsed = false;

        if ($rating) {
          $isReviewed = true;
          $shopResponsed = $rating->reply !== null;

          $ratingInfo = [
            'rated' => true,
            'rating' => $rating->rating,
            'description' => $rating->description,
            'reply' => $rating->reply,
            'reply_date' => $rating->reply_date,
          ];
        }

        $itemData = [
          'id' => $item->id,
          'name' => $item->name,
          'image' => $item->product->image,
          'description' => $item->description,
          'quantity' => $item->quantity,
          'price' => $item->price,
          'amount' => $item->amount,
          'product_id' => $item->product_id,
          'created_at' => $item->created_at,
          'updated_at' => $item->updated_at,
          'is_reviewed' => $isReviewed,
          'shop_responsed' => $shopResponsed,
        ];

        if ($isReviewed) {
          $itemData['rating_info'] = $ratingInfo;
        }

        return $itemData;
      });

      // Kiểm tra xem khách hàng đã đánh giá cửa hàng chưa
      $shopRating = $subOrder->shop->ratingShop->where('customer_id', $customer->id)->first();

      $isReviewed = false;
      $isResponsed = false;
      $ratingInfo = null;

      if ($shopRating) {
        $isReviewed = true;
        $isResponsed = $shopRating->reply !== null;

        $ratingInfo = [
          'rated' => true,
          'rating' => $shopRating->rating,
          'description' => $shopRating->description,
          'reply' => $shopRating->reply,
          'reply_date' => $shopRating->reply_date,
        ];
      }

      // Tạo dữ liệu cho mỗi subOrder
      $result[] = [
        'sub_order_id' => $subOrder->id,
        'sub_total_prices' => $subOrder->sub_total_prices,
        'status' => $subOrder->status,
        'order_id' => $subOrder->order->id,
        'address' => $subOrder->order->address,
        'payment_method' => $subOrder->order->payment_method,
        'transaction_order_id' => $subOrder->order->transaction_order_id,
        'shop_id' => $subOrder->shop->id,
        'shop_name' => $subOrder->shop->name,
        'shop_image' => $subOrder->shop->image,
        'is_shop_reviewed' => $isReviewed,
        'is_responsed' => $isResponsed,
        'rating_info' => $ratingInfo,
        'created_at' => $subOrder->created_at,
        'updated_at' => $subOrder->updated_at,
        'cart_items' => $filteredCartItems->values()->all(),
      ];
    }

    // Tính tổng số suborders và tổng số trang
    $total_suborders = SubOrder::whereHas('order', function ($query) use ($customer) {
      $query->where('customer_id', $customer->id);
    })->where('status', 'Done')->count();
    $total_pages = ceil($total_suborders / $num_of_page);

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
