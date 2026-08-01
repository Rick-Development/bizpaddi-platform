<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('feature_prices', function (Blueprint $table) {
            $table->id();
            $table->string('feature_name'); // e.g., "Auth Module", "Bill Payment", "VTU"
            $table->string('feature_code')->unique(); // e.g., "auth_module", "bill_payment"
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2); // Price in your currency
            $table->string('currency', 3)->default('NGN'); // NGN, USD, etc.
            $table->enum('billing_type', ['one_time', 'monthly', 'yearly'])->default('one_time');
            $table->boolean('is_active')->default(true);
            $table->json('parameters')->nullable(); // Additional params as JSON
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('feature_prices');
    }
};
