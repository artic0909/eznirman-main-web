<?php

namespace App\Http\Controllers\accounts;

use App\Http\Controllers\Controller;
use App\Models\UnauthorizedPurchase;
use App\Models\WorkingSite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class UnauthorizedPurchaseController extends Controller
{
    public function index(Request $request)
    {
        $sites = WorkingSite::all();

        $query = UnauthorizedPurchase::with(['site', 'user']);

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

        // Only show approved unauthorized purchases
        $query->where('approval', 1);

        $purchases = $query->orderBy('updated_at', 'desc')->paginate(10)->withQueryString();
        
        $roles = \App\Models\User::select('role')->distinct()->whereNotNull('role')->pluck('role');

        return view('account.purchase.unauthorised', compact('sites', 'purchases', 'roles'));
    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            $purchase = UnauthorizedPurchase::findOrFail($id);

            if ($purchase->invoice_file) {
                Storage::disk('public')->delete($purchase->invoice_file);
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

            DB::commit();
            return redirect()->back()->with('success', 'Unauthorized Purchase deleted and amount refunded successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }

    public function bulkDelete(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:unauthorized_purchases,id'
        ]);

        try {
            DB::beginTransaction();
            
            $purchases = UnauthorizedPurchase::whereIn('id', $request->ids)->get();

            foreach ($purchases as $purchase) {
                if ($purchase->invoice_file) {
                    Storage::disk('public')->delete($purchase->invoice_file);
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
            }

            DB::commit();
            return redirect()->back()->with('success', 'Selected Unauthorized Purchases deleted and amount refunded successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }
}
