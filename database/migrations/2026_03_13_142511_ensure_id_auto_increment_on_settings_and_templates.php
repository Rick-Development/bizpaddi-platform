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
        Schema::table('settings', function (Blueprint $table) {
            $table->id()->change();
        });

        Schema::table('email_templates', function (Blueprint $table) {
            $table->id()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No easy way to reverse auto-increment safely without knowing previous state
    }
};
