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
        try {
            Schema::table('visitors', function (Blueprint $table) {
                $table->bigIncrements('id')->change();
            });
        } catch (\Throwable $e) {
            // Already auto-increment
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('visitors', function (Blueprint $table) {
            $table->bigInteger('id')->unsigned()->change();
        });
    }
};
