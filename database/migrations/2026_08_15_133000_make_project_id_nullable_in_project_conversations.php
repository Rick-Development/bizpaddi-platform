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
        // 1. Drop foreign key constraint safely in separate try-catch blocks
        try {
            Schema::table('project_conversations', function (Blueprint $table) {
                $table->dropForeign('pm_conversations_project_id_foreign');
            });
        } catch (\Exception $e) {}

        try {
            Schema::table('project_conversations', function (Blueprint $table) {
                $table->dropForeign('project_conversations_project_id_foreign');
            });
        } catch (\Exception $e) {}

        try {
            Schema::table('project_conversations', function (Blueprint $table) {
                $table->dropForeign(['project_id']);
            });
        } catch (\Exception $e) {}

        // 2. Drop unique constraints safely in separate try-catch blocks
        try {
            Schema::table('project_conversations', function (Blueprint $table) {
                $table->dropUnique('pm_conversations_project_id_type_unique');
            });
        } catch (\Exception $e) {}

        try {
            Schema::table('project_conversations', function (Blueprint $table) {
                $table->dropUnique('project_conversations_project_id_type_unique');
            });
        } catch (\Exception $e) {}

        try {
            Schema::table('project_conversations', function (Blueprint $table) {
                $table->dropUnique(['project_id', 'type']);
            });
        } catch (\Exception $e) {}

        // 3. Make project_id nullable and restore foreign key constraint
        Schema::table('project_conversations', function (Blueprint $table) {
            $table->foreignId('project_id')->nullable()->change();
            
            $table->foreign('project_id')
                ->references('id')
                ->on('pm_projects')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('project_conversations', function (Blueprint $table) {
            try {
                $table->dropForeign(['project_id']);
            } catch (\Exception $e) {}
            
            $table->foreignId('project_id')->nullable(false)->change();
            
            $table->foreign('project_id')
                ->references('id')
                ->on('pm_projects')
                ->onDelete('cascade');

            $table->unique(['project_id', 'type']);
        });
    }
};
