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

    $customer_accounts = Account::whereHas('role', function ($query) {
      $query->where('role_name', 'ROLE_CUSTOMER');
    })->get();

    foreach ($blogs as $blog) {
      // Tạo số lượng comment ngẫu nhiên cho mỗi blog
      $number_of_comments = $faker->numberBetween(6, 10);
      $comments = [];

      // Tạo các comments ban đầu
      for ($i = 0; $i < $number_of_comments; $i++) {
        $author = $faker->randomElement($customer_accounts);

        $comment = Comment::factory()->create([
          'text' => $faker->paragraph(),
          'account_id' => $author->id,
          'blog_id' => $blog->id,
          'created_at' => now(),
          'updated_at' => now(),
        ]);

        $comments[] = $comment->id;
      }

      // Tạo các sub-comments
      foreach ($comments as $comment_id) {
        if ($faker->boolean(25)) { // 25% cơ hội để tạo sub-comment
          $parent_comment = Comment::find($comment_id);
          $author = $faker->randomElement($customer_accounts);

          // Đảm bảo account_id của sub-comment khác account_id của comment cha
          while ($author->id == $parent_comment->account_id) {
            $author = $faker->randomElement($customer_accounts);
          }

          Comment::factory()->create([
            'text' => $faker->paragraph(),
            'account_id' => $author->id,
            'blog_id' => $blog->id,
            'parent_comments_id' => $comment_id,
            'created_at' => now(),
            'updated_at' => now(),
          ]);
        }
      }
    }
  }
}
