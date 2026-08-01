<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('career_jobs', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('department');
            $table->string('location');
            $table->text('description');
            $table->text('requirements');
            $table->enum('type', ['full-time', 'part-time', 'contract', 'remote']);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('career_jobs'); }
};
