<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$purchases = \App\Models\UnauthorizedPurchase::where('approval', 1)->get();
$updated = 0;

foreach ($purchases as $purchase) {
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
                $transaction->updated_at = $purchase->updated_at; // sync timestamp
                $transaction->save();
                $updated++;
            }
        }
    }
}

echo "Successfully updated $updated associated transactions to approval=1.\n";
