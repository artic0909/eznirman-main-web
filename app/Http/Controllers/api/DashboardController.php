<?php

namespace App\Http\Controllers\api;

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
    /**
     * Store new transaction (transfer or request)
     */
    public function storeTransaction(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
            'type' => 'required|in:credit,debit',
            'note' => 'nullable|string|max:255',
            'accountcode_id' => 'nullable|exists:accountcodes,id',
            'date' => 'nullable|date',
            'pay_to' => 'nullable|in:worker,contractor,others,from',
            'pay_to_code' => 'nullable|string',
        ]);

        $user = $request->user();
        $wallet = $user->wallet;

        if (!$wallet) {
            $wallet = $user->wallet()->create(['current_balance' => 0]);
        }

        if ($request->type === 'debit' && $wallet->current_balance < $request->amount) {
            return response()->json([
                'success' => false,
                'message' => 'Insufficient wallet balance.'
            ], 422);
        }

        $note = $request->note;
        if ($request->type === 'debit' && $request->pay_to) {
            $payeeLabel = ucfirst($request->pay_to);
            $payeeCode = $request->pay_to_code ? " ({$request->pay_to_code})" : "";
            $note = "Disbursal to {$payeeLabel}{$payeeCode} — " . ($request->note ?? 'General Disbursal');
        }

        $transaction = \Illuminate\Support\Facades\DB::transaction(function () use ($wallet, $request, $note) {
            if ($request->type === 'credit') {
                $wallet->increment('current_balance', $request->amount);
            } else {
                $wallet->decrement('current_balance', $request->amount);
            }

            $balanceAfter = $wallet->fresh()->current_balance;

            return $wallet->transactions()->create([
                'date' => $request->date ? \Carbon\Carbon::parse($request->date) : now(),
                'accountcode_id' => $request->accountcode_id,
                'amount' => $request->amount,
                'note' => $note ?? ($request->type === 'credit' ? 'Fund Allocation Request' : 'Standard Transfer'),
                'type' => $request->type,
                'balance_after' => $balanceAfter,
                'pay_to' => $request->pay_to,
                'pay_to_code' => $request->pay_to_code,
            ]);
        });

        return response()->json([
            'success' => true,
            'message' => $request->type === 'credit' ? 'Fund request submitted successfully.' : 'Funds transferred successfully.',
            'balance' => $wallet->current_balance,
            'transaction' => $transaction
        ]);
    }

    public function credits(Request $request)
    {
        $user = $request->user();
        $wallet = $user->wallet()->firstOrCreate([], ['current_balance' => 0]);
        
        $credits = $wallet->transactions()
            ->with('accountcode')
            ->where('type', 'credit')
            ->orderBy('id', 'desc')
            ->paginate(10);

        return response()->json($credits);
    }

    public function debits(Request $request)
    {
        $user = $request->user();
        $wallet = $user->wallet()->firstOrCreate([], ['current_balance' => 0]);
        
        $debits = $wallet->transactions()
            ->with('accountcode')
            ->where('type', 'debit')
            ->orderBy('id', 'desc')
            ->paginate(10);

        return response()->json($debits);
    }

    public function transactions(Request $request)
    {
        $user = $request->user();
        $wallet = $user->wallet()->firstOrCreate([], ['current_balance' => 0]);
        
        $transactions = $wallet->transactions()
            ->with('accountcode')
            ->orderBy('id', 'desc')
            ->paginate(15);

        return response()->json($transactions);
    }

    public function profile(Request $request)
    {
        $user = $request->user()->load(['site', 'designation', 'skill']);
        return response()->json(['user' => $user]);
    }

    public function profileUpdate(Request $request)
    {
        $user = $request->user();

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

        return response()->json([
            'success' => true,
            'message' => 'Profile updated successfully.',
            'user' => $user
        ]);
    }

    public function sendMoneyData(Request $request)
    {
        $accountCodes = Accountcode::all();
        $workers = \App\Models\User::where('role', 'worker')->get();

        return response()->json([
            'accountCodes' => $accountCodes,
            'workers' => $workers
        ]);
    }
}
