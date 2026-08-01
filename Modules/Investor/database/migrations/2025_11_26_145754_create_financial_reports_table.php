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
        Schema::create('financial_reports', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->enum('report_type', ['quarterly', 'annual', 'special'])->default('quarterly');
            $table->year('fiscal_year');
            $table->tinyInteger('fiscal_quarter')->nullable()->comment('1-4 for quarterly reports');
            $table->date('report_date');
            $table->text('summary')->nullable();
            $table->boolean('is_published')->default(false);
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
            
            $table->index(['fiscal_year', 'fiscal_quarter']);
            $table->index('report_type');
            $table->index('is_published');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('financial_reports');
    }
};
