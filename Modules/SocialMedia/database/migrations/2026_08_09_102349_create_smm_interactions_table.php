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
        Schema::create('smm_interactions', function (Blueprint $table) {
            $table->id();
            $table->string('platform');
            $table->foreignId('post_id')->nullable();
            $table->string('external_id')->nullable();
            $table->string('user_handle')->nullable();
            $table->text('message');
            $table->string('sentiment')->nullable();
            $table->string('category')->nullable();
            $table->string('priority')->default('low');
            $table->string('status')->default('pending');
            $table->text('ai_response')->nullable();
            $table->text('human_response')->nullable();
            $table->timestamp('responded_at')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('smm_interactions');
    }
};
