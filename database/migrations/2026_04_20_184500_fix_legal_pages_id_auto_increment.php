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
        if (Schema::hasTable('legal_pages')) {
            // Ensure the id column is BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY
            // We use raw SQL as change() might require doctrine/dbal or have issues with AI in some environments
            
            try {
                DB::statement('ALTER TABLE legal_pages MODIFY id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY');
            } catch (\Exception $e) {
                // If it's already a primary key, just try to modify it to AUTO_INCREMENT
                try {
                    DB::statement('ALTER TABLE legal_pages MODIFY id BIGINT UNSIGNED AUTO_INCREMENT');
                } catch (\Exception $e2) {
                    // Log or ignore if it fails (already fixed or different issue)
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
