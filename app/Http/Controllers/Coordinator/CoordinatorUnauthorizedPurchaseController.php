<?php

namespace App\Http\Controllers\Coordinator;

use App\Http\Controllers\Controller;
use App\Models\UnauthorizedPurchase;
use App\Models\WorkingSite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CoordinatorUnauthorizedPurchaseController extends Controller
{
    public function sitePurchases(Request $request, $site_id)
    {
        // 1. Check if coordinator is assigned to this site
        $user = Auth::guard('web')->user();
        $coordinator = \App\Models\Coordinator::where('user_id', $user->id)->first();
        $assignedSitesIds = $coordinator ? $coordinator->assigned_sites_ids : [];
        
        if (!in_array($site_id, $assignedSitesIds ?? [])) {
            abort(403, 'Unauthorized access to this site.');
        }

        // Pass assigned sites for the dropdown filter to function correctly
        $sites = WorkingSite::whereIn('id', $assignedSitesIds ?? [])->get();
        $currentSite = WorkingSite::findOrFail($site_id);

        // Force query for this specific site
        $query = UnauthorizedPurchase::with(['site', 'user'])->where('working_site_id', $site_id);

        if ($request->filled('from_date')) {
            $query->whereDate('updated_at', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $query->whereDate('updated_at', '<=', $request->to_date);
        }

        if ($request->filled('role')) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('role', $request->role);
            });
        }

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        // Pass roles for the dropdown filter
        $roles = \App\Models\User::where('working_site_id', $site_id)->select('role')->distinct()->whereNotNull('role')->pluck('role');

        if ($request->has('export') && $request->export === 'excel') {
            return \App\Services\ExportService::exportToExcel(
                $query->orderBy('updated_at', 'desc'),
                'unauthorized_purchases_'.$currentSite->site_name.'.xlsx',
                function ($purchase) {
                    return [
                        'Approved Date' => $purchase->updated_at ? $purchase->updated_at->format('Y-m-d H:i') : '-',
                        'Purchase Date' => \Carbon\Carbon::parse($purchase->purchase_date)->format('Y-m-d'),
                        'Unique ID' => $purchase->unauthorized_unique_id,
                        'Product Name' => $purchase->product_name,
                        'Site' => $purchase->site->site_name ?? 'N/A',
                        'Purchased By' => $purchase->user->name ?? 'N/A',
                        'Total Amount' => $purchase->amount,
                    ];
                }
            );
        }

        $purchases = $query->orderBy('updated_at', 'desc')->paginate(10)->withQueryString();

        return view('admin.purchase.site_unauthorized_purchase', compact('sites', 'purchases', 'roles', 'currentSite'));
    }

    public function approvePurchase(Request $request, $id)
    {
        $purchase = UnauthorizedPurchase::findOrFail($id);

        // Check if coordinator is assigned to this site
        $user = Auth::guard('web')->user();
        $coordinator = \App\Models\Coordinator::where('user_id', $user->id)->first();
        $assignedSitesIds = $coordinator ? $coordinator->assigned_sites_ids : [];
        
        if (!in_array($purchase->working_site_id, $assignedSitesIds ?? [])) {
            abort(403, 'Unauthorized access to this transaction.');
        }

        $purchase->approval = 1;
        $purchase->save();

        // Find the corresponding transaction and approve it
        if ($purchase->user_id) {
            $wallet = \App\Models\Wallet::where('user_id', $purchase->user_id)->first();
            if ($wallet) {
                $transaction = \App\Models\Transaction::where('wallet_id', $wallet->id)
                    ->where('type', 'debit')
                    ->where('approval', 0)
                    ->where('note', 'Unauthorized Purchase: ' . $purchase->product_name)
                    ->where('amount', $purchase->amount)
                    ->whereDate('date', $purchase->purchase_date)
                    ->first();
                
                if ($transaction) {
                    $transaction->approval = 1;
                    $transaction->updated_at = now();
                    $transaction->save();
                }
            }
        }

        return back()->with('success', 'Unauthorized Purchase approved successfully.');
    }

    public function deletePurchase(Request $request, $id)
    {
        $purchase = UnauthorizedPurchase::findOrFail($id);

        // Check if coordinator is assigned to this site
        $user = Auth::guard('web')->user();
        $coordinator = \App\Models\Coordinator::where('user_id', $user->id)->first();
        $assignedSitesIds = $coordinator ? $coordinator->assigned_sites_ids : [];
        
        if (!in_array($purchase->working_site_id, $assignedSitesIds ?? [])) {
            abort(403, 'Unauthorized access to this transaction.');
        }

        $rejectionReason = $request->input('rejection_reason', 'No reason provided.');

        \Illuminate\Support\Facades\DB::transaction(function () use ($purchase) {
            if ($purchase->invoice_file) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($purchase->invoice_file);
            }

            // Refund logic
            if ($purchase->user_id) {
                $wallet = \App\Models\Wallet::firstOrCreate(
                    ['user_id' => $purchase->user_id],
                    ['current_balance' => 0]
                );

                $amount = $purchase->amount;
                $newBalance = $wallet->current_balance + $amount;

                $wallet->update(['current_balance' => $newBalance]);

                \App\Models\Transaction::create([
                    'wallet_id' => $wallet->id,
                    'date' => now(),
                    'amount' => $amount,
                    'note' => 'Refund for deleted unauthorized purchase (' . $purchase->unauthorized_unique_id . ')',
                    'type' => 'credit',
                    'pay_to' => 'System',
                    'pay_to_code' => 'Refund',
                    'balance_after' => $newBalance,
                ]);
            }

            $purchase->delete();
        });

        if ($purchase->user && $purchase->user->email) {
            $user = $purchase->user;
            $details = "Item: " . $purchase->item_name . " | Amount: ₹" . number_format($purchase->amount, 2) . " | Description: " . $purchase->description;
            \Illuminate\Support\Facades\Mail::to($user->email)->send(new \App\Mail\TransactionRejected($user->name, $details, $rejectionReason, 'Unauthorized Purchase'));
        }

        return back()->with('success', 'Unauthorized Purchase rejected, amount refunded, and user notified.');
    }
}
