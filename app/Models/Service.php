<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Service extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'services';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'description',
        'price',
        'image',
        'sold_quantity',
        'medical_center_id',
        'service_category_id',
    ];

    public function medicalCenter()
    {
        return $this->belongsTo(MedicalCenter::class, 'medical_center_id');
    }

    public function category()
    {
        return $this->belongsTo(ServiceCategory::class, 'service_category_id');
    }

    public function calculateServiceRating()
    {
        $ratings = $this->ratings;

        // Đếm số lượng đánh giá
        $count = $ratings->count();

        // Nếu không có đánh giá nào, trả về 0
        if ($count === 0) {
            return 0;
        }

        // Tính tổng điểm rating
        $totalRating = $ratings->sum('rating');

        // Tính điểm trung bình
        $averageRating = $totalRating / $count;

        // Làm tròn điểm trung bình đến một chữ số thập phân
        $averageRating = round($averageRating, 2);

        return number_format($averageRating, 2, '.', '');
    }

    // Mối quan hệ một-nhiều với RatingService
    public function ratings()
    {
        return $this->hasMany(RatingService::class, 'service_id');
    }
}
