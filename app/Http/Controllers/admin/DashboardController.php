<?php

namespace App\Http\Controllers\admin;

use App\Models\Machinary;
use App\Models\User;
use App\Models\WorkingSite;
use App\Models\Transfer;
use App\Models\MaterialPurchase;
use App\Models\MaterialConsume;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Machinery Counts
        $machineryCounts = [
            'total' => Machinary::count(),
            'running' => Machinary::where('condition', 'running')->count(),
            'repair' => Machinary::where('condition', 'repair')->count(),
            'damage' => Machinary::where('condition', 'damage')->count(),
        ];

        // HR Counts
        $hrCounts = [
            'total' => User::whereIn('role', ['worker', 'supervisor', 'staff', 'hr'])->count(),
            'worker' => User::where('role', 'worker')->count(),
            'supervisor' => User::where('role', 'supervisor')->count(),
            'staff' => User::where('role', 'staff')->count(),
            'hr' => User::where('role', 'hr')->count(),
        ];

        // Site Counts
        $siteCount = WorkingSite::count();

        // Material Counts
        $materialCounts = [
            'purchases' => MaterialPurchase::count(),
            'consumes' => MaterialConsume::count(),
        ];

        // Recent Activities
        $recentTransfers = Transfer::with(['machinery', 'fromSite', 'toSite'])->latest()->take(5)->get();
        $recentUsers = User::whereIn('role', ['worker', 'supervisor', 'staff', 'hr'])->latest()->take(5)->get();

        return view('admin.dashboard.index', compact(
            'machineryCounts', 
            'hrCounts', 
            'siteCount', 
            'materialCounts',
            'recentTransfers',
            'recentUsers'
        ));
    }
}
