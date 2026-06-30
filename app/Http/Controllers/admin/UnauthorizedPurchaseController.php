<?php

namespace App\Http\Controllers\Admin;

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

        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('product_name', 'like', '%' . $request->search . '%')
                  ->orWhere('unauthorized_unique_id', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->site_id) {
            $query->where('working_site_id', $request->site_id);
        }

        if ($request->date) {
            $query->whereDate('purchase_date', $request->date);
        }

        $purchases = $query->latest()->paginate(10)->withQueryString();

        return view('admin.purchase.unauthorized_purchase', compact('sites', 'purchases'));
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
}
