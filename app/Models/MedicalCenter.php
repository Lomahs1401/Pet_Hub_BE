<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MedicalCenter extends Model
{
  use HasFactory, SoftDeletes;

  /**
   * The table associated with the model.
   *
   * @var string
   */
  protected $table = 'medical_centers';

  /**
   * The attributes that are mass assignable.
   *
   * @var array<int, string>
   */
  protected $fillable = [
    'name',
    'description',
    'image',
    'phone',
    'address',
    'website',
    'fanpage',
    'work_time',
    'establish_year',
    'certificate',
    'account_id',
  ];

  public function account()
  {
    return $this->belongsTo(Account::class);
  }

  public function calculateMedicalCenterRating()
  {
    // Lấy tất cả các đánh giá của sản phẩm
    $ratings = $this->ratings;

    // Đếm số lượng đánh giá
    $count = $ratings->count();

    // Nếu không có đánh giá nào, trả về 0
    if ($count === 0) {
      return [
        'average' => 0,
        'count' => 0
      ];
    }

    // Tính tổng điểm rating
    $totalRating = $ratings->sum('rating');

    // Tính điểm trung bình
    $averageRating = $totalRating / $count;

    // Làm tròn điểm trung bình đến một chữ số thập phân
    $averageRating = round($averageRating, 2);

    return [
      'average' => number_format($averageRating, 2, '.', ''),
      'count' => $count
    ];;
  }

  public function ratings()
  {
    return $this->hasMany(RatingMedicalCenter::class, 'medical_center_id');
  }

  public function doctors()
  {
    return $this->hasMany(Doctor::class, 'medical_center_id');
  }

  public function getWorkTimes()
  {
    // Giả sử work_time có định dạng "06:00 AM : 18:00 PM"
    $times = explode(' : ', $this->work_time);

    // Chuyển đổi thời gian từ định dạng 12 giờ sang định dạng 24 giờ
    $work_start_time = Carbon::createFromFormat('h:i A', trim($times[0]))->format('H:i');
    $work_end_time = Carbon::createFromFormat('h:i A', trim($times[1]))->format('H:i');

    return [
      'start' => $work_start_time,
      'end' => $work_end_time,
    ];
  }
}
