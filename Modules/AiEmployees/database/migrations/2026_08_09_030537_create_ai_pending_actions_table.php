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
        Schema::create('ai_pending_actions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ai_employee_id')->constrained('ai_employees')->onDelete('cascade');
            $table->string('tool_name');
            $table->json('tool_arguments')->nullable();
            $table->string('status')->default('pending'); // pending, approved, rejected, executed, failed
            $table->text('result')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ai_pending_actions');
    }
};
