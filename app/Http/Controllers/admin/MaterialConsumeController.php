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
            });
        }

        if ($request->site_id) {
            $query->where('from_site_id', $request->site_id);
        }

        $consumes = $query->latest()->paginate(10)->withQueryString();

        return view('admin.purchase.material-consume', compact('purchases', 'sites', 'consumes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'material_purchase_id' => 'required|exists:material_purchases,id',
            'consume_date' => 'required|date',
            'used_quantity' => 'required|numeric',
            'use_now' => 'required|in:0,1',
            'from_site_id' => 'nullable|exists:working_sites,id',
            'to_site_id' => 'nullable|required_if:use_now,1|exists:working_sites,id',
        ]);

        $purchase = MaterialPurchase::findOrFail($request->material_purchase_id);
        
        // Calculate available quantity
        $totalConsumed = MaterialConsume::where('material_purchase_id', $purchase->id)->sum('used_quantity');
        $availableBefore = $purchase->quantity - $totalConsumed;

        if ($request->used_quantity > $availableBefore) {
            return redirect()->back()->with('error', 'Insufficient quantity available. Current balance: ' . $availableBefore);
        }

        $data = $request->all();
        $data['quantity_current'] = $availableBefore;
        $data['available_quantity'] = $availableBefore - $request->used_quantity;
        $data['unit'] = $purchase->unit->name;

        MaterialConsume::create($data);

        return redirect()->back()->with('success', 'Material usage/transfer recorded successfully.');
    }

    public function destroy($id)
    {
        $consume = MaterialConsume::findOrFail($id);
        $consume->delete();

        return redirect()->back()->with('success', 'Consume record deleted successfully.');
    }
}
