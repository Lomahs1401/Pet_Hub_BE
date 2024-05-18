<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'products';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'description',
        'price',
        'images',
        'quantity',
        'sold_quantity',
        'status',
        'product_category_id',
        'shop_id',
    ];

    public function shop()
    {
        return $this->belongsTo(Shop::class, 'shop_id');
    }

    public function category()
    {
        return $this->belongsTo(ProductCategory::class, 'product_category_id');
    }

    public function printAverageRating()
    {
        // Sử dụng Eloquent ORM
        $averageRatingEloquent = RatingProduct::avg('rating');
        echo "Average Rating (Eloquent ORM): " . $averageRatingEloquent . PHP_EOL;
    }

    public function calculateProductRating()
    {
        // Lấy tất cả các đánh giá của sản phẩm
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

        return $averageRating;
    }

    // Mối quan hệ một-nhiều với RatingProduct
    public function ratings()
    {
        return $this->hasMany(RatingProduct::class, 'product_id');
    }
}
