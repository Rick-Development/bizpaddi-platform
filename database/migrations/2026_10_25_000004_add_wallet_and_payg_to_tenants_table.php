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
        if (Schema::hasTable('tenants')) {
            Schema::table('tenants', function (Blueprint $table) {
                if (!Schema::hasColumn('tenants', 'billing_model')) {
                    $table->enum('billing_model', ['subscription', 'payg'])->default('subscription')->after('status');
                }
                if (!Schema::hasColumn('tenants', 'wallet_balance')) {
                    $table->decimal('wallet_balance', 15, 2)->default(0.00)->after('billing_model');
                }
            });
        }

        if (!Schema::hasTable('tenant_wallet_transactions')) {
            Schema::create('tenant_wallet_transactions', function (Blueprint $table) {
                $table->id();
                $table->foreignId('tenant_id')->constrained('tenants')->onDelete('cascade');
                $table->enum('type', ['credit', 'debit']);
                $table->decimal('amount', 15, 2);
                $table->decimal('balance_before', 15, 2);
                $table->decimal('balance_after', 15, 2);
                $table->string('action', 50); // deposit, invoice_generation, quote_generation, team_operation, inflow_fee
                $table->string('description');
                $table->string('reference')->nullable()->index();
                $table->json('metadata')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tenant_wallet_transactions');

        if (Schema::hasTable('tenants')) {
            Schema::table('tenants', function (Blueprint $table) {
                if (Schema::hasColumn('tenants', 'wallet_balance')) {
                    $table->dropColumn('wallet_balance');
                }
                if (Schema::hasColumn('tenants', 'billing_model')) {
                    $table->dropColumn('billing_model');
                }
            });
        }
    }
};
