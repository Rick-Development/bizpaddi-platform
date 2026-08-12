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
        Schema::create('smm_social_accounts', function (Blueprint $table) {
            $table->id();
            $table->string('platform');
            $table->string('account_name');
            $table->string('account_id')->nullable();
            $table->text('access_token')->nullable();
            $table->text('refresh_token')->nullable();
            $table->json('platform_settings')->nullable();
            $table->boolean('is_connected')->default(false);
            $table->string('status')->default('active');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('smm_social_accounts');
    }
};
