<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\AidCenter;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminAidCenterController extends Controller
{
  public function getAidCenters(Request $request)
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

    // Lấy danh sách các aid center có tài khoản đã được approved và chưa bị chặn (deleted_at = null)
    $aidCentersQuery = AidCenter::whereHas('account', function ($query) use ($accountIds) {
      $query->whereIn('accounts.id', $accountIds);
    });

    // Thêm điều kiện tìm kiếm theo search_term nếu có
    if (!empty($searchTerm)) {
      $aidCentersQuery = $aidCentersQuery->where(function ($query) use ($searchTerm) {
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
    $totalAidCenters = $aidCentersQuery->count();
    $totalPages = ceil($totalAidCenters / $numOfPage);
    $offset = ($pageNumber - 1) * $numOfPage;

    // Lấy dữ liệu các aid center theo phân trang
    $aidCenters = $aidCentersQuery->offset($offset)
      ->limit($numOfPage)
      ->get();

    // Định dạng dữ liệu medical center để trả về
    $formattedAidCenters = $aidCenters->map(function ($aid_center) {

      return [
        'id' => $aid_center->id,
        'account_id' => $aid_center->account->id,
        'name' => $aid_center->name,
        'email' => $aid_center->account->email,
        'username' => $aid_center->account->username,
        'avatar' => $aid_center->account->avatar,
        'address' => $aid_center->address,
        'phone' => $aid_center->phone,
        'work_time' => $aid_center->work_time,
        'establish_year' => $aid_center->establish_year,
        'created_at' => $aid_center->created_at,
        'updated_at' => $aid_center->updated_at,
      ];
    });

    // Trả về JSON response
    return response()->json([
      'message' => 'Aid center retrieved successfully!',
      'status' => 200,
      'page_number' => $pageNumber,
      'num_of_page' => $numOfPage,
      'total_pages' => $totalPages,
      'total_aid_centers' => $totalAidCenters,
      'data' => $formattedAidCenters,
    ]);
  }

  // Những account bị xoá trước khi được approve sẽ không hiển thị
  public function getAidCentersWaitingApproved(Request $request)
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

    // Lấy danh sách các aid_center có tài khoản đang chờ approved
    $aidCentersQuery = AidCenter::whereHas('account', function ($query) use ($accountIds) {
      $query->whereIn('accounts.id', $accountIds);
    });

    // Thêm điều kiện tìm kiếm theo search_term nếu có
    if (!empty($searchTerm)) {
      $aidCentersQuery = $aidCentersQuery->where(function ($query) use ($searchTerm) {
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
    $totalAidCenters = $aidCentersQuery->count();
    $totalPages = ceil($totalAidCenters / $numOfPage);
    $offset = ($pageNumber - 1) * $numOfPage;

    // Lấy dữ liệu các aid_center theo phân trang
    $aid_centers = $aidCentersQuery->offset($offset)
      ->limit($numOfPage)
      ->get();

    // Định dạng dữ liệu aid_center để trả về
    $formattedAidCenters = $aid_centers->map(function ($aid_center) {

      return [
        'id' => $aid_center->id,
        'account_id' => $aid_center->account->id,
        'name' => $aid_center->name,
        'email' => $aid_center->account->email,
        'username' => $aid_center->account->username,
        'avatar' => $aid_center->account->avatar,
        'address' => $aid_center->address,
        'phone' => $aid_center->phone,
        'work_time' => $aid_center->work_time,
        'establish_year' => $aid_center->establish_year,
        'created_at' => $aid_center->created_at,
        'updated_at' => $aid_center->updated_at,
      ];
    });

    // Trả về JSON response
    return response()->json([
      'message' => 'Aid centers waiting approve retrieved successfully!',
      'status' => 200,
      'page_number' => $pageNumber,
      'num_of_page' => $numOfPage,
      'total_pages' => $totalPages,
      'total_aid_centers' => $totalAidCenters,
      'data' => $formattedAidCenters,
    ]);
  }

  public function getAidCentersBlocked(Request $request)
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

    // Lấy danh sách các aid_center có tài khoản đã bị chặn
    $aidCentersQuery = AidCenter::withTrashed()->whereHas('account', function ($query) use ($accountIds) {
      $query->whereIn('accounts.id', $accountIds);
    })->whereNotNull('deleted_at');

    // Thêm điều kiện tìm kiếm theo search_term nếu có
    if (!empty($searchTerm)) {
      $aidCentersQuery = $aidCentersQuery->where(function ($query) use ($searchTerm) {
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
    $totalAidCenters = $aidCentersQuery->count();
    $totalPages = ceil($totalAidCenters / $numOfPage);
    $offset = ($pageNumber - 1) * $numOfPage;

    // Lấy dữ liệu các aid_center theo phân trang
    $aid_centers = $aidCentersQuery->offset($offset)
      ->limit($numOfPage)
      ->get();

    // Định dạng dữ liệu aid_center để trả về
    $formattedAidCenters = $aid_centers->map(function ($aid_center) {
      return [
        'id' => $aid_center->id,
        'account_id' => $aid_center->account->id,
        'name' => $aid_center->name,
        'email' => $aid_center->account->email,
        'username' => $aid_center->account->username,
        'avatar' => $aid_center->account->avatar,
        'address' => $aid_center->address,
        'phone' => $aid_center->phone,
        'work_time' => $aid_center->work_time,
        'establish_year' => $aid_center->establish_year,
        'created_at' => $aid_center->created_at,
        'updated_at' => $aid_center->updated_at,
        'deleted_at' => $aid_center->deleted_at,
      ];
    });

    // Trả về JSON response
    return response()->json([
      'message' => 'Aid centers blocked retrieved successfully!',
      'status' => 200,
      'page_number' => $pageNumber,
      'num_of_page' => $numOfPage,
      'total_pages' => $totalPages,
      'total_aid_centers' => $totalAidCenters,
      'data' => $formattedAidCenters,
    ]);
  }

  public function getAidCenterDetail($aid_center_id)
  {
    // Tìm kiếm aid center bằng id
    $aid_center = AidCenter::withTrashed()->find($aid_center_id);

    // Nếu không tìm thấy aid center, trả về lỗi
    if (!$aid_center) {
      return response()->json([
        'message' => 'Aid center not found',
        'status' => 404,
      ], 404);
    }

    // Định dạng dữ liệu medical center để trả về
    $shopDetail = [
      'id' => $aid_center->id,
      'username' => $aid_center->account->username,
      'email' => $aid_center->account->email,
      'avatar' => $aid_center->account->avatar,
      'name' => $aid_center->name,
      'description' => $aid_center->description,
      'image' => $aid_center->image,
      'phone' => $aid_center->phone,
      'address' => $aid_center->address,
      'website' => $aid_center->website,
      'fanpage' => $aid_center->fanpage,
      'work_time' => $aid_center->work_time,
      'establish_year' => $aid_center->establish_year,
      'certificate' => $aid_center->certificate,
      'created_at' => $aid_center->created_at,
      'updated_at' => $aid_center->updated_at,
    ];

    // Trả về JSON response
    return response()->json([
      'message' => 'Aid center detail retrieved successfully!',
      'status' => 200,
      'data' => $shopDetail,
    ]);
  }
  // Phê duyệt aid center
  public function approvedAidCenter($account_id)
  {
    // Tìm tài khoản với account_id
    $account = Account::find($account_id);

    if (!$account) {
      return response()->json([
        'message' => 'Account not found!',
        'status' => 404
      ], 404);
    }

    // Kiểm tra xem tài khoản có role_id là ROLE_AID_CENTER hay không
    $roleName = DB::table('roles')->where('id', $account->role_id)->value('role_name');

    if ($roleName !== 'ROLE_AID_CENTER') {
      return response()->json([
        'message' => 'Account is not an aid center!',
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

  // Chặn aid center
  public function blockAidCenter($account_id)
  {
    // Tìm tài khoản với account_id
    $account = Account::find($account_id);

    if (!$account) {
      return response()->json([
        'message' => 'Account not found!',
        'status' => 404
      ], 404);
    }

    // Kiểm tra xem tài khoản có role_id là ROLE_AID_CENTER hay không
    $roleName = DB::table('roles')->where('id', $account->role_id)->value('role_name');

    if ($roleName !== 'ROLE_AID_CENTER') {
      return response()->json([
        'message' => 'Account is not an aid center!',
        'status' => 400
      ], 400);
    }

    // Chặn tài khoản
    $account->enabled = false;
    $account->save();

    // Cập nhật medical_center tương ứng
    $aid_center = AidCenter::where('account_id', $account_id)->first();

    if ($aid_center) {
      $aid_center->deleted_at = now();
      $aid_center->save();
    }

    return response()->json([
      'message' => 'Account blocked successfully!',
      'status' => 200
    ]);
  }

  public function restoreAidCenter($account_id)
  {
    // Tìm tài khoản với account_id
    $account = Account::find($account_id);

    if (!$account) {
      return response()->json([
        'message' => 'Account not found!',
        'status' => 404
      ], 404);
    }

    // Kiểm tra xem tài khoản có role_id là ROLE_AID_CENTER hay không
    $roleName = DB::table('roles')->where('id', $account->role_id)->value('role_name');

    if ($roleName !== 'ROLE_AID_CENTER') {
      return response()->json([
        'message' => 'Account is not a aid center!',
        'status' => 400
      ], 400);
    }

    // Nếu is_approved = false => tk này đc xoá trước khi được approve
    if ($account->is_approved) {
      // Chặn tài khoản
      $account->enabled = true;
      $account->save();
    }

    // Cập nhật aid center tương ứng
    $aid_center = AidCenter::withTrashed()->where('account_id', $account_id)->first();

    if ($aid_center) {
      $aid_center->restore();
    }

    return response()->json([
      'message' => 'Account restored successfully!',
      'status' => 200
    ]);
  }
}
