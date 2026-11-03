<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasTable('salary_disbursements') && !Schema::hasColumn('salary_disbursements', 'tenant_id')) {
            Schema::table('salary_disbursements', function (Blueprint $table) {
                $table->unsignedBigInteger('tenant_id')->nullable()->after('id')->index();
            });

            // Backfill existing rows to tenant 1 (initial platform owner)
            DB::table('salary_disbursements')->whereNull('tenant_id')->update(['tenant_id' => 1]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('salary_disbursements') && Schema::hasColumn('salary_disbursements', 'tenant_id')) {
            Schema::table('salary_disbursements', function (Blueprint $table) {
                $table->dropColumn('tenant_id');
            });
        }
    }
};
