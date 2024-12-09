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
        Schema::create('known_schools', function (Blueprint $table) {
            $table->increments('id');
            $table->string('schoolname', 255);
            $table->string('schoollevel', 25);
            $table->string('academiccycle', 25);
            $table->float('highestgwa');
            $table->timestamps();
        });

        DB::table('known_schools')->insert([
            [
                'schoolname' => "Polytechnic University of the Philippines",
                'schoollevel' => "College",
                'academiccycle' => "Semester",
                'highestgwa' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'schoolname' => "DEPED",
                'schoollevel' => "Senior High",
                'academiccycle' => "Semester",
                'highestgwa' => 100,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'schoolname' => "DEPED",
                'schoollevel' => "Junior High",
                'academiccycle' => "Quarter",
                'highestgwa' => 100,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'schoolname' => "DEPED",
                'schoollevel' => "Elementary",
                'academiccycle' => "Quarter",
                'highestgwa' => 100,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('known_schools');
    }
};
