<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Drop legacy subscribers table if it exists
        Schema::dropIfExists('subscribers');
    }

    public function down(): void
    {
        // No rollback needed
    }
};
