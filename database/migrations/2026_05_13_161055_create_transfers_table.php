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
        Schema::create('transfers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('machinery_id')->constrained('machinaries')->onDelete('cascade');
            $table->foreignId('from_site_id')->nullable()->constrained('working_sites')->onDelete('set null');
            $table->foreignId('to_site_id')->constrained('working_sites')->onDelete('cascade');
            $table->date('transfer_date');
            $table->text('remarks')->nullable();
            $table->enum('status', ['pending', 'completed', 'cancelled'])->default('completed');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transfers');
    }
};
