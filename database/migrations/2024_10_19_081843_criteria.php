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
        Schema::create('criteria', function (Blueprint $table) {
            $table->increments('crid');
            $table->unsignedInteger('cshours');
            $table->float('cgwa');
            $table->float('shsgwa');
            $table->float('jhsgwa');
            $table->float('elemgwa');
            $table->unsignedInteger('fincome');
            $table->unsignedInteger('mincome');
            $table->unsignedInteger('sincome');
            $table->unsignedInteger('aincome');
            $table->timestamps();
        });

        Schema::create('institutions', function (Blueprint $table) {
            $table->increments('inid');
            $table->string('schoolname', 255)->unique();
            $table->timestamps();
        });

        Schema::create('courses', function (Blueprint $table) {
            $table->increments('coid');
            $table->string('level', 15);
            $table->string('coursename', 255)->unique();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('criteria');
        Schema::dropIfExists('institutions');
        Schema::dropIfExists('courses');
    }
};
