<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        if (DB::table('admins')->where('email', 'admin.ez@nirman.com')->exists()) {
            return;
        }

        DB::table('admins')->insert([
            'name'              => 'Admin',
            'email'             => 'admin@ez.com',
            'email_verified_at' => Carbon::now(),
            'password'          => Hash::make('12345678'),
            'remember_token'    => \Str::random(10),
            'created_at'        => Carbon::now(),
            'updated_at'        => Carbon::now(),
        ]);
    }
}
