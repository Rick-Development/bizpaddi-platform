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
        Schema::table('invoices', function (Blueprint $table) {
            $table->string('client_name')->nullable()->after('client_id');
            $table->string('client_email')->nullable()->after('client_name');
            $table->text('client_address')->nullable()->after('client_email');
            $table->string('client_phone')->nullable()->after('client_address');
            
            $table->decimal('subtotal', 10, 2)->default(0)->after('due_date');
            $table->decimal('tax_rate', 5, 2)->default(0)->after('subtotal');
            $table->decimal('tax_amount', 10, 2)->default(0)->after('tax_rate');
            $table->decimal('discount_amount', 10, 2)->default(0)->after('tax_amount');
            $table->decimal('total_amount', 10, 2)->default(0)->after('discount_amount');
            
            $table->text('terms_conditions')->nullable()->after('notes');
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null')->after('terms_conditions');
            $table->unsignedBigInteger('quote_id')->nullable()->after('created_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropForeign(['created_by']);
            $table->dropColumn([
                'client_name',
                'client_email',
                'client_address',
                'client_phone',
                'subtotal',
                'tax_rate',
                'tax_amount',
                'discount_amount',
                'total_amount',
                'terms_conditions',
                'created_by',
                'quote_id',
            ]);
        });
    }
};
