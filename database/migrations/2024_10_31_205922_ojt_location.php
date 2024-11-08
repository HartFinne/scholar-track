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
        Schema::create('ojt_location', function (Blueprint $table) {
            $table->increments('ojtLocationID');
            $table->unsignedInteger('ojtID')->nullable();
            $table->string('ojt_from', 100)->nullable();
            $table->string('ojt_to', 100)->nullable();
            $table->string('ojt_estimated_time', 100)->nullable();
            $table->string('ojt_vehicle_type', 100)->nullable();
            $table->integer('ojt_fare_rate')->nullable();
            $table->timestamps();

            $table->foreign('ojtID')->references('ojtID')->on('ojt_travel_itinerary')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ojt_location');
    }
};
