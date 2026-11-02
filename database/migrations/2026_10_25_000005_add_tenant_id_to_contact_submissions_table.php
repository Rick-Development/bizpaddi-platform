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
        if (Schema::hasTable('contact_submissions') && !Schema::hasColumn('contact_submissions', 'tenant_id')) {
            Schema::table('contact_submissions', function (Blueprint $table) {
                $table->unsignedBigInteger('tenant_id')->nullable()->after('id')->index();
            });

            // Backfill existing contact inquiries to tenant 1 (Rick Development)
            DB::table('contact_submissions')->whereNull('tenant_id')->update(['tenant_id' => 1]);
        }

        // Also ensure projects table (portfolio projects) has tenant_id if present
        if (Schema::hasTable('projects') && !Schema::hasColumn('projects', 'tenant_id')) {
            Schema::table('projects', function (Blueprint $table) {
                $table->unsignedBigInteger('tenant_id')->nullable()->after('id')->index();
            });

            DB::table('projects')->whereNull('tenant_id')->update(['tenant_id' => 1]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('contact_submissions') && Schema::hasColumn('contact_submissions', 'tenant_id')) {
            Schema::table('contact_submissions', function (Blueprint $table) {
                $table->dropColumn('tenant_id');
            });
        }

        if (Schema::hasTable('projects') && Schema::hasColumn('projects', 'tenant_id')) {
            Schema::table('projects', function (Blueprint $table) {
                $table->dropColumn('tenant_id');
            });
        }
    }
};
