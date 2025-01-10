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
        'status',
        'disable',
        'deleted_at'  




    ];
    protected $dates = ['deleted_at'];

    // Scope to only show active payslips
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    // Method to soft delete
    public function softDeletePayslip()
    {
        $this->status = 'deleted';
        $this->save();
    }
}
