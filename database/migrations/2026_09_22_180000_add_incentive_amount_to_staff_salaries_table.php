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
        Schema::table('staff_salaries', function (Blueprint $table) {
            $table->decimal('incentive_amount', 12, 2)->default(0.00)->after('amount');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('staff_salaries', function (Blueprint $table) {
            $table->dropColumn('incentive_amount');
        });
    }
};
