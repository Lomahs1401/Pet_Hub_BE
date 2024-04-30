<?php

namespace Database\Seeders;

use App\Models\Shop;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class ShopSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();

        $number_of_shops = 50;

        for ($i = 0; $i < $number_of_shops; $i++) {
            $startTime = $faker->time('H:i A'); // Giờ bắt đầu làm việc (AM/PM format)
            $endTime = $faker->time('H:i A');   // Giờ kết thúc làm việc (AM/PM format)
            $work_time = $startTime . ' : ' . $endTime;

            Shop::factory()->create([
                'name' => $faker->company(),
                'email' => $faker->companyEmail(),
                'description' => $faker->paragraph(5),
                'image' => $faker->imageUrl(),
                'phone' => $faker->phoneNumber(),
                'address' => $faker->city(),
                'website' => $faker->url(),
                'fanpage' => $faker->url(),
                'work_time' => $work_time,
                'establish_year' => $faker->year(),
            ]);
        }
    }
}
