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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('wallet_id')->constrained('wallets')->onDelete('cascade');
            $table->timestamp('date');
            $table->foreignId('accountcode_id')->nullable()->constrained('accountcodes')->onDelete('set null');
            $table->decimal('amount', 15, 2);
            $table->text('note')->nullable();
            $table->enum('type', ['credit', 'debit']);
            $table->decimal('balance_after', 15, 2)->default(0.00);
            $table->string('pay_to')->nullable();
            $table->string('pay_to_code')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
