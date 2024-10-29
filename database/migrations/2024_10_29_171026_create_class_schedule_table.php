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
        Schema::create('class_schedule', function (Blueprint $table) {
            $table->increments('classSchedID');
            $table->unsignedInteger('classID');
            $table->string('time_slot', 100);
            $table->string('mon', 50)->nullable();
            $table->string('tue', 50)->nullable();
            $table->string('wed', 50)->nullable();
            $table->string('thu', 50)->nullable();
            $table->string('fri', 50)->nullable();
            $table->string('sat', 50)->nullable();
            $table->string('sun', 50)->nullable();
            $table->timestamps();

            $table->foreign('classID')->references('classID')->on('class_reference')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('class_schedule');
    }
};
