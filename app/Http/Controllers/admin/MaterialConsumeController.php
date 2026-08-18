<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MaterialConsume;
use App\Models\MaterialPurchase;
use App\Models\WorkingSite;
use Illuminate\Http\Request;

class MaterialConsumeController extends Controller
{
    public function index(Request $request)
    {
        $purchasesQuery = MaterialPurchase::with('materialCode');
        
        if (\Illuminate\Support\Facades\Auth::guard('web')->check()) {
            $user = \Illuminate\Support\Facades\Auth::guard('web')->user();
            $coordinator = \App\Models\Coordinator::where('user_id', $user->id)->first();
            if ($coordinator && $coordinator->assigned_sites_ids) {
                $purchasesQuery->whereIn('working_site_id', $coordinator->assigned_sites_ids);
            } else {
                $purchasesQuery->where('id', 0); // Hide all
            }
        }
        $purchases = $purchasesQuery->get();

        $sites = WorkingSite::all();

        $query = MaterialConsume::with(['purchase.materialCode', 'fromSite', 'toSite']);

        if ($request->search) {
            $query->whereHas('purchase.materialCode', function($q) use ($request) {
                $q->where('material_name', 'like', '%' . $request->search . '%');
                $q->orWhere('material_unique_id', 'like', '%' . $request->search . '%');
            });
        }

        if (\Illuminate\Support\Facades\Auth::guard('web')->check()) {
            $user = \Illuminate\Support\Facades\Auth::guard('web')->user();
            $coordinator = \App\Models\Coordinator::where('user_id', $user->id)->first();
            if ($coordinator && $coordinator->assigned_sites_ids) {
                $assignedSites = $coordinator->assigned_sites_ids;
                $query->where(function($q) use ($assignedSites) {
                    $q->whereIn('from_site_id', $assignedSites)
                      ->orWhereIn('to_site_id', $assignedSites);
                });
            } else {
                $query->where('id', 0); // Hide all
            }
        }

        if ($request->site_id) {
            $query->where(function($q) use ($request) {
                $q->where('from_site_id', $request->site_id)
                  ->orWhere('to_site_id', $request->site_id);
            });
        }

        if ($request->has('type') && $request->type !== null) {
            $query->where('use_now', $request->type);
        }

        if ($request->from_date) {
            $query->whereDate('consume_date', '>=', $request->from_date);
        }

        if ($request->to_date) {
            $query->whereDate('consume_date', '<=', $request->to_date);
        }

        if ($request->has('export') && $request->export === 'excel') {
            return \App\Services\ExportService::exportToExcel(
                $query->latest(),
                'material_consumes_export.xlsx',
                function ($consume) {
                    return [
                        'Date' => \Carbon\Carbon::parse($consume->consume_date)->format('d M, Y'),
                        'Material ID' => $consume->purchase->material_unique_id ?? 'N/A',
                        'Material Name' => $consume->purchase->materialCode->material_name ?? 'N/A',
                        'From Site' => $consume->fromSite->site_name ?? 'N/A',
                        'Type' => $consume->use_now ? 'Transfer' : 'Consume',
                        'To Site' => $consume->use_now ? ($consume->toSite->site_name ?? 'N/A') : 'N/A',
                        'Used Quantity' => $consume->used_quantity,
                        'Unit' => $consume->unit,
                    ];
                }
            );
        }

        $consumes = $query->latest()->paginate(10)->withQueryString();

        return view('admin.purchase.material-consume', compact('purchases', 'sites', 'consumes'));
    }

    public function wastage(Request $request)
    {
        $sites = WorkingSite::all();
        $query = MaterialConsume::with(['purchase.materialCode', 'fromSite'])->where('use_now', 2);

        if ($request->search) {
            $query->whereHas('purchase.materialCode', function($q) use ($request) {
                $q->where('material_name', 'like', '%' . $request->search . '%');
                $q->orWhere('material_unique_id', 'like', '%' . $request->search . '%');
            });
        }

        if (\Illuminate\Support\Facades\Auth::guard('web')->check()) {
            $user = \Illuminate\Support\Facades\Auth::guard('web')->user();
            $coordinator = \App\Models\Coordinator::where('user_id', $user->id)->first();
            if ($coordinator && $coordinator->assigned_sites_ids) {
                $query->whereIn('from_site_id', $coordinator->assigned_sites_ids);
            } else {
                $query->where('id', 0); // Hide all
            }
        }

        if ($request->site_id) {
            $query->where('from_site_id', $request->site_id);
        }

        if ($request->from_date) {
            $query->whereDate('consume_date', '>=', $request->from_date);
        }
        if ($request->to_date) {
            $query->whereDate('consume_date', '<=', $request->to_date);
        }

        $wastages = $query->latest()->paginate(10)->withQueryString();

        return view('admin.purchase.material-wastage', compact('sites', 'wastages'));
    }

    public function getStockLocations($purchase_id)
    {
        $locations = MaterialConsume::getStockLocations($purchase_id);
        return response()->json($locations);
    }

    public function store(Request $request)
    {
        $request->validate([
            'material_purchase_id' => 'required|exists:material_purchases,id',
            'consume_date' => 'required|date',
            'used_quantity' => 'required|numeric|min:0.01',
            'use_now' => 'required|in:0,1,2',
            'from_site_id' => 'required|exists:working_sites,id',
            'to_site_id' => 'nullable|required_if:use_now,1|exists:working_sites,id',
        ]);

        $purchase = MaterialPurchase::findOrFail($request->material_purchase_id);
        
        // Calculate available quantity AT THE SPECIFIC SITE
        $siteBalance = MaterialConsume::getSiteStock($purchase->id, $request->from_site_id);

        if ($request->used_quantity > $siteBalance) {
            return redirect()->back()->with('error', 'Insufficient stock at selected site! Available: ' . $siteBalance . ' ' . $purchase->unit->name);
        }

        $data = $request->all();
        $data['quantity_current'] = $siteBalance;
        $data['available_quantity'] = $siteBalance - $request->used_quantity;
        $data['unit'] = $purchase->unit->name;

        if (\Illuminate\Support\Facades\Auth::guard('web')->check()) {
            $data['created_by'] = \Illuminate\Support\Facades\Auth::guard('web')->id();
            $data['creator_type'] = 'coordinator';
            $data['type'] = 'coordinator'; // Ensure older type column is populated
        } else if (\Illuminate\Support\Facades\Auth::guard('admin')->check()) {
            $data['created_by'] = \Illuminate\Support\Facades\Auth::guard('admin')->id();
            $data['creator_type'] = 'admin';
            $data['type'] = 'admin';
        }

        MaterialConsume::create($data);

        return redirect()->back()->with('success', 'Material transaction recorded successfully.');
    }

    public function destroy($id)
    {
        $consume = MaterialConsume::findOrFail($id);
        $consume->delete();

        return redirect()->back()->with('success', 'Consume record deleted successfully.');
    }
}
