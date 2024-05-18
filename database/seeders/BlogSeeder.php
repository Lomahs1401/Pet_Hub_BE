<?php

namespace Database\Seeders;

use App\Models\Account;
use App\Models\AccountHasRole;
use App\Models\Blog;
use App\Models\BlogCategory;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class BlogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();

        $role_customer = Role::where('role_name', 'ROLE_CUSTOMER')->first()->id;
        $role_shop = Role::where('role_name', 'ROLE_SHOP')->first()->id;
        $role_medical_center = Role::where('role_name', 'ROLE_MEDICAL_CENTER')->first()->id;
        $role_aid_center = Role::where('role_name', 'ROLE_AID_CENTER')->first()->id;

        $blog_category_model = new BlogCategory();
        $blog_category_ids = $blog_category_model->pluck('id')->toArray();

        for ($i = 0; $i < 9; $i++) {
            Blog::factory()->create([
                'title' => $faker->sentence(10),
                'text' => $faker->paragraph(16),
                'image' => 'gs://petshop-3d4ae.appspot.com/blogs/blog_' . ($i+1) . '.jpg',
                'account_id' => $faker->randomElement([$role_customer, $role_shop, $role_medical_center, $role_aid_center]),
                'category_id' => $faker->randomElement($blog_category_ids),
            ]);
        }
    }
}
