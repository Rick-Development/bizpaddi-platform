<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('subsidiaries', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description');
            $table->string('website')->nullable();
            $table->string('location')->nullable();
            $table->timestamps();
        });
        Schema::create('partners', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description');
            $table->string('website')->nullable();
            $table->string('partnership_type');
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('partners');
        Schema::dropIfExists('subsidiaries');
    }
};
