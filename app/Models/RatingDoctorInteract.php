<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RatingDoctorInteract extends Model
{
  use HasFactory, SoftDeletes;

  /**
   * The table associated with the model.
   *
   * @var string
   */
  protected $table = 'rating_doctor_interacts';

  /**
   * The attributes that are mass assignable.
   *
   * @var array<int, string>
   */
  protected $fillable = [
    'account_id',
    'rating_doctor_id',
  ];

  public function ratingDoctor()
  {
    return $this->belongsTo(RatingDoctor::class);
  }

  public function account()
  {
    return $this->belongsTo(Account::class);
  }
}
