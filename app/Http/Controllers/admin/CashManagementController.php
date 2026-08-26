<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;

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

        // Only show credits, approved debits, or debits from Head Office (HO1)
        $query->where(function ($q) {
            $q->where('type', 'credit')
              ->orWhere(function ($sub) {
                  $sub->where('type', 'debit')
                      ->where(function ($debitQuery) {
                          $debitQuery->where('approval', 1)
                                     ->orWhereHas('site', function ($siteQuery) {
                                         $siteQuery->where('site_code', 'HO1');
                                     });
                      });
              });
        });

        // Calculate KPA Totals based on current filters, excluding refund entries
        $totalCredits = (clone $query)->where('type', 'credit')->where('note', 'not like', 'Refund%')->sum('amount');
        $totalDebits = (clone $query)->where('type', 'debit')->where('note', 'not like', 'Refund%')->sum('amount');

        // Handle Export
        if ($request->has('export')) {
            $exportData = $query->orderBy('updated_at', 'desc')->get();
            $filename = "cash_management_transactions_" . date('Y-m-d_H-i-s') . ".csv";
            
            $headers = [
                "Content-type"        => "text/csv",
                "Content-Disposition" => "attachment; filename=$filename",
                "Pragma"              => "no-cache",
                "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
                "Expires"             => "0"
            ];
            
            $columns = ['Approved Date', 'Transaction Date', 'Time', 'User Name', 'User Code', 'Role', 'Account Code', 'Description', 'Pay To', 'Credit (In)', 'Debit (Out)'];
            
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
                    
                    fputcsv($file, [$approvedDate, $date, $time, $userName, $userCode, $role, $accountCode, $note, $payTo, $credit, $debit]);
                }
                
                fclose($file);
            };
            
            return response()->stream($callback, 200, $headers);
        }

        // Get paginated transactions
        $transactions = $query->orderBy('updated_at', 'desc')->paginate(20)->withQueryString();

        // Get roles for dropdown
        $roles = User::select('role')->distinct()->whereNotNull('role')->pluck('role');

        return view('admin.cashmanagement.index', compact('transactions', 'totalCredits', 'totalDebits', 'roles'));
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
}
