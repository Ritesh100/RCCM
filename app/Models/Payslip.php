<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payslip extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'reportingTo',
        'week_range',
        'gross_earning',
        'hrs_worked',
        'hrlyRate',

    ];
}
