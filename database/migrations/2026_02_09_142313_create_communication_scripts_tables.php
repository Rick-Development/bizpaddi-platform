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
        Schema::create('communication_channels', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('icon')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('communication_scripts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('channel_id')->constrained('communication_channels')->onDelete('cascade');
            $table->string('title');
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('communication_script_steps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('script_id')->constrained('communication_scripts')->onDelete('cascade');
            $table->integer('order');
            $table->string('name');
            $table->text('content');
            $table->json('key_points')->nullable();
            $table->timestamps();
        });

        Schema::create('communication_script_exceptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('step_id')->constrained('communication_script_steps')->onDelete('cascade');
            $table->string('trigger_text');
            $table->text('response_content');
            $table->string('action')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('communication_script_exceptions');
        Schema::dropIfExists('communication_script_steps');
        Schema::dropIfExists('communication_scripts');
        Schema::dropIfExists('communication_channels');
    }
};
