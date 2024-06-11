<?php

namespace Database\Seeders;

use App\Models\Account;
use App\Models\RatingShop;
use App\Models\RatingShopInteract;
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

		$customer_accounts = Account::whereHas('role', function ($query) {
			$query->where('role_name', 'ROLE_CUSTOMER');
		})->get(['id', 'created_at']);

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
					$random_customer = $faker->randomElement($customer_accounts);
				} while (in_array($random_customer->id, $selected_customer_ids));

				// Thêm khách hàng vào danh sách đã chọn
				$selected_customer_ids[] = $random_customer->id;

        // Random created_at trong khoảng từ 2 năm trước đến hiện tại
				$created_at = $faker->dateTimeBetween($random_customer->created_at, 'now');

        // Random reply và reply_date (nếu có reply)
				$reply = $faker->boolean(40) ? $faker->paragraph(6) : null;
				$reply_date = $reply ? $faker->dateTimeBetween($created_at, 'now') : null;

				$ratingShop = RatingShop::create([
					'rating' => $rating,
					'description' => $faker->paragraph(8),
					'customer_id' => $random_customer->id,
					'shop_id' => $shop_id,
          'reply' => $reply,
					'reply_date' => $reply_date,
					'created_at' => $created_at,
					'updated_at' => $created_at // giữ updated_at giống created_at cho consistency
				]);

        // Random số lượng liked cho rating shop này từ các customer khác nhau
				$num_likes = $faker->numberBetween(0, 5);
				$liked_customer_ids = $faker->randomElements($customer_accounts->pluck('id')->toArray(), $num_likes);

				foreach ($liked_customer_ids as $liked_customer_id) {
					RatingShopInteract::create([
						'rating_shop_id' => $ratingShop->id,
						'account_id' => $liked_customer_id,
					]);
				}

        // Nếu rating shop có reply, quyết định liệu shop có like hay không
				if ($ratingShop->reply) {
					$shop_like = $faker->boolean(50);

					if ($shop_like) {
						// Lấy account_id của shop bán sản phẩm hiện tại
						$shop_account_id = Shop::find($shop_id)->account_id;

						RatingShopInteract::create([
							'rating_shop_id' => $ratingShop->id,
							'account_id' => $shop_account_id,
						]);
					}
				}
			}

			$selected_customer_ids = [];
		}
	}
}
