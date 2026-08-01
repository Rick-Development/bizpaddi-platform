<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ai_models', function (Blueprint $table) {
            $table->id();
            $table->string('provider'); // openai, anthropic, gemini
            $table->string('name'); // e.g. gpt-4o
            $table->string('label')->nullable(); // e.g. GPT-4o
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ai_models');
    }
};
