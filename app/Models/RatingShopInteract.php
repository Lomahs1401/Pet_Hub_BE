<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RatingShopInteract extends Model
{
  use HasFactory, SoftDeletes;

  /**
   * The table associated with the model.
   *
   * @var string
   */
  protected $table = 'rating_shop_interacts';

  /**
   * The attributes that are mass assignable.
   *
   * @var array<int, string>
   */
  protected $fillable = [
    'account_id',
    'rating_shop_id',
  ];

  public function ratingShop()
  {
    return $this->belongsTo(RatingShop::class);
  }

  public function account()
  {
    return $this->belongsTo(Account::class);
  }
}
