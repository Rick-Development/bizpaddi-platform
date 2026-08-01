<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('featured_contents', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('excerpt')->nullable();
            $table->string('type'); // 'project', 'news', 'blog', 'service'
            $table->string('link')->nullable();
            $table->integer('order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            $table->index('type');
            $table->index('is_active');
            $table->index('order');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('featured_contents');
    }
};
