<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('case_studies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->string('client_name');
            $table->text('challenge');
            $table->text('solution');
            $table->text('results');
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('case_studies'); }
};
