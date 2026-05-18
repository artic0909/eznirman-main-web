<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Wallet;
use App\Models\Transaction;
use App\Models\Accountcode;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // 1. Ensure user has a wallet
        $wallet = $user->wallet()->firstOrCreate([], [
            'current_balance' => 48250.00 // Give initial balance for beautiful demonstration
        ]);

        // 2. Calculate monthly statistics
        $startOfMonth = now()->startOfMonth();
        $endOfMonth = now()->endOfMonth();

        $transactionsThisMonth = $wallet->transactions()
            ->whereBetween('date', [$startOfMonth, $endOfMonth])
            ->get();

        $totalTransactionsCount = $transactionsThisMonth->count();
        $totalTransactionsAmount = $transactionsThisMonth->sum('amount');

        // Total debit (spent) this month for the progress bar
        $monthlySpend = $transactionsThisMonth->where('type', 'debit')->sum('amount');

        // 3. Retrieve recent transactions
        $recentTransactions = $wallet->transactions()
            ->with('accountcode')
            ->orderBy('date', 'desc')
            ->take(15)
            ->get();

        // 4. Retrieve all account codes for the transfer/request forms dropdown
        $accountCodes = Accountcode::all();

        // 5. Retrieve workers list for select2 dropdown
        $workers = \App\Models\User::where('role', 'worker')->get();

        return view('user.dashbaord.index', compact(
            'wallet',
            'totalTransactionsCount',
            'totalTransactionsAmount',
            'monthlySpend',
            'recentTransactions',
            'accountCodes',
            'workers'
        ));
    }

    /**
     * Store new transaction (transfer or request) via AJAX or standard form submission
     */
    public function storeTransaction(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
            'type' => 'required|in:credit,debit',
            'note' => 'nullable|string|max:255',
            'accountcode_id' => 'nullable|exists:accountcodes,id',
            'date' => 'nullable|date',
            'pay_to' => 'nullable|in:worker,contractor,others',
            'pay_to_code' => 'nullable|string',
        ]);

        $user = Auth::user();
        $wallet = $user->wallet;

        if ($request->type === 'debit' && $wallet->current_balance < $request->amount) {
            return response()->json([
                'success' => false,
                'message' => 'Insufficient wallet balance.'
            ], 422);
        }

        // Update wallet balance first to ensure exact running state
        if ($request->type === 'credit') {
            $wallet->increment('current_balance', $request->amount);
        } else {
            $wallet->decrement('current_balance', $request->amount);
        }

        $balanceAfter = $wallet->fresh()->current_balance;

        $note = $request->note;
        if ($request->type === 'debit' && $request->pay_to) {
            $payeeLabel = ucfirst($request->pay_to);
            $payeeCode = $request->pay_to_code ? " ({$request->pay_to_code})" : "";
            $note = "Disbursal to {$payeeLabel}{$payeeCode} — " . ($request->note ?? 'General Disbursal');
        }

        // Create transaction with saved running balance_after
        $transaction = $wallet->transactions()->create([
            'date' => $request->date ? \Carbon\Carbon::parse($request->date) : now(),
            'accountcode_id' => $request->accountcode_id,
            'amount' => $request->amount,
            'note' => $note ?? ($request->type === 'credit' ? 'Fund Allocation Request' : 'Standard Transfer'),
            'type' => $request->type,
            'balance_after' => $balanceAfter,
            'pay_to' => $request->pay_to,
            'pay_to_code' => $request->pay_to_code,
        ]);

        return response()->json([
            'success' => true,
            'message' => $request->type === 'credit' ? 'Fund request submitted successfully.' : 'Funds transferred successfully.',
            'balance' => number_format($wallet->current_balance, 2),
            'transaction' => $transaction
        ]);
    }
}
