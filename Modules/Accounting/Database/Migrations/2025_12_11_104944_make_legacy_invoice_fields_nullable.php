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
            $table->foreignId('user_id')->nullable()->change();
            $table->decimal('amount', 12, 2)->nullable()->change();
            $table->decimal('total', 12, 2)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            // Cannot easily revert to non-nullable without data loss or default values if nulls exist.
            // But we can try to revert definition.
            $table->foreignId('user_id')->nullable(false)->change();
            $table->decimal('amount', 12, 2)->nullable(false)->change();
            $table->decimal('total', 12, 2)->nullable(false)->change();
        });
    }
};
