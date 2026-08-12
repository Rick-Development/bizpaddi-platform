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
        Schema::create('smm_hashtags', function (Blueprint $table) {
            $table->id();
            $table->string('hashtag');
            $table->string('platform')->nullable();
            $table->integer('usage_frequency')->default(0);
            $table->decimal('performance_score', 5, 2)->default(0);
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('smm_hashtags');
    }
};
