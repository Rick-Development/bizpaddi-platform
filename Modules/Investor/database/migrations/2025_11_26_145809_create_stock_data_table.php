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
        Schema::create('stock_data', function (Blueprint $table) {
            $table->id();
            $table->date('date')->unique();
            $table->decimal('opening_price', 10, 2)->nullable();
            $table->decimal('closing_price', 10, 2);
            $table->decimal('high_price', 10, 2)->nullable();
            $table->decimal('low_price', 10, 2)->nullable();
            $table->bigInteger('volume')->nullable();
            $table->decimal('market_cap', 15, 2)->nullable();
            $table->timestamps();
            
            $table->index('date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_data');
    }
};
