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
        Schema::table('leads', function (Blueprint $table) {
            $table->decimal('value', 12, 2)->nullable()->after('score')->comment('Estimated deal value');
            $table->date('expected_close_date')->nullable()->after('value')->comment('Expected date to close the deal');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->dropColumn(['value', 'expected_close_date']);
        });
    }
};
