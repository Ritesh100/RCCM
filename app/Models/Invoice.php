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
        'charge_name',
        'charge_total',
        'total_charge',
        'total_transferred',
        'previous_credits',
        'image_path'
    ];


    public function charges()
    {
        return $this->hasMany(Charge::class);
    }

    public function images()
    {
        return $this->hasMany(Invoiceimage::class);
    }
}
