<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubOrder extends Model
{
  use HasFactory;

  /**
   * The table associated with the model.
   *
   * @var string
   */
  protected $table = 'sub_orders';

  /**
   * The attributes that are mass assignable.
   *
   * @var array<int, string>
   */
  protected $fillable = [
    'sub_total_prices',
    'status',
    'order_id',
    'shop_id',
  ];

  public function shop()
  {
    return $this->belongsTo(Shop::class);
  }

  public function order()
  {
    return $this->belongsTo(Order::class);
  }
}
