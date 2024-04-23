<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Staff extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'employees';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'full_name',
        'gender',
        'birthdate',
        'CMND',
        'address',
        'phone',
        'account_bank',
        'name_bank',
        'day_start',
        'day_quit',
        'image',
        'status',
        'account_id',
    ];

    protected $dates = ['day_start', 'day_quit'];
}
