<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class ExpoToken extends Model
{
  use HasFactory, Notifiable;

  /**
   * The table associated with the model.
   *
   * @var string
   */
  protected $table = 'expo_tokens';

  /**
   * The attributes that are mass assignable.
   *
   * @var array<int, string>
   */
  protected $fillable = [
    'account_id',
    'expo_token'
  ];

  protected function casts(): array
  {
    return [
      'expo_token' => ExpoToken::class
    ];
  }
  /**
   * @return Collection<int, ExpoPushToken>
   */
  public function routeNotificationForExpo(): Collection
  {
    return $this->devices->pluck('expo_token');
  }

  public function account()
  {
    return $this->belongsTo(Account::class);
  }
}
