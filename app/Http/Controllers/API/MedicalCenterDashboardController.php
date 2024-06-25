<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\RatingMedicalCenter;
use Carbon\Carbon;
use Illuminate\Http\Request;

class MedicalCenterDashboardController extends Controller
{
  public function getReviewsComparison(Request $request)
  {
    // Nhận tùy chọn thời gian từ request, mặc định là Last Month
    $option = strtolower($request->query('option')) ?? 'last month';

    // Lấy medical_center_id của medical center hiện tại
    $medical_center_id = auth()->user()->medicalCenter->id;

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

    // Lấy tổng số lượng rating_medical_center trong khoảng thời gian hiện tại
    $currentPeriodCount = RatingMedicalCenter::whereHas('medicalCenter', function ($query) use ($medical_center_id) {
      $query->where('medical_center_id', $medical_center_id);
    })
      ->whereBetween('created_at', [$startDate, $endDate])
      ->count();

    // Lấy tổng số lượng rating_medical_center trong khoảng thời gian trước đó
    $previousPeriodCount = RatingMedicalCenter::whereHas('medicalCenter', function ($query) use ($medical_center_id) {
      $query->where('medical_center_id', $medical_center_id);
    })
      ->whereBetween('created_at', [$previousStartDate, $previousEndDate])
      ->count();

    // Tính toán tỷ lệ phần trăm chênh lệch
    if ($previousPeriodCount == 0) {
      $percentageChange = $currentPeriodCount > 0 ? 100 : 0;
    } else {
      $percentageChange = (($currentPeriodCount - $previousPeriodCount) / $previousPeriodCount) * 100;
    }

    return response()->json([
      'message' => 'Reviews comparison retrieved successfully',
      'status' => 200,
      'data' => [
        'current_period_count' => $currentPeriodCount,
        'previous_period_count' => $previousPeriodCount,
        'percentage_change' => $percentageChange,
      ],
    ], 200);
  }

  public function getRepliesComparison(Request $request)
  {
    $option = strtolower($request->query('option')) ?? 'last month';

    // Lấy medical_center_id của medical center hiện tại
    $medical_center_id = auth()->user()->medicalCenter->id;

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

    // Lấy tổng số lượng rating_medical_center đã reply trong khoảng thời gian hiện tại
    $currentPeriodCount = RatingMedicalCenter::whereHas('medicalCenter', function ($query) use ($medical_center_id) {
      $query->where('medical_center_id', $medical_center_id);
    })
      ->whereNotNull('reply')
      ->whereBetween('created_at', [$startDate, $endDate])
      ->count();

    // Lấy tổng số lượng rating_medical_center đã reply trong khoảng thời gian trước đó
    $previousPeriodCount = RatingMedicalCenter::whereHas('medicalCenter', function ($query) use ($medical_center_id) {
      $query->where('medical_center_id', $medical_center_id);
    })
      ->whereNotNull('reply')
      ->whereBetween('created_at', [$previousStartDate, $previousEndDate])
      ->count();

    // Tính toán tỷ lệ phần trăm chênh lệch
    if ($previousPeriodCount == 0) {
      $percentageChange = $currentPeriodCount > 0 ? 100 : 0;
    } else {
      $percentageChange = (($currentPeriodCount - $previousPeriodCount) / $previousPeriodCount) * 100;
    }

    return response()->json([
      'message' => 'Replies comparison retrieved successfully',
      'status' => 200,
      'data' => [
        'current_period_count' => $currentPeriodCount,
        'previous_period_count' => $previousPeriodCount,
        'percentage_change' => $percentageChange,
      ],
    ], 200);
  }

  public function getDoctorsComparison(Request $request)
  {
    // Nhận tùy chọn thời gian từ request, mặc định là Last Month
    $option = strtolower($request->query('option')) ?? 'last month';

    // Lấy medical_center_id của medical center hiện tại
    $medical_center_id = auth()->user()->medicalCenter->id;

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

    // Lấy tổng số lượng doctor trong khoảng thời gian hiện tại
    $currentPeriodCount = Doctor::where('medical_center_id', $medical_center_id)
      ->whereBetween('created_at', [$startDate, $endDate])
      ->count();

    // Lấy tổng số lượng doctor trong khoảng thời gian trước đó
    $previousPeriodCount = Doctor::where('medical_center_id', $medical_center_id)
      ->whereBetween('created_at', [$previousStartDate, $previousEndDate])
      ->count();

    // Tính toán tỷ lệ phần trăm chênh lệch
    if ($previousPeriodCount == 0) {
      $percentageChange = $currentPeriodCount > 0 ? 100 : 0;
    } else {
      $percentageChange = (($currentPeriodCount - $previousPeriodCount) / $previousPeriodCount) * 100;
    }

    return response()->json([
      'message' => 'Doctors comparison retrieved successfully',
      'status' => 200,
      'data' => [
        'current_period_count' => $currentPeriodCount,
        'previous_period_count' => $previousPeriodCount,
        'percentage_change' => $percentageChange,
      ],
    ], 200);
  }

