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
            $table->foreignId('milestone_id')->nullable()->constrained('pm_milestones')->onDelete('set null');
            $table->integer('order')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pm_tasks', function (Blueprint $table) {
            $table->dropConstrainedForeignId('milestone_id');
            $table->dropColumn('order');
        });
    }
};
