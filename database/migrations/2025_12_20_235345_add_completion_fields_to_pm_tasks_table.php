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
        Schema::table('pm_tasks', function (Blueprint $table) {
            $table->timestamp('completed_at')->nullable()->after('status');
            $table->integer('quality_score')->nullable()->after('completed_at')->comment('1-10 rating');
        });
    }

    public function down(): void
    {
        Schema::table('pm_tasks', function (Blueprint $table) {
            $table->dropColumn(['completed_at', 'quality_score']);
        });
    }
};
