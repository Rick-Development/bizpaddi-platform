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
        Schema::create('smm_social_inboxes', function (Blueprint $table) {
            $table->id();
            $table->string('platform');
            $table->string('external_id')->nullable();
            $table->string('sender_name')->nullable();
            $table->text('message');
            $table->string('category')->default('general');
            $table->string('sentiment')->nullable();
            $table->string('status')->default('unread');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('smm_social_inboxes');
    }
};
