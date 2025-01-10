<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        DB::table('users_tbl')->insert([
            'userName'    => 'SuperAdmin',
            'userEmail'   => 'superadmin@mail.com',
            'password'    => Hash::make('superadmin123'), // Hashed password
            'role'        => 'admin',
            'reportingTo' => null, // Nullable
            'hrlyRate'    => null, // Nullable
            'created_at'  => now(),
            'updated_at'  => now(),
        ]);
    }
}
