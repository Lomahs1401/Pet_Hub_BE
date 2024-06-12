<?php

namespace Database\Seeders;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Product;
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

        $isActive = rand(0, 100) < 1; // 1% true (gio hang dang hoat dong), 99% false (da thanh toan)

        // Tạo giỏ hàng cho khách hàng
        $cart = Cart::create([
          'customer_id' => $customer->id,
          'total_prices' => 0,
          'is_active' => $isActive,
        ]);

        // Tạo từ 1 đến 5 sản phẩm trong giỏ hàng
        $numberOfItems = rand(1, 5);
        $totalPrices = 0;
        $shopIds = [];

        for ($i = 0; $i < $numberOfItems; $i++) {
          $product = $products->random();
          $quantity = rand(1, 10);
          $amount = $product->price;
          $price = $amount * $quantity;

          // Thêm sản phẩm vào giỏ hàng
          CartItem::create([
            'cart_id' => $cart->id,
            'product_id' => $product->id,
            'name' => $product->name,
            'description' => $product->description,
            'quantity' => $quantity,
            'amount' => $amount,
            'price' => $price,
          ]);

          // Tính tổng giá trị giỏ hàng
          $totalPrices += $price;

          // Lưu lại shop_id của sản phẩm
          $shopIds[$product->shop_id][] = $price;
        }

        // Cập nhật tổng giá trị giỏ hàng
        $cart->total_prices = $totalPrices;
        $cart->save();

        // Random order_date từ 2 thang trước đến thời điểm hiện tại
        $order_date = $faker->dateTimeBetween('-2 months', 'now');

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
          foreach ($shopIds as $shopId => $prices) {
            $subTotalPrices = array_sum($prices);

            // Xác định trạng thái dựa trên phương thức thanh toán
            if ($paymentMethod == 'Paypal') {
              $statuses = ['Paid', 'Delivering', 'Done'];
            } else {
              $statuses = ['Created', 'Delivering', 'Done'];
            }

            $status = $statuses[array_rand($statuses)];

            SubOrder::create([
              'order_id' => $order->id,
              'shop_id' => $shopId,
              'sub_total_prices' => $subTotalPrices,
              'status' => $status,
              'created_at' => $order_date,
              'updated_at' => $order_date,
            ]);
          }
        }
      }
    }
  }
}
