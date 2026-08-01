<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('campaign_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('campaign_id')->constrained()->cascadeOnDelete();
            $table->foreignId('contact_id')->constrained()->cascadeOnDelete();
            $table->enum('status', ['sent', 'opened', 'clicked', 'bounced', 'failed'])->default('sent');
            $table->timestamp('sent_at')->nullable();
            $table->timestamp('opened_at')->nullable();
            $table->timestamp('clicked_at')->nullable();
            $table->text('error_message')->nullable();
            $table->timestamps();
            
            $table->index(['campaign_id', 'contact_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('campaign_logs');
    }
};
