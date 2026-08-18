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
        $sites = WorkingSite::query();
        if (\Illuminate\Support\Facades\Auth::guard('web')->check()) {
            $user = \Illuminate\Support\Facades\Auth::guard('web')->user();
            $coordinator = \App\Models\Coordinator::where('user_id', $user->id)->first();
            if ($coordinator && $coordinator->assigned_sites_ids) {
                $sites->whereIn('id', $coordinator->assigned_sites_ids);
            } else {
                $sites->whereIn('id', []); // Coordinator with no sites
            }
        }
        $sites = $sites->get();
        
        $materialCodes = MaterialCode::all();
        $units = Unit::where('status', 'active')->get();

        $query = MaterialPurchase::with(['site', 'materialCode', 'unit']);

        if (\Illuminate\Support\Facades\Auth::guard('web')->check()) {
            $user = \Illuminate\Support\Facades\Auth::guard('web')->user();
            $coordinator = \App\Models\Coordinator::where('user_id', $user->id)->first();
            if ($coordinator && $coordinator->assigned_sites_ids) {
                $query->whereIn('working_site_id', $coordinator->assigned_sites_ids);
            } else {
                $query->whereIn('working_site_id', []); // Hide all
            }
        }

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

        if ($request->has('export') && $request->export === 'excel') {
            return \App\Services\ExportService::exportToExcel(
                $query->latest(),
                'material_purchases_export.xlsx',
                function ($purchase) {
                    return [
                        'Date' => \Carbon\Carbon::parse($purchase->purchase_date)->format('Y-m-d'),
                        'Site' => $purchase->site->site_name ?? 'N/A',
                        'Material Code' => $purchase->materialCode->code ?? 'N/A',
                        'Product Name' => $purchase->product_name,
                        'Party Name' => $purchase->party_name,
                        'Invoice No' => $purchase->invoice_no,
                        'Quantity' => $purchase->quantity . ' ' . ($purchase->unit->name ?? ''),
                        'Rate' => $purchase->rate,
                        'Amount' => $purchase->amount,
                    ];
                }
            );
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

        if ($request->hasFile('invoice_file')) {
            $data['invoice_file'] = $request->file('invoice_file')->store('invoices', 'public');
        }

        if (\Illuminate\Support\Facades\Auth::guard('web')->check()) {
            $data['created_by'] = \Illuminate\Support\Facades\Auth::guard('web')->id();
            $data['creator_type'] = 'coordinator';
            // Wait, does material purchase also have user_id? Yes, earlier migrations had user_id
            $data['user_id'] = \Illuminate\Support\Facades\Auth::guard('web')->id();
        } else if (\Illuminate\Support\Facades\Auth::guard('admin')->check()) {
            $data['created_by'] = \Illuminate\Support\Facades\Auth::guard('admin')->id();
            $data['creator_type'] = 'admin';
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
            'note' => 'nullable|string',
        ]);

        $data = $request->all();

        if ($request->hasFile('invoice_file')) {
            if ($purchase->invoice_file) {
                Storage::disk('public')->delete($purchase->invoice_file);
            }
            $data['invoice_file'] = $request->file('invoice_file')->store('invoices', 'public');
        }

        if (\Illuminate\Support\Facades\Auth::guard('web')->check()) {
            $data['updated_by'] = \Illuminate\Support\Facades\Auth::guard('web')->id();
            $data['updater_type'] = 'coordinator';
        } else if (\Illuminate\Support\Facades\Auth::guard('admin')->check()) {
            $data['updated_by'] = \Illuminate\Support\Facades\Auth::guard('admin')->id();
            $data['updater_type'] = 'admin';
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
