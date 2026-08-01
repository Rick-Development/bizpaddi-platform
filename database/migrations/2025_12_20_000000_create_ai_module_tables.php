<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // AI Settings Table
        if (!Schema::hasTable('ai_settings')) {
            Schema::create('ai_settings', function (Blueprint $table) {
                $table->id();
                $table->string('provider'); // openai, anthropic, gemini
                $table->string('key'); // api_key, model, etc.
                $table->text('value')->nullable();
                $table->string('type')->default('string'); // string, boolean, integer, json
                $table->boolean('is_encrypted')->default(false);
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }

        // Chat Conversations Table
        if (!Schema::hasTable('chat_conversations')) {
            Schema::create('chat_conversations', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
                $table->string('session_id')->unique();
                $table->string('visitor_name')->nullable();
                $table->string('visitor_email')->nullable();
                $table->string('status')->default('active'); // active, resolved, closed
                $table->string('sentiment')->nullable();
                $table->text('summary')->nullable();
                $table->timestamp('last_message_at')->nullable();
                $table->timestamps();
            });
        }

        // Chat Messages Table
        if (!Schema::hasTable('chat_messages')) {
            Schema::create('chat_messages', function (Blueprint $table) {
                $table->id();
                $table->foreignId('conversation_id')->constrained('chat_conversations')->cascadeOnDelete();
                $table->string('role'); // user, assistant, system
                $table->text('message');
                $table->json('metadata')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('chat_messages');
        Schema::dropIfExists('chat_conversations');
        Schema::dropIfExists('ai_settings');
    }
};
