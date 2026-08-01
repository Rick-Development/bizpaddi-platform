<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('services', function (Blueprint $table) {
            // Add category_id column for accounting services
            $table->foreignId('category_id')->nullable()->after('id')->constrained('service_categories')->onDelete('cascade');
            
            // Modify existing columns to match accounting services structure
            $table->decimal('base_price', 10, 2)->nullable()->after('description');
            $table->string('unit')->default('hour')->after('base_price');
        });
    }

    public function down(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->dropForeign(['category_id']);
            $table->dropColumn(['category_id', 'base_price', 'unit']);
        });
    }
};
