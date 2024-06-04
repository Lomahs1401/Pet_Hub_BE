<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RatingServiceInteract extends Model
{
  use HasFactory, SoftDeletes;

  /**
   * The table associated with the model.
   *
   * @var string
   */
  protected $table = 'rating_service_interacts';

  /**
   * The attributes that are mass assignable.
   *
   * @var array<int, string>
   */
  protected $fillable = [
    'account_id',
    'rating_service_id',
  ];

  public function ratingService()
  {
    return $this->belongsTo(RatingService::class);
  }

  public function account()
  {
    return $this->belongsTo(Account::class);
  }
}
