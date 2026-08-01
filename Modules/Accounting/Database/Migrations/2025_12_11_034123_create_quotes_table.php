<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('quotes', function (Blueprint $table) {
            $table->id();
            $table->string('quote_number')->unique();
            $table->string('client_name');
            $table->string('client_email');
            $table->string('client_company')->nullable();
            $table->string('client_phone')->nullable();
            $table->date('issue_date');
            $table->date('valid_until');
            $table->decimal('subtotal', 10, 2)->default(0);
            $table->decimal('tax_rate', 5, 2)->default(0);
            $table->decimal('tax_amount', 10, 2)->default(0);
            $table->decimal('discount_amount', 10, 2)->default(0);
            $table->decimal('total_amount', 10, 2)->default(0);
            $table->enum('status', ['draft', 'sent', 'approved', 'rejected', 'expired', 'converted'])->default('draft');
            $table->text('notes')->nullable();
            $table->text('terms_conditions')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('approved_at')->nullable();
            $table->foreignId('converted_to_invoice_id')->nullable()->constrained('invoices')->onDelete('set null');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('quotes');
    }
};
