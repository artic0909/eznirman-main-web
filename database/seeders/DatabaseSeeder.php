<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(AdminSeeder::class);
        $this->call(AccountSeeder::class);

        // Seed a supervisor user for testing
        \App\Models\User::updateOrCreate(
            ['email' => 'user@eznirman.com'],
            [
                'name' => 'Supervisor John',
                'password' => \Illuminate\Support\Facades\Hash::make('user123'),
                'role' => 'supervisor',
                'code' => '11074',
                'status' => 'active',
                'mobile' => '9876543210',
                'joining_date' => now(),
            ]
        );
    }
}
