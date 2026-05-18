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

        // Seed some standard Account codes
        $ac1 = \App\Models\Accountcode::updateOrCreate(['code' => '01'], ['name' => 'Materials Procurement']);
        $ac2 = \App\Models\Accountcode::updateOrCreate(['code' => '02'], ['name' => 'Labour Wages Disbursal']);
        $ac3 = \App\Models\Accountcode::updateOrCreate(['code' => '03'], ['name' => 'Logistics & Transport']);
        $ac4 = \App\Models\Accountcode::updateOrCreate(['code' => '04'], ['name' => 'Equipment Hire Charges']);

        // Seed a supervisor user for testing
        $supervisor = \App\Models\User::updateOrCreate(
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

        // Seed some distinct worker users for select2 search demo
        \App\Models\User::updateOrCreate(
            ['code' => 'W-001'],
            [
                'name' => 'Aarav Kumar',
                'email' => 'aarav@eznirman.com',
                'password' => \Illuminate\Support\Facades\Hash::make('worker123'),
                'role' => 'worker',
                'status' => 'active',
                'mobile' => '9876500001',
                'joining_date' => now(),
            ]
        );

        \App\Models\User::updateOrCreate(
            ['code' => 'W-002'],
            [
                'name' => 'Amit Sharma',
                'email' => 'amit@eznirman.com',
                'password' => \Illuminate\Support\Facades\Hash::make('worker123'),
                'role' => 'worker',
                'status' => 'active',
                'mobile' => '9876500002',
                'joining_date' => now(),
            ]
        );

        \App\Models\User::updateOrCreate(
            ['code' => 'W-003'],
            [
                'name' => 'Deepak Singh',
                'email' => 'deepak@eznirman.com',
                'password' => \Illuminate\Support\Facades\Hash::make('worker123'),
                'role' => 'worker',
                'status' => 'active',
                'mobile' => '9876500003',
                'joining_date' => now(),
            ]
        );

        \App\Models\User::updateOrCreate(
            ['code' => 'W-004'],
            [
                'name' => 'Rohan Patel',
                'email' => 'rohan@eznirman.com',
                'password' => \Illuminate\Support\Facades\Hash::make('worker123'),
                'role' => 'worker',
                'status' => 'active',
                'mobile' => '9876500004',
                'joining_date' => now(),
            ]
        );

        // Seed wallet and transactions for this supervisor
        $wallet = \App\Models\Wallet::updateOrCreate(
            ['user_id' => $supervisor->id],
            ['current_balance' => 37500.00]
        );

        // Seed ledger items if empty
        if ($wallet->transactions()->count() === 0) {
            $wallet->transactions()->createMany([
                [
                    'date' => now()->subDays(2),
                    'accountcode_id' => $ac1->id,
                    'amount' => 50000.00,
                    'note' => 'Initial Site Allocation Release',
                    'type' => 'credit',
                    'balance_after' => 50000.00
                ],
                [
                    'date' => now()->subDay(),
                    'accountcode_id' => $ac1->id,
                    'amount' => 8450.00,
                    'note' => '25 Cement Bags - UltraTech',
                    'type' => 'debit',
                    'balance_after' => 41550.00
                ],
                [
                    'date' => now()->subHours(6),
                    'accountcode_id' => $ac2->id,
                    'amount' => 2850.00,
                    'note' => 'Daily wages helper offloading',
                    'type' => 'debit',
                    'balance_after' => 38700.00
                ],
                [
                    'date' => now()->subHours(2),
                    'accountcode_id' => $ac3->id,
                    'amount' => 1200.00,
                    'note' => 'Supervisor Site Checking Fuel claim',
                    'type' => 'debit',
                    'balance_after' => 37500.00
                ]
            ]);
        }
    }
}
