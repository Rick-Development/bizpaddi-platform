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
        Schema::table('leads', function (Blueprint $table) {
            // Make email unique if not already
            $table->string('email')->unique()->change();
            
            // Add missing lead management fields without positional constraints
            if (!Schema::hasColumn('leads', 'source')) {
                $table->string('source')->nullable()->comment('e.g., Website Form, Referral, Cold Call, Social Media');
            }
            
            if (!Schema::hasColumn('leads', 'score')) {
                $table->integer('score')->default(0)->comment('Lead score 0-100');
            }
            
            if (!Schema::hasColumn('leads', 'assigned_to')) {
                $table->unsignedBigInteger('assigned_to')->nullable();
            }

            if (!Schema::hasColumn('leads', 'last_contacted_at')) {
                $table->timestamp('last_contacted_at')->nullable();
            }
            
            if (!Schema::hasColumn('leads', 'converted_to_contact_id')) {
                $table->unsignedBigInteger('converted_to_contact_id')->nullable();
            }
            
            if (!Schema::hasColumn('leads', 'converted_at')) {
                $table->timestamp('converted_at')->nullable();
            }
            
            if (!Schema::hasColumn('leads', 'metadata')) {
                $table->json('metadata')->nullable();
            }
            
            // Update status enum to include all required values
            $table->enum('status', ['new', 'contacted', 'nurturing', 'qualified', 'converted', 'lost'])->default('new')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->dropColumn([
                'source',
                'score',
                'assigned_to',
                'last_contacted_at',
                'converted_to_contact_id',
                'converted_at',
                'metadata',
            ]);
            
            // Revert status enum
            $table->enum('status', ['new', 'contacted', 'qualified', 'converted', 'lost'])->default('new')->change();
        });
    }
};
