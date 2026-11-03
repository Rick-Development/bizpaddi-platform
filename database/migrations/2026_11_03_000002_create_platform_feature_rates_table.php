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
        if (!Schema::hasTable('platform_feature_rates')) {
            Schema::create('platform_feature_rates', function (Blueprint $table) {
                $table->id();
                $table->string('action_key')->unique();
                $table->string('name');
                $table->string('category', 50)->default('General');
                $table->decimal('fee', 15, 2)->default(0.00);
                $table->string('currency', 10)->default('NGN');
                $table->string('unit_label', 50)->default('per action');
                $table->text('description')->nullable();
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('platform_feature_rates');
    }
};

