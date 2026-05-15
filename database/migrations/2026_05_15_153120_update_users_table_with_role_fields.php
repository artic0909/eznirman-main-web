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
        Schema::table('users', function (Blueprint $table) {
            $table->string('code')->unique()->after('id');
            $table->enum('role', ['worker', 'supervisor', 'staff', 'hr'])->after('code');
            $table->date('joining_date')->nullable()->after('name');
            $table->text('current_address')->nullable()->after('joining_date');
            $table->unsignedBigInteger('work_skill_id')->nullable()->after('current_address');
            $table->unsignedBigInteger('designation_id')->nullable()->after('work_skill_id');
            $table->unsignedBigInteger('working_site_id')->nullable()->after('designation_id');
            $table->string('mobile')->nullable()->after('working_site_id');
            $table->string('esi_no')->nullable()->after('mobile');
            $table->string('pf_no')->nullable()->after('esi_no');
            $table->string('bank_account_no')->nullable()->after('pf_no');
            $table->string('pancard')->nullable()->after('bank_account_no'); // file path
            $table->string('adhaarcard')->nullable()->after('pancard'); // file path
            $table->string('profile_image')->nullable()->after('adhaarcard');
            $table->string('status')->default('active')->after('profile_image');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'code', 'role', 'joining_date', 'current_address', 
                'work_skill_id', 'designation_id', 'working_site_id', 
                'mobile', 'esi_no', 'pf_no', 'bank_account_no', 
                'pancard', 'adhaarcard', 'profile_image', 'status'
            ]);
        });
    }
};
