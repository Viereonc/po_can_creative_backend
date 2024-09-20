<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('buses', function (Blueprint $table) {
            $table->id('bus_id');
            $table->string('bus_name');
            $table->integer('seat_capacity');
            $table->timestamps();
        });
    }
    
    public function down(): void
    {
        Schema::dropIfExists('buses');
    }
};
