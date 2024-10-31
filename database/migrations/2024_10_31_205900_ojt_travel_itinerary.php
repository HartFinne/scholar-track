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
            $table->increments('ojtID');
            $table->unsignedInteger('regularID');
            $table->date('start_of_ojt');
            $table->date('end_of_ojt');
            $table->string('endorsement', 255);
            $table->string('acceptance', 255);
            $table->timestamps();

            $table->foreign('regularID')->references('regularID')->on('regular_allowance')->onDelete('cascade');
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
