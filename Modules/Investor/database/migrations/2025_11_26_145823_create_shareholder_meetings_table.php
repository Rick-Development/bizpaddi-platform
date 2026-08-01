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
        Schema::create('shareholder_meetings', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->enum('meeting_type', ['agm', 'egm', 'special'])->default('agm');
            $table->dateTime('meeting_date');
            $table->string('location')->nullable();
            $table->text('description')->nullable();
            $table->longText('agenda')->nullable();
            $table->boolean('is_upcoming')->default(true);
            $table->timestamps();
            
            $table->index('meeting_type');
            $table->index('meeting_date');
            $table->index('is_upcoming');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shareholder_meetings');
    }
};
