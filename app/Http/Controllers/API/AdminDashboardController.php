<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\Appointment;
use App\Models\Role;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
  public function getShop(Request $request)
  {
    // $year = $request->get('year', Carbon::now()->year); // Lấy năm từ request hoặc sử dụng năm hiện tại

    // // Khởi tạo mảng để chứa dữ liệu theo tháng
    // $accountData = array_fill(0, 12, ['name' => '', 'count' => 0]);

    // // Tên các tháng
    // $monthNames = [
    //   'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun',
    //   'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'
    // ];

    // $role_id = Role::where('role_name', 'ROLE_SHOP')->value('id');

    // foreach ($monthNames as $index => $month) {
    //   $monthStart = Carbon::create($year, $index + 1, 1)->startOfMonth();
    //   $monthEnd = $monthStart->copy()->endOfMonth();

    //   // Lấy số lượng account được tạo ra trong khoảng thời gian của tháng đó
    //   $accountCount = Account::where('role_id', $role_id)->whereBetween('created_at', [$monthStart, $monthEnd])->count();

    //   $accountData[$index] = [
    //     'name' => $month,
    //     'count' => (int)$accountCount, // Ép kiểu về số nguyên
    //   ];
    // }

    // return response()->json([
    //   'message' => 'Shop accounts retrieved successfully!',
    //   'status' => 200,
    //   'data' => $accountData
    // ]);
    $page_number = intval($request->query('page_number', 1));
    $num_of_page = intval($request->query('num_of_page', 10));

    // Tính toán offset
    $offset = ($page_number - 1) * $num_of_page;

    // Lấy số lượng các cuộc hẹn đã hoàn thành
    $total_appointments = Appointment::where('done', true)->count();
    $total_pages = ceil($total_appointments / $num_of_page);

    // Lấy danh sách các cuộc hẹn đã hoàn thành
    $appointments = Appointment::where('done', true)
      ->offset($offset)
      ->limit($num_of_page)
      ->orderBy('start_time', 'desc')
      ->get();

    return response()->json([
      'message' => 'Query successfully!',
      'status' => 200,
      'page_number' => $page_number,
      'num_of_page' => $num_of_page,
      'total_pages' => $total_pages,
      'total_appointments' => $total_appointments,
      'data' => $appointments,
    ]);
  }


  public function getMedicalCenter(Request $request)
  {
    $year = $request->get('year', Carbon::now()->year); // Lấy năm từ request hoặc sử dụng năm hiện tại

    // Khởi tạo mảng để chứa dữ liệu theo tháng
    $accountData = array_fill(0, 12, ['name' => '', 'count' => 0]);

    // Tên các tháng
    $monthNames = [
      'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun',
      'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'
    ];

    $role_id = Role::where('role_name', 'ROLE_MEDICAL_CENTER')->value('id');

    foreach ($monthNames as $index => $month) {
      $monthStart = Carbon::create($year, $index + 1, 1)->startOfMonth();
      $monthEnd = $monthStart->copy()->endOfMonth();

      // Lấy số lượng account được tạo ra trong khoảng thời gian của tháng đó
      $accountCount = Account::where('role_id', $role_id)->whereBetween('created_at', [$monthStart, $monthEnd])->count();

      $accountData[$index] = [
        'name' => $month,
        'count' => (int)$accountCount, // Ép kiểu về số nguyên
      ];
    }

    return response()->json([
      'message' => 'Medical Center accounts retrieved successfully!',
      'status' => 200,
      'data' => $accountData
    ]);
  }

  public function getAidCenter(Request $request)
  {
    $year = $request->get('year', Carbon::now()->year); // Lấy năm từ request hoặc sử dụng năm hiện tại

    // Khởi tạo mảng để chứa dữ liệu theo tháng
    $accountData = array_fill(0, 12, ['name' => '', 'count' => 0]);

    // Tên các tháng
    $monthNames = [
      'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun',
      'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'
    ];

    $role_id = Role::where('role_name', 'ROLE_AID_CENTER')->value('id');

    foreach ($monthNames as $index => $month) {
      $monthStart = Carbon::create($year, $index + 1, 1)->startOfMonth();
      $monthEnd = $monthStart->copy()->endOfMonth();

      // Lấy số lượng account được tạo ra trong khoảng thời gian của tháng đó
      $accountCount = Account::where('role_id', $role_id)->whereBetween('created_at', [$monthStart, $monthEnd])->count();

      $accountData[$index] = [
        'name' => $month,
        'count' => (int)$accountCount, // Ép kiểu về số nguyên
      ];
    }

    return response()->json([
      'message' => 'Aid Center accounts retrieved successfully!',
      'status' => 200,
      'data' => $accountData
    ]);
  }

  public function getCustomer(Request $request)
  {
    $year = $request->get('year', Carbon::now()->year); // Lấy năm từ request hoặc sử dụng năm hiện tại

    // Khởi tạo mảng để chứa dữ liệu theo tháng
    $accountData = array_fill(0, 12, ['name' => '', 'count' => 0]);

    // Tên các tháng
    $monthNames = [
      'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun',
      'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'
    ];

    $role_id = Role::where('role_name', 'ROLE_CUSTOMER')->value('id');

    foreach ($monthNames as $index => $month) {
      $monthStart = Carbon::create($year, $index + 1, 1)->startOfMonth();
      $monthEnd = $monthStart->copy()->endOfMonth();

      // Lấy số lượng account được tạo ra trong khoảng thời gian của tháng đó
      $accountCount = Account::where('role_id', $role_id)->whereBetween('created_at', [$monthStart, $monthEnd])->count();

      $accountData[$index] = [
        'name' => $month,
        'count' => (int)$accountCount, // Ép kiểu về số nguyên
      ];
    }

    return response()->json([
      'message' => 'Shop accounts retrieved successfully!',
      'status' => 200,
      'data' => $accountData
    ]);
  }

  public function getAccountType(Request $request)
  {
    // Định nghĩa các loại role mà chúng ta cần lấy số lượng account
    $roles = [
      'ROLE_SHOP',
      'ROLE_MEDICAL_CENTER',
      'ROLE_AID_CENTER',
      'ROLE_CUSTOMER',
    ];

    // Mảng để chứa kết quả
    $accountTypes = [];

    // Lặp qua từng role và lấy số lượng account
    foreach ($roles as $roleName) {
      // Lấy id của role hiện tại
      $role_id = Role::where('role_name', $roleName)->value('id');

      if ($role_id) {
        // Đếm số lượng account có role_id này
        $accountCount = Account::where('role_id', $role_id)->count();

        // Thêm vào mảng kết quả
        $accountTypes[] = [
          'role' => $roleName,
          'count' => $accountCount
        ];
      }
    }

    return response()->json([
      'message' => 'Account types retrieved successfully!',
      'status' => 200,
      'data' => $accountTypes
    ]);
  }

  public function getAccountStatus(Request $request)
  {
    $doctorRoleId = Role::where('role_name', 'ROLE_DOCTOR')->value('id');

    // Đếm số lượng tài khoản theo các trạng thái khác nhau
    $notApprovedCount = Account::where('is_approved', false)->where('role_id', '!=', $doctorRoleId)->count();
    $enabledCount = Account::where('enabled', true)->where('role_id', '!=', $doctorRoleId)->count();
    $blockedCount = Account::where('enabled', false)->where('role_id', '!=', $doctorRoleId)->count();

    // Định dạng dữ liệu trả về
    $data = [
      [
        "status" => "Actived",
        "count" => $enabledCount
      ],
      [
        "status" => "Waiting Approve",
        "count" => $notApprovedCount
      ],
      [
        "status" => "Blocked",
        "count" => $blockedCount
      ],
    ];

    // Trả về kết quả dưới dạng JSON
    return response()->json([
      'message' => 'Account status retrieved successfully!',
      'status' => 200,
      'data' => $data
    ]);
  }

  public function getAccountByApproved(Request $request)
  {
    // Lấy id của các vai trò
    $roleIds = Role::whereIn('role_name', ['ROLE_CUSTOMER', 'ROLE_SHOP', 'ROLE_MEDICAL_CENTER', 'ROLE_AID_CENTER'])
      ->pluck('id', 'role_name')->toArray();

    // Khởi tạo dữ liệu cho radar chart
    $data = [
      // [
      //   "role" => "Customer",
      //   "approved" => 0,
      //   "not_approved" => 0,
      //   "blocked" => 0,
      // ],
      [
        "role" => "Shop",
        "approved" => 0,
        "not_approved" => 0,
        "blocked" => 0
      ],
      [
        "role" => "Medical Center",
        "approved" => 0,
        "not_approved" => 0,
        "blocked" => 0
      ],
      [
        "role" => "Aid Center",
        "approved" => 0,
        "not_approved" => 0,
        "blocked" => 0
      ]
    ];

    // Biến để tính tổng
    $totalApproved = 0;
    $totalNotApproved = 0;
    $totalBlocked = 0;

    foreach ($data as &$item) {
      $roleName = "ROLE_" . strtoupper(str_replace(" ", "_", $item["role"]));
      if (isset($roleIds[$roleName])) {
        $roleId = $roleIds[$roleName];

        // Lấy số lượng tài khoản được approved
        $approvedCount = Account::where('role_id', $roleId)
          ->where('is_approved', true)
          ->count();

        // Lấy số lượng tài khoản chưa được approved
        $notApprovedCount = Account::where('role_id', $roleId)
          ->where('is_approved', false)
          ->count();

        // Lấy số lượng tài khoản bị blocked
        $blockedCount = Account::where('role_id', $roleId)
          ->where('enabled', false)
          ->count();

        $item["approved"] = $approvedCount;
        $item["not_approved"] = $notApprovedCount;
        $item["blocked"] = $blockedCount;

        // Cộng số lượng tài khoản vào tổng
        $totalApproved += $approvedCount;
        $totalNotApproved += $notApprovedCount;
        $totalBlocked += $blockedCount;
      }
    }

    // Dữ liệu tổng
    $total = [
      "approved" => $totalApproved,
      "not_approved" => $totalNotApproved,
      "blocked" => $totalBlocked
    ];

    return response()->json([
      'message' => 'Accounts by approved status retrieved successfully!',
      'status' => 200,
      'data' => $data,
      'total' => $total
    ]);
  }

  public function getRecentWaitingApprovedAccount(Request $request)
  {
    // Lấy id của vai trò ROLE_DOCTOR
    $doctorRoleId = Role::where('role_name', 'ROLE_DOCTOR')->value('id');

    // Lấy danh sách các account đang chờ duyệt (is_approved = false) và không có vai trò là ROLE_DOCTOR, sắp xếp giảm dần theo thời gian tạo
    $waitingAccounts = Account::where('is_approved', false)
      ->where('role_id', '!=', $doctorRoleId)
      ->orderBy('created_at', 'desc')
      ->with('role') // Sử dụng eager loading để lấy thông tin vai trò
      ->limit(10)
      ->get();

    // Định dạng dữ liệu trả về
    $data = $waitingAccounts->map(function ($account) {
      return [
        'id' => $account->id,
        'username' => $account->username,
        'email' => $account->email,
        'is_approved' => $account->is_approved,
        'created_at' => $account->created_at,
        'role_name' => $account->role->role_name, // Lấy tên vai trò từ quan hệ
      ];
    });

    // Trả về danh sách dưới dạng JSON
    return response()->json([
      'message' => 'Recent waiting approved accounts retrieved successfully!',
      'status' => 200,
      'data' => $data
    ]);
  }
}
