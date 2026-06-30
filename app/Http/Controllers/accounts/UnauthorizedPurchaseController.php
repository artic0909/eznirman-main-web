<?php

namespace App\Http\Controllers\accounts;

use App\Http\Controllers\Controller;
use App\Models\UnauthorizedPurchase;
use App\Models\WorkingSite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UnauthorizedPurchaseController extends Controller
{
    public function index(Request $request)
    {
        $sites = WorkingSite::all();

        $query = UnauthorizedPurchase::with(['site', 'user']);

        if ($request->filled('from_date')) {
            $query->whereDate('purchase_date', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $query->whereDate('purchase_date', '<=', $request->to_date);
        }

        if ($request->filled('role')) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('role', $request->role);
            });
        }

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        $purchases = $query->latest()->paginate(10)->withQueryString();
        
        $roles = \App\Models\User::select('role')->distinct()->whereNotNull('role')->pluck('role');

        return view('account.purchase.unauthorised', compact('sites', 'purchases', 'roles'));
    }

    public function destroy($id)
    {
        $purchase = UnauthorizedPurchase::findOrFail($id);

        if ($purchase->invoice_file) {
            Storage::disk('public')->delete($purchase->invoice_file);
        }

        $purchase->delete();

        return redirect()->back()->with('success', 'Unauthorized Purchase deleted successfully.');
    }

    public function bulkDelete(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:unauthorized_purchases,id'
        ]);

        $purchases = UnauthorizedPurchase::whereIn('id', $request->ids)->get();

        foreach ($purchases as $purchase) {
            if ($purchase->invoice_file) {
                Storage::disk('public')->delete($purchase->invoice_file);
            }
            $purchase->delete();
        }

        return redirect()->back()->with('success', 'Selected Unauthorized Purchases deleted successfully.');
    }
}
