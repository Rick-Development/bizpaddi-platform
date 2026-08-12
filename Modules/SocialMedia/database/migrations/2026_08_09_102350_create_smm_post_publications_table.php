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
        Schema::create('smm_post_publications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('post_id');
            $table->string('platform');
            $table->string('status');
            $table->text('api_response')->nullable();
            $table->integer('retry_count')->default(0);
            $table->timestamp('published_at')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('smm_post_publications');
    }
};
