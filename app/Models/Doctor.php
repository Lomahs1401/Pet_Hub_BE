<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Doctor extends Model
{
	use HasFactory, SoftDeletes;

	/**
	 * The table associated with the model.
	 *
	 * @var string
	 */
	protected $table = 'doctors';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array<int, string>
	 */
	protected $fillable = [
		'full_name',
		'gender',
		'birthdate',
		'description',
		'CMND',
		'phone',
		'address',
		'image',
		'certificate',
		'account_id',
		'medical_center_id'
	];

  public function account()
  {
    return $this->belongsTo(Account::class, 'account_id');
  }

  public function calculateDoctorRating()
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
		return $this->hasMany(RatingDoctor::class, 'doctor_id');
	}
}
