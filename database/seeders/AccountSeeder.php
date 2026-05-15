<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Account;
use Illuminate\Support\Facades\Hash;

class AccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Account::updateOrCreate(
            ['email' => 'accounts.ez@nirman.com'],
            ['password' => Hash::make('accounts.ez123')]
        );
    }
}
