<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Blog extends Model
{
  use HasFactory, SoftDeletes;

  /**
   * The table associated with the model.
   *
   * @var string
   */
  protected $table = 'blogs';

  /**
   * The attributes that are mass assignable.
   *
   * @var array<int, string>
   */
  protected $fillable = [
    'title',
    'text',
    'image',
    'account_id',
    'blog_category_id',
  ];

  public function account()
  {
    return $this->belongsTo(Account::class);
  }

  public function blogCategory()
  {
    return $this->belongsTo(BlogCategory::class);
  }
}
