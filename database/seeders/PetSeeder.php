<?php

namespace Database\Seeders;

use App\Models\AidCenter;
use App\Models\Breed;
use App\Models\Pet;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class PetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();

        $dog_breed_ids = Breed::where('type', 'Dog')->pluck('id')->toArray();
        $cat_breed_ids = Breed::where('type', 'Cat')->pluck('id')->toArray();
        $aid_centers_ids = AidCenter::pluck('id')->toArray();

        $number_of_pets = 80;

        for ($i = 0; $i < $number_of_pets; $i++) {
            // Loại pet (dog hoặc cat)
            $type = $faker->randomElement(['dog', 'cat']);

            // Thuần chủng hoặc lai giống hoặc bị bỏ rơi
            $is_purebred = $faker->randomElement([true, false]);
            $is_crossbred = ($is_purebred) ? false : $faker->randomElement([true, false]);
            $is_adopt = (!$is_purebred && !$is_crossbred);

            Pet::factory()->create([
                'name' => $faker->lastName(),
                'type' => $type,
                'age' => $faker->numberBetween(1, 120),
                'gender' => $faker->randomElement(['male', 'female']),
                'description' => $faker->paragraph(3),
                'price' => $is_adopt ? 0 : $faker->randomFloat(2, 50, 1000),
                'image' => $faker->imageUrl(),
                'is_purebred' => $is_purebred,
                'is_adopt' => $is_adopt,
                'status' => $faker->randomElement([true, false]),
                'breed_id' => ($type == 'dog') ? $faker->randomElement($dog_breed_ids) : $faker->randomElement($cat_breed_ids),
                'aid_center_id' => $faker->randomElement($aid_centers_ids),
            ]);
        }
    }
}
