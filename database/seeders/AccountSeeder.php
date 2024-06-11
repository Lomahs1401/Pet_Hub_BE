<?php

namespace Database\Seeders;

use App\Models\Account;
use App\Models\Ranking;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;

class AccountSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run(): void
  {
    $faker = Faker::create();

    $role_customer = Role::where('role_name', 'ROLE_CUSTOMER')->first()->id;
    $role_admin = Role::where('role_name', 'ROLE_ADMIN')->first()->id;

    $list_customer_avatars = [
      'gs://new_petshop_bucket/avatars/customer/1.jpg',
      'gs://new_petshop_bucket/avatars/customer/2.jpg',
      'gs://new_petshop_bucket/avatars/customer/3.jpg',
      'gs://new_petshop_bucket/avatars/customer/4.jpg',
      'gs://new_petshop_bucket/avatars/customer/5.jpg',
      'gs://new_petshop_bucket/avatars/customer/6.jpg',
      'gs://new_petshop_bucket/avatars/customer/7.jpg',
      'gs://new_petshop_bucket/avatars/customer/8.jpg',
      'gs://new_petshop_bucket/avatars/customer/9.jpg',
      'gs://new_petshop_bucket/avatars/customer/10.jpg',
      'gs://new_petshop_bucket/avatars/customer/11.jpg',
      'gs://new_petshop_bucket/avatars/customer/12.jpg',
      'gs://new_petshop_bucket/avatars/customer/13.jpg',
      'gs://new_petshop_bucket/avatars/customer/14.jpg',
      'gs://new_petshop_bucket/avatars/customer/15.jpg',
    ];

    $list_admin_avatars = [
      'gs://new_petshop_bucket/avatars/admin/1.jpg',
      'gs://new_petshop_bucket/avatars/admin/2.jpg',
      'gs://new_petshop_bucket/avatars/admin/3.jpg',
      'gs://new_petshop_bucket/avatars/admin/4.jpg',
      'gs://new_petshop_bucket/avatars/admin/5.jpg',
      'gs://new_petshop_bucket/avatars/admin/6.jpg',
      'gs://new_petshop_bucket/avatars/admin/7.jpg',
    ];

    define('TOTAL_CUSTOMER_ACCOUNT', count($list_customer_avatars));
    define('TOTAL_ADMIN_ACCOUNT', count($list_admin_avatars));

    // --------------------------      CUSTOMERS     --------------------------
    $rankings = Ranking::all();

    for ($i = 0; $i < TOTAL_CUSTOMER_ACCOUNT; $i++) {
      $created_at = $faker->dateTimeBetween('-2 years', 'now');

      $customer_account = Account::factory()->create([
        'username' => $faker->userName(),
        'email' => $faker->safeEmail(),
        'password' => Hash::make('customer123'),
        'avatar' => $list_customer_avatars[$i],
        'enabled' => $faker->boolean(100),
        'role_id' => $role_customer,
        'reset_code' => null,
        'reset_code_expires_at' => null,
        'reset_code_attempts' => null,
        'created_at' => $created_at,
        'updated_at' => $created_at,
      ]);

      $ranking_point = $faker->randomNumber(4);
      foreach ($rankings as $ranking) {
        if ($ranking_point >= $ranking->check_point) {
          $ranking_id = $ranking->id;
          break;
        }
      }

      if (!isset($ranking_id)) {
        $ranking_id = 1; // Giá trị mặc định
      }

      $is_male_customer = $faker->boolean(chanceOfGettingTrue: 50);

      DB::table('customers')->insert([
        'account_id' => $customer_account->id,
        'full_name' => $is_male_customer ? $faker->lastName() . ' ' . $faker->firstNameMale()
          : $faker->lastName() . ' ' . $faker->firstNameFemale(),
        'gender' => $is_male_customer ? 'Male' : 'Female',
        'birthdate' => $faker->dateTimeInInterval('-20 years', '+2 years', 'Asia/Ho_Chi_Minh')->format('Y-m-d'),
        'address' => $faker->city(),
        'phone' => $faker->regexify('0(3|5|7|8|9){1}([0-9]{8})'),
        'ranking_point' => $ranking_point,
        'ranking_id' => $ranking_id,
        'created_at' => $created_at,
        'updated_at' => $created_at,
      ]);
    }

    // --------------------------      ADMINS     -------------------------- 
    for ($i = 0; $i < TOTAL_ADMIN_ACCOUNT; $i++) {
      $created_at = $faker->dateTimeBetween('-2 years', 'now');

      $admin_account = Account::factory()->create([
        'username' => $faker->userName(),
        'email' => $faker->safeEmail(),
        'password' => Hash::make('admin123'),
        'avatar' => $list_admin_avatars[$i],
        'enabled' => $faker->boolean(100),
        'role_id' => $role_admin,
        'reset_code' => null,
        'reset_code_expires_at' => null,
        'reset_code_attempts' => null,
        'created_at' => $created_at,
        'updated_at' => $created_at,
      ]);

      $is_male_admin = $faker->boolean(chanceOfGettingTrue: 50);

      DB::table('admins')->insert([
        'account_id' => $admin_account->id,
        'full_name' => $is_male_admin ? $faker->lastName() . ' ' . $faker->firstNameMale()
          : $faker->lastName() . ' ' . $faker->firstNameFemale(),
        'gender' => $is_male_admin ? 'Male' : 'Female',
        'birthdate' => $faker->dateTimeInInterval('-20 years', '+2 years', 'Asia/Ho_Chi_Minh')->format('Y-m-d'),
        'CMND' => $faker->numerify('#########'),
        'address' => $faker->city(),
        'phone' => $faker->regexify('0(3|5|7|8|9){1}([0-9]{8})'),
        'image' => $faker->imageUrl(),
        'status' => $faker->boolean(100),
        'created_at' => $created_at,
        'updated_at' => $created_at,
      ]);
    }
  }
}
