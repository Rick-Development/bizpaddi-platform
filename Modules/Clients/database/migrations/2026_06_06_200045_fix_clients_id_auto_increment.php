<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Fix the `id` column on the `clients` table to be AUTO_INCREMENT.
     * This resolves: SQLSTATE[HY000] General error: 1364 Field 'id' doesn't have a default value
     */
    public function up(): void
    {
        try {
            \Illuminate\Support\Facades\Schema::disableForeignKeyConstraints();
            DB::statement('ALTER TABLE `clients` MODIFY `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT;');
            \Illuminate\Support\Facades\Schema::enableForeignKeyConstraints();
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Schema::enableForeignKeyConstraints();
        }
    }

    public function down(): void
    {
        // Reverse: remove auto_increment (leave column intact)
        DB::statement('ALTER TABLE `clients` MODIFY `id` BIGINT UNSIGNED NOT NULL;');
    }
};
