<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
  use HasFactory;

  /**
   * The table associated with the model.
   *
   * @var string
   */
  protected $table = 'customers';

  /**
   * The attributes that are mass assignable.
   *
   * @var array<int, string>
   */
  protected $fillable = [
    'full_name',
    'gender',
    'birthdate',
    'address',
    'phone',
    'ranking_point',
    'account_id',
    'ranking_id'
  ];

  public function pets()
  {
    return $this->hasMany(Pet::class);
  }

  public function adoptedPets()
  {
    return $this->hasMany(HistoryAdopt::class);
  }

  public function ratings()
  {
    return $this->hasMany(RatingProduct::class);
  }

  public function account()
  {
    return $this->belongsTo(Account::class, 'account_id');
  }
}
