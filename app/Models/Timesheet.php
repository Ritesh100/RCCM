<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Timesheet extends Model
{
    use HasFactory;

     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'day',
        'date',
        'cost_center',
        'start_time',
        'close_time',
        'break_start',
        'break_end',
        'timezone',
        'user_email',
        'work_time',
        'reportingTo',
    ];
}
