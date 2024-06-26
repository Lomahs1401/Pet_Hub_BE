<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistoryDiagnosis extends Model
{
	use HasFactory;

	/**
	 * The table associated with the model.
	 *
	 * @var string
	 */
	protected $table = 'history_diagnosis';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array<int, string>
	 */
	protected $fillable = [
		'reason',
		'diagnosis',
		'treatment',
		'health_condition',
		'note',
		'doctor_id',
		'pet_id',
	];

  public function doctor()
  {
    return $this->belongsTo(Doctor::class);
  }
}
