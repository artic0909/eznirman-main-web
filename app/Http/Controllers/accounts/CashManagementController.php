<?php

namespace App\Http\Controllers\Accounts;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CashManagementController extends Controller
{
    /**
     * Display a listing of all transactions in the system.
     */
    public function index(Request $request)
    {
        $query = Transaction::with(['wallet.user', 'accountcode', 'site']);

        // Apply Filters
        if ($request->filled('from_date')) {
            $query->whereDate('date', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $query->whereDate('date', '<=', $request->to_date);
        }

        if ($request->filled('role')) {
            $query->whereHas('wallet.user', function ($q) use ($request) {
                $q->where('role', $request->role);
            });
        }

        if ($request->filled('user_id')) {
            $query->whereHas('wallet', function ($q) use ($request) {
                $q->where('user_id', $request->user_id);
            });
        }

        // Calculate KPA Totals based on current filters
        $totalCredits = (clone $query)->where('type', 'credit')->sum('amount');
        $totalDebits = (clone $query)->where('type', 'debit')->sum('amount');

        // Handle Export
        if ($request->has('export')) {
            $exportData = $query->orderBy('created_at', 'desc')->get();
            $filename = "cash_management_transactions_" . date('Y-m-d_H-i-s') . ".csv";
            
            $headers = [
                "Content-type"        => "text/csv",
                "Content-Disposition" => "attachment; filename=$filename",
                "Pragma"              => "no-cache",
                "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
                "Expires"             => "0"
            ];
            
            $columns = ['Date', 'Time', 'User Name', 'User Code', 'Role', 'Account Code', 'Description', 'Pay To', 'Credit (In)', 'Debit (Out)', 'Balance After'];
            
            $callback = function() use($exportData, $columns) {
                $file = fopen('php://output', 'w');
                fputcsv($file, $columns);
                
                foreach ($exportData as $tx) {
                    $date = $tx->date ? $tx->date->format('d M Y') : $tx->created_at->format('d M Y');
                    $time = $tx->created_at->format('h:i A');
                    $userName = $tx->wallet && $tx->wallet->user ? $tx->wallet->user->name : 'N/A';
                    $userCode = $tx->wallet && $tx->wallet->user ? $tx->wallet->user->code : 'N/A';
                    $role = $tx->wallet && $tx->wallet->user ? ucfirst($tx->wallet->user->role) : 'N/A';
                    $accountCode = $tx->accountcode ? $tx->accountcode->name . ' (' . $tx->accountcode->code . ')' : 'N/A';
                    
                    $credit = $tx->type === 'credit' ? $tx->amount : 0;
                    $debit = $tx->type === 'debit' ? $tx->amount : 0;
                    $payTo = $tx->pay_to ? $tx->pay_to . ($tx->pay_to_code ? ' ('.$tx->pay_to_code.')' : '') : 'N/A';
                    
                    $note = $tx->note;
                    if (strpos($note, '—') !== false) {
                        $noteParts = explode('—', $note, 2);
                        $note = trim($noteParts[1] ?? '');
                    }
                    
                    fputcsv($file, [$date, $time, $userName, $userCode, $role, $accountCode, $note, $payTo, $credit, $debit, $tx->balance_after]);
                }
                
                fclose($file);
            };
            
            return response()->stream($callback, 200, $headers);
        }

        // Get paginated transactions
        $transactions = $query->orderBy('created_at', 'desc')->paginate(20)->withQueryString();

        // Get roles for dropdown
        $roles = User::select('role')->distinct()->whereNotNull('role')->pluck('role');

        return view('account.cashmanagement.index', compact('transactions', 'totalCredits', 'totalDebits', 'roles'));
    }

    /**
     * AJAX endpoint to get users by role
     */
    public function getUsersByRole(Request $request)
    {
        $users = User::where('role', $request->role)
            ->with('wallet:id,user_id,current_balance')
            ->select('id', 'name', 'code')
            ->orderBy('name', 'asc')
            ->get();
            
        return response()->json($users);
    }

    /**
     * Show the form to send money to a user.
     */
    public function sendForm()
    {
        // Get all distinct roles for the dropdown
        $roles = User::select('role')->distinct()->whereNotNull('role')->pluck('role');

        return view('account.send.form', compact('roles'));
    }

    /**
     * Process sending money to a user.
     */
    public function sendMoney(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'amount' => 'required|numeric|min:0.01',
        ]);

        try {
            DB::beginTransaction();

            // Find the user
            $user = User::findOrFail($request->user_id);

            // Find or create their wallet
            $wallet = Wallet::firstOrCreate(
                ['user_id' => $user->id],
                ['current_balance' => 0]
            );

            $amount = $request->amount;
            $newBalance = $wallet->current_balance + $amount;

            // Update wallet balance
            $wallet->update(['current_balance' => $newBalance]);

            // Create a transaction record
            Transaction::create([
                'wallet_id' => $wallet->id,
                'date' => now(),
                'amount' => $amount,
                'note' => 'Received from Head Office',
                'type' => 'credit',
                'pay_to' => 'from',
                'pay_to_code' => 'Head Office',
                'balance_after' => $newBalance,
                'site_id' => $user->working_site_id,
            ]);

            DB::commit();

            return redirect()->route('account.cashmanagement.send')->with('success', 'Money successfully sent to ' . $user->name . '.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }

    public function refund($id)
    {
        try {
            DB::beginTransaction();

            $transaction = Transaction::findOrFail($id);
            $wallet = Wallet::findOrFail($transaction->wallet_id);

            $amount = $transaction->amount;

            if ($transaction->type === 'debit') {
                $newBalance = $wallet->current_balance + $amount;
                // Update wallet balance
                $wallet->update(['current_balance' => $newBalance]);

                // Create a refund transaction
                Transaction::create([
                    'wallet_id' => $wallet->id,
                    'date' => now(),
                    'amount' => $amount,
                    'note' => 'Refund for deleted transaction',
                    'type' => 'credit',
                    'pay_to' => $transaction->pay_to,
                    'pay_to_code' => $transaction->pay_to_code,
                    'balance_after' => $newBalance,
                ]);
            } elseif ($transaction->type === 'credit') {
                $newBalance = $wallet->current_balance - $amount;
                // Update wallet balance
                $wallet->update(['current_balance' => $newBalance]);
                
                // No transaction record is auto-stored for deducted credits
            } else {
                return back()->with('error', 'Only debit and credit transactions can be deleted.');
            }

            // Delete original transaction
            $transaction->delete();

            DB::commit();

            return back()->with('success', 'Transaction deleted successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }
}
