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
        Schema::table('material_purchases', function (Blueprint $table) {
            $table->unsignedBigInteger('created_by')->nullable()->after('note');
            $table->string('type')->nullable()->after('created_by'); // admin or user
        });

        Schema::table('material_consumes', function (Blueprint $table) {
            $table->unsignedBigInteger('created_by')->nullable()->after('note');
            $table->string('type')->nullable()->after('created_by'); // admin or user
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('material_purchases', function (Blueprint $table) {
            $table->dropColumn(['created_by', 'type']);
        });

        Schema::table('material_consumes', function (Blueprint $table) {
            $table->dropColumn(['created_by', 'type']);
        });
    }
};
