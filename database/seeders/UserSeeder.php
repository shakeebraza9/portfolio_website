<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class UserSeeder extends Seeder
{

    public function run(): void
    {
        DB::table('users')->updateOrInsert(
            ['email' => 'shakeeb@gmail.com'], 
            [
                'name' => 'Shakeeb',
                'email_verified_at' => Carbon::now(),
                'password' => Hash::make('123456789'), 
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]
        );
    }
}