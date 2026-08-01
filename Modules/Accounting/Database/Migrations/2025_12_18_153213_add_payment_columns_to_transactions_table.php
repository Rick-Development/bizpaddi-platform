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
        Schema::table('transactions', function (Blueprint $table) {
            $table->nullableMorphs('payable');
            $table->string('currency_code', 3)->default('NGN');
            $table->string('status')->default('pending');
            $table->string('provider')->nullable();
            $table->string('provider_reference')->nullable();
            $table->timestamp('paid_at')->nullable();
            
            // Modify existing columns to be compatible with standalone usage
            $table->unsignedBigInteger('account_id')->nullable()->change(); 
            $table->string('transaction_number')->nullable()->change();
            $table->string('source')->default('automated')->change();
        });
    }

    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropMorphs('payable');
            $table->dropColumn(['currency_code', 'status', 'provider', 'provider_reference', 'paid_at']);
            
            // Revert changes (might fail if data violates constraints, but best effort)
            $table->unsignedBigInteger('account_id')->nullable(false)->change();
            $table->string('transaction_number')->nullable(false)->change();
        });
    }
};
