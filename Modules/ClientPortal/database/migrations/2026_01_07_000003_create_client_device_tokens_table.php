<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('client_device_tokens', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('client_id'); // Assuming clients table uses 'id'
            $table->string('fcm_token')->unique();
            $table->string('device_type')->nullable(); // android, ios, web
            $table->timestamps();

            // Foreign key constraint (assuming clients table exists)
            // If strictly isolated, we might skip the foreign key constraint definition here if 'clients' isn't guaranteed, 
            // but usually it is. I'll add it safely.
            if (Schema::hasTable('clients')) {
               $table->foreign('client_id')->references('id')->on('clients')->onDelete('cascade');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('client_device_tokens');
    }
};
