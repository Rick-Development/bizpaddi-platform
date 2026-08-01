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
        Schema::create('service_orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('client_id');
            $table->unsignedBigInteger('service_id');
            $table->enum('status', ['pending', 'reviewed', 'quoted', 'invoiced', 'contracted', 'completed', 'cancelled'])->default('pending');
            $table->json('details')->nullable(); // For custom requirements
            $table->text('admin_notes')->nullable();
            $table->text('admin_feedback')->nullable();
            $table->json('feature_selections')->nullable(); // Snapshot of selected features
            
            // Linked modules
            $table->unsignedBigInteger('quote_id')->nullable();
            $table->unsignedBigInteger('invoice_id')->nullable();
            // Contract relationship can be polymorphic or direct if Contract model exists
            $table->unsignedBigInteger('contract_id')->nullable(); 

            $table->timestamps();

            $table->foreign('client_id')->references('id')->on('clients')->onDelete('cascade');
            $table->foreign('service_id')->references('id')->on('services')->onDelete('cascade');
            // Assuming quotes/invoices tables exist, but making nullable Foreign Keys safe if tables are created later or names differ
            // We'll skip strict FK constraints for linked modules to avoid dependency hell for now, or check table names.
            // Quotes -> quotes table? Invoices -> invoices table?
            // Let's add them if we are sure.
            $table->foreign('quote_id')->references('id')->on('quotes')->nullOnDelete();
            $table->foreign('invoice_id')->references('id')->on('invoices')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_orders');
    }
};
