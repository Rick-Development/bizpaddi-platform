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
        // Use raw SQL to fix the id column constraint
        \Illuminate\Support\Facades\DB::statement('ALTER TABLE settings MODIFY id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
