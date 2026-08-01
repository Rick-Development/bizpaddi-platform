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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('account_id')->constrained('accounts')->cascadeOnDelete();
            $table->string('transaction_number')->unique();
            $table->date('transaction_date');
            $table->enum('type', ['debit', 'credit']);
            $table->decimal('amount', 15, 2);
            $table->string('reference')->nullable(); // Invoice #, Payment #, etc.
            $table->text('description');
            $table->enum('source', ['manual', 'automated'])->default('manual');
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->json('metadata')->nullable(); // Related invoice_id, payment_id, etc.
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
