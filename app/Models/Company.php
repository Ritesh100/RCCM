<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    protected $table = 'company_tbl'; // Specify the table name

    protected $fillable = ['name', 'email', 'password','address','contact','contact_person','master_agreement_path','service_agreement_path','service_schedule_path'];
}
