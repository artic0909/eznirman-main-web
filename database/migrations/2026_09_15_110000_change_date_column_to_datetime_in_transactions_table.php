<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Alter 'date' column in transactions to DATETIME so MySQL will NOT automatically update it ON UPDATE CURRENT_TIMESTAMP
        DB::statement("ALTER TABLE transactions MODIFY `date` DATETIME NOT NULL");

        // 2. Fix existing transactions where 'date' was inadvertently overwritten with approval/update timestamp while created_at was earlier
        DB::statement("
            UPDATE transactions 
            SET `date` = created_at 
            WHERE (
                (`date` = approved_at AND `date` > created_at)
                OR (`date` = updated_at AND `date` > created_at)
            )
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE transactions MODIFY `date` TIMESTAMP NOT NULL");
    }
};
