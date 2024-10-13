<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('humanitiesclass', function (Blueprint $table) {
            $table->increments('hcid');
            $table->binary('image1')->nullable();
            $table->binary('image2')->nullable();
            $table->binary('image3')->nullable();
            $table->string('topic', 255);
            $table->date('hcdate')->default(DB::raw('CURRENT_DATE'));
            $table->time('hcstarttime');
            $table->time('hcendtime')->nullable();
            $table->smallInteger('totalattendees')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('humanitiesclass');
    }
};
