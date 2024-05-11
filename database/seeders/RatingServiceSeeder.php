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

        $customer_role_ids = Role::where('role_type', 'Customer')->pluck('id')->toArray();
        $customer_account_ids = Account::whereHas('roles', function ($query) use ($customer_role_ids) {
            $query->whereIn('roles.id', $customer_role_ids);
        })->pluck('accounts.id')->toArray();

        $service_ids = Service::pluck('id')->toArray();

        foreach ($service_ids as $service_id) {
            $num_ratings_for_service = $faker->numberBetween(0, 5);

            $selected_customer_ids = [];

            for ($i = 0; $i < $num_ratings_for_service; $i++) {
                // Random một khách hàng chưa được chọn trước đó
                do {
                    $random_customer_id = $faker->randomElement($customer_account_ids);
                } while (in_array($random_customer_id, $selected_customer_ids));

                // Thêm khách hàng vào danh sách đã chọn
                $selected_customer_ids[] = $random_customer_id;

                RatingService::create([
                    'rating' => $faker->numberBetween(1, 5),
                    'description' => $faker->paragraph(8),
                    'customer_id' => $random_customer_id,
                    'service_id' => $service_id,
                ]);
            }

            $selected_customer_ids = [];
        }
    }
}