  public function getAppointmentsComparison(Request $request)
  {
    // Nhận tùy chọn thời gian từ request, mặc định là Last Month
    $option = strtolower($request->query('option')) ?? 'last month';

    // Lấy medical_center_id của medical center hiện tại
    $medical_center_id = auth()->user()->medicalCenter->id;

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

    // Lấy tổng số lượng appointments trong khoảng thời gian hiện tại
    $currentPeriodCount = Appointment::whereHas('doctor', function ($query) use ($medical_center_id) {
      $query->where('medical_center_id', $medical_center_id);
    })
      ->whereBetween('created_at', [$startDate, $endDate])
      ->count();

    // Lấy tổng số lượng appointments trong khoảng thời gian trước đó
    $previousPeriodCount = Appointment::whereHas('doctor', function ($query) use ($medical_center_id) {
      $query->where('medical_center_id', $medical_center_id);
    })
      ->whereBetween('created_at', [$previousStartDate, $previousEndDate])
      ->count();

    // Tính toán tỷ lệ phần trăm chênh lệch
    if ($previousPeriodCount == 0) {
      $percentageChange = $currentPeriodCount > 0 ? 100 : 0;
    } else {
      $percentageChange = (($currentPeriodCount - $previousPeriodCount) / $previousPeriodCount) * 100;
    }

    return response()->json([
      'message' => 'Appointments comparison retrieved successfully',
      'status' => 200,
      'data' => [
        'current_period_count' => $currentPeriodCount,
        'previous_period_count' => $previousPeriodCount,
        'percentage_change' => $percentageChange,
      ],
    ], 200);
  }

  public function getLastWeekAppointments(Request $request)
  {
    // Lấy medical_center_id của medical center hiện tại
    $medical_center_id = auth()->user()->medicalCenter->id;

    if (!$medical_center_id) {
      return response()->json([
        'message' => 'Medical Center not found for the current user!',
        'status' => 404,
      ], 404);
    }

    // Lấy thời gian hiện tại và thời gian 7 ngày trước
    $endDate = Carbon::now();
    $startDate = $endDate->copy()->subDays(7);

    // Khởi tạo mảng chứa số lượng appointments theo ngày
    $appointmentsData = [];

    // Lấy số lượng appointments theo từng ngày trong khoảng thời gian 7 ngày
    for ($date = $startDate; $date <= $endDate; $date->addDay()) {
      // Tính tổng số lượng appointments trong ngày
      $totalAppointments = Appointment::whereHas('doctor', function ($query) use ($medical_center_id) {
        $query->where('medical_center_id', $medical_center_id);
      })
        ->whereDate('created_at', $date)
        ->count();

      // Lấy tên của thứ trong tuần
      $dayName = $date->format('D'); // 'D' format sẽ trả về tên thứ ngắn (Mon, Tue, Wed, ...)

      // Thêm dữ liệu vào mảng
      $appointmentsData[] = [
        'name' => $dayName,
        'appointments' => $totalAppointments,
      ];
    }

    // Tính tổng số lượng appointments của tất cả các ngày trong mảng
    $totalAppointments = array_sum(array_column($appointmentsData, 'appointments'));

    return response()->json([
      'message' => 'Appointments fetched successfully!',
      'status' => 200,
      'total_appointments' => $totalAppointments,
      'data' => $appointmentsData,
    ], 200);
  }

  public function getRecentReviews(Request $request)
  {
    // Lấy medical_center_id của medical center hiện tại
    $medical_center_id = auth()->user()->medicalCenter->id;

    // Kiểm tra nếu không có medical_center_id
    if (!$medical_center_id) {
      return response()->json([
        'message' => 'Medical Center not found for the current user!',
        'status' => 404,
      ], 404);
    }

    // Nhận các tham số page_number và num_of_page từ request, mặc định là page 1 và 10 items per page
    $page_number = intval($request->query('page_number', 1));
    $num_of_page = intval($request->query('num_of_page', 10));

    $total_reviews_query = RatingMedicalCenter::query()
      ->whereNull('deleted_at')
      ->where('medical_center_id', $medical_center_id);

    // Tính tổng số trang
    $total_reviews = $total_reviews_query->count();
    $total_pages = ceil($total_reviews / $num_of_page);

    // Tính toán offset
    $offset = ($page_number - 1) * $num_of_page;

    // Lấy danh sách các rating theo medical_center_id, sắp xếp từ mới đến cũ và phân trang
    $recentReviews = $total_reviews_query
      ->orderBy('created_at', 'desc')
      ->offset($offset)
      ->limit($num_of_page)
      ->get();

    // Trả về kết quả dưới dạng JSON
    return response()->json([
      'message' => 'Recent reviews fetched successfully!',
      'status' => 200,
      'page_number' => $page_number,
      'num_of_page' => $num_of_page,
      'total_pages' => $total_pages,
      'total_reviews' => $total_reviews,
      'data' => $recentReviews,
    ], 200);
  }
}
