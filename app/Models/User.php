<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $table = 'users_tbl'; // Specify the table

    protected $fillable = [
        'userName','abn', 'userEmail', 'password'
    ];

    protected $hidden = [
        'password',
    ];
}
