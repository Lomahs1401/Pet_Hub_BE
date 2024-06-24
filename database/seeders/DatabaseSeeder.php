<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
  /**
   * Seed the application's database.
   *
   * @return void
   */
  public function run(): void
  {
    \App\Models\User::factory(10)->create();

    $this->call(RoleSeeder::class);
    $this->call(RankingSeeder::class);
    $this->call(AccountSeeder::class);
    $this->call(BreedSeeder::class);
    $this->call(BlogSeeder::class);
    $this->call(CommentSeeder::class);
    $this->call(InteractSeeder::class);
    $this->call(ShopSeeder::class);
    $this->call(MedicalCenterSeeder::class);
    $this->call(AidCenterSeeder::class);
    $this->call(PetSeeder::class);
    $this->call(AppointmentSeeder::class);
    $this->call(ProductCategorySeeder::class);
    $this->call(ProductSeeder::class);
    $this->call(CartSeeder::class);
  }
}
