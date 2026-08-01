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
        Schema::create('pm_comments', function (Blueprint $row) {
            $row->id();
            $row->morphs('commentable'); // project or task
            $row->unsignedBigInteger('user_id')->nullable(); // user or admin
            $row->string('user_type')->nullable(); // Guard/Type
            $row->text('content');
            $row->boolean('is_internal')->default(false); // Admin only view
            $row->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pm_comments');
    }
};
