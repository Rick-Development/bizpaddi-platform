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
        Schema::create('pm_attendance', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->date('date')->index();
            $table->timestamp('clock_in')->nullable();
            $table->timestamp('clock_out')->nullable();
            $table->integer('duration_minutes')->default(0); // Total minutes worked
            $table->string('status')->default('present'); // present, absent, half_day
            $table->string('work_mode')->default('remote'); // remote, office, hybrid
            $table->string('ip_address')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            // Prevent multiple entries for same user on same day
            $table->unique(['user_id', 'date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pm_attendance');
    }
};
