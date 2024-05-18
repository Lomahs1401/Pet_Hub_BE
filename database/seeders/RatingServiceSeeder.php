<?php

namespace Database\Seeders;

use App\Models\Account;
use App\Models\RatingService;
use App\Models\Role;
use App\Models\Service;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class RatingServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();

        $customer_account_ids = Account::whereHas('role', function ($query) {
            $query->where('role_name', 'ROLE_CUSTOMER');
        })->pluck('id')->toArray();

        $service_ids = Service::pluck('id')->toArray();

        foreach ($service_ids as $service_id) {
            $num_ratings_for_service = $faker->numberBetween(0, 5);

            $selected_customer_ids = [];

            // 25% xác suất cho rating 5 sao
            // 45% xác suất cho rating 4 sao
            // 12% xác suất cho rating 3 sao
            // 6% xác suất cho rating 2 sao
            // 12% xác suất cho rating 1 sao
            $rating = $faker->randomElement([5, 4, 4, 3, 3, 2, 1, 1]);

            for ($i = 0; $i < $num_ratings_for_service; $i++) {
                // Random một khách hàng chưa được chọn trước đó
                do {
                    $random_customer_id = $faker->randomElement($customer_account_ids);
                } while (in_array($random_customer_id, $selected_customer_ids));

                // Thêm khách hàng vào danh sách đã chọn
                $selected_customer_ids[] = $random_customer_id;

                RatingService::create([
                    'rating' => $rating,
                    'description' => $faker->paragraph(8),
                    'customer_id' => $random_customer_id,
                    'service_id' => $service_id,
                ]);
            }

            $selected_customer_ids = [];
        }
    }
}
