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
        Schema::create('pm_conversations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained('pm_projects')->onDelete('cascade');
            $table->string('type')->default('client_team'); // client_team, internal_team
            $table->timestamps();

            // Ensure one client-team chat per project
            $table->unique(['project_id', 'type']);
        });

        Schema::create('pm_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('conversation_id')->constrained('pm_conversations')->onDelete('cascade');
            $table->unsignedBigInteger('sender_id');
            $table->string('sender_type'); // App\Models\User or Modules\Clients\Models\Client
            $table->text('content');
            $table->json('attachments')->nullable();
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
            
            // Index for faster lookups
            $table->index(['sender_id', 'sender_type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pm_messages');
        Schema::dropIfExists('pm_conversations');
    }


};
