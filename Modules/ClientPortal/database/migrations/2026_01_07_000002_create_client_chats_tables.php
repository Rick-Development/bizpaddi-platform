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
        // Create client_chats table (separate from support_tickets)
        Schema::create('client_chats', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); // Client
            $table->unsignedBigInteger('admin_id')->nullable(); 
            $table->string('subject')->nullable();
            $table->boolean('is_closed')->default(false);
            $table->timestamps();
        });

        // Create client_chat_messages table (separate from support_ticket_messages)
        Schema::create('client_chat_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_chat_id')->constrained('client_chats')->onDelete('cascade');
            
            // Sender
            $table->unsignedBigInteger('sender_id');
            $table->string('sender_type'); // 'user' or 'admin'
            
            $table->text('message')->nullable();
            $table->string('attachment_path')->nullable();
            $table->string('attachment_type')->nullable();
            $table->string('attachment_name')->nullable();
            
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
            
            $table->index(['sender_id', 'sender_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('client_chat_messages');
        Schema::dropIfExists('client_chats');
    }
};
