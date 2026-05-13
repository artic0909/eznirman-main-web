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
        Schema::create('material_consumes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('material_purchase_id')->constrained()->onDelete('cascade');
            $table->date('consume_date');
            $table->double('quantity_current');
            $table->double('used_quantity');
            $table->double('available_quantity');
            $table->string('unit'); // As per user comment "unit"
            $table->tinyInteger('use_now')->default(0); // 0: Site, 1: Site Transfer
            $table->foreignId('from_site_id')->nullable()->constrained('working_sites')->onDelete('set null');
            $table->foreignId('to_site_id')->nullable()->constrained('working_sites')->onDelete('set null');
            $table->text('note')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('material_consumes');
    }
};
