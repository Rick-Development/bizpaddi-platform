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
        Schema::create('ai_settings', function (Blueprint $table) {
            $table->id();
            $table->string('provider')->nullable(); // openai, anthropic, etc.
            $table->string('key')->index();
            $table->text('value')->nullable();
            $table->string('type')->default('string'); // string, boolean, integer, json
            $table->boolean('is_encrypted')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            $table->unique(['provider', 'key']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ai_settings');
    }
};
