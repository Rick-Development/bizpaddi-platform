<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('contracts', function (Blueprint $table) {
            $table->integer('duration_weeks')->nullable()->after('duration_months');
        });
    }

    public function down(): void
    {
        Schema::table('contracts', function (Blueprint $table) {
            $table->dropColumn('duration_weeks');
        });
    }
};
