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
        Schema::table('visitors', function (Blueprint $table) {
            $table->string('country')->nullable()->after('referer');
            $table->string('state')->nullable()->after('country');
            $table->string('city')->nullable()->after('state');
            $table->string('lga')->nullable()->after('city');
            $table->string('browser')->nullable()->after('lga');
            $table->string('device')->nullable()->after('browser');
            $table->string('platform')->nullable()->after('device');
            $table->integer('time_spent')->default(0)->after('platform'); // in seconds
            $table->string('session_id')->nullable()->after('time_spent');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('visitors', function (Blueprint $table) {
            $table->dropColumn([
                'country',
                'state',
                'city',
                'lga',
                'browser',
                'device',
                'platform',
                'time_spent',
                'session_id',
            ]);
        });
    }
};
