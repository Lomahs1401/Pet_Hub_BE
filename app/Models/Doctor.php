<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Doctor extends Model
{
	use HasFactory, SoftDeletes;

	/**
	 * The table associated with the model.
	 *
	 * @var string
	 */
	protected $table = 'doctors';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array<int, string>
	 */
	protected $fillable = [
		'full_name',
		'gender',
		'birthdate',
		'description',
		'CMND',
		'phone',
		'address',
		'image',
		'certificate',
		'account_id',
		'medical_center_id'
	];
}
