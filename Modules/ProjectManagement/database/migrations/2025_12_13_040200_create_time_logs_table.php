<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pm_time_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('task_id')->constrained('pm_tasks')->cascadeOnDelete();
            $table->unsignedBigInteger('user_id');
            $table->string('user_type');
            $table->dateTime('start_time');
            $table->dateTime('end_time')->nullable();
            $table->integer('duration_minutes')->default(0);
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pm_time_logs');
    }
};
