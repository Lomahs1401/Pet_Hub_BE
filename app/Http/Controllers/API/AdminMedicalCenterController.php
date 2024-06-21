<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\MedicalCenter;
use App\Models\RatingMedicalCenter;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminMedicalCenterController extends Controller
{
  public function getMedicalCenters(Request $request)
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

    // Lấy danh sách các medical center có tài khoản đã được approved
    $medicalCentersQuery = MedicalCenter::whereHas('account', function ($query) use ($accountIds) {
      $query->whereIn('accounts.id', $accountIds);
    });

    // Thêm điều kiện tìm kiếm theo search_term nếu có
    if (!empty($searchTerm)) {
      $medicalCentersQuery = $medicalCentersQuery->where(function ($query) use ($searchTerm) {
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
    $totalMedicalCenters = $medicalCentersQuery->count();
    $totalPages = ceil($totalMedicalCenters / $numOfPage);
    $offset = ($pageNumber - 1) * $numOfPage;

    // Lấy dữ liệu các medical center theo phân trang
    $medicalCenters = $medicalCentersQuery->offset($offset)
      ->limit($numOfPage)
      ->get();

    // Định dạng dữ liệu medical center để trả về
    $formattedMedicalCenters = $medicalCenters->map(function ($medical_center) {
      $ratingData = $medical_center->calculateMedicalCenterRating();

      return [
        'id' => $medical_center->id,
        'account_id' => $medical_center->account->id,
        'name' => $medical_center->name,
        'email' => $medical_center->account->email,
        'username' => $medical_center->account->username,
        'avatar' => $medical_center->account->avatar,
        'address' => $medical_center->address,
        'phone' => $medical_center->phone,
        'work_time' => $medical_center->work_time,
        'establish_year' => $medical_center->establish_year,
        'rating' => $ratingData['average'],
        'rating_count' => $ratingData['count'],
        'created_at' => $medical_center->created_at,
        'updated_at' => $medical_center->updated_at,
      ];
    });

    // Trả về JSON response
    return response()->json([
      'message' => 'Medical center retrieved successfully!',
      'status' => 200,
      'page_number' => $pageNumber,
      'num_of_page' => $numOfPage,
      'total_pages' => $totalPages,
      'total_medical_centers' => $totalMedicalCenters,
      'data' => $formattedMedicalCenters,
    ]);
  }

  // Những account bị xoá trước khi được approve sẽ không hiển thị
  public function getMedicalCentersWaitingApproved(Request $request)
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

    // Lấy danh sách các tài khoản đang chờ approved
    $approvedAccounts = Account::where('is_approved', false)
      ->whereBetween('created_at', [$startDate, $endDate])
      ->get();

    $accountIds = $approvedAccounts->pluck('id');

    // Lấy danh sách các medical_center có tài khoản đang chờ approved
    $medicalCentersQuery = MedicalCenter::whereHas('account', function ($query) use ($accountIds) {
      $query->whereIn('accounts.id', $accountIds);
    });

    // Thêm điều kiện tìm kiếm theo search_term nếu có
    if (!empty($searchTerm)) {
      $medicalCentersQuery = $medicalCentersQuery->where(function ($query) use ($searchTerm) {
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
    $totalMedicalCenters = $medicalCentersQuery->count();
    $totalPages = ceil($totalMedicalCenters / $numOfPage);
    $offset = ($pageNumber - 1) * $numOfPage;

    // Lấy dữ liệu các medical_center theo phân trang
    $medical_centers = $medicalCentersQuery->offset($offset)
      ->limit($numOfPage)
      ->get();

    // Định dạng dữ liệu medical_center để trả về
    $formattedMedicalCenters = $medical_centers->map(function ($medical_center) {

      return [
        'id' => $medical_center->id,
        'account_id' => $medical_center->account->id,
        'name' => $medical_center->name,
        'email' => $medical_center->account->email,
        'username' => $medical_center->account->username,
        'avatar' => $medical_center->account->avatar,
        'address' => $medical_center->address,
        'phone' => $medical_center->phone,
        'work_time' => $medical_center->work_time,
        'establish_year' => $medical_center->establish_year,
        'created_at' => $medical_center->created_at,
        'updated_at' => $medical_center->updated_at,
      ];
    });

    // Trả về JSON response
    return response()->json([
      'message' => 'Medical centers waiting approve retrieved successfully!',
      'status' => 200,
      'page_number' => $pageNumber,
      'num_of_page' => $numOfPage,
      'total_pages' => $totalPages,
      'total_medical_centers' => $totalMedicalCenters,
      'data' => $formattedMedicalCenters,
    ]);
  }

  public function getMedicalCentersBlocked(Request $request)
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

    // Lấy danh sách các medical_center có tài khoản đã bị chặn
    $medicalCentersQuery = MedicalCenter::withTrashed()->whereHas('account', function ($query) use ($accountIds) {
      $query->whereIn('accounts.id', $accountIds);
    })->whereNotNull('deleted_at');

    // Thêm điều kiện tìm kiếm theo search_term nếu có
    if (!empty($searchTerm)) {
      $medicalCentersQuery = $medicalCentersQuery->where(function ($query) use ($searchTerm) {
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
    $totalMedicalCenters = $medicalCentersQuery->count();
    $totalPages = ceil($totalMedicalCenters / $numOfPage);
    $offset = ($pageNumber - 1) * $numOfPage;

    // Lấy dữ liệu các medical_center theo phân trang
    $medical_centers = $medicalCentersQuery->offset($offset)
      ->limit($numOfPage)
      ->get();

    // Định dạng dữ liệu medical_center để trả về
    $formattedMedicalCenters = $medical_centers->map(function ($medical_center) {
      return [
        'id' => $medical_center->id,
        'account_id' => $medical_center->account->id,
        'name' => $medical_center->name,
        'email' => $medical_center->account->email,
        'username' => $medical_center->account->username,
        'avatar' => $medical_center->account->avatar,
        'address' => $medical_center->address,
        'phone' => $medical_center->phone,
        'work_time' => $medical_center->work_time,
        'establish_year' => $medical_center->establish_year,
        'created_at' => $medical_center->created_at,
        'updated_at' => $medical_center->updated_at,
        'deleted_at' => $medical_center->deleted_at,
      ];
    });

    // Trả về JSON response
    return response()->json([
      'message' => 'Medical centers blocked retrieved successfully!',
      'status' => 200,
      'page_number' => $pageNumber,
      'num_of_page' => $numOfPage,
      'total_pages' => $totalPages,
      'total_medical_centers' => $totalMedicalCenters,
      'data' => $formattedMedicalCenters,
    ]);
  }

  public function getRatingByMedicalCenter(Request $request, $medical_center_id)
  {
    $user = auth()->user();
    $role_user = DB::table('roles')->where('id', '=', $user->role_id)->value('role_name');

    // Lấy thông tin medical_center của user nếu role là medical_center
    if ($role_user === 'ROLE_MEDICAL_CENTER') {
      $auth_medical_center_id = DB::table('medical_centers')->where('account_id', '=', $user->id)->value('id');
    }

    // Nếu role là medical_center và medical_center_id không phải medical_center của user, trả về lỗi
    if ($role_user === 'ROLE_MEDICAL_CENTER' && $auth_medical_center_id !== $medical_center_id) {
      return response()->json([
        'message' => 'You do not have access to this medical_center.',
      ], 403); // 403 Forbidden
    }

    // Lấy thông tin của medical_center
    $medical_center = MedicalCenter::withTrashed()->with('account')->find($medical_center_id);
    if (!$medical_center) {
      return response()->json([
        'message' => 'Medical center not found!',
        'status' => 404
      ], 404);
    }

    // Lấy rating của medical_center cụ thể
    $ratings = RatingMedicalCenter::with(['customer.account', 'customer.ratings', 'interacts.account'])
      ->where('medical_center_id', $medical_center_id)
      ->orderBy('created_at', 'desc')
      ->get()
      ->map(function ($rating) use ($user, $medical_center) {
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

        // Kiểm tra xem medical_center có nằm trong danh sách likes hay không
        $medical_center_liked = $rating->interacts->contains('account_id', $user->id);

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
          'medical_center_avatar' => $medical_center->account->avatar,
          'likes' => [
            'total_likes' => $rating->interacts->count(),
            'medical_center_liked' => $medical_center_liked,
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

  public function getMedicalCenterDetail($medical_center_id)
  {
    // Tìm kiếm medical center bằng id
    $medical_center = MedicalCenter::withTrashed()->find($medical_center_id);

    // Nếu không tìm thấy medical center, trả về lỗi
    if (!$medical_center) {
      return response()->json([
        'message' => 'Medical center not found',
        'status' => 404,
      ], 404);
    }

    $ratingData = $medical_center->calculateMedicalCenterRating();

    // Định dạng dữ liệu medical center để trả về
    $shopDetail = [
      'id' => $medical_center->id,
      'username' => $medical_center->account->username,
      'email' => $medical_center->account->email,
      'avatar' => $medical_center->account->avatar,
      'name' => $medical_center->name,
      'description' => $medical_center->description,
      'image' => $medical_center->image,
      'phone' => $medical_center->phone,
      'address' => $medical_center->address,
      'website' => $medical_center->website,
      'fanpage' => $medical_center->fanpage,
      'work_time' => $medical_center->work_time,
      'establish_year' => $medical_center->establish_year,
      'certificate' => $medical_center->certificate,
      'rating' => $ratingData['average'],
      'rating_count' => $ratingData['count'],
      'created_at' => $medical_center->created_at,
      'updated_at' => $medical_center->updated_at,
    ];

    // Trả về JSON response
    return response()->json([
      'message' => 'Medical center detail retrieved successfully!',
      'status' => 200,
      'data' => $shopDetail,
    ]);
  }

  // Phê duyệt medical center
  public function approveMedicalCenter($account_id)
  {
    // Tìm tài khoản với account_id
    $account = Account::find($account_id);

    if (!$account) {
      return response()->json([
        'message' => 'Account not found!',
        'status' => 404
      ], 404);
    }

    // Kiểm tra xem tài khoản có role_id là ROLE_MEDICAL_CENTER hay không
    $roleName = DB::table('roles')->where('id', $account->role_id)->value('role_name');

    if ($roleName !== 'ROLE_MEDICAL_CENTER') {
      return response()->json([
        'message' => 'Account is not a medical center!',
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

  // Chặn medical center
  public function blockMedicalCenter($account_id)
  {
    // Tìm tài khoản với account_id
    $account = Account::find($account_id);

    if (!$account) {
      return response()->json([
        'message' => 'Account not found!',
        'status' => 404
      ], 404);
    }

    // Kiểm tra xem tài khoản có role_id là ROLE_MEDICAL_CENTER hay không
    $roleName = DB::table('roles')->where('id', $account->role_id)->value('role_name');

    if ($roleName !== 'ROLE_MEDICAL_CENTER') {
      return response()->json([
        'message' => 'Account is not a medical center!',
        'status' => 400
      ], 400);
    }

    // Chặn tài khoản
    $account->enabled = false;
    $account->save();

    // Cập nhật medical center tương ứng
    $medical_center = MedicalCenter::where('account_id', $account_id)->first();

    if ($medical_center) {
      $medical_center->deleted_at = now();
      $medical_center->save();
    }

    return response()->json([
      'message' => 'Account blocked successfully!',
      'status' => 200
    ]);
  }

  public function restoreMedicalCenter($account_id)
  {
    // Tìm tài khoản với account_id
    $account = Account::find($account_id);

    if (!$account) {
      return response()->json([
        'message' => 'Account not found!',
        'status' => 404
      ], 404);
    }

    // Kiểm tra xem tài khoản có role_id là ROLE_MEDICAL_CENTER hay không
    $roleName = DB::table('roles')->where('id', $account->role_id)->value('role_name');

    if ($roleName !== 'ROLE_MEDICAL_CENTER') {
      return response()->json([
        'message' => 'Account is not a medical center!',
        'status' => 400
      ], 400);
    }

    // Nếu is_approved = false => tk này đc xoá trước khi được approve
    if ($account->is_approved) {
      // Chặn tài khoản
      $account->enabled = true;
      $account->save();
    }

    // Cập nhật medical center tương ứng
    $medical_center = MedicalCenter::withTrashed()->where('account_id', $account_id)->first();

    if ($medical_center) {
      $medical_center->restore();
    }

    return response()->json([
      'message' => 'Account restored successfully!',
      'status' => 200
    ]);
  }
}
