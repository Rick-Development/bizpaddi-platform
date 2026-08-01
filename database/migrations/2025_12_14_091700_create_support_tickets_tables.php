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
        Schema::create('support_tickets', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('client_id');
            $table->string('subject');
            $table->enum('status', ['open', 'active', 'pending', 'resolved', 'closed'])->default('open');
            $table->enum('priority', ['low', 'medium', 'high', 'urgent'])->default('medium');
            $table->timestamps();

            $table->foreign('client_id')->references('id')->on('clients')->onDelete('cascade');
        });

        Schema::create('support_ticket_messages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('support_ticket_id');
            $table->nullableMorphs('sender'); // sender_id, sender_type (Client or User)
            $table->text('message');
            $table->string('attachment_path')->nullable();
            $table->timestamps();

            $table->foreign('support_ticket_id')->references('id')->on('support_tickets')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('support_ticket_messages');
        Schema::dropIfExists('support_tickets');
        Schema::dropIfExists('support_tickets_tables'); // Just in case
    }
};
