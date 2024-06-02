<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RatingProductInteract extends Model
{
	use HasFactory, SoftDeletes;

	/**
	 * The table associated with the model.
	 *
	 * @var string
	 */
	protected $table = 'rating_product_interacts';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array<int, string>
	 */
	protected $fillable = [
		'account_id',
		'rating_product_id',
		'liked',
	];

	public function ratingProduct()
	{
		return $this->belongsTo(RatingProduct::class);
	}

	// Quan hệ với model Account
	public function account()
	{
		return $this->belongsTo(Account::class);
	}
}
