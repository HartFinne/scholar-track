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
        Schema::create('ojt_travel_itinerary', function (Blueprint $table) {
            $table->increments('ojtLocationID');
            $table->unsignedInteger('ojtID');
            $table->string('ojt_from', 100);
            $table->string('ojt_to', 100);
            $table->string('ojt_estimated_time', 100);
            $table->string('ojt_vehicle_type', 100);
            $table->integer('ojt_fare_rate');
            $table->timestamps();

            $table->foreign('ojtID')->references('ojtID')->on('ojt_location')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ojt_travel_itinerary');
    }
};
