<?php

namespace Database\Seeders;

use App\Models\Account;
use App\Models\Doctor;
use App\Models\RatingDoctor;
use App\Models\RatingDoctorInteract;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class RatingDoctorSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    $faker = Faker::create();

    $customer_accounts = Account::whereHas('role', function ($query) {
      $query->where('role_name', 'ROLE_CUSTOMER');
    })->get(['id', 'created_at']);

    $doctor_ids = Doctor::pluck('id')->toArray();

    foreach ($doctor_ids as $doctor_id) {
      // Random số lượng rating cho mỗi sản phẩm
      $num_ratings_for_doctor = $faker->numberBetween(0, 10);

      $selected_customer_ids = [];

      for ($i = 0; $i < $num_ratings_for_doctor; $i++) {
        // 35% xác suất cho rating 5 sao
        // 45% xác suất cho rating 4 sao
        // 6% xác suất cho rating 3 sao
        // 6% xác suất cho rating 2 sao
        // 8% xác suất cho rating 1 sao
        $ratings = array_merge(
          array_fill(0, 35, 5),
          array_fill(0, 45, 4),
          array_fill(0, 6, 3),
          array_fill(0, 6, 2),
          array_fill(0, 8, 1)
        );
        $rating = $faker->randomElement($ratings);

        // Random một khách hàng chưa được chọn trước đó
        do {
          $random_customer = $faker->randomElement($customer_accounts);
        } while (in_array($random_customer->id, $selected_customer_ids));

        // Thêm khách hàng vào danh sách đã chọn
        $selected_customer_ids[] = $random_customer->id;

        // Random created_at trong khoảng từ 2 năm trước đến hiện tại
        $created_at = $faker->dateTimeBetween($random_customer->created_at, 'now');

        // Random reply và reply_date (nếu có reply)
        $reply = $faker->boolean(40) ? $faker->paragraph(6) : null;
        $reply_date = $reply ? $faker->dateTimeBetween($created_at, 'now') : null;

        $ratingDoctor = RatingDoctor::create([
          'rating' => $rating,
          'description' => $faker->paragraph(8),
          'customer_id' => $random_customer->id,
          'doctor_id' => $doctor_id,
          'reply' => $reply,
          'reply_date' => $reply_date,
          'created_at' => $created_at,
          'updated_at' => $created_at // giữ updated_at giống created_at cho consistency
        ]);

        // Random số lượng liked cho rating doctor này từ các customer khác nhau
        $num_likes = $faker->numberBetween(0, 5);
        $liked_customer_ids = $faker->randomElements($customer_accounts->pluck('id')->toArray(), $num_likes);

        foreach ($liked_customer_ids as $liked_customer_id) {
          RatingDoctorInteract::create([
            'rating_doctor_id' => $ratingDoctor->id,
            'account_id' => $liked_customer_id,
          ]);
        }

        // Nếu rating doctor có reply, quyết định liệu doctor có like hay không
        if ($ratingDoctor->reply) {
          $doctor_like = $faker->boolean(50);

          if ($doctor_like) {
            // Lấy account_id của doctor
            $doctor_account_id = Doctor::find($doctor_id)->account_id;

            RatingDoctorInteract::create([
              'rating_doctor_id' => $ratingDoctor->id,
              'account_id' => $doctor_account_id,
            ]);
          }
        }
      }

      $selected_customer_ids = [];
    }
  }
}
