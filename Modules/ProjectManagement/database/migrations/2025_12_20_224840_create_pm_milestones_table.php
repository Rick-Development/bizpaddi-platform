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
        Schema::create('pm_milestones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained('pm_projects')->onDelete('cascade');
            $table->string('title');
            $table->text('description')->nullable();
            $table->date('due_date')->nullable();
            $table->enum('status', ['pending', 'completed'])->default('pending');
            $table->integer('order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pm_milestones');
    }
};
