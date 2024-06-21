<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AidCenter extends Model
{
  use HasFactory, SoftDeletes;

  /**
   * The table associated with the model.
   *
   * @var string
   */
  protected $table = 'aid_centers';

  /**
   * The attributes that are mass assignable.
   *
   * @var array<int, string>
   */
  protected $fillable = [
    'name',
    'description',
    'image',
    'phone',
    'address',
    'website',
    'fanpage',
    'work_time',
    'establish_year',
    'certificate',
    'account_id',
  ];

  public function account()
  {
    return $this->belongsTo(Account::class);
  }
}
