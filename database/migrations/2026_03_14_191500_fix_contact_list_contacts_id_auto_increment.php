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
            $hasPrimaryKey = collect(DB::select("SHOW INDEXES FROM contact_list_contacts WHERE Key_name = 'PRIMARY'"))->isNotEmpty();
            if (!$hasPrimaryKey) {
                DB::statement('ALTER TABLE contact_list_contacts ADD PRIMARY KEY (id)');
            }
            $isAutoIncrement = collect(DB::select("SHOW COLUMNS FROM contact_list_contacts WHERE Field = 'id' AND Extra = 'auto_increment'"))->isNotEmpty();
            if (!$isAutoIncrement) {
                DB::statement('ALTER TABLE contact_list_contacts MODIFY id BIGINT UNSIGNED AUTO_INCREMENT');
            }
        } catch (\Throwable $e) {
            // Ignore if already configured
        }

        // Add updated_at if it's missing
        if (!Schema::hasColumn('contact_list_contacts', 'updated_at')) {
            Schema::table('contact_list_contacts', function (Blueprint $table) {
                $table->timestamp('updated_at')->nullable()->after('created_at');
            });
            DB::statement('UPDATE contact_list_contacts SET updated_at = created_at WHERE updated_at IS NULL');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No down migration needed for a structural fix like this
    }
};
