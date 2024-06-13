<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
  use HasFactory;

  /**
   * The table associated with the model.
   *
   * @var string
   */
  protected $table = 'orders';

  /**
   * The attributes that are mass assignable.
   *
   * @var array<int, string>
   */
  protected $fillable = [
    'total_prices',
    'address',
    'payment_method',
    'transaction_order_id',
    'customer_id',
    'cart_id',
  ];

  public function subOrder()
  {
    return $this->hasMany(SubOrder::class);
  }

  public function cart()
  {
    return $this->belongsTo(Cart::class);
  }

  public function customer()
  {
    return $this->belongsTo(Customer::class);
  }
}
