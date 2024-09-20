<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('destinations', function (Blueprint $table) {
            $table->id('destination_id');
            $table->unsignedBigInteger('bus_id');
            $table->string('start_location');
            $table->string('end_location');
            $table->dateTime('departure_time');
            $table->timestamps();

            $table->foreign('bus_id')->references('bus_id')->on('buses')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('destinations');
    }
};
