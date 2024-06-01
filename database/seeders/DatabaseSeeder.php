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
        
        \App\Models\User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        $this->call(RoleSeeder::class);
        $this->call(RankingSeeder::class);
        $this->call(AccountSeeder::class);
        $this->call(BreedSeeder::class);
        $this->call(BlogCategorySeeder::class);
        $this->call(BlogSeeder::class);
        $this->call(CommentSeeder::class);
        $this->call(InteractSeeder::class);
        $this->call(ShopSeeder::class);
        $this->call(MedicalCenterSeeder::class);
        $this->call(AppointmentSeeder::class);
        $this->call(AidCenterSeeder::class);
        $this->call(PetSeeder::class);
        $this->call(ProductCategorySeeder::class);
        $this->call(ProductSeeder::class);
        $this->call(ServiceCategorySeeder::class);
        $this->call(ServiceSeeder::class);
        $this->call(RatingProductSeeder::class);
        $this->call(RatingServiceSeeder::class);
        $this->call(RatingShopSeeder::class);
        $this->call(RatingMedicalCenterSeeder::class);
    }
}
