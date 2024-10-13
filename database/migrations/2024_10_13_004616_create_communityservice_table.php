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
        Schema::create('communityservice', function (Blueprint $table) {
            $table->increments('csid');
            $table->binary('image')->nullable();
            $table->string('title');
            $table->string('eventloc');
            $table->date('eventdate');
            $table->string('meetingplace');
            $table->time('calltime');
            $table->time('starttime');
            $table->string('facilitator');
            $table->smallInteger('slotnum');
            $table->smallInteger('volunteersnum')->default(0);
            $table->string('eventstatus')->default('Open');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('communityservice');
    }
};
