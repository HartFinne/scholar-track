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
        Schema::create('travel_location', function (Blueprint $table) {
            $table->increments('locationID');
            $table->unsignedInteger('travelID');
            $table->string('travel_from', 100)->nullable();
            $table->string('travel_to', 100)->nullable();
            $table->string('estimated_time', 100)->nullable();
            $table->string('vehicle_type', 100)->nullable();
            $table->integer('fare_rate')->nullable();
            $table->timestamps();

            $table->foreign('travelID')->references('travelID')->on('travel_itinerary')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('travel_location');
    }
};
