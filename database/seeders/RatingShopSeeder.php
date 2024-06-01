<?php

namespace Database\Seeders;

use App\Models\Account;
use App\Models\RatingShop;
use App\Models\Shop;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class RatingShopSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		$faker = Faker::create();

		$customer_account_ids = Account::whereHas('role', function ($query) {
			$query->where('role_name', 'ROLE_CUSTOMER');
		})->pluck('id')->toArray();

		$shop_ids = Shop::pluck('id')->toArray();

		foreach ($shop_ids as $shop_id) {
			$num_ratings_for_shop = $faker->numberBetween(0, 10);

			$selected_customer_ids = [];

			for ($i = 0; $i < $num_ratings_for_shop; $i++) {
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
					$random_customer_id = $faker->randomElement($customer_account_ids);
				} while (in_array($random_customer_id, $selected_customer_ids));

				// Thêm khách hàng vào danh sách đã chọn
				$selected_customer_ids[] = $random_customer_id;

				RatingShop::create([
					'rating' => $rating,
					'description' => $faker->paragraph(8),
					'customer_id' => $random_customer_id,
					'shop_id' => $shop_id,
				]);
			}

			$selected_customer_ids = [];
		}
	}
}
