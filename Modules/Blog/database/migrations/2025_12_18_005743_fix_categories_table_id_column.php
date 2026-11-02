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
        try {
            Schema::disableForeignKeyConstraints();
            DB::statement('ALTER TABLE categories MODIFY id BIGINT UNSIGNED AUTO_INCREMENT');
            Schema::enableForeignKeyConstraints();
        } catch (\Throwable $e) {
            Schema::enableForeignKeyConstraints();
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No need to reverse this fix
    }
};
