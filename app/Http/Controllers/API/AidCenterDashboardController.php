<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\AdoptRequest;
use App\Models\Pet;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AidCenterDashboardController extends Controller
{
  public function getAdoptRequestComparison(Request $request)
  {
    // Nhận tùy chọn thời gian từ request, mặc định là Last Month
    $option = strtolower($request->query('option')) ?? 'last month';

    // Lấy aid_center_id của aid center hiện tại
    $aid_center_id = auth()->user()->aidCenter->id;

    // Xác định khoảng thời gian hiện tại và khoảng thời gian trước đó
    $endDate = Carbon::now();
    switch ($option) {
      case 'last day':
        $startDate = Carbon::today();
        $previousStartDate = Carbon::yesterday();
        $previousEndDate = Carbon::today()->subSecond();
        break;
      case 'last week':
        $startDate = Carbon::now()->startOfWeek();
        $previousStartDate = Carbon::now()->subWeek()->startOfWeek();
        $previousEndDate = Carbon::now()->subWeek()->endOfWeek();
        break;
      case 'last year':
        $startDate = Carbon::now()->startOfYear();
        $previousStartDate = Carbon::now()->subYear()->startOfYear();
        $previousEndDate = Carbon::now()->subYear()->endOfYear();
        break;
      case 'last month':
      default:
        $startDate = Carbon::now()->startOfMonth();
        $previousStartDate = Carbon::now()->subMonth()->startOfMonth();
        $previousEndDate = Carbon::now()->subMonth()->endOfMonth();
        break;
    }

    // Lấy tổng số lượng adopt request trong khoảng thời gian hiện tại
    $currentPeriodCount = AdoptRequest::where('aid_center_id', $aid_center_id)
      ->whereBetween('created_at', [$startDate, $endDate])
      ->count();

    $previousPeriodCount = AdoptRequest::where('aid_center_id', $aid_center_id)
      ->whereBetween('created_at', [$previousStartDate, $previousEndDate])
      ->count();

    $percentageChange = $previousPeriodCount == 0 ? ($currentPeriodCount > 0 ? 100 : 0) : (($currentPeriodCount - $previousPeriodCount) / $previousPeriodCount) * 100;

    return response()->json([
      'message' => 'Adopt request comparison retrieved successfully',
      'status' => 200,
      'data' => [
        'current_period_count' => $currentPeriodCount,
        'previous_period_count' => $previousPeriodCount,
        'percentage_change' => $percentageChange,
      ],
    ], 200);
  }

  public function getNewPetsComparison(Request $request)
  {
    // Nhận tùy chọn thời gian từ request, mặc định là Last Month
    $option = strtolower($request->query('option')) ?? 'last month';

    // Lấy aid_center_id của aid center hiện tại
    $aid_center_id = auth()->user()->aidCenter->id;

    // Xác định khoảng thời gian hiện tại và khoảng thời gian trước đó
    $endDate = Carbon::now();
    switch ($option) {
      case 'last day':
        $startDate = Carbon::today();
        $previousStartDate = Carbon::yesterday();
        $previousEndDate = Carbon::today()->subSecond();
        break;
      case 'last week':
        $startDate = Carbon::now()->startOfWeek();
        $previousStartDate = Carbon::now()->subWeek()->startOfWeek();
        $previousEndDate = Carbon::now()->subWeek()->endOfWeek();
        break;
      case 'last year':
        $startDate = Carbon::now()->startOfYear();
        $previousStartDate = Carbon::now()->subYear()->startOfYear();
        $previousEndDate = Carbon::now()->subYear()->endOfYear();
        break;
      case 'last month':
      default:
        $startDate = Carbon::now()->startOfMonth();
        $previousStartDate = Carbon::now()->subMonth()->startOfMonth();
        $previousEndDate = Carbon::now()->subMonth()->endOfMonth();
        break;
    }

    $currentPeriodCount = Pet::where('aid_center_id', $aid_center_id)
      ->whereBetween('created_at', [$startDate, $endDate])
      ->count();

    $previousPeriodCount = Pet::where('aid_center_id', $aid_center_id)
      ->whereBetween('created_at', [$previousStartDate, $previousEndDate])
      ->count();

    $percentageChange = $previousPeriodCount == 0 ? ($currentPeriodCount > 0 ? 100 : 0) : (($currentPeriodCount - $previousPeriodCount) / $previousPeriodCount) * 100;

    return response()->json([
      'message' => 'New pets comparison retrieved successfully',
      'status' => 200,
      'data' => [
        'current_period_count' => $currentPeriodCount,
        'previous_period_count' => $previousPeriodCount,
        'percentage_change' => $percentageChange,
      ],
    ], 200);
  }

  public function getAdoptedPetsComparison(Request $request)
  {
    $option = strtolower($request->query('option')) ?? 'last month';
    $aid_center_id = auth()->user()->aidCenter->id;

    $endDate = Carbon::now();
    switch ($option) {
      case 'last day':
        $startDate = Carbon::today();
        $previousStartDate = Carbon::yesterday();
        $previousEndDate = Carbon::today()->subSecond();
        break;
      case 'last week':
        $startDate = Carbon::now()->startOfWeek();
        $previousStartDate = Carbon::now()->subWeek()->startOfWeek();
        $previousEndDate = Carbon::now()->subWeek()->endOfWeek();
        break;
      case 'last year':
        $startDate = Carbon::now()->startOfYear();
        $previousStartDate = Carbon::now()->subYear()->startOfYear();
        $previousEndDate = Carbon::now()->subYear()->endOfYear();
        break;
      case 'last month':
      default:
        $startDate = Carbon::now()->startOfMonth();
        $previousStartDate = Carbon::now()->subMonth()->startOfMonth();
        $previousEndDate = Carbon::now()->subMonth()->endOfMonth();
        break;
    }

    $currentPeriodCount = Pet::where('aid_center_id', $aid_center_id)
      ->where('status', true)
      ->whereBetween('created_at', [$startDate, $endDate])
      ->count();

    $previousPeriodCount = Pet::where('aid_center_id', $aid_center_id)
      ->where('status', true)
      ->whereBetween('created_at', [$previousStartDate, $previousEndDate])
      ->count();

    $percentageChange = $previousPeriodCount == 0 ? ($currentPeriodCount > 0 ? 100 : 0) : (($currentPeriodCount - $previousPeriodCount) / $previousPeriodCount) * 100;

    return response()->json([
      'message' => 'Adopted pets comparison retrieved successfully',
      'status' => 200,
      'data' => [
        'current_period_count' => $currentPeriodCount,
        'previous_period_count' => $previousPeriodCount,
        'percentage_change' => $percentageChange,
      ],
    ], 200);
  }

  public function getUnadoptedPetsComparison(Request $request)
  {
    $option = strtolower($request->query('option')) ?? 'last month';
    $aid_center_id = auth()->user()->aidCenter->id;

    $endDate = Carbon::now();
    switch ($option) {
      case 'last day':
        $startDate = Carbon::today();
        $previousStartDate = Carbon::yesterday();
        $previousEndDate = Carbon::today()->subSecond();
        break;
      case 'last week':
        $startDate = Carbon::now()->startOfWeek();
        $previousStartDate = Carbon::now()->subWeek()->startOfWeek();
        $previousEndDate = Carbon::now()->subWeek()->endOfWeek();
        break;
      case 'last year':
        $startDate = Carbon::now()->startOfYear();
        $previousStartDate = Carbon::now()->subYear()->startOfYear();
        $previousEndDate = Carbon::now()->subYear()->endOfYear();
        break;
      case 'last month':
      default:
        $startDate = Carbon::now()->startOfMonth();
        $previousStartDate = Carbon::now()->subMonth()->startOfMonth();
        $previousEndDate = Carbon::now()->subMonth()->endOfMonth();
        break;
    }

    $currentPeriodCount = Pet::where('aid_center_id', $aid_center_id)
      ->where('status', false)
      ->whereBetween('created_at', [$startDate, $endDate])
      ->count();

    $previousPeriodCount = Pet::where('aid_center_id', $aid_center_id)
      ->where('status', false)
      ->whereBetween('created_at', [$previousStartDate, $previousEndDate])
      ->count();

    $percentageChange = $previousPeriodCount == 0 ? ($currentPeriodCount > 0 ? 100 : 0) : (($currentPeriodCount - $previousPeriodCount) / $previousPeriodCount) * 100;

    return response()->json([
      'message' => 'Unadopted pets comparison retrieved successfully',
      'status' => 200,
      'data' => [
        'current_period_count' => $currentPeriodCount,
        'previous_period_count' => $previousPeriodCount,
        'percentage_change' => $percentageChange,
      ],
    ], 200);
  }

  public function getLastWeekAdoptRequest(Request $request)
  {
    // Lấy aid_center_id của aid center hiện tại (giả sử aid_center_id được lưu trong session hoặc lấy từ authenticated user)
    $aid_center_id = auth()->user()->aidCenter->id;

    if (!$aid_center_id) {
      return response()->json([
        'message' => 'Aid Center not found for the current user!',
        'status' => 404,
      ], 404);
    }

    // Lấy thời gian hiện tại và thời gian 7 ngày trước
    $endDate = Carbon::now();
    $startDate = $endDate->copy()->subDays(7);

    // Khởi tạo mảng chứa số lượng yêu cầu nhận nuôi theo ngày
    $adoptRequestData = [];

    // Lấy số lượng yêu cầu nhận nuôi theo từng ngày trong khoảng thời gian 7 ngày
    for ($date = $startDate; $date <= $endDate; $date->addDay()) {
      // Tính tổng số lượng yêu cầu nhận nuôi trong ngày
      $totalAdoptRequests = AdoptRequest::where('aid_center_id', $aid_center_id)
        ->whereDate('created_at', $date)
        ->count();

      // Lấy tên của thứ trong tuần
      $dayName = $date->format('D'); // 'D' format sẽ trả về tên thứ ngắn (Mon, Tue, Wed, ...)

      // Thêm dữ liệu vào mảng
      $adoptRequestData[] = [
        'name' => $dayName,
        'adopt_request_count' => $totalAdoptRequests,
      ];
    }

    // Tính tổng số lượng yêu cầu nhận nuôi của tất cả các ngày trong mảng
    $totalAdoptRequests = array_sum(array_column($adoptRequestData, 'adopt_request_count'));

    return response()->json([
      'message' => 'Adopt requests fetched successfully!',
      'status' => 200,
      'total_adopt_requests' => $totalAdoptRequests,
      'data' => $adoptRequestData,
    ], 200);
  }

  public function getBarAdoptRequest(Request $request)
  {
    $aid_center_id = auth()->user()->aidCenter->id;
    $year = $request->get('year', Carbon::now()->year); // Lấy năm từ request hoặc sử dụng năm hiện tại

    // Khởi tạo mảng để chứa dữ liệu theo tháng
    $adoptRequestData = array_fill(0, 12, ['name' => '', 'adopt_request_count' => 0]);

    // Tên các tháng
    $monthNames = [
      'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun',
      'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'
    ];

    foreach ($monthNames as $index => $month) {
      $monthStart = Carbon::create($year, $index + 1, 1)->startOfMonth();
      $monthEnd = $monthStart->copy()->endOfMonth();

      // Lấy tổng số lượng yêu cầu nhận nuôi
      $totalAdoptRequests = AdoptRequest::where('aid_center_id', $aid_center_id)
        ->whereBetween('created_at', [$monthStart, $monthEnd])
        ->count();

      $adoptRequestData[$index] = [
        'name' => $month,
        'adopt_request_count' => (int)$totalAdoptRequests, // Ép kiểu về số nguyên
      ];
    }

    return response()->json([
      'message' => 'Adopted requests retrieved successfully!',
      'status' => 200,
      'data' => $adoptRequestData
    ]);
  }

  public function getBarAdoptedPets(Request $request)
  {
    $aid_center_id = auth()->user()->aidCenter->id;
    $year = $request->get('year', Carbon::now()->year); // Lấy năm từ request hoặc sử dụng năm hiện tại

    // Khởi tạo mảng để chứa dữ liệu theo tháng
    $adoptedPetsData = array_fill(0, 12, ['name' => '', 'adopted_count' => 0]);

    // Tên các tháng
    $monthNames = [
      'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun',
      'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'
    ];

    foreach ($monthNames as $index => $month) {
      $monthStart = Carbon::create($year, $index + 1, 1)->startOfMonth();
      $monthEnd = $monthStart->copy()->endOfMonth();

      // Lấy tổng số lượng thú cưng đã được nhận nuôi
      $totalAdoptedPets = Pet::where('aid_center_id', $aid_center_id)
        ->whereBetween('updated_at', [$monthStart, $monthEnd])
        ->where('status', 1) // Thú cưng đã được nhận nuôi
        ->count();

      $adoptedPetsData[$index] = [
        'name' => $month,
        'adopted_count' => (int)$totalAdoptedPets, // Ép kiểu về số nguyên
      ];
    }

    return response()->json([
      'message' => 'Adopted pets retrieved successfully!',
      'status' => 200,
      'data' => $adoptedPetsData
    ]);
  }

  public function getPiePets(Request $request)
  {
    $aid_center_id = auth()->user()->aidCenter->id;

    // Đếm số lượng pets chưa được nhận nuôi (status là 0)
    $unadoptedPetsCount = Pet::where('aid_center_id', $aid_center_id)
      ->where('status', 0)
      ->count();

    // Đếm số lượng pets đã được nhận nuôi (status là 1)
    $adoptedPetsCount = Pet::where('aid_center_id', $aid_center_id)
      ->where('status', 1)
      ->count();

    // Tạo dữ liệu trả về
    $data = [
      [
        'type' => 'Unadopted',
        'total_quantity' => $unadoptedPetsCount,
      ],
      [
        'type' => 'Adopted',
        'total_quantity' => $adoptedPetsCount,
      ],
    ];

    return response()->json([
      'message' => 'Pets status fetched successfully!',
      'status' => 200,
      'data' => $data,
    ]);
  }

  public function getRecentAdoptRequest()
  {
    // Get the current user's aid center ID
    $aid_center_id = auth()->user()->aidCenter->id;

    // Fetch the recent adopt requests with status "Pending", limited to 15
    $recentAdoptRequests = AdoptRequest::with('customer', 'customer.account', 'pet')->where('aid_center_id', $aid_center_id)
      ->where('status', 'Pending')
      ->orderBy('created_at', 'desc')
      ->limit(15)
      ->get();

    // Format the data
    $data = $recentAdoptRequests->map(function ($adoptRequest) {
      return [
        'id' => $adoptRequest->id,
        'status' => $adoptRequest->status,
        'note' => $adoptRequest->note,
        'pet_id' => $adoptRequest->pet->id,
        'pet_name' => $adoptRequest->pet->name,
        'pet_age' => $adoptRequest->pet->age,
        'pet_image' => $adoptRequest->pet->image,
        'customer_id' => $adoptRequest->customer->id,
        'customer_full_name' => $adoptRequest->customer->full_name,
        'customer_username' => $adoptRequest->customer->account->username,
        'customer_email' => $adoptRequest->customer->account->email,
        'customer_avatar' => $adoptRequest->customer->account->avatar,
        'created_at' => $adoptRequest->created_at,
        'updated_at' => $adoptRequest->updated_at,
      ];
    });

    return response()->json([
      'message' => 'Recent pending adopt requests retrieved successfully!',
      'status' => 200,
      'data' => $data
    ]);
  }
}
