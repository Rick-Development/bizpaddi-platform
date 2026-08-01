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
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('excerpt')->nullable();
            $table->longText('content');
            $table->foreignId('category_id')->nullable()->constrained('categories')->nullOnDelete();
            $table->foreignId('author_id')->constrained('users')->cascadeOnDelete();
            
            // SEO fields
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->text('meta_keywords')->nullable();
            
            // AI generation tracking
            $table->boolean('ai_generated')->default(false);
            $table->string('ai_model')->nullable(); // e.g., 'gpt-4', 'claude-3'
            $table->timestamp('ai_generated_at')->nullable();
            $table->json('ai_metadata')->nullable(); // Store AI generation parameters
            
            // Featured image (will use Spatie Media Library)
            $table->string('featured_image_prompt')->nullable(); // AI image generation prompt
            
            // Publishing
            $table->enum('status', ['draft', 'published', 'scheduled', 'archived'])->default('draft');
            $table->timestamp('published_at')->nullable();
            $table->timestamp('scheduled_at')->nullable();
            
            // Analytics
            $table->unsignedBigInteger('views_count')->default(0);
            $table->unsignedInteger('reading_time')->nullable(); // in minutes
            
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes
            $table->index('slug');
            $table->index('status');
            $table->index('published_at');
            $table->index('category_id');
            $table->index('author_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
