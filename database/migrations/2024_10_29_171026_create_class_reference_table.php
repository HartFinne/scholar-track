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
        Schema::create('class_reference', function (Blueprint $table) {
            $table->increments('classID');
            $table->unsignedInteger('regularID');
            $table->string('registration_form', 255);
            $table->timestamps();

            $table->foreign('regularID')->references('regularID')->on('regular_allowance')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('class_reference');
    }
};
