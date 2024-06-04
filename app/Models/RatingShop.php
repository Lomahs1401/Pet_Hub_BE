<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RatingShop extends Model
{
	use HasFactory, SoftDeletes;

  /**
   * The table associated with the model.
   *
   * @var string
   */
  protected $table = 'rating_shops';

  /**
   * The attributes that are mass assignable.
   *
   * @var array<int, string>
   */
  protected $fillable = [
    'rating',
    'description',
    'reply',
    'reply_date',
    'customer_id',
    'service_id',
  ];

  public function customer()
  {
    return $this->belongsTo(Customer::class);
  }

  public function shop()
  {
    return $this->belongsTo(Shop::class);
  }

  public function interacts()
  {
    return $this->hasMany(RatingShopInteract::class);
  }
}
