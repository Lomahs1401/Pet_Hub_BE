<?php

namespace Database\Seeders;

use App\Models\Account;
use App\Models\Product;
use App\Models\RatingProduct;
use App\Models\RatingProductInteract;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class RatingProductSeeder extends Seeder
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

		$product_ids = Product::pluck('id')->toArray();

		foreach ($product_ids as $product_id) {
			// Random số lượng rating cho mỗi sản phẩm
			$num_ratings_for_product = $faker->numberBetween(0, 10);

			$selected_customer_ids = [];

			for ($i = 0; $i < $num_ratings_for_product; $i++) {
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

				// Random created_at trong khoảng từ 2 năm trước đến hiện tại
				$created_at = $faker->dateTimeBetween('-2 years', 'now');

				// Random reply và reply_date (nếu có reply)
				$reply = $faker->boolean(40) ? $faker->paragraph(6) : null;
				$reply_date = $reply ? $faker->dateTimeBetween($created_at, 'now') : null;

				$ratingProduct = RatingProduct::create([
					'rating' => $rating,
					'description' => $faker->paragraph(8),
					'customer_id' => $random_customer_id,
					'product_id' => $product_id,
					'reply' => $reply,
					'reply_date' => $reply_date,
					'created_at' => $created_at,
					'updated_at' => $created_at // giữ updated_at giống created_at cho consistency
				]);

				// Random số lượng liked cho rating product này từ các customer khác nhau
				$num_likes = $faker->numberBetween(0, 5);
				$liked_customer_ids = $faker->randomElements($customer_account_ids, $num_likes);

				foreach ($liked_customer_ids as $liked_customer_id) {
					RatingProductInteract::create([
						'rating_product_id' => $ratingProduct->id,
						'account_id' => $liked_customer_id,
					]);
				}

				// Nếu rating product có reply, quyết định liệu shop có like hay không
				if ($ratingProduct->reply) {
					$shop_like = $faker->boolean(50);

					if ($shop_like) {
						// Lấy account_id của shop bán sản phẩm hiện tại
						$shop_account_id = Product::find($product_id)->shop->account_id;

						RatingProductInteract::create([
							'rating_product_id' => $ratingProduct->id,
							'account_id' => $shop_account_id,
						]);
					}
				}
			}

			$selected_customer_ids = [];
		}
	}
}
