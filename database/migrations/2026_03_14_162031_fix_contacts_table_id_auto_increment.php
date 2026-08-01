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
        Schema::table('contacts', function (Blueprint $table) {
            // Shorten email to avoid "Specified key was too long" error with utf8mb4
            $table->string('email', 191)->change();
        });

        // Use raw SQL to add PK and AI only if needed, 
        // but since we know they are there from my manual inspection, 
        // we can just make this section conditional or comment it out if it would fail.
        // Better: use direct checks.
        
        $hasPrimaryKey = collect(DB::select("SHOW INDEXES FROM contacts WHERE Key_name = 'PRIMARY'"))->isNotEmpty();
        if (!$hasPrimaryKey) {
            DB::statement('ALTER TABLE contacts ADD PRIMARY KEY (id)');
        }

        $isAutoIncrement = collect(DB::select("SHOW COLUMNS FROM contacts WHERE Field = 'id' AND Extra = 'auto_increment'"))->isNotEmpty();
        if (!$isAutoIncrement) {
             DB::statement('ALTER TABLE contacts MODIFY id BIGINT UNSIGNED AUTO_INCREMENT');
        }

        Schema::table('contacts', function (Blueprint $table) {
            if (!collect(DB::select("SHOW INDEXES FROM contacts WHERE Key_name = 'contacts_email_unique'"))->isNotEmpty()) {
                $table->unique('email');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('contacts', function (Blueprint $table) {
            //
        });
    }
};
