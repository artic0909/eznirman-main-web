<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            if (!Schema::hasColumn('transactions', 'pay_to')) {
                $table->string('pay_to')->nullable()->after('balance_after');
            }
            if (!Schema::hasColumn('transactions', 'pay_to_code')) {
                $table->string('pay_to_code')->nullable()->after('pay_to');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn(['pay_to', 'pay_to_code']);
        });
    }
};
