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
        if (Schema::hasTable('pm_comments') && !Schema::hasColumn('pm_comments', 'tenant_id')) {
            Schema::table('pm_comments', function (Blueprint $table) {
                $table->unsignedBigInteger('tenant_id')->nullable()->after('id')->index();
                $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
            });

            if (DB::getDriverName() === 'mysql' && DB::table('tenants')->where('id', 1)->exists()) {
                // Backfill from Task
                DB::statement("
                    UPDATE pm_comments c
                    JOIN pm_tasks t ON c.commentable_id = t.id AND c.commentable_type LIKE '%Task%'
                    SET c.tenant_id = t.tenant_id
                    WHERE t.tenant_id IS NOT NULL
                ");

                // Backfill from Project
                DB::statement("
                    UPDATE pm_comments c
                    JOIN pm_projects p ON c.commentable_id = p.id AND c.commentable_type LIKE '%Project%'
                    SET c.tenant_id = p.tenant_id
                    WHERE p.tenant_id IS NOT NULL
                ");

                DB::statement("
                    UPDATE pm_comments
                    SET tenant_id = 1
                    WHERE tenant_id IS NULL
                ");
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('pm_comments') && Schema::hasColumn('pm_comments', 'tenant_id')) {
            Schema::table('pm_comments', function (Blueprint $table) {
                $table->dropForeign(['tenant_id']);
                $table->dropColumn('tenant_id');
            });
        }
    }
};
