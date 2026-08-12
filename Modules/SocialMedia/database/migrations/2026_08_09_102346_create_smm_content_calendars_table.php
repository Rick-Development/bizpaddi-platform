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
        Schema::create('smm_content_calendars', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('theme')->nullable();
            $table->date('start_date');
            $table->date('end_date');
            $table->string('status')->default('draft');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('smm_content_calendars');
    }
};
