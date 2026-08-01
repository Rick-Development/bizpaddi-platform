<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('campaigns', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('subject');
            $table->text('content');
            $table->enum('type', ['email', 'whatsapp', 'both']);
            $table->enum('status', ['draft', 'scheduled', 'sent'])->default('draft');
            $table->timestamp('scheduled_at')->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('campaigns'); }
};
