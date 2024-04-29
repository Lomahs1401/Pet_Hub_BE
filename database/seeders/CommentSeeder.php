<?php

namespace Database\Seeders;

use App\Models\Account;
use App\Models\Blog;
use App\Models\Comment;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class CommentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();

        $blogs = Blog::all();

        $customer_accounts = Account::whereHas('roles', function ($query) {
            $query->where('role_type', 'Customer');
        })->get();

        foreach ($blogs as $blog) {
            // Tạo số lượng comment ngẫu nhiên cho mỗi blog
            $numberOfComments = $faker->numberBetween(2, 5);

            for ($i = 0; $i < $numberOfComments; $i++) {
                $author = $faker->randomElement($customer_accounts);

                Comment::factory()->create([
                    'text' => $faker->paragraph(),
                    'account_id' => $author->id,
                    'blog_id' => $blog->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}