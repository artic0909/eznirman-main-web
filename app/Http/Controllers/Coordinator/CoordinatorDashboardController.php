<?php

namespace App\Http\Controllers\Coordinator;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Machinary;
use App\Models\User;
use App\Models\WorkingSite;
use App\Models\Transfer;
use App\Models\MaterialPurchase;
use App\Models\MaterialConsume;

class CoordinatorDashboardController extends Controller
{
    public function index()
    {
        // Fetch Assigned Sites if the user is a Coordinator
        $assignedSites = collect();
        $assignedSitesIds = [];
        if (\Illuminate\Support\Facades\Auth::guard('web')->check()) {
            $user = \Illuminate\Support\Facades\Auth::guard('web')->user();
            $coordinator = \App\Models\Coordinator::where('user_id', $user->id)->first();
            if ($coordinator && $coordinator->assigned_sites_ids) {
                $assignedSitesIds = $coordinator->assigned_sites_ids;
                $assignedSites = WorkingSite::whereIn('id', $assignedSitesIds)->get();
            }
        }

        // Helper to filter machinery by assigned sites
        $machineryQuery = Machinary::where(function($q) use ($assignedSitesIds) {
            if (empty($assignedSitesIds)) {
                $q->where('id', 0);
            } else {
                $q->whereHas('transfers', function($sq) use ($assignedSitesIds) {
                    $sq->whereIn('to_site_id', $assignedSitesIds)
                       ->where('id', function($subQuery) {
                           $subQuery->select('id')->from('transfers')->whereColumn('machinery_id', 'machinaries.id')->orderByDesc('id')->limit(1);
                       });
                })->orWhereDoesntHave('transfers');
            }
        });

        // Machinery Counts
        $machineryCounts = [
            'total' => (clone $machineryQuery)->count(),
            'running' => (clone $machineryQuery)->where('condition', 'running')->count(),
            'repair' => (clone $machineryQuery)->where('condition', 'repair')->count(),
            'damage' => (clone $machineryQuery)->where('condition', 'damage')->count(),
            'missing' => (clone $machineryQuery)->where('condition', 'missing')->count(),
        ];

        // HR Counts (Hide for coordinator)
        $hrCounts = [
            'total' => 0,
            'worker' => 0,
            'supervisor' => 0,
            'staff' => 0,
            'hr' => 0,
        ];

        // Site Counts
        $siteCount = $assignedSites->count();

        // Material Counts
        $materialCounts = [
            'purchases' => empty($assignedSitesIds) ? 0 : MaterialPurchase::whereIn('working_site_id', $assignedSitesIds)->count(),
            'consumes' => empty($assignedSitesIds) ? 0 : MaterialConsume::where(function($q) use ($assignedSitesIds) {
                $q->whereIn('from_site_id', $assignedSitesIds)->orWhereIn('to_site_id', $assignedSitesIds);
            })->count(),
            'wastage' => empty($assignedSitesIds) ? 0 : MaterialConsume::where('use_now', 2)->whereIn('from_site_id', $assignedSitesIds)->count(),
        ];

        // Recent Activities
        $recentTransfers = empty($assignedSitesIds) ? collect() : Transfer::with(['machinery', 'fromSite', 'toSite'])
            ->where(function($q) use ($assignedSitesIds) {
                $q->whereIn('from_site_id', $assignedSitesIds)->orWhereIn('to_site_id', $assignedSitesIds);
            })->latest()->take(5)->get();
            
        $recentUsers = collect(); // Hidden for coordinator

        return view('admin.dashboard.index', compact(
            'machineryCounts', 
            'hrCounts', 
            'siteCount', 
            'materialCounts',
            'recentTransfers',
            'recentUsers',
            'assignedSites'
        ));
    }
}
