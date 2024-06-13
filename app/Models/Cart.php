<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
  use HasFactory;

  /**
   * The table associated with the model.
   *
   * @var string
   */
  protected $table = 'carts';

  /**
   * The attributes that are mass assignable.
   *
   * @var array<int, string>
   */
  protected $fillable = [
    'total_prices',
    'is_active',
    'customer_id',
  ];

  // Quan hệ với Customer
  public function customer()
  {
    return $this->belongsTo(Customer::class);
  }

  // Quan hệ với CartItem
  public function cartItem()
  {
    return $this->hasMany(CartItem::class);
  }

  public function order()
  {
    return $this->hasOne(Order::class);
  }
}
