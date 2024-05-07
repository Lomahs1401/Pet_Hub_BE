<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shop extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'shops';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'description',
        'image',
        'phone',  
        'address',
        'website',
        'fanpage',
        'work_time',
        'establish_year',
    ];

    public function products() {
        return $this->belongsToMany(Product::class, 'shop_has_products', 'shop_id', 'product_id');
    }
}
