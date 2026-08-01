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
        Schema::create('rate_limits', function (Blueprint $table) {
            $table->id();
            $table->string('category');
            $table->string('route_name')->unique();
            $table->string('route_path');
            $table->integer('max_attempts')->default(60);
            $table->integer('decay_minutes')->default(1);
            $table->boolean('is_enabled')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rate_limits');
    }
};
