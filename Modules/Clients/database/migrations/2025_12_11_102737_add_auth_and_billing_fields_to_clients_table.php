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
        Schema::table('clients', function (Blueprint $table) {
            // Authentication
            $table->string('email')->unique()->after('name');
            $table->string('password')->after('email');
            
            // Company / Billing Info
            $table->string('company_name')->nullable()->after('password');
            $table->string('phone')->nullable()->after('company_name');
            $table->string('address')->nullable()->after('phone');
            $table->string('city')->nullable()->after('address');
            $table->string('state')->nullable()->after('city');
            $table->string('zip')->nullable()->after('state');
            $table->string('country')->nullable()->after('zip');
            $table->string('tax_id')->nullable()->after('country');
            
            // Status
            $table->boolean('is_active')->default(true)->after('description');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->dropColumn([
                'email',
                'password',
                'company_name',
                'phone',
                'address',
                'city',
                'state',
                'zip',
                'country',
                'tax_id',
                'is_active',
            ]);
        });
    }
};
