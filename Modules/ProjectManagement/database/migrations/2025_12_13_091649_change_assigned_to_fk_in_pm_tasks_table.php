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
        Schema::table('pm_tasks', function (Blueprint $table) {
            // Drop foreign key to admins
            $table->dropForeign(['assigned_to']);
            
            // Re-add foreign key to users
            // Assuming users table exists and id is appropriate
            $table->foreign('assigned_to')->references('id')->on('users')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pm_tasks', function (Blueprint $table) {
            $table->dropForeign(['assigned_to']);
            $table->foreign('assigned_to')->references('id')->on('admins')->nullOnDelete();
        });
    }
};
