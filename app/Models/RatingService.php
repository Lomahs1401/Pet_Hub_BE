<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RatingService extends Model
{
  use HasFactory, SoftDeletes;

  /**
   * The table associated with the model.
   *
   * @var string
   */
  protected $table = 'rating_services';

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

  public function service()
  {
    return $this->belongsTo(Service::class);
  }

  public function interacts()
  {
    return $this->hasMany(RatingServiceInteract::class);
  }
}
