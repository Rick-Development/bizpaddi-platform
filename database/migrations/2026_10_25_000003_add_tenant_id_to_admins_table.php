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
        if (Schema::hasTable('admins') && !Schema::hasColumn('admins', 'tenant_id')) {
            Schema::table('admins', function (Blueprint $table) {
                $table->unsignedBigInteger('tenant_id')->nullable()->after('id')->index();
                $table->foreign('tenant_id')->references('id')->on('tenants')->nullOnDelete();
            });

            // Backfill existing admin #2 to flagship tenant #1
            \DB::table('admins')->where('id', 2)->update(['tenant_id' => 1]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('admins') && Schema::hasColumn('admins', 'tenant_id')) {
            Schema::table('admins', function (Blueprint $table) {
                $table->dropForeign(['tenant_id']);
                $table->dropColumn('tenant_id');
            });
        }
    }
};
