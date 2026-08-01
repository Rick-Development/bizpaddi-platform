<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contract_templates', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('contract_type', ['sla', 'nda', 'employment', 'service_agreement', 'partnership', 'vendor', 'other']);
            $table->text('description')->nullable();
            $table->longText('content'); // Template with placeholders
            $table->text('default_terms')->nullable();
            $table->boolean('is_active')->default(true);
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
            $table->timestamps();
            
            $table->index('contract_type');
            $table->index('is_active');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contract_templates');
    }
};
