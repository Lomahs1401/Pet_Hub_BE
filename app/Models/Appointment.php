<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Appointment extends Model
{
  use HasFactory, SoftDeletes;

  /**
   * The table associated with the model.
   *
   * @var string
   */
  protected $table = 'appointments';

  /**
   * The attributes that are mass assignable.
   *
   * @var array<int, string>
   */
  protected $fillable = [
    'start_time',
    'message',
    'done',
    'customer_id',
    'medical_center_id',
    'doctor_id',
    'pet_id',
  ];

  public function customer()
  {
    return $this->belongsTo(Customer::class);
  }

  public function pet()
  {
    return $this->belongsTo(Pet::class);
  }

  public function doctor()
  {
    return $this->belongsTo(Doctor::class);
  }
}
