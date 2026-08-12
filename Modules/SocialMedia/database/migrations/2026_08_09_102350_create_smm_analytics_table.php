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
        Schema::create('smm_analytics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('post_id')->nullable();
            $table->foreignId('campaign_id')->nullable();
            $table->string('platform');
            $table->integer('impressions')->default(0);
            $table->integer('clicks')->default(0);
            $table->integer('likes')->default(0);
            $table->integer('shares')->default(0);
            $table->integer('comments')->default(0);
            $table->decimal('engagement_rate', 5, 2)->default(0);
            $table->timestamp('tracked_at');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('smm_analytics');
    }
};
