<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('career_applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('career_job_id')->constrained('career_jobs')->cascadeOnDelete();
            $table->foreignId('candidate_id')->constrained()->cascadeOnDelete();
            $table->text('cover_letter')->nullable();
            $table->enum('status', ['pending', 'reviewing', 'interview', 'rejected', 'accepted'])->default('pending');
            $table->integer('ai_score')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('career_applications'); }
};
