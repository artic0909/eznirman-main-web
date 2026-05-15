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
        Schema::table('material_codes', function (Blueprint $table) {
            $table->string('sub_category')->after('product_category_id');
            $table->string('sub_category_two')->nullable()->after('sub_category');
            $table->string('brand')->after('sub_category_two');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('material_codes', function (Blueprint $table) {
            $table->dropColumn(['sub_category', 'sub_category_two', 'brand']);
        });
    }
};
