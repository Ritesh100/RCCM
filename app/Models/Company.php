<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    protected $table = 'company_tbl'; // Specify the table name

    protected $fillable = ['name', 'email', 'password','address','contact'];
}
