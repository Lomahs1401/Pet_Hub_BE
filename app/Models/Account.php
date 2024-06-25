<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;

class Account extends Authenticatable implements JWTSubject
{
  use HasFactory, HasApiTokens, Notifiable;

  /**
   * The table associated with the model.
   *
   * @var string
   */
  protected $table = 'accounts';

  /**
   * The attributes that are mass assignable.
   *
   * @var array<int, string>
   */
  protected $fillable = [
    'username',
    'email',
    'password',
    'avatar',
    'enabled',
    'role_id',
    'is_approved',
    'reset_code',
    'reset_code_expired_at',
    'reset_code_attempts'
  ];

  /**
   * The attributes that should be hidden for serialization.
   *
   * @var array<int, string>
   */
  protected $hidden = [
    'password',
    'reset_code',
    'reset_code_expires_at',
    'reset_code_attempts'
  ];

  // Rest omitted for brevity

  /**
   * Get the identifier that will be stored in the subject claim of the JWT.
   *
   * @return mixed
   */
  public function getJWTIdentifier()
  {
    return $this->getKey();
  }

  /**
   * Return a key value array, containing any custom claims to be added to the JWT.
   *
   * @return array
   */
  public function getJWTCustomClaims()
  {
    return [];
  }

  // /**
  //    * Get the attributes that should be cast.
  //    *
  //    * @return array<string, string>
  //    */
  //   protected function casts(): array
  //   {
  //       return [
  //           'expo_token' => ExpoPushToken::class
  //       ];
  //   }

  public function expoTokens()
  {
    return $this->hasMany(ExpoToken::class);
  }

  public function routeNotificationForExpo()
  {
    return $this->expoTokens->pluck('expo_token')->toArray();
  }

  public function customer()
  {
    return $this->hasOne(Customer::class, 'account_id');
  }

  public function admin()
  {
    return $this->hasOne(Admin::class, 'account_id');
  }

  public function shop()
  {
    return $this->hasOne(Shop::class, 'account_id');
  }

  public function medicalCenter()
  {
    return $this->hasOne(MedicalCenter::class, 'account_id');
  }

  public function aidCenter()
  {
    return $this->hasOne(AidCenter::class, 'account_id');
  }

  public function role()
  {
    return $this->belongsTo(Role::class, 'role_id');
  }
}
