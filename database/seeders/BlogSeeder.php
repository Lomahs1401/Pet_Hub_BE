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

        $customer_role_ids = Role::where('role_type', 'Customer')->pluck('id')->toArray();
        $customer_account_ids = Account::whereHas('roles', function ($query) use ($customer_role_ids) {
            $query->whereIn('roles.id', $customer_role_ids);
        })->pluck('accounts.id')->toArray();

        $blog_category_model = new BlogCategory();
        $blog_category_ids = $blog_category_model->pluck('id')->toArray();

        for ($i = 0; $i < 9; $i++) {
            Blog::factory()->create([
                'title' => $faker->sentence(10),
                'text' => $faker->paragraph(16),
                'image' => 'gs://petshop-3d4ae.appspot.com/blogs/blog_' . ($i+1) . '.jpg',
                'account_id' => $faker->randomElement($customer_account_ids),
                'category_id' => $faker->randomElement($blog_category_ids),
            ]);
        }
    }
}
