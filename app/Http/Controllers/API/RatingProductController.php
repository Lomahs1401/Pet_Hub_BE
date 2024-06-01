<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\RatingProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RatingProductController extends Controller
{
	public function getCustomerRatingsOfProductId(Request $request, $product_id)
	{
		// Lấy thông tin shop hiện tại từ tài khoản đăng nhập
		$user = auth()->user();
		$shopId = DB::table('shops')->where('account_id', '=', $user->id)->value('id');

		// Lấy các sản phẩm thuộc shop hiện tại
		$shopProductIds = DB::table('products')->where('shop_id', $shopId)->pluck('id')->toArray();

		// Lấy rating của sản phẩm cụ thể
		$ratings = RatingProduct::with(['customer.account', 'customer.ratings'])
			->where('product_id', $product_id)
			->get()
			->map(function ($rating) use ($shopProductIds) {
				$customer = $rating->customer;
				$account = $customer->account;

				// Lọc rating của customer đối với các sản phẩm thuộc shop hiện tại
				$customerShopRatings = $customer->ratings->filter(function ($customerRating) use ($shopProductIds) {
					return in_array($customerRating->product_id, $shopProductIds);
				});

				return [
					'rating_score' => $rating->rating,
					'description' => $rating->description,
					'rating_date' => $rating->created_at,
					'customer_username' => $account->username,
					'customer_avatar' => $account->avatar,
					'account_creation_date' => $account->created_at,
					'customer_rating_count' => $customerShopRatings->count(),
					// Assuming 'comment_interactions' is a method or accessor in Customer model for interaction count
					'customer_comment_interactions' => $customer->comment_interactions ?? 0,
				];
			});

		// Tính tổng số ratings và tổng số trang
		$totalRatings = $ratings->count();
		$num_of_page = intval($request->query('num_of_page', 5));
		$total_pages = ceil($totalRatings / $num_of_page);
		$page_number = intval($request->query('page_number', 1));
		$offset = ($page_number - 1) * $num_of_page;

		// Phân trang dữ liệu ratings
		$paginatedRatings = $ratings->slice($offset, $num_of_page);

		return response()->json([
			'message' => 'Query successfully!',
			'status' => 200,
			'page_number' => $page_number,
			'num_of_page' => $num_of_page,
			'total_pages' => $total_pages,
			'total_ratings' => $totalRatings,
			'data' => $paginatedRatings,
		]);
	}

	public function getDetailRating($product_id)
    {
		$product = Product::find($product_id);
		if (!$product) {
			return response()->json([
				'message' => 'Product not found!',
				'status' => 404
			], 404);
		}

        // Lấy số lượng rating cho từng mức điểm từ 1 đến 5
        $ratings = RatingProduct::select('rating', DB::raw('count(*) as count'))
            ->where('product_id', $product_id)
            ->groupBy('rating')
            ->orderBy('rating', 'desc')
            ->get();

        // Tạo một mảng để lưu số lượng các rating từ 1 đến 5 sao
        $ratingCounts = [
            'five_star' => 0,
            'four_star' => 0,
            'three_star' => 0,
            'two_star' => 0,
            'one_star' => 0,
        ];

        // Cập nhật số lượng rating vào mảng
        foreach ($ratings as $rating) {
            switch ($rating->rating) {
				case 5:
					$ratingCounts['five_star'] = $rating->count;
					break;
				case 4:
					$ratingCounts['four_star'] = $rating->count;
					break;
				case 3:
					$ratingCounts['three_star'] = $rating->count;
					break;
				case 2:
					$ratingCounts['two_star'] = $rating->count;
					break;
				case 1:
					$ratingCounts['one_star'] = $rating->count;
					break;
			}
        }

        // Trả về kết quả dưới dạng JSON
        return response()->json([
            'message' => 'Query successfully!',
            'status' => 200,
            'data' => $ratingCounts
        ]);
    }
}
