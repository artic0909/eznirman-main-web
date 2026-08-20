<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Transaction;
use App\Models\UnauthorizedPurchase;

class UpdateTransactionSites extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:update-transaction-sites';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Backfill missing site_id for old transactions and unauthorized purchases based on the user\'s currently assigned site.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting transaction site_id update...');
        
        $transactions = Transaction::whereNull('site_id')->with('wallet.user')->get();
        $updatedCount = 0;

        foreach ($transactions as $transaction) {
            if ($transaction->wallet && $transaction->wallet->user && $transaction->wallet->user->working_site_id) {
                $transaction->site_id = $transaction->wallet->user->working_site_id;
                $transaction->save();
                $updatedCount++;
            }
        }

        $this->info("Updated {$updatedCount} transactions.");

        // Also backfill unauthorized purchases if they are missing working_site_id
        $this->info('Checking unauthorized purchases...');
        $purchases = UnauthorizedPurchase::whereNull('working_site_id')->with('user')->get();
        $purchasesCount = 0;

        foreach ($purchases as $purchase) {
            if ($purchase->user && $purchase->user->working_site_id) {
                $purchase->working_site_id = $purchase->user->working_site_id;
                $purchase->save();
                $purchasesCount++;
            }
        }

        $this->info("Updated {$purchasesCount} unauthorized purchases.");

        // Approve all old pending transactions (handle both 0 and NULL)
        $this->info('Approving all old pending transactions...');
        $approvedTxCount = Transaction::where(function($query) {
            $query->where('approval', 0)->orWhereNull('approval');
        })->update(['approval' => 1]);
        $this->info("Approved {$approvedTxCount} transactions.");

        // Approve all old pending unauthorized purchases (handle both 0 and NULL)
        $this->info('Approving all old pending unauthorized purchases...');
        $approvedPurchasesCount = UnauthorizedPurchase::where(function($query) {
            $query->where('approval', 0)->orWhereNull('approval');
        })->update(['approval' => 1]);
        $this->info("Approved {$approvedPurchasesCount} unauthorized purchases.");

        // Fix updated_at to match created_at
        $this->info('Fixing updated_at to match created_at for all old records...');
        \Illuminate\Support\Facades\DB::statement("UPDATE transactions SET updated_at = created_at");
        \Illuminate\Support\Facades\DB::statement("UPDATE unauthorized_purchases SET updated_at = created_at");
        $this->info('Timestamps fixed.');

        $this->info('Done.');
    }
}
