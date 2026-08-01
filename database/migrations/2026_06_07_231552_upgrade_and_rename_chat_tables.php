<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Drop foreign keys to allow renaming without constraint issues
        try {
            Schema::table('pm_messages', function (Blueprint $table) {
                $table->dropForeign(['conversation_id']);
            });
        } catch (\Exception $e) {}

        if (Schema::hasTable('pm_conversations')) {
            Schema::rename('pm_conversations', 'project_conversations');
        }
        
        if (Schema::hasTable('pm_messages')) {
            Schema::rename('pm_messages', 'project_messages');
        }

        if (!Schema::hasColumn('project_conversations', 'status')) {
            Schema::table('project_conversations', function (Blueprint $table) {
                $table->enum('status', ['active', 'suspended', 'terminated'])->default('active')->after('type');
                $table->string('name')->nullable()->after('status');
            });
        }

        if (!Schema::hasColumn('project_messages', 'parent_id')) {
            Schema::table('project_messages', function (Blueprint $table) {
                $table->foreign('conversation_id')->references('id')->on('project_conversations')->onDelete('cascade');
                $table->foreignId('parent_id')->nullable()->constrained('project_messages')->onDelete('cascade')->after('id');
            });
        }

        if (!Schema::hasTable('project_participants')) {
            Schema::create('project_participants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('conversation_id')->constrained('project_conversations')->onDelete('cascade');
            $table->unsignedBigInteger('user_id');
            $table->string('user_type', 191); // App\Models\User, Modules\Clients\Models\Client, App\Models\Admin
            $table->timestamps();
            
            $table->index(['user_id', 'user_type']);
            $table->unique(['conversation_id', 'user_id', 'user_type'], 'project_participant_unique');
        });

        }

        if (!Schema::hasTable('project_message_reads')) {
            Schema::create('project_message_reads', function (Blueprint $table) {
                $table->id();
                $table->foreignId('message_id')->constrained('project_messages')->onDelete('cascade');
                $table->unsignedBigInteger('user_id');
                $table->string('user_type', 191);
                $table->timestamp('read_at')->useCurrent();
                $table->timestamps();

                $table->index(['user_id', 'user_type']);
                $table->unique(['message_id', 'user_id', 'user_type'], 'project_message_read_unique');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('project_message_reads');
        Schema::dropIfExists('project_participants');

        Schema::table('project_messages', function (Blueprint $table) {
            $table->dropForeign(['parent_id']);
            $table->dropColumn('parent_id');
            $table->dropForeign(['conversation_id']);
        });

        Schema::table('project_conversations', function (Blueprint $table) {
            $table->dropColumn(['status', 'name']);
        });

        Schema::rename('project_messages', 'pm_messages');
        Schema::rename('project_conversations', 'pm_conversations');

        Schema::table('pm_messages', function (Blueprint $table) {
            $table->foreign('conversation_id')->references('id')->on('pm_conversations')->onDelete('cascade');
        });
    }
};
