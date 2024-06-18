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

    // Lấy ID của các role
    $role_customer_id = Role::where('role_name', 'ROLE_CUSTOMER')->pluck('id')->first();
    $role_aid_center_id = Role::where('role_name', 'ROLE_AID_CENTER')->pluck('id')->first();

    // Lấy danh sách các account_id từ bảng accounts với role_id là 1 và 5
    $account_ids = Account::whereIn('role_id', [$role_customer_id, $role_aid_center_id])->pluck('id')->toArray();

    for ($i = 0; $i < 20; $i++) {
      // Lấy một role ID ngẫu nhiên từ hai mảng role
      $random_role_id = $faker->randomElement($account_ids);

      $created_at = $faker->dateTimeBetween('-4 months', 'now');

      Blog::factory()->create([
        'title' => $faker->sentence(10),
        'text' => $faker->paragraph(16),
        'image' => 'gs://new_petshop_bucket/blogs/' . ($i + 1) . '/',
        'account_id' => $random_role_id,
        'created_at' => $created_at,
        'updated_at' => $created_at,
      ]);
    }
  }
}
