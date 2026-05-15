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
        DB::table('admins')->insert([
            'name'              => 'Admin',
            'email'             => 'admin.ez@nirman.com',
            'email_verified_at' => Carbon::now(),
            'password'          => Hash::make('admin.ez123'),
            'remember_token'    => \Str::random(10),
            'created_at'        => Carbon::now(),
            'updated_at'        => Carbon::now(),
        ]);
    }
}
