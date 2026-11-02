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
        if (!Schema::hasTable('chats')) {
            Schema::create('chats', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id'); // Client
                $table->unsignedBigInteger('admin_id')->nullable(); 
                $table->string('subject')->nullable(); // e.g. "Project X Query"
                $table->boolean('is_closed')->default(false);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('chat_messages')) {
            Schema::create('chat_messages', function (Blueprint $table) {
                $table->id();
                $table->foreignId('chat_id')->constrained('chats')->onDelete('cascade');
                $table->unsignedBigInteger('sender_id');
                $table->string('sender_type');
                $table->text('message')->nullable();
                $table->string('attachment_path')->nullable();
                $table->string('attachment_type')->nullable();
                $table->string('attachment_name')->nullable();
                $table->timestamp('read_at')->nullable();
                $table->timestamps();
                $table->index(['sender_id', 'sender_type']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chat_messages');
        Schema::dropIfExists('chats');
    }
};
