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
        Schema::create('regular_allowance', function (Blueprint $table) {
            $table->increments('regularID');
            $table->unsignedInteger('gid');
            $table->date('start_of_semester');
            $table->date('end_of_semester');
            $table->date('date_of_release')->nullable();
            $table->string('status', 20);
            $table->timestamps();

            $table->foreign('gid')->references('gid')->on('grades')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('regular_allowance');
    }
};
