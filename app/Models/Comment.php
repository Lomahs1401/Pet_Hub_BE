<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comment extends Model
{
  use HasFactory, SoftDeletes;

  /**
   * The table associated with the model.
   *
   * @var string
   */
  protected $table = 'comments';

  /**
   * The attributes that are mass assignable.
   *
   * @var array<int, string>
   */
  protected $fillable = [
    'text',
    'account_id',
    'parent_comments_id',
    'blog_id',
  ];

  public function account()
  {
    return $this->belongsTo(Account::class);
  }

  public function subComments()
  {
    return $this->hasMany(Comment::class, 'parent_comments_id');
  }
}
