<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\RatingShop;
use App\Models\Shop;
use App\Models\SubOrder;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
  public function getShops(Request $request)
  {
    // Lấy tham số start_date và end_date từ request, nếu không có thì sử dụng ngày hiện tại
    $startDate = $request->query('start_date', Carbon::today()->toDateString());
    $endDate = $request->query('end_date', Carbon::today()->toDateString());

    // Chuyển đổi start_date và end_date thành đối tượng Carbon
    $startDate = Carbon::parse($startDate);
    $endDate = Carbon::parse($endDate);

    // Kiểm tra sự chênh lệch giữa start_date và end_date
    if ($startDate->greaterThan($endDate)) {
      return response()->json([
        'message' => 'Start date cannot be greater than end date',
        'status' => 400,
      ], 400);
    }

    // Lấy tham số search_term từ request
    $searchTerm = $request->query('search_term', '');

    // Lấy danh sách các tài khoản đã được approved
    $approvedAccounts = Account::where('is_approved', true)
      ->whereBetween('created_at', [$startDate, $endDate])
      ->get();

    $accountIds = $approvedAccounts->pluck('id');

    // Lấy danh sách các shop có tài khoản đã được approved
    $shopsQuery = Shop::whereHas('account', function ($query) use ($accountIds) {
      $query->whereIn('accounts.id', $accountIds);
    });

    // Thêm điều kiện tìm kiếm theo search_term nếu có
    if (!empty($searchTerm)) {
      $shopsQuery = $shopsQuery->where(function ($query) use ($searchTerm) {
        $query->where('name', 'like', '%' . $searchTerm . '%')
          ->orWhere('phone', 'like', '%' . $searchTerm . '%')
          ->orWhereHas('account', function ($query) use ($searchTerm) {
            $query->where('email', 'like', '%' . $searchTerm . '%');
          });
      });
    }
    // Thực hiện phân trang
    $pageNumber = intval($request->query('page_number', 1));
    $numOfPage = intval($request->query('num_of_page', 10));
    $totalShops = $shopsQuery->count();
    $totalPages = ceil($totalShops / $numOfPage);
    $offset = ($pageNumber - 1) * $numOfPage;

    // Lấy dữ liệu các shop theo phân trang
    $shops = $shopsQuery->offset($offset)
      ->limit($numOfPage)
      ->get();

    // Định dạng dữ liệu shop để trả về
    $formattedShops = $shops->map(function ($shop) {
      $ratingData = $shop->calculateShopRating();

      return [
        'id' => $shop->id,
        'account_id' => $shop->account->id,
        'name' => $shop->name,
        'email' => $shop->account->email,
        'username' => $shop->account->username,
        'avatar' => $shop->account->avatar,
        'address' => $shop->address,
        'phone' => $shop->phone,
        'work_time' => $shop->work_time,
        'establish_year' => $shop->establish_year,
        'rating' => $ratingData['average'],
        'rating_count' => $ratingData['count'],
        'created_at' => $shop->created_at,
        'updated_at' => $shop->updated_at,
      ];
    });

    // Trả về JSON response
    return response()->json([
      'message' => 'Shops retrieved successfully!',
      'status' => 200,
      'page_number' => $pageNumber,
      'num_of_page' => $numOfPage,
      'total_pages' => $totalPages,
      'total_shops' => $totalShops,
      'data' => $formattedShops,
    ]);
  }

  public function getShopsNotApproved(Request $request)
  {
    // Lấy tham số start_date và end_date từ request, nếu không có thì sử dụng ngày hiện tại
    $startDate = $request->query('start_date', Carbon::today()->toDateString());
    $endDate = $request->query('end_date', Carbon::today()->toDateString());

    // Chuyển đổi start_date và end_date thành đối tượng Carbon
    $startDate = Carbon::parse($startDate);
    $endDate = Carbon::parse($endDate);

    // Kiểm tra sự chênh lệch giữa start_date và end_date
    if ($startDate->greaterThan($endDate)) {
      return response()->json([
        'message' => 'Start date cannot be greater than end date',
        'status' => 400,
      ], 400);
    }

    // Lấy tham số search_term từ request
    $searchTerm = $request->query('search_term', '');

    // Lấy danh sách các tài khoản đã được approved
    $approvedAccounts = Account::where('is_approved', false)
      ->whereBetween('created_at', [$startDate, $endDate])
      ->get();

    $accountIds = $approvedAccounts->pluck('id');

    // Lấy danh sách các shop có tài khoản đã được approved
    $shopsQuery = Shop::whereHas('account', function ($query) use ($accountIds) {
      $query->whereIn('accounts.id', $accountIds);
    });

    // Thêm điều kiện tìm kiếm theo search_term nếu có
    if (!empty($searchTerm)) {
      $shopsQuery = $shopsQuery->where(function ($query) use ($searchTerm) {
        $query->where('name', 'like', '%' . $searchTerm . '%')
          ->orWhere('phone', 'like', '%' . $searchTerm . '%')
          ->orWhereHas('account', function ($query) use ($searchTerm) {
            $query->where('email', 'like', '%' . $searchTerm . '%');
          });
      });
    }
    // Thực hiện phân trang
    $pageNumber = intval($request->query('page_number', 1));
    $numOfPage = intval($request->query('num_of_page', 10));
    $totalShops = $shopsQuery->count();
    $totalPages = ceil($totalShops / $numOfPage);
    $offset = ($pageNumber - 1) * $numOfPage;

    // Lấy dữ liệu các shop theo phân trang
    $shops = $shopsQuery->offset($offset)
      ->limit($numOfPage)
      ->get();

    // Định dạng dữ liệu shop để trả về
    $formattedShops = $shops->map(function ($shop) {

      return [
        'id' => $shop->id,
        'account_id' => $shop->account->id,
        'name' => $shop->name,
        'email' => $shop->account->email,
        'username' => $shop->account->username,
        'avatar' => $shop->account->avatar,
        'address' => $shop->address,
        'phone' => $shop->phone,
        'work_time' => $shop->work_time,
        'establish_year' => $shop->establish_year,
        'created_at' => $shop->created_at,
        'updated_at' => $shop->updated_at,
      ];
    });

    // Trả về JSON response
    return response()->json([
      'message' => 'Shops not approved retrieved successfully!',
      'status' => 200,
      'page_number' => $pageNumber,
      'num_of_page' => $numOfPage,
      'total_pages' => $totalPages,
      'total_shops' => $totalShops,
      'data' => $formattedShops,
    ]);
  }

  public function getShopsBlocked(Request $request)
  {
    // Lấy tham số start_date và end_date từ request, nếu không có thì sử dụng ngày hiện tại
    $startDate = $request->query('start_date', '2000-01-01'); // Sử dụng một ngày rất xa trong quá khứ
    $endDate = $request->query('end_date', Carbon::tomorrow()->toDateString()); // Sử dụng ngày mai để bao quát cả ngày hiện tại

    // Chuyển đổi start_date và end_date thành đối tượng Carbon
    $startDate = Carbon::parse($startDate)->startOfDay();
    $endDate = Carbon::parse($endDate)->endOfDay();

    // Kiểm tra sự chênh lệch giữa start_date và end_date
    if ($startDate->greaterThan($endDate)) {
      return response()->json([
        'message' => 'Start date cannot be greater than end date',
        'status' => 400,
      ], 400);
    }

    // Lấy tham số search_term từ request
    $searchTerm = $request->query('search_term', '');

    // Lấy danh sách các tài khoản blocked
    $blockedAccounts = Account::where('enabled', false)
      ->whereBetween('created_at', [$startDate, $endDate])
      ->get();

    $accountIds = $blockedAccounts->pluck('id');

    // Lấy danh sách các shop có tài khoản đã bị chặn
    $shopsQuery = Shop::withTrashed()->whereHas('account', function ($query) use ($accountIds) {
      $query->whereIn('accounts.id', $accountIds);
    });

    // Thêm điều kiện tìm kiếm theo search_term nếu có
    if (!empty($searchTerm)) {
      $shopsQuery = $shopsQuery->where(function ($query) use ($searchTerm) {
        $query->where('name', 'like', '%' . $searchTerm . '%')
          ->orWhere('phone', 'like', '%' . $searchTerm . '%')
          ->orWhereHas('account', function ($query) use ($searchTerm) {
            $query->where('email', 'like', '%' . $searchTerm . '%');
          });
      });
    }

    // Thực hiện phân trang
    $pageNumber = intval($request->query('page_number', 1));
    $numOfPage = intval($request->query('num_of_page', 10));
    $totalShops = $shopsQuery->count();
    $totalPages = ceil($totalShops / $numOfPage);
    $offset = ($pageNumber - 1) * $numOfPage;

    // Lấy dữ liệu các shop theo phân trang
    $shops = $shopsQuery->offset($offset)
      ->limit($numOfPage)
      ->get();

    // Định dạng dữ liệu shop để trả về
    $formattedShops = $shops->map(function ($shop) {
      return [
        'id' => $shop->id,
        'account_id' => $shop->account->id,
        'name' => $shop->name,
        'email' => $shop->account->email,
        'username' => $shop->account->username,
        'avatar' => $shop->account->avatar,
        'address' => $shop->address,
        'phone' => $shop->phone,
        'work_time' => $shop->work_time,
        'establish_year' => $shop->establish_year,
        'created_at' => $shop->created_at,
        'updated_at' => $shop->updated_at,
        'deleted_at' => $shop->deleted_at,
      ];
    });

    // Trả về JSON response
    return response()->json([
      'message' => 'Shops blocked retrieved successfully!',
      'status' => 200,
      'page_number' => $pageNumber,
      'num_of_page' => $numOfPage,
      'total_pages' => $totalPages,
      'total_shops' => $totalShops,
      'data' => $formattedShops,
    ]);
  }

  public function getShopDetail($shop_id)
  {
    // Tìm kiếm shop bằng id
    $shop = Shop::withTrashed()->find($shop_id);

    // Nếu không tìm thấy shop, trả về lỗi
    if (!$shop) {
      return response()->json([
        'message' => 'Shop not found',
        'status' => 404,
      ], 404);
    }

    $ratingData = $shop->calculateShopRating();

    // Định dạng dữ liệu shop để trả về
    $shopDetail = [
      'id' => $shop->id,
      'username' => $shop->account->username,
      'email' => $shop->account->email,
      'avatar' => $shop->account->avatar,
      'name' => $shop->name,
      'description' => $shop->description,
      'image' => $shop->image,
      'phone' => $shop->phone,
      'address' => $shop->address,
      'website' => $shop->website,
      'fanpage' => $shop->fanpage,
      'work_time' => $shop->work_time,
      'establish_year' => $shop->establish_year,
      'certificate' => $shop->certificate,
      'rating' => $ratingData['average'],
      'rating_count' => $ratingData['count'],
      'created_at' => $shop->created_at,
      'updated_at' => $shop->updated_at,
    ];

    // Trả về JSON response
    return response()->json([
      'message' => 'Shop detail retrieved successfully!',
      'status' => 200,
      'data' => $shopDetail,
    ]);
  }

  public function getRevenueByShop(Request $request, $shop_id)
  {
    $year = $request->get('year', Carbon::now()->year); // Lấy năm từ request hoặc sử dụng năm hiện tại

    // Khởi tạo mảng để chứa dữ liệu theo tháng
    $salesData = array_fill(0, 12, ['name' => '', 'revenue' => 0]);

    // Tên các tháng
    $monthNames = [
      'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun',
      'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'
    ];

    foreach ($monthNames as $index => $month) {
      $monthStart = Carbon::create($year, $index + 1, 1)->startOfMonth();
      $monthEnd = $monthStart->copy()->endOfMonth();

      // Lấy tổng doanh thu từ sub_orders
      $revenueData = SubOrder::where('shop_id', $shop_id)
        ->whereBetween('created_at', [$monthStart, $monthEnd])
        ->sum('sub_total_prices');

      $salesData[$index] = [
        'name' => $month,
        'revenue' => (float)$revenueData, // Ép kiểu về số thực
      ];
    }

    return response()->json([
      'message' => 'Revenue retrieved successfully!',
      'status' => 200,
      'data' => $salesData
    ]);
  }

  public function getRatingByShop(Request $request, $shop_id)
  {
    $user = auth()->user();
    $role_user = DB::table('roles')->where('id', '=', $user->role_id)->value('role_name');

    // Lấy thông tin shop của user nếu role là shop
    if ($role_user === 'ROLE_SHOP') {
      $auth_shop_id = DB::table('shops')->where('account_id', '=', $user->id)->value('id');
    }

    // Nếu role là shop và shop_id không phải shop của user, trả về lỗi
    if ($role_user === 'ROLE_SHOP' && $auth_shop_id !== $shop_id) {
      return response()->json([
        'message' => 'You do not have access to this shop.',
      ], 403); // 403 Forbidden
    }

    // Lấy thông tin của shop
    $shop = Shop::withTrashed()->with('account')->find($shop_id);
    if (!$shop) {
      return response()->json([
        'message' => 'Shop not found!',
        'status' => 404
      ], 404);
    }

    // Lấy rating của shop cụ thể
    $ratings = RatingShop::with(['customer.account', 'customer.ratings', 'interacts.account'])
      ->where('shop_id', $shop_id)
      ->orderBy('created_at', 'desc')
      ->get()
      ->map(function ($rating) use ($user, $shop) {
        $customer = $rating->customer;
        $account = $customer->account;

        // Lấy thông tin về lượt like
        $likes = $rating->interacts->map(function ($interact) {
          return [
            'account_id' => $interact->account_id,
            'username' => $interact->account->username,
            'avatar' => $interact->account->avatar,
          ];
        });

        // Kiểm tra xem shop có nằm trong danh sách likes hay không
        $shop_liked = $rating->interacts->contains('account_id', $user->id);

        return [
          'rating_id' => $rating->id,
          'rating_score' => $rating->rating,
          'description' => $rating->description,
          'reply' => $rating->reply,
          'reply_date' => $rating->reply_date,
          'rating_date' => $rating->created_at,
          'customer_username' => $account->username,
          'customer_avatar' => $account->avatar,
          'account_creation_date' => $account->created_at,
          'customer_rating_count' => $customer->ratings->count(),
          'customer_ranking_point' => $customer->ranking_point ?? 0,
          'shop_avatar' => $shop->account->avatar, // Thêm trường shop_avatar vào từng đánh giá
          'likes' => [
            'total_likes' => $rating->interacts->count(),
            'shop_liked' => $shop_liked,
            'details' => $likes,
          ]
        ];
      });

    // Tính tổng số ratings và tổng số trang
    $totalRatings = $ratings->count();
    $num_of_page = intval($request->query('num_of_page', 5));
    $total_pages = ceil($totalRatings / $num_of_page);
    $page_number = intval($request->query('page_number', 1));

    // Phân trang dữ liệu ratings
    $paginatedRatings = $ratings->forPage($page_number, $num_of_page)->values();

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

  // Phê duyệt shop
  public function approvedShop($account_id)
  {
    // Tìm tài khoản với account_id
    $account = Account::find($account_id);

    if (!$account) {
      return response()->json([
        'message' => 'Account not found!',
        'status' => 404
      ], 404);
    }

    // Kiểm tra xem tài khoản có role_id là ROLE_SHOP hay không
    $roleName = DB::table('roles')->where('id', $account->role_id)->value('role_name');

    if ($roleName !== 'ROLE_SHOP') {
      return response()->json([
        'message' => 'Account is not a shop!',
        'status' => 400
      ], 400);
    }

    // Phê duyệt tài khoản
    $account->is_approved = true;
    $account->enabled = true;
    $account->save();

    return response()->json([
      'message' => 'Account approved successfully!',
      'status' => 200
    ]);
  }

  // Chặn shop
  public function blockShop(Request $request, $account_id)
  {
    // Tìm tài khoản với account_id
    $account = Account::find($account_id);

    if (!$account) {
      return response()->json([
        'message' => 'Account not found!',
        'status' => 404
      ], 404);
    }

    // Kiểm tra xem tài khoản có role_id là ROLE_SHOP hay không
    $roleName = DB::table('roles')->where('id', $account->role_id)->value('role_name');

    if ($roleName !== 'ROLE_SHOP') {
      return response()->json([
        'message' => 'Account is not a shop!',
        'status' => 400
      ], 400);
    }

    // Chặn tài khoản
    $account->enabled = false;
    $account->save();

    // Cập nhật shop tương ứng
    $shop = Shop::where('account_id', $account_id)->first();

    if ($shop) {
      $shop->deleted_at = now();
      $shop->save();
    }

    return response()->json([
      'message' => 'Account blocked successfully!',
      'status' => 200
    ]);
  }

  // Chặn shop
  public function restoreShop(Request $request, $account_id)
  {
    // Tìm tài khoản với account_id
    $account = Account::find($account_id);

    if (!$account) {
      return response()->json([
        'message' => 'Account not found!',
        'status' => 404
      ], 404);
    }

    // Kiểm tra xem tài khoản có role_id là ROLE_SHOP hay không
    $roleName = DB::table('roles')->where('id', $account->role_id)->value('role_name');

    if ($roleName !== 'ROLE_SHOP') {
      return response()->json([
        'message' => 'Account is not a shop!',
        'status' => 400
      ], 400);
    }

    // Chặn tài khoản
    $account->enabled = true;
    $account->save();

    // Cập nhật shop tương ứng
    $shop = Shop::withTrashed()->where('account_id', $account_id)->first();

    if ($shop) {
      $shop->restore();
    }

    return response()->json([
      'message' => 'Account restored successfully!',
      'status' => 200
    ]);
  }
}
