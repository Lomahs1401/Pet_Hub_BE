<?php

namespace Database\Seeders;

use App\Models\Account;
use App\Models\Role;
use App\Models\Shop;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\Hash;

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

    $role_shop = Role::where('role_name', 'ROLE_SHOP')->first()->id;

    $shops = [
      ['name' => 'Pet Mart Đà Nẵng', 'address' => '151 Nguyễn Văn Linh, Đà Nẵng'],
      ['name' => 'Petshop Tom & Jerry', 'address' => '177 Nguyễn Hoàng, Đà Nẵng'],
      ['name' => 'Nobipet Petshop', 'address' => '169 Cù Chính Lan, Đà Nẵng'],
      ['name' => 'Happy Petshop Đà Nẵng', 'address' => '55 Thái Thị Bôi, Đà Nẵng'],
      ['name' => 'LuLu Petshop', 'address' => '34 Phạm Nhữ Tăng, Đà Nẵng'],
      ['name' => 'QUỲNH TRANG PETSHOP', 'address' => '454 Nguyễn Tri Phương, Đà Nẵng'],
      ['name' => 'HABANA - Phụ kiện thú cưng', 'address' => '69 Hoàng Thúc Trâm, Đà Nẵng'],
      ['name' => 'Thế Giới Thú Cưng Đà Nẵng', 'address' => '16 Nguyễn Hoàng, Đà Nẵng'],
      ['name' => 'Siêu Thị Chó Mèo Golden Pet', 'address' => '243 Trường Chinh, Đà Nẵng'],
      ['name' => 'Petpro Petshop', 'address' => '167 Hùng Vương, Đà Nẵng'],
      ['name' => 'Punky Pets', 'address' => '627 Ngô Quyền, Đà Nẵng'],
      ['name' => 'SubaRot Petshop', 'address' => '205 Thái Thị Bôi, Đà Nẵng'],
    ];

    $start_time_options = ['05:00 AM', '05:30 AM', '06:00 AM', '06:30 AM', '07:00 AM']; // Giờ bắt đầu làm việc (AM/PM format)
    $end_time_options = ['22:00 PM', '22:30 PM', '23:00 PM', '23:30 PM', '24:00 PM']; // Giờ bắt đầu làm việc (AM/PM format)

    foreach ($shops as $i => $shop) {
      $start_time = $faker->randomElement($start_time_options);
      $end_time = $faker->randomElement($end_time_options);
      $work_time = $start_time . ' : ' . $end_time;

      $created_at = $faker->dateTimeBetween('-2 years', 'now');

      $approved = $faker->boolean(80);

      $shop_account = Account::factory()->create([
        'username' => $faker->userName(),
        'email' => $faker->companyEmail(),
        'password' => Hash::make('shop123'),
        'avatar' => 'gs://new_petshop_bucket/avatars/shop/' . ($i + 1) . '.jpg',
        'enabled' => $approved,
        'is_approved' => $approved,
        'role_id' => $role_shop,
        'created_at' => $created_at,
        'updated_at' => $created_at,
        'reset_code' => null,
        'reset_code_expires_at' => null,
        'reset_code_attempts' => null
      ]);

      Shop::factory()->create([
        'name' => $shop['name'],
        'description' => $faker->paragraph(5),
        'image' => 'gs://new_petshop_bucket/shops/' . ($i + 1) . '/',
        'phone' => $faker->phoneNumber(),
        'address' => $shop['address'],
        'website' => $faker->url(),
        'fanpage' => $faker->url(),
        'work_time' => $work_time,
        'establish_year' => $faker->year(),
        'account_id' => $shop_account->id,
        'created_at' => $created_at,
        'updated_at' => $created_at,
      ]);
    }
  }
}
