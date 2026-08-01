<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('testimonials', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained()->cascadeOnDelete();
            $table->text('content');
            $table->string('author_name');
            $table->string('author_position');
            $table->integer('rating')->default(5);
            $table->boolean('is_featured')->default(false);
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('testimonials'); }
};
