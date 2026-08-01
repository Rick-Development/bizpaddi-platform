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
        Schema::table('support_ticket_messages', function (Blueprint $table) {
            $table->boolean('is_admin')->default(false)->after('message');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('support_ticket_messages', function (Blueprint $table) {
            $table->dropColumn('is_admin');
        });
    }
};
