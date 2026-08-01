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
            $table->boolean('requires_client_approval')->default(false)->after('status');
            $table->enum('approval_status', ['pending', 'approved', 'changes_requested'])->nullable()->after('requires_client_approval');
            $table->text('client_feedback')->nullable()->after('approval_status');
            $table->timestamp('approved_at')->nullable()->after('client_feedback');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pm_tasks', function (Blueprint $table) {
            $table->dropColumn(['requires_client_approval', 'approval_status', 'client_feedback', 'approved_at']);
        });
    }
};
