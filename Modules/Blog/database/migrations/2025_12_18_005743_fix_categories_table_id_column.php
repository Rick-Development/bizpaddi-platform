<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Fix the id column to be auto-increment
        DB::statement('ALTER TABLE categories MODIFY id BIGINT UNSIGNED AUTO_INCREMENT');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No need to reverse this fix
    }
};
