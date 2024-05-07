<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Shop;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class ShopHasProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Lấy danh sách tất cả các cửa hàng
        $shops = Shop::all();

        // Lấy danh sách tất cả các sản phẩm
        $products = Product::all();

        // Lặp qua từng cửa hàng
        foreach ($shops as $shop) {
            // Số lượng sản phẩm muốn gán cho mỗi cửa hàng (có thể điều chỉnh)
            $number_of_products = rand(30, 50);

            // Lấy ngẫu nhiên các sản phẩm
            $random_products = $products->random($number_of_products);

            // Gán các sản phẩm cho cửa hàng hiện tại
            $now = Carbon::now();
            $data = $random_products->mapWithKeys(function ($product) use ($now) {
                return [$product->id => ['created_at' => $now, 'updated_at' => $now]];
            })->toArray();

            $shop->products()->attach($random_products);
        }
    }
}
