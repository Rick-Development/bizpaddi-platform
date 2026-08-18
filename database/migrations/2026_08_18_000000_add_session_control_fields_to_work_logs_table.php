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
        Schema::table('work_logs', function (Blueprint $table) {
            $table->json('planned_projects')->nullable()->after('resources_needed');
            $table->text('session_goal')->nullable()->after('planned_projects');
            $table->json('planned_assignments')->nullable()->after('session_goal');
            $table->json('tasks_data')->nullable()->after('planned_assignments');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('work_logs', function (Blueprint $table) {
            $table->dropColumn(['planned_projects', 'session_goal', 'planned_assignments', 'tasks_data']);
        });
    }
};
