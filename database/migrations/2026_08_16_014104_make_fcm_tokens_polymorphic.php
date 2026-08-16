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
        Schema::table('fcm_tokens', function (Blueprint $table) {
            if (!Schema::hasColumn('fcm_tokens', 'user_type')) {
                // Try dropping foreign key, but ignore if it fails
                try {
                    $table->dropForeign(['user_id']);
                } catch (\Exception $e) {
                    // Ignore missing foreign key
                }
                $table->string('user_type')->default('App\Models\User')->after('user_id');
                $table->index(['user_id', 'user_type']);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('fcm_tokens', function (Blueprint $table) {
            $table->dropColumn('user_type');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }
};
