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
            Schema::table('contacts', function (Blueprint $table) {
                $table->string('email', 191)->change();
            });
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
        Schema::table('contacts', function (Blueprint $table) {
            //
        });
    }
};
