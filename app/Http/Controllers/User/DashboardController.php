<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
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
            'current_balance' => 0 
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

        // 3. Retrieve recent transactions (Last 5)
        $recentTransactions = $wallet->transactions()
            ->with('accountcode')
            ->orderBy('id', 'desc')
            ->take(5)
            ->get();

        // 4. Retrieve all account codes for the transfer/request forms dropdown
        $accountCodes = Accountcode::all();

        // 5. Retrieve workers list for select2 dropdown
        $workers = \App\Models\User::where('role', 'worker')->get();

        return view('user.dashboard.index', compact(
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
        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'amount' => 'required|numeric|min:1',
            'type' => 'required|in:credit,debit',
            'note' => 'nullable|string|max:255',
            'accountcode_id' => 'nullable|exists:accountcodes,id',
            'date' => 'nullable|date|after_or_equal:today',
            'pay_to' => 'nullable|in:worker,contractor,others,from',
            'pay_to_code' => 'nullable|string',
        ], [
            'date.after_or_equal' => 'you cant choose past date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }

        $user = Auth::user();
        $wallet = $user->wallet;

        if (!$wallet) {
            $wallet = $user->wallet()->create(['current_balance' => 0]);
        }

        $note = $request->note;
        if ($request->type === 'debit' && $request->pay_to) {
            $payeeLabel = ucfirst($request->pay_to);
            $payeeCode = $request->pay_to_code ? " ({$request->pay_to_code})" : "";
            $note = "Disbursal to {$payeeLabel}{$payeeCode} — " . ($request->note ?? 'General Disbursal');
        }

        $transaction = \Illuminate\Support\Facades\DB::transaction(function () use ($wallet, $request, $note, $user) {
            // Update wallet balance first to ensure exact running state
            if ($request->type === 'credit') {
                $wallet->increment('current_balance', $request->amount);
            } else {
                $wallet->decrement('current_balance', $request->amount);
            }

            $balanceAfter = $wallet->fresh()->current_balance;

            // Create transaction with saved running balance_after
            return $wallet->transactions()->create([
                'date' => $request->date ? \Carbon\Carbon::parse($request->date) : now(),
                'accountcode_id' => $request->accountcode_id,
                'amount' => $request->amount,
                'note' => $note ?? ($request->type === 'credit' ? 'Fund Allocation Request' : 'Standard Transfer'),
                'type' => $request->type,
                'balance_after' => $balanceAfter,
                'pay_to' => $request->pay_to,
                'pay_to_code' => $request->pay_to_code,
                'site_id' => $user->working_site_id,
                'approval' => $request->type === 'debit' ? 0 : null,
            ]);
        });

        return response()->json([
            'success' => true,
            'message' => $request->type === 'credit' ? 'Fund request submitted successfully.' : 'Funds transferred successfully.',
            'balance' => number_format($wallet->current_balance, 2),
            'transaction' => $transaction
        ]);
    }

    /**
     * Show Credits page
     */
    public function credits()
    {
        $user = Auth::user();
        $wallet = $user->wallet()->firstOrCreate([]);
        
        $credits = $wallet->transactions()
            ->with('accountcode')
            ->where('type', 'credit')
            ->orderBy('id', 'desc')
            ->paginate(10);

        return view('user.dashboard.credits', compact('wallet', 'credits'));
    }

    /**
     * Show Debits page
     */
    public function debits()
    {
        $user = Auth::user();
        $wallet = $user->wallet()->firstOrCreate([]);
        
        $debits = $wallet->transactions()
            ->with('accountcode')
            ->where('type', 'debit')
            ->orderBy('id', 'desc')
            ->paginate(10);

        return view('user.dashboard.debits', compact('wallet', 'debits'));
    }

    /**
     * Show All Transactions page
     */
    public function transactions()
    {
        $user = Auth::user();
        $wallet = $user->wallet()->firstOrCreate([]);
        
        $transactions = $wallet->transactions()
            ->with('accountcode')
            ->orderBy('id', 'desc')
            ->paginate(15);

        return view('user.dashboard.transactions', compact('wallet', 'transactions'));
    }

    /**
     * Show Profile Settings page
     */
    public function profile()
    {
        $user = Auth::user();
        return view('user.dashboard.profile', compact('user'));
    }

    /**
     * Update Profile Settings
     */
    public function profileUpdate(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'mobile' => 'nullable|string|max:20',
            'current_address' => 'nullable|string|max:500',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'password' => 'nullable|string|min:6|confirmed',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->mobile = $request->mobile;
        $user->current_address = $request->current_address;

        if ($request->hasFile('profile_image')) {
            // Delete old profile image if exists
            if ($user->profile_image) {
                Storage::disk('public')->delete($user->profile_image);
            }
            $path = $request->file('profile_image')->store('profiles', 'public');
            $user->profile_image = $path;
        }

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->back()->with('success', 'Profile updated successfully.');
    }

    /**
     * Show Send Money page
     */
    public function sendMoney()
    {
        $user = Auth::user();
        $wallet = $user->wallet()->firstOrCreate([]);
        $accountCodes = Accountcode::all();
        $workers = \App\Models\User::where('role', 'worker')->get();

        return view('user.wallet.sendmoney', compact('wallet', 'accountCodes', 'workers'));
    }

    /**
     * Show Add Money page
     */
    public function addMoney()
    {
        $user = Auth::user();
        $wallet = $user->wallet()->firstOrCreate([]);

        return view('user.wallet.addmoney', compact('wallet'));
    }
}
