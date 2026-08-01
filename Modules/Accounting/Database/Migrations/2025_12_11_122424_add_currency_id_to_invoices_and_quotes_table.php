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
        Schema::table('invoices', function (Blueprint $table) {
            $table->foreignId('currency_id')->nullable()->after('client_id')->constrained('currencies')->nullOnDelete();
        });

        Schema::table('quotes', function (Blueprint $table) {
            $table->foreignId('currency_id')->nullable()->after('client_id')->constrained('currencies')->nullOnDelete();
        });
        
        // Update existing records to use default currency if available
        $defaultCurrencyId = \Illuminate\Support\Facades\DB::table('currencies')->where('is_default', true)->value('id');
        if ($defaultCurrencyId) {
            \Illuminate\Support\Facades\DB::table('invoices')->update(['currency_id' => $defaultCurrencyId]);
            \Illuminate\Support\Facades\DB::table('quotes')->update(['currency_id' => $defaultCurrencyId]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropForeign(['currency_id']);
            $table->dropColumn('currency_id');
        });

        Schema::table('quotes', function (Blueprint $table) {
            $table->dropForeign(['currency_id']);
            $table->dropColumn('currency_id');
        });
    }
};
