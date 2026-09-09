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
        Schema::table('transactions', function (Blueprint $table) {
            $table->timestamp('approved_at')->nullable()->after('approval');
        });

        Schema::table('unauthorized_purchases', function (Blueprint $table) {
            $table->timestamp('approved_at')->nullable()->after('approval');
        });

        // Backfill approved_at for existing approved records
        DB::statement("UPDATE transactions SET approved_at = COALESCE(updated_at, created_at) WHERE approval = 1 OR type = 'credit'");
        DB::statement("UPDATE unauthorized_purchases SET approved_at = COALESCE(updated_at, created_at) WHERE approval = 1");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn('approved_at');
        });

        Schema::table('unauthorized_purchases', function (Blueprint $table) {
            $table->dropColumn('approved_at');
        });
    }
};
