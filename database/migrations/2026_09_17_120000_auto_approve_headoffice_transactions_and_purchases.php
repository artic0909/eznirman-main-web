<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use App\Models\WorkingSite;
use App\Models\User;
use App\Models\Wallet;
use App\Models\Transaction;
use App\Models\UnauthorizedPurchase;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Identify all Head Office site IDs
        $hoSiteIds = WorkingSite::where('site_code', 'LIKE', 'HO%')
            ->orWhere('site_name', 'LIKE', '%HEAD OFFICE%')
            ->pluck('id')
            ->toArray();

        // 2. Identify all users assigned to Head Office sites
        $hoUserIds = User::whereIn('working_site_id', $hoSiteIds)
            ->pluck('id')
            ->toArray();

        $hoWalletIds = Wallet::whereIn('user_id', $hoUserIds)
            ->pluck('id')
            ->toArray();

        // 3. Auto-approve all existing Head Office transactions
        Transaction::where(function ($query) use ($hoSiteIds, $hoWalletIds) {
            if (!empty($hoSiteIds)) {
                $query->whereIn('site_id', $hoSiteIds);
            }
            if (!empty($hoWalletIds)) {
                $query->orWhereIn('wallet_id', $hoWalletIds);
            }
        })->where(function ($query) {
            $query->where('approval', 0)->orWhereNull('approval');
        })->update([
            'approval' => 1,
            'approved_at' => DB::raw('COALESCE(approved_at, created_at)')
        ]);

        // 4. Auto-approve all existing Head Office unauthorized purchases
        UnauthorizedPurchase::where(function ($query) use ($hoSiteIds, $hoUserIds) {
            if (!empty($hoSiteIds)) {
                $query->whereIn('working_site_id', $hoSiteIds);
            }
            if (!empty($hoUserIds)) {
                $query->orWhereIn('user_id', $hoUserIds);
            }
        })->where(function ($query) {
            $query->where('approval', 0)->orWhereNull('approval');
        })->update([
            'approval' => 1,
            'approved_at' => DB::raw('COALESCE(approved_at, created_at)')
        ]);

        // 5. Ensure approved_at is populated for any already approved records with missing approved_at
        DB::statement("UPDATE transactions SET approved_at = COALESCE(updated_at, created_at) WHERE (approval = 1 OR type = 'credit') AND approved_at IS NULL");
        DB::statement("UPDATE unauthorized_purchases SET approved_at = COALESCE(updated_at, created_at) WHERE approval = 1 AND approved_at IS NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No down action needed for data normalization
    }
};
