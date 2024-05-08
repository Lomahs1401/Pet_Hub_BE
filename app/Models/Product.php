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
        'status',
        'product_category_id', 
        'shop_id',  
    ];

    public function shop() {
        return $this->belongsTo(Shop::class, 'shop_id');
    }

    public function category() {
        return $this->belongsTo(ProductCategory::class, 'product_category_id');
    }
}
