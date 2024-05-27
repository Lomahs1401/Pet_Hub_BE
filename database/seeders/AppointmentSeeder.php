<?php

namespace Database\Seeders;

use App\Models\Account;
use App\Models\Appointment;
use App\Models\MedicalCenter;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class AppointmentSeeder extends Seeder
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
        $medical_center_ids = MedicalCenter::pluck('id')->toArray();

        foreach($customer_account_ids as $customer_account_id) {
            $random_amount_of_appointments = rand(0, 5);

            for ($i = 0; $i < $random_amount_of_appointments; $i++) {
                $start_time = $faker->dateTimeBetween('+3 days', '+2 weeks');

                Appointment::factory()->create([
                    'message' => $faker->paragraph(),
                    'start_time' => $start_time,
                    'customer_id' => $customer_account_id,
                    'medical_center_id' => $faker->randomElement($medical_center_ids),
                ]);
            }
        }
    }
}
