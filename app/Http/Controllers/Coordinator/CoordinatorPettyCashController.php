<?php

namespace App\Http\Controllers\Coordinator;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\User;
use App\Models\WorkingSite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CoordinatorPettyCashController extends Controller
{
    /**
     * Display a listing of transactions for a specific site.
     */
    public function siteTransactions(Request $request, $site_id)
    {
        // 1. Check if coordinator is assigned to this site
        $user = Auth::guard('web')->user();
        $coordinator = \App\Models\Coordinator::where('user_id', $user->id)->first();
        $assignedSitesIds = $coordinator ? $coordinator->assigned_sites_ids : [];
        
        if (!in_array($site_id, $assignedSitesIds ?? [])) {
            abort(403, 'Unauthorized access to this site.');
        }

        $site = WorkingSite::findOrFail($site_id);

        // 2. Fetch users assigned to this site
        $siteUsersIds = User::where('working_site_id', $site_id)->pluck('id');

        // 3. Fetch transactions for this site strictly based on transaction's site_id
        // Exclude unauthorized purchases since they have their own dedicated section
        $query = Transaction::with(['wallet.user', 'accountcode', 'site'])
            ->where('site_id', $site_id)
            ->where('note', 'not like', 'Unauthorized Purchase:%')
            ->where('note', 'not like', 'Refund for deleted unauthorized purchase%');

        // Apply Filters
        if ($request->filled('from_date')) {
            $query->whereDate('updated_at', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $query->whereDate('updated_at', '<=', $request->to_date);
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

        // Calculate Totals based on current filters, excluding refunds
        $totalCredits = (clone $query)->where('type', 'credit')->where('note', 'not like', 'Refund%')->sum('amount');
        $totalDebits = (clone $query)->where('type', 'debit')->where('note', 'not like', 'Refund%')->sum('amount');

        // Handle Export
        if ($request->has('export')) {
            $exportData = $query->orderBy('updated_at', 'desc')->get();
            $filename = "petty_cash_".$site->site_name."_" . date('Y-m-d_H-i-s') . ".csv";
            
            $headers = [
                "Content-type"        => "text/csv",
                "Content-Disposition" => "attachment; filename=$filename",
                "Pragma"              => "no-cache",
                "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
                "Expires"             => "0"
            ];
            
            $columns = ['Approved Date', 'Transaction Date', 'Time', 'User Name', 'User Code', 'Role', 'Account Code', 'Description', 'Pay To', 'Credit (In)', 'Debit (Out)', 'Balance After'];
            
            $callback = function() use($exportData, $columns) {
                $file = fopen('php://output', 'w');
                fputcsv($file, $columns);
                
                foreach ($exportData as $tx) {
                    $approvedDate = $tx->updated_at->format('d M Y h:i A');
                    $date = $tx->date ? $tx->date->format('d M Y') : $tx->created_at->format('d M Y');
                    $time = $tx->date ? $tx->date->format('h:i A') : $tx->created_at->format('h:i A');
                    $userName = $tx->wallet && $tx->wallet->user ? $tx->wallet->user->name : 'N/A';
                    $userCode = $tx->wallet && $tx->wallet->user ? $tx->wallet->user->code : 'N/A';
                    $role = $tx->wallet && $tx->wallet->user ? ucfirst($tx->wallet->user->role) : 'N/A';
                    $accountCode = $tx->accountcode ? $tx->accountcode->name : 'N/A';
                    
                    $credit = $tx->type === 'credit' ? $tx->amount : 0;
                    $debit = $tx->type === 'debit' ? $tx->amount : 0;
                    $payTo = $tx->pay_to ? $tx->pay_to . ($tx->pay_to_code ? ' ('.$tx->pay_to_code.')' : '') : 'N/A';
                    
                    $note = $tx->note;
                    if (strpos($note, '—') !== false) {
                        $noteParts = explode('—', $note, 2);
                        $note = trim($noteParts[1] ?? '');
                    }
                    
                    fputcsv($file, [$approvedDate, $date, $time, $userName, $userCode, $role, $accountCode, $note, $payTo, $credit, $debit, $tx->balance_after]);
                }
                
                fclose($file);
            };
            
            return response()->stream($callback, 200, $headers);
        }

        // Get paginated transactions
        $transactions = $query->orderBy('updated_at', 'desc')->paginate(20)->withQueryString();

        // Get roles for dropdown (only from users assigned to this site)
        $roles = User::where('working_site_id', $site_id)->select('role')->distinct()->whereNotNull('role')->pluck('role');
        
        // Also provide site users for the user filter dropwdown directly
        $siteUsers = User::where('working_site_id', $site_id)->select('id', 'name', 'code')->orderBy('name')->get();

        return view('admin.pettycash.site_transactions', compact('transactions', 'totalCredits', 'totalDebits', 'roles', 'site', 'siteUsers'));
    }

    /**
     * AJAX endpoint to get users by role for a specific site
     */
    public function getUsersByRole(Request $request, $site_id)
    {
        // 1. Check if coordinator is assigned to this site
        $user = Auth::guard('web')->user();
        $coordinator = \App\Models\Coordinator::where('user_id', $user->id)->first();
        $assignedSitesIds = $coordinator ? $coordinator->assigned_sites_ids : [];
        
        if (!in_array($site_id, $assignedSitesIds ?? [])) {
            return response()->json([], 403);
        }

        $users = User::where('working_site_id', $site_id)
            ->when($request->role, function($query, $role) {
                return $query->where('role', $role);
            })
            ->with('wallet:id,user_id,current_balance')
            ->select('id', 'name', 'code')
            ->orderBy('name', 'asc')
            ->get();
            
        return response()->json($users);
    }
    /**
     * Approve a debit transaction.
     */
    public function approveTransaction(Request $request, $id)
    {
        $transaction = Transaction::findOrFail($id);

        // Check if coordinator is assigned to this site
        $user = Auth::guard('web')->user();
        $coordinator = \App\Models\Coordinator::where('user_id', $user->id)->first();
        $assignedSitesIds = $coordinator ? $coordinator->assigned_sites_ids : [];
        
        if (!in_array($transaction->site_id, $assignedSitesIds ?? [])) {
            abort(403, 'Unauthorized access to this transaction.');
        }

        $transaction->approval = 1;
        $transaction->save();

        // Also flash success message to session for sweetalert or standard alert
        return back()->with('success', 'Transaction approved successfully.');
    }

    /**
     * Delete and refund a transaction.
     */
    public function deleteTransaction(Request $request, $id)
    {
        $transaction = Transaction::findOrFail($id);

        // Check if coordinator is assigned to this site
        $user = Auth::guard('web')->user();
        $coordinator = \App\Models\Coordinator::where('user_id', $user->id)->first();
        $assignedSitesIds = $coordinator ? $coordinator->assigned_sites_ids : [];
        
        if (!in_array($transaction->site_id, $assignedSitesIds ?? [])) {
            abort(403, 'Unauthorized access to this transaction.');
        }

        \Illuminate\Support\Facades\DB::transaction(function () use ($transaction) {
            $wallet = $transaction->wallet;
            if ($wallet) {
                // Refund logic
                if ($transaction->type === 'debit') {
                    $newBalance = $wallet->current_balance + $transaction->amount;
                    $wallet->update(['current_balance' => $newBalance]);

                    Transaction::create([
                        'wallet_id' => $wallet->id,
                        'date' => now(),
                        'amount' => $transaction->amount,
                        'note' => 'Refund for deleted transaction',
                        'type' => 'credit',
                        'pay_to' => $transaction->pay_to,
                        'pay_to_code' => $transaction->pay_to_code,
                        'balance_after' => $newBalance,
                        'approval' => 1,
                        'site_id' => $transaction->site_id,
                    ]);
                } elseif ($transaction->type === 'credit') {
                    $newBalance = $wallet->current_balance - $transaction->amount;
                    $wallet->update(['current_balance' => $newBalance]);

                    Transaction::create([
                        'wallet_id' => $wallet->id,
                        'date' => now(),
                        'amount' => $transaction->amount,
                        'note' => 'Refund for deleted transaction',
                        'type' => 'debit',
                        'pay_to' => $transaction->pay_to,
                        'pay_to_code' => $transaction->pay_to_code,
                        'balance_after' => $newBalance,
                        'approval' => 1,
                        'site_id' => $transaction->site_id,
                    ]);
                }
            }
            $transaction->delete();
        });

        return back()->with('success', 'Transaction deleted and amount refunded successfully.');
    }
}
