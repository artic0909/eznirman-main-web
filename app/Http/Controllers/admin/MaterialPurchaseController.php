<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MaterialCode;
use App\Models\MaterialPurchase;
use App\Models\Unit;
use App\Models\WorkingSite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MaterialPurchaseController extends Controller
{
    public function index(Request $request)
    {
        $sites = WorkingSite::all();
        $materialCodes = MaterialCode::all();
        $units = Unit::where('status', 'active')->get();

        $query = MaterialPurchase::with(['site', 'materialCode', 'unit']);

        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('product_name', 'like', '%' . $request->search . '%')
                  ->orWhere('party_name', 'like', '%' . $request->search . '%')
                  ->orWhere('invoice_no', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->site_id) {
            $query->where('working_site_id', $request->site_id);
        }

        if ($request->date) {
            $query->whereDate('purchase_date', $request->date);
        }

        $purchases = $query->latest()->paginate(10)->withQueryString();

        return view('admin.purchase.material-purchase', compact('sites', 'materialCodes', 'units', 'purchases'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'working_site_id' => 'required|exists:working_sites,id',
            'purchase_date' => 'required|date',
            'material_code_id' => 'required|exists:material_codes,id',
            'product_name' => 'required|string|max:255',
            'party_name' => 'required|string|max:255',
            'invoice_no' => 'required|string|max:255',
            'quantity' => 'required|numeric',
            'unit_id' => 'required|exists:units,id',
            'rate' => 'required|numeric',
            'gst_amount' => 'nullable|numeric',
            'amount' => 'required|numeric',
            'invoice_file' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        $data = $request->all();

        // Generate Unique Material ID (MTRC-0001 format)
        $lastPurchase = MaterialPurchase::orderBy('id', 'desc')->first();
        $nextId = $lastPurchase ? $lastPurchase->id + 1 : 1;
        $data['material_unique_id'] = 'MTRC-' . str_pad($nextId, 4, '0', STR_PAD_LEFT);

        if ($request->hasFile('invoice_file')) {
            $data['invoice_file'] = $request->file('invoice_file')->store('invoices', 'public');
        }

        MaterialPurchase::create($data);

        return redirect()->back()->with('success', 'Material Purchase recorded successfully.');
    }

    public function update(Request $request, $id)
    {
        $purchase = MaterialPurchase::findOrFail($id);

        $request->validate([
            'working_site_id' => 'required|exists:working_sites,id',
            'purchase_date' => 'required|date',
            'material_code_id' => 'required|exists:material_codes,id',
            'product_name' => 'required|string|max:255',
            'party_name' => 'required|string|max:255',
            'invoice_no' => 'required|string|max:255',
            'quantity' => 'required|numeric',
            'unit_id' => 'required|exists:units,id',
            'rate' => 'required|numeric',
            'gst_amount' => 'nullable|numeric',
            'amount' => 'required|numeric',
            'invoice_file' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        $data = $request->all();

        if ($request->hasFile('invoice_file')) {
            if ($purchase->invoice_file) {
                Storage::disk('public')->delete($purchase->invoice_file);
            }
            $data['invoice_file'] = $request->file('invoice_file')->store('invoices', 'public');
        }

        $purchase->update($data);

        return redirect()->back()->with('success', 'Material Purchase updated successfully.');
    }

    public function destroy($id)
    {
        $purchase = MaterialPurchase::findOrFail($id);
        if ($purchase->invoice_file) {
            Storage::disk('public')->delete($purchase->invoice_file);
        }
        $purchase->delete();

        return redirect()->back()->with('success', 'Material Purchase deleted successfully.');
    }
}
