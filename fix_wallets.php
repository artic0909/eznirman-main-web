<?php
$wallets = App\Models\Wallet::all();
foreach($wallets as $wallet) {
    $totalCredits = $wallet->transactions()->where('type', 'credit')->sum('amount');
    $totalDebits = $wallet->transactions()->where('type', 'debit')->sum('amount');
    $wallet->update(['current_balance' => $totalCredits - $totalDebits]);
    echo "Updated wallet " . $wallet->id . " to " . ($totalCredits - $totalDebits) . "\n";
}
