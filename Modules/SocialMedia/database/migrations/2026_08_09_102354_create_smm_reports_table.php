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
        Schema::create('smm_reports', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('type');
            $table->json('data_summary');
            $table->text('ai_analysis')->nullable();
            $table->date('period_start');
            $table->date('period_end');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('smm_reports');
    }
};
