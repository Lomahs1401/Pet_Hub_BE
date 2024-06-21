<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pet extends Model
{
  use HasFactory, SoftDeletes;

  /**
   * The table associated with the model.
   *
   * @var string
   */
  protected $table = 'pets';

  /**
   * The attributes that are mass assignable.
   *
   * @var array<int, string>
   */
  protected $fillable = [
    'name',
    'type',
    'age',
    'gender',
    'description',
    'image',
    'is_purebred',
    'status',
    'breed_id',
    'aid_center_id',
    'customer_id',
  ];

  public function breed()
  {
    return $this->belongsTo(Breed::class, 'breed_id');
  }

  public function aidCenter()
  {
    return $this->belongsTo(AidCenter::class, 'aid_center_id');
  }

  public function customer()
  {
    return $this->belongsTo(Customer::class, 'customer_id');
  }

  public function historyAdoptions()
  {
    return $this->hasOne(HistoryAdopt::class);
  }

  public function historyVaccines()
  {
    return $this->hasMany(HistoryVaccine::class);
  }

  public function historyDiagnosis()
  {
    return $this->hasMany(HistoryDiagnosis::class);
  }

  public function getIsPurebredAttribute($value)
  {
    return $value ? true : false;
  }
}
