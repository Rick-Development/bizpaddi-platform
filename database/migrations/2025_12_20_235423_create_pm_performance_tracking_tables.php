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
        Schema::create('pm_kpis', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('key')->unique(); // e.g., 'tasks_completed', 'on_time_percentage'
            $table->integer('target')->default(100);
            $table->string('unit')->default('%');
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('pm_team_performance', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->integer('month');
            $table->integer('year');
            $table->decimal('score', 5, 2)->default(100.00);
            $table->integer('tasks_completed')->default(0);
            $table->integer('tasks_late')->default(0);
            $table->integer('tasks_early')->default(0);
            $table->decimal('bonus_points', 5, 2)->default(0.00);
            $table->decimal('penalty_points', 5, 2)->default(0.00);
            $table->json('kpi_data')->nullable(); // Detailed KPI breakdown
            $table->timestamps();

            $table->unique(['user_id', 'month', 'year']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pm_team_performance');
        Schema::dropIfExists('pm_kpis');
    }
};
