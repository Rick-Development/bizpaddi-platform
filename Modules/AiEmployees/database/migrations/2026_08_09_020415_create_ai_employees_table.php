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
        Schema::create('ai_employees', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('role');
            $table->text('description')->nullable();
            $table->longText('system_prompt');
            $table->unsignedBigInteger('ai_model_id')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ai_employees');
    }
};
