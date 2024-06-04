<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RatingMedicalCenterInteract extends Model
{
	use HasFactory, SoftDeletes;

	/**
	 * The table associated with the model.
	 *
	 * @var string
	 */
	protected $table = 'rating_medical_center_interacts';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array<int, string>
	 */
	protected $fillable = [
		'account_id',
		'rating_medical_center_id',
	];

	public function ratingMedicalCenter()
	{
		return $this->belongsTo(RatingMedicalCenter::class);
	}

	public function account()
	{
		return $this->belongsTo(Account::class);
	}
}
