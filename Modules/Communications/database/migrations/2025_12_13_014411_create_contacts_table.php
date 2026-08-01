<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contacts', function (Blueprint $table) {
            $table->id();
            $table->string('email')->unique();
            $table->string('first_name', 100)->nullable();
            $table->string('last_name', 100)->nullable();
            $table->string('phone', 20)->nullable();
            $table->string('company')->nullable();
            $table->enum('status', ['active', 'unsubscribed', 'bounced'])->default('active');
            $table->timestamp('subscribed_at')->nullable();
            $table->timestamp('unsubscribed_at')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contacts');
    }
};
