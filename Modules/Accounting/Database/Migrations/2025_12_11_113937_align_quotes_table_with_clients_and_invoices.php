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
            $table->foreignId('client_id')->nullable()->after('quote_number')->constrained('clients')->nullOnDelete();
            $table->text('client_address')->nullable()->after('client_email');
            $table->dropColumn('client_company');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('quotes', function (Blueprint $table) {
            $table->dropForeign(['client_id']);
            $table->dropColumn(['client_id', 'client_address']);
            $table->string('client_company')->nullable();
        });
    }
};
