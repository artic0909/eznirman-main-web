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
        $tables = [
            'machinaries',
            'product_categories',
            'units',
            'material_codes',
            'designations',
            'skills',
            'users'
        ];

        foreach ($tables as $tableName) {
            Schema::table($tableName, function (Blueprint $table) {
                $table->unsignedBigInteger('created_by')->nullable();
                $table->string('creator_type')->nullable();
                $table->unsignedBigInteger('updated_by')->nullable();
                $table->string('updater_type')->nullable();
            });
        }

        $materialTables = ['material_purchases', 'material_consumes'];
        foreach ($materialTables as $tableName) {
            Schema::table($tableName, function (Blueprint $table) {
                $table->unsignedBigInteger('updated_by')->nullable();
                $table->string('updater_type')->nullable();
                $table->string('creator_type')->nullable();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $tables = [
            'machinaries',
            'product_categories',
            'units',
            'material_codes',
            'designations',
            'skills',
            'users'
        ];

        foreach ($tables as $tableName) {
            Schema::table($tableName, function (Blueprint $table) {
                $table->dropColumn(['created_by', 'creator_type', 'updated_by', 'updater_type']);
            });
        }

        $materialTables = ['material_purchases', 'material_consumes'];
        foreach ($materialTables as $tableName) {
            Schema::table($tableName, function (Blueprint $table) {
                $table->dropColumn(['updated_by', 'updater_type', 'creator_type']);
            });
        }
    }
};
