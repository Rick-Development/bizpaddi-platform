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
        if (Schema::hasColumn('contact_list_contacts', 'updated_at')) {
            Schema::table('contact_list_contacts', function (Blueprint $table) {
                $table->dropColumn('updated_at');
            });
        }
    }
};
