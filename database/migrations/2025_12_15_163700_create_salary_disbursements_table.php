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
        Schema::create('salary_disbursements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->decimal('amount', 15, 2);
            $table->string('currency')->default('NGN');
            $table->string('bank_code');
            $table->string('account_number');
            $table->string('account_name')->nullable();
            $table->string('session_id')->nullable();
            $table->string('reference')->unique();
            $table->string('status')->default('pending'); // pending, processing, successful, failed
            $table->text('description')->nullable();
            $table->timestamp('payment_date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('salary_disbursements');
    }
};
