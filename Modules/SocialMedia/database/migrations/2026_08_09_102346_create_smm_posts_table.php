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
        Schema::create('smm_posts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('campaign_id')->nullable();
            $table->foreignId('calendar_id')->nullable();
            $table->string('platform');
            $table->text('content')->nullable();
            $table->json('media_urls')->nullable();
            $table->json('hashtags')->nullable();
            $table->string('status')->default('draft');
            $table->timestamp('scheduled_for')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('smm_posts');
    }
};
