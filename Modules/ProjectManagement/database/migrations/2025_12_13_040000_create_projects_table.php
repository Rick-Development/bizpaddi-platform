<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pm_projects', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->foreignId('client_id')->nullable()->constrained('clients')->nullOnDelete();
            $table->enum('status', ['pending', 'active', 'on_hold', 'completed', 'cancelled'])->default('pending');
            $table->date('start_date')->nullable();
            $table->date('due_date')->nullable();
            $table->date('completed_at')->nullable();
            $table->decimal('budget', 15, 2)->nullable();
            $table->decimal('hourly_rate', 10, 2)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pm_projects');
    }
};
