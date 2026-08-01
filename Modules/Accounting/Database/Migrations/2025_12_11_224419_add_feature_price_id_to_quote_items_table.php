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
        Schema::table('quote_items', function (Blueprint $table) {
            $table->foreignId('feature_price_id')->nullable()->after('service_id')->constrained('feature_prices')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('quote_items', function (Blueprint $table) {
            $table->dropForeign(['feature_price_id']);
            $table->dropColumn('feature_price_id');
        });
    }
};
