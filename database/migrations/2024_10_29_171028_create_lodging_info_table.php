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
        Schema::create('lodging_info', function (Blueprint $table) {
            $table->increments('lodgingID');
            $table->unsignedInteger('regularID');
            $table->string('address', 255)->nullable();
            $table->string('name_owner', 255)->nullable();
            $table->string('contact_no_owner', 15)->nullable();
            $table->decimal('monthly_rent', 10, 2)->nullable();
            $table->enum('lodging_type', ['Dorm', 'Boarding House', 'Bed Space', 'Not Applicable']);
            $table->timestamps();

            $table->foreign('regularID')->references('regularID')->on('regular_allowance')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lodging_info');
    }
};
