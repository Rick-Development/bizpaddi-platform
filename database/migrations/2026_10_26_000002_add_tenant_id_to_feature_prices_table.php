<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('feature_prices') && !Schema::hasColumn('feature_prices', 'tenant_id')) {
            Schema::table('feature_prices', function (Blueprint $table) {
                $table->unsignedBigInteger('tenant_id')->nullable()->after('id')->index();
            });

            // Backfill all existing feature prices to Tenant 1 (Rick Development LTD)
            DB::table('feature_prices')->update(['tenant_id' => 1]);
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('feature_prices') && Schema::hasColumn('feature_prices', 'tenant_id')) {
            Schema::table('feature_prices', function (Blueprint $table) {
                $table->dropColumn('tenant_id');
            });
        }
    }
};
