<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contracts', function (Blueprint $table) {
            $table->id();
            $table->string('contract_number')->unique();
            $table->string('title');
            $table->enum('contract_type', ['sla', 'nda', 'employment', 'service_agreement', 'partnership', 'vendor', 'other']);
            $table->enum('party_type', ['client', 'employee', 'vendor', 'partner', 'other'])->nullable();
            $table->unsignedBigInteger('party_id')->nullable(); // Generic foreign key
            $table->string('party_name');
            $table->string('party_email')->nullable();
            $table->text('party_address')->nullable();
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->integer('duration_months')->nullable();
            $table->boolean('auto_renew')->default(false);
            $table->decimal('value', 15, 2)->nullable();
            $table->foreignId('currency_id')->nullable()->constrained('currencies')->nullOnDelete();
            $table->enum('status', ['draft', 'pending_signature', 'active', 'expired', 'terminated', 'completed'])->default('draft');
            $table->longText('content'); // Contract body
            $table->text('terms')->nullable(); // Special terms
            $table->text('notes')->nullable(); // Internal notes
            $table->datetime('signed_at')->nullable();
            $table->string('signed_by')->nullable();
            $table->string('signature_file')->nullable(); // Path to signature image
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->datetime('approved_at')->nullable();
            $table->timestamps();
            
            $table->index(['contract_type', 'status']);
            $table->index('party_type');
            $table->index('start_date');
            $table->index('end_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contracts');
    }
};
