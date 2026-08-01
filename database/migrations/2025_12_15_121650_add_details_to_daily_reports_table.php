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
        Schema::table('daily_reports', function (Blueprint $table) {
            $table->longText('tasks_completed')->nullable()->after('user_id');
            $table->longText('tasks_planned')->nullable()->after('tasks_completed');
            $table->longText('challenges')->nullable()->after('tasks_planned');
            $table->string('content')->nullable()->change(); // Make existing content nullable
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('daily_reports', function (Blueprint $table) {
            $table->dropColumn(['tasks_completed', 'tasks_planned', 'challenges']);
            $table->string('content')->nullable(false)->change();
        });
    }
};
