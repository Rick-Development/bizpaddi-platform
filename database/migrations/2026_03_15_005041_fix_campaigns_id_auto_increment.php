<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
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
            DB::statement('ALTER TABLE campaigns MODIFY id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT');
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
        DB::statement('ALTER TABLE campaigns MODIFY id BIGINT UNSIGNED NOT NULL');
    }
};
