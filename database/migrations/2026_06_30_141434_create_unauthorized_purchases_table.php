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
        Schema::create('unauthorized_purchases', function (Blueprint $table) {
            $table->id();
            $table->string('unauthorized_unique_id')->nullable();
            $table->unsignedBigInteger('working_site_id')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->date('purchase_date')->nullable();
            $table->string('product_name')->nullable();
            $table->decimal('amount', 15, 2)->default(0.00);
            $table->string('invoice_file')->nullable();
            $table->text('note')->nullable();
            $table->timestamps();
            
            $table->foreign('working_site_id')->references('id')->on('working_sites')->onDelete('set null');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('unauthorized_purchases');
    }
};
