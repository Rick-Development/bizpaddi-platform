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
        Schema::create('smm_engagements', function (Blueprint $table) {
            $table->id();
            $table->string('platform');
            $table->string('target_account')->nullable();
            $table->string('opportunity_type');
            $table->text('context')->nullable();
            $table->string('priority')->default('medium');
            $table->text('suggested_response')->nullable();
            $table->string('status')->default('pending');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('smm_engagements');
    }
};
