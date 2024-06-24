<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdoptRequest extends Model
{
  use HasFactory;

  /**
   * The table associated with the model.
   *
   * @var string
   */
  protected $table = 'adopt_requests';

  /**
   * The attributes that are mass assignable.
   *
   * @var array<int, string>
   */
  protected $fillable = [
    'status',
    'note',
    'aid_center_id',
    'pet_id',
    'customer_id',
  ];

  public function customer()
  {
    return $this->belongsTo(Customer::class);
  }

  public function pet()
  {
    return $this->belongsTo(Pet::class);
  }

  public function aidCenter()
  {
    return $this->belongsTo(AidCenter::class);
  }
}
