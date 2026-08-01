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
        Schema::create('chats', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); // Client
            // We assume 'admins' table exists, but use loose coupling or polymorphic if unsure.
            // Better to allow admin_id to be nullable.
            $table->unsignedBigInteger('admin_id')->nullable(); 
            $table->string('subject')->nullable(); // e.g. "Project X Query"
            $table->boolean('is_closed')->default(false);
            $table->timestamps();
            
            // Foreign keys if tables are standard
            // $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

        Schema::create('chat_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('chat_id')->constrained('chats')->onDelete('cascade');
            
            // Sender
            $table->unsignedBigInteger('sender_id');
            $table->string('sender_type'); // 'user' or 'admin' or Model class
            
            $table->text('message')->nullable(); // Nullable if only file
            $table->string('attachment_path')->nullable();
            $table->string('attachment_type')->nullable(); // image, file, etc
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
        Schema::dropIfExists('chat_messages');
        Schema::dropIfExists('chats');
    }
};
