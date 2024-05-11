<?php

namespace Database\Seeders;

use App\Models\Account;
use App\Models\Pet;
use App\Models\RatingPet;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class RatingPetSeeder extends Seeder
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

        $pet_ids = Pet::pluck('id')->toArray();

        foreach ($pet_ids as $pet_id) {
            $num_ratings_for_pet = $faker->numberBetween(0, 5);

            $selected_customer_ids = [];

            for ($i = 0; $i < $num_ratings_for_pet; $i++) {
                // Random một khách hàng chưa được chọn trước đó
                do {
                    $random_customer_id = $faker->randomElement($customer_account_ids);
                } while (in_array($random_customer_id, $selected_customer_ids));

                // Thêm khách hàng vào danh sách đã chọn
                $selected_customer_ids[] = $random_customer_id;

                RatingPet::create([
                    'rating' => $faker->numberBetween(1, 5),
                    'description' => $faker->paragraph(8),
                    'customer_id' => $random_customer_id,
                    'pet_id' => $pet_id,
                ]);
            }

            $selected_customer_ids = [];
        }
    }
}
