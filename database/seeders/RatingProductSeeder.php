<?php

namespace Database\Seeders;

use App\Models\Account;
use App\Models\Product;
use App\Models\RatingProduct;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class RatingProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();

        $customer_role_ids = Role::where('role_type', 'Customer')->pluck('id')->toArray();
        $customer_account_ids = Account::whereHas('roles', function ($query) use ($customer_role_ids) {
            $query->whereIn('roles.id', $customer_role_ids);
        })->pluck('accounts.id')->toArray();

        $product_ids = Product::pluck('id')->toArray();

        foreach ($product_ids as $product_id) {
            $num_ratings_for_product = $faker->numberBetween(0, 5);

            $selected_customer_ids = [];

            // 25% xác suất cho rating 5 sao
            // 45% xác suất cho rating 4 sao
            // 12% xác suất cho rating 3 sao
            // 6% xác suất cho rating 2 sao
            // 12% xác suất cho rating 1 sao
            $rating = $faker->randomElement([5.0, 4.0, 4.0, 3.0, 3.0, 2.0, 1.0, 1.0]);

            for ($i = 0; $i < $num_ratings_for_product; $i++) {
                // Random một khách hàng chưa được chọn trước đó
                do {
                    $random_customer_id = $faker->randomElement($customer_account_ids);
                } while (in_array($random_customer_id, $selected_customer_ids));

                // Thêm khách hàng vào danh sách đã chọn
                $selected_customer_ids[] = $random_customer_id;

                RatingProduct::create([
                    'rating' => $rating,
                    'description' => $faker->paragraph(8),
                    'customer_id' => $random_customer_id,
                    'product_id' => $product_id,
                ]);
            }

            $selected_customer_ids = [];
        }
    }
}
