<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('industry_id')->nullable()->constrained()->nullOnDelete();
            $table->string('title');
            $table->string('client');
            $table->text('description');
            $table->text('impact_metrics')->nullable();
            $table->year('year');
            $table->boolean('is_featured')->default(false);
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('projects'); }
};
