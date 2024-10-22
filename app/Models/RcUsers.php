<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RcUsers extends Model
{
    use HasFactory;
    protected $table = 'rccPartner_tbl'; // Specify the table name

    protected $fillable = ['name', 'email', 'password','reportingTo','hrlyRate','address','contact'];

    public function leave()
    {
        return $this->hasOne(Leave::class);
    }
}
