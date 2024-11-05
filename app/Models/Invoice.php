<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'rc_partner_id',
        'week_range',
        'invoice_for',
        'email',
        'invoice_from',
        'invoice_number',
        'total_charge',
        'total_transferred',
        'previous_credits',
        'charge_name',
        'charge_total',
        'image_path',
    ];

    public function user()
{
    return $this->belongsTo(User::class, 'rc_partner_id'); // Adjust the foreign key as needed
}
public function getWeekRangeAttribute($value)
{
    return ucfirst($value); // Example of modifying the output
}

}
