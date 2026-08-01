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
        Schema::table('quotes', function (Blueprint $table) {
            $table->foreignId('service_category_id')->nullable()->after('currency_id')->constrained('service_categories')->nullOnDelete();
            $table->foreignId('service_id')->nullable()->after('service_category_id')->constrained('services')->nullOnDelete();
        });

        Schema::table('invoices', function (Blueprint $table) {
            $table->foreignId('service_category_id')->nullable()->after('currency_id')->constrained('service_categories')->nullOnDelete();
            $table->foreignId('service_id')->nullable()->after('service_category_id')->constrained('services')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('quotes', function (Blueprint $table) {
            $table->dropForeign(['service_category_id']);
            $table->dropForeign(['service_id']);
            $table->dropColumn(['service_category_id', 'service_id']);
        });

        Schema::table('invoices', function (Blueprint $table) {
            $table->dropForeign(['service_category_id']);
            $table->dropForeign(['service_id']);
            $table->dropColumn(['service_category_id', 'service_id']);
        });
    }
};
