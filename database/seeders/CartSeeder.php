<?php

namespace Database\Seeders;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Product;
use App\Models\RatingProduct;
use App\Models\RatingProductInteract;
use App\Models\RatingShop;
use App\Models\RatingShopInteract;
use App\Models\Shop;
use App\Models\SubOrder;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class CartSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    $faker = Faker::create();

    // Lấy tất cả khách hàng
    $customers = Customer::all();
    $products = Product::all();

    foreach ($customers as $customer) {
      // Random số lượt mà khách hàng có giỏ hàng
      $numberOfCarts = rand(1, 100);

      for ($n = 0; $n < $numberOfCarts; $n++) {
        // Kiểm tra xem khách hàng đã có giỏ hàng đang hoạt động chưa
        $activeCart = Cart::where('customer_id', $customer->id)
          ->where('is_active', true)
          ->exists();

        // Nếu khách hàng đã có giỏ hàng đang hoạt động, bỏ qua vòng lặp hiện tại
        if ($activeCart) {
          break;
        }

        $isActive = rand(0, 100) < 1; // 1% true (giỏ hàng đang hoạt động), 99% false (đã thanh toán)

        // Tạo giỏ hàng cho khách hàng
        $cart = Cart::create([
          'customer_id' => $customer->id,
          'total_prices' => 0,
          'is_active' => $isActive,
        ]);

        // Tạo từ 1 đến 5 sản phẩm trong giỏ hàng
        $numberOfItems = rand(1, 8);
        $totalPrices = 0;
        $shopIds = [];
        $subOrders = [];

        for ($i = 0; $i < $numberOfItems; $i++) {
          $product = $products->random();
          $quantity = rand(1, 10);
          $price = $product->price;
          $amount = $price * $quantity;

          // Thêm sản phẩm vào giỏ hàng
          CartItem::create([
            'cart_id' => $cart->id,
            'product_id' => $product->id,
            'name' => $product->name,
            'description' => $product->description,
            'quantity' => $quantity,
            'price' => $price,
            'amount' => $amount,
          ]);

          // Tính tổng giá trị giỏ hàng
          $totalPrices += $amount;

          // Lưu lại shop_id của sản phẩm
          $shopIds[$product->shop_id][] = $amount;

          $product->increment('sold_quantity', $quantity);
          $product->decrement('quantity', $quantity);
        }

        // Cập nhật tổng giá trị giỏ hàng
        $cart->total_prices = $totalPrices;
        $cart->save();

        // Random order_date từ 2 tháng trước đến thời điểm hiện tại
        $order_date = $faker->dateTimeBetween('-2 years', 'now');

        if (!$isActive) {
          $paymentMethod = (rand(0, 1) == 1) ? 'COD' : 'Paypal';
          // Tạo đơn hàng nếu giỏ hàng không còn hoạt động
          $order = Order::create([
            'customer_id' => $customer->id,
            'cart_id' => $cart->id,
            'total_prices' => $totalPrices,
            'address' => $customer->address,
            'payment_method' => $paymentMethod,
            'transaction_order_id' => strtoupper(uniqid()),
            'created_at' => $order_date,
            'updated_at' => $order_date,
          ]);

          // Tạo SubOrder cho mỗi shop
          foreach ($shopIds as $shopId => $amount) {
            $subTotalPrices = array_sum($amount);

            // Xác định trạng thái dựa trên phương thức thanh toán
            if ($paymentMethod == 'Paypal') {
              $statuses = ['Paid', 'Delivering', 'Done'];
            } else {
              $statuses = ['Created', 'Delivering', 'Done'];
            }

            $status = $statuses[array_rand($statuses)];

            $subOrder = SubOrder::create([
              'order_id' => $order->id,
              'shop_id' => $shopId,
              'sub_total_prices' => $subTotalPrices,
              'status' => $status,
              'created_at' => $order_date,
              'updated_at' => $order_date,
            ]);

            $subOrders[] = $subOrder;
          }
        }

        // Tạo RatingProduct hoặc RatingShop nếu SubOrder có status là Done
        foreach ($subOrders as $subOrder) {
          if ($subOrder->status == 'Done') {
            // Xác suất 20% để tạo đánh giá sản phẩm và cửa hàng
            if ($faker->randomFloat(2, 0, 1) <= 0.2) {
              // Tạo đánh giá cho sản phẩm trong subOrder
              $ratedProducts = [];
              $productsInSubOrder = CartItem::where('cart_id', $cart->id)
                ->whereIn('product_id', Product::where('shop_id', $subOrder->shop_id)->pluck('id'))
                ->get();

              foreach ($productsInSubOrder as $productInSubOrder) {
                // Kiểm tra xem đã đánh giá sản phẩm này chưa
                if (!in_array($productInSubOrder->product_id, $ratedProducts)) {
                  $existingRating = RatingProduct::where('customer_id', $customer->id)
                    ->where('product_id', $productInSubOrder->product_id)
                    ->exists();

                  if (!$existingRating) {
                    // Tạo rating ngẫu nhiên
                    $ratings = array_merge(
                      array_fill(0, 35, 5),
                      array_fill(0, 45, 4),
                      array_fill(0, 6, 3),
                      array_fill(0, 6, 2),
                      array_fill(0, 8, 1)
                    );
                    $rating = $faker->randomElement($ratings);

                    // Tạo RatingProduct
                    $ratingProduct = RatingProduct::create([
                      'rating' => $rating,
                      'description' => $faker->paragraph(8),
                      'customer_id' => $customer->id,
                      'product_id' => $productInSubOrder->product_id,
                      'created_at' => $order_date,
                      'updated_at' => $order_date
                    ]);

                    // Tạo RatingProductInteract
                    $num_likes = $faker->numberBetween(0, 5);
                    $liked_customer_ids = $faker->randomElements($customers->pluck('id')->toArray(), $num_likes);

                    foreach ($liked_customer_ids as $liked_customer_id) {
                      RatingProductInteract::create([
                        'rating_product_id' => $ratingProduct->id,
                        'account_id' => $liked_customer_id,
                      ]);
                    }

                    // Thêm sản phẩm vào mảng đã đánh giá
                    $ratedProducts[] = $productInSubOrder->product_id;
                  }
                }
              }

              // Kiểm tra xem đã đánh giá cửa hàng này chưa
              $existingRatingShop = RatingShop::where('customer_id', $customer->id)
                ->where('shop_id', $subOrder->shop_id)
                ->exists();

              if (!$existingRatingShop) {
                // Tạo rating ngẫu nhiên cho cửa hàng
                $ratings = array_merge(
                  array_fill(0, 35, 5),
                  array_fill(0, 45, 4),
                  array_fill(0, 6, 3),
                  array_fill(0, 6, 2),
                  array_fill(0, 8, 1)
                );
                $rating = $faker->randomElement($ratings);

                // Tạo RatingShop
                $ratingShop = RatingShop::create([
                  'rating' => $rating,
                  'description' => $faker->paragraph(8),
                  'customer_id' => $customer->id,
                  'shop_id' => $subOrder->shop_id,
                  'created_at' => $order_date,
                  'updated_at' => $order_date
                ]);

                // Tạo RatingShopInteract
                $num_likes = $faker->numberBetween(0, 5);
                $liked_customer_ids = $faker->randomElements($customers->pluck('id')->toArray(), $num_likes);

                foreach ($liked_customer_ids as $liked_customer_id) {
                  RatingShopInteract::create([
                    'rating_shop_id' => $ratingShop->id,
                    'account_id' => $liked_customer_id,
                  ]);
                }
              }
            }
          }
        }
      }
    }
  }
}
