<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RatingDoctor extends Model
{
  use HasFactory, SoftDeletes;
  
  /**
   * The table associated with the model.
   *
   * @var string
   */
  protected $table = 'rating_doctors';

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
    'doctor_id',
  ];

  public function customer()
  {
    return $this->belongsTo(Customer::class);
  }

  public function doctor()
  {
    return $this->belongsTo(Doctor::class);
  }

  public function interacts()
  {
    return $this->hasMany(RatingDoctorInteract::class);
  }
}
