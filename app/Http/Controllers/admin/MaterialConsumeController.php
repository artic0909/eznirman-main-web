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
        $purchases = MaterialPurchase::with('materialCode')->get();
        $sites = WorkingSite::all();

        $query = MaterialConsume::with(['purchase.materialCode', 'fromSite', 'toSite']);

        if ($request->search) {
            $query->whereHas('purchase.materialCode', function($q) use ($request) {
                $q->where('material_name', 'like', '%' . $request->search . '%');
                $q->orWhere('material_unique_id', 'like', '%' . $request->search . '%');
            });
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
            'use_now' => 'required|in:0,1',
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
