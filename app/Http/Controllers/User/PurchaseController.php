<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\MaterialCode;
use App\Models\MaterialPurchase;
use App\Models\UnauthorizedPurchase;
use App\Models\Unit;
use App\Models\WorkingSite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class PurchaseController extends Controller
{
    public function index(Request $request)
    {
        $sites = WorkingSite::all();
        $materialCodes = MaterialCode::all();
        $units = Unit::where('status', 'active')->get();

        // 1. Fetch Authorized Purchases
        $authQuery = MaterialPurchase::with(['site', 'materialCode', 'unit'])
                    ->where('user_id', Auth::id());

        if ($request->search) {
            $authQuery->where(function($q) use ($request) {
                $q->where('product_name', 'like', '%' . $request->search . '%')
                  ->orWhere('party_name', 'like', '%' . $request->search . '%')
                  ->orWhere('invoice_no', 'like', '%' . $request->search . '%');
            });
        }
        if ($request->site_id) {
            $authQuery->where('working_site_id', $request->site_id);
        }
        if ($request->date) {
            $authQuery->whereDate('purchase_date', $request->date);
        }
        $authorizedPurchases = $authQuery->get();

        // Add type flag for unified view
        $authorizedPurchases->map(function ($item) {
            $item->purchase_type = 'authorized';
            $item->unique_id_display = $item->material_unique_id;
            return $item;
        });

        // 2. Fetch Unauthorized Purchases
        $unauthQuery = UnauthorizedPurchase::with(['site'])
                        ->where('user_id', Auth::id());

        if ($request->search) {
            $unauthQuery->where('product_name', 'like', '%' . $request->search . '%');
        }
        if ($request->site_id) {
            $unauthQuery->where('working_site_id', $request->site_id);
        }
        if ($request->date) {
            $unauthQuery->whereDate('purchase_date', $request->date);
        }
        $unauthorizedPurchases = $unauthQuery->get();

        // Add type flag for unified view
        $unauthorizedPurchases->map(function ($item) {
            $item->purchase_type = 'unauthorized';
            $item->unique_id_display = $item->unauthorized_unique_id;
            // Add missing properties for view compatibility
            $item->materialCode = null;
            $item->quantity = null;
            $item->unit = null;
            return $item;
        });

        // 3. Merge and Sort
        $allPurchases = $authorizedPurchases->merge($unauthorizedPurchases)
                                            ->sortByDesc('purchase_date')
                                            ->values();

        // Manual Pagination for merged collection
        $page = $request->input('page', 1);
        $perPage = 10;
        $purchases = new \Illuminate\Pagination\LengthAwarePaginator(
            $allPurchases->forPage($page, $perPage),
            $allPurchases->count(),
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        return view('user.purchase.index', compact('sites', 'materialCodes', 'units', 'purchases'));
    }

    public function create()
    {
        $site = Auth::user()->site;
        $materialCodes = MaterialCode::all();
        $units = Unit::where('status', 'active')->get();

        return view('user.purchase.create', compact('site', 'materialCodes', 'units'));
    }

    public function store(Request $request)
    {
        $isAuthorized = $request->input('purchase_type') === 'authorized';

        $rules = [
            'working_site_id' => 'required|exists:working_sites,id',
            'purchase_date' => $isAuthorized ? 'required|date' : 'required|date|after_or_equal:today',
            'product_name' => 'required|string|max:255',
            'amount' => 'required|numeric',
            'invoice_file' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'note' => 'nullable|string'
        ];

        if ($isAuthorized) {
            $rules = array_merge($rules, [
                'material_code_id' => 'required|exists:material_codes,id',
                'party_name' => 'required|string|max:255',
                'invoice_no' => 'required|string|max:255',
                'quantity' => 'required|numeric',
                'unit_id' => 'required|exists:units,id',
                'rate' => 'required|numeric',
                'gst_amount' => 'nullable|numeric',
            ]);
        }

        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), $rules, [
            'purchase_date.after_or_equal' => 'you cant choose past date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }
        $data = $request->except(['purchase_type']);

        if ($request->hasFile('invoice_file')) {
            $data['invoice_file'] = $request->file('invoice_file')->store('invoices', 'public');
        }

        if ($isAuthorized) {
            $data['user_id'] = Auth::id();
            $data['created_by'] = Auth::id();
            $data['type'] = 'user';
            MaterialPurchase::create($data);
        } else {
            $data['user_id'] = Auth::id();
            UnauthorizedPurchase::create($data);

            $wallet = \App\Models\Wallet::firstOrCreate(
                ['user_id' => Auth::id()],
                ['current_balance' => 0]
            );

            $newBalance = $wallet->current_balance - $data['amount'];
            $wallet->update(['current_balance' => $newBalance]);

            \App\Models\Transaction::create([
                'wallet_id' => $wallet->id,
                'date' => $data['purchase_date'],
                'amount' => $data['amount'],
                'note' => 'Unauthorized Purchase: ' . $data['product_name'],
                'type' => 'debit',
                'balance_after' => $newBalance,
            ]);
        }

        return redirect()->route('user.purchase.index')->with('success', 'Purchase recorded successfully.');
    }

    /*
    public function edit(Request $request, $id)
    {
        $type = $request->query('type');
        
        if ($type === 'unauthorized') {
            $purchase = UnauthorizedPurchase::where('id', $id)
                            ->where('user_id', Auth::id())
                            ->firstOrFail();
            $purchase->purchase_type = 'unauthorized';
        } else {
            $purchase = MaterialPurchase::where('id', $id)
                            ->where('created_by', Auth::id())
                            ->where('type', 'user')
                            ->firstOrFail();
            $purchase->purchase_type = 'authorized';
        }

        $site = Auth::user()->site;
        $materialCodes = MaterialCode::all();
        $units = Unit::where('status', 'active')->get();

        return view('user.purchase.edit', compact('purchase', 'site', 'materialCodes', 'units'));
    }

    public function update(Request $request, $id)
    {
        $type = $request->input('type');
        
        $rules = [
            'working_site_id' => 'required|exists:working_sites,id',
            'purchase_date' => 'required|date',
            'product_name' => 'required|string|max:255',
            'amount' => 'required|numeric',
            'invoice_file' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'note' => 'nullable|string'
        ];

        if ($type !== 'unauthorized') {
            $rules = array_merge($rules, [
                'material_code_id' => 'required|exists:material_codes,id',
                'party_name' => 'required|string|max:255',
                'invoice_no' => 'required|string|max:255',
                'quantity' => 'required|numeric',
                'unit_id' => 'required|exists:units,id',
                'rate' => 'required|numeric',
                'gst_amount' => 'nullable|numeric',
            ]);
        }

        $request->validate($rules);
        $data = $request->except(['purchase_type', 'type', '_method', '_token']);

        if ($type === 'unauthorized') {
            $purchase = UnauthorizedPurchase::where('id', $id)
                            ->where('user_id', Auth::id())
                            ->firstOrFail();
        } else {
            $purchase = MaterialPurchase::where('id', $id)
                            ->where('created_by', Auth::id())
                            ->where('type', 'user')
                            ->firstOrFail();
        }

        if ($request->hasFile('invoice_file')) {
            if ($purchase->invoice_file) {
                Storage::disk('public')->delete($purchase->invoice_file);
            }
            $data['invoice_file'] = $request->file('invoice_file')->store('invoices', 'public');
        }

        $purchase->update($data);

        return redirect()->route('user.purchase.index')->with('success', 'Purchase updated successfully.');
    }

    public function destroy(Request $request, $id)
    {
        $type = $request->query('type');

        if ($type === 'unauthorized') {
            $purchase = UnauthorizedPurchase::where('id', $id)
                            ->where('user_id', Auth::id())
                            ->firstOrFail();
        } else {
            $purchase = MaterialPurchase::where('id', $id)
                            ->where('created_by', Auth::id())
                            ->where('type', 'user')
                            ->firstOrFail();
        }

        if ($purchase->invoice_file) {
            Storage::disk('public')->delete($purchase->invoice_file);
        }
        $purchase->delete();

        return redirect()->back()->with('success', 'Purchase deleted successfully.');
    }
    */
}
