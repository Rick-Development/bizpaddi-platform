<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('leads', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('phone')->nullable();
            $table->string('company')->nullable();
            
            // Lead tracking
            $table->enum('status', ['new', 'contacted', 'nurturing', 'qualified', 'converted', 'lost'])->default('new');
            $table->string('source')->nullable()->comment('e.g., Website Form, Referral, Cold Call, Social Media');
            $table->integer('score')->default(0)->comment('Lead score 0-100');
            
            // Assignment
            $table->foreignId('assigned_to')->nullable()->constrained('users')->onDelete('set null');
            
            // Activity tracking
            $table->timestamp('last_contacted_at')->nullable();
            
            // Conversion tracking
            $table->foreignId('converted_to_contact_id')->nullable()->constrained('contacts')->onDelete('set null');
            $table->timestamp('converted_at')->nullable();
            
            // Flexible data storage
            $table->json('metadata')->nullable();
            $table->text('notes')->nullable();
            
            $table->timestamps();
            
            // Indexes for performance
            $table->index('status');
            $table->index('assigned_to');
            $table->index('source');
            $table->index('score');
        });
    }
    public function down(): void { Schema::dropIfExists('leads'); }
};
