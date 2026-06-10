<?php

namespace App\Http\Controllers\Accounts;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display the accounts dashboard.
     */
    public function index(Request $request)
    {
        // 1. User Metrics
        $totalSupervisors = \App\Models\User::where('role', 'supervisor')->count();
        $totalStaffs = \App\Models\User::where('role', 'staff')->count();
        $totalWorkers = \App\Models\User::where('role', 'worker')->count();
        $totalHrs = \App\Models\User::where('role', 'hr')->count();

        // 2. Year Filter & Transactions Query
        $year = $request->input('year', date('Y'));
        $yearlyTransactions = \App\Models\Transaction::whereYear('date', $year)->get();

        // 3. Transaction & Financial Metrics
        $totalTransactions = $yearlyTransactions->count();
        
        $totalCredits = $yearlyTransactions->where('type', 'credit')->sum('amount');
        $totalDebits = $yearlyTransactions->where('type', 'debit')->sum('amount');

        // 4. Chart Data Generation
        // Initialize arrays with 0 for all 12 months
        $monthlyCredits = array_fill(1, 12, 0);
        $monthlyDebits = array_fill(1, 12, 0);

        foreach ($yearlyTransactions as $tx) {
            $month = (int) $tx->date->format('n');
            if ($tx->type === 'credit') {
                $monthlyCredits[$month] += $tx->amount;
            } elseif ($tx->type === 'debit') {
                $monthlyDebits[$month] += $tx->amount;
            }
        }

        // Prepare for ApexCharts (indexed 0-11)
        $chartCredits = array_values($monthlyCredits);
        $chartDebits = array_values($monthlyDebits);

        return view('account.dashboard.index', compact(
            'totalSupervisors', 'totalStaffs', 'totalWorkers', 'totalHrs',
            'totalTransactions', 'totalCredits', 'totalDebits', 'year',
            'chartCredits', 'chartDebits'
        ));
    }
}
