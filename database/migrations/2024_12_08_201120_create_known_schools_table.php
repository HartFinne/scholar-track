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
            $table->string('academiccycle', 25);
            $table->float('highestgwa');
            $table->timestamps();
        });

        DB::table('known_schools')->insert([
            [
                'schoolname' => "Adamson University",
                'academiccycle' => "Semester",
                'highestgwa' => 1,
            ],
            [
                'schoolname' => "Ateneo de Davao University",
                'academiccycle' => "Semester",
                'highestgwa' => 4,
            ],
            [
                'schoolname' => "Ateneo de Manila University",
                'academiccycle' => "Semester",
                'highestgwa' => 4,
            ],
            [
                'schoolname' => "Bicol State College of Applied Sciences and Technology",
                'academiccycle' => "Semester",
                'highestgwa' => 1,
            ],
            [
                'schoolname' => "Bicol University",
                'academiccycle' => "Semester",
                'highestgwa' => 1,
            ],
            [
                'schoolname' => "Centro Escolar University",
                'academiccycle' => "Semester",
                'highestgwa' => 1,
            ],
            [
                'schoolname' => "Colegio de la Ciudad de Zamboanga",
                'academiccycle' => "Semester",
                'highestgwa' => 1,
            ],
            [
                'schoolname' => "De La Salle University",
                'academiccycle' => "Trimester",
                'highestgwa' => 4,
            ],
            [
                'schoolname' => "Far Eastern University",
                'academiccycle' => "Semester",
                'highestgwa' => 4,
            ],
            [
                'schoolname' => "Iloilo Science and Technology University",
                'academiccycle' => "Semester",
                'highestgwa' => 1,
            ],
            [
                'schoolname' => "Lyceum of the Philippines University",
                'academiccycle' => "Semester",
                'highestgwa' => 1,
            ],
            [
                'schoolname' => "Mapúa University",
                'academiccycle' => "Trimester",
                'highestgwa' => 4,
            ],
            [
                'schoolname' => "Pamantasan ng Lungsod ng Maynila",
                'academiccycle' => "Semester",
                'highestgwa' => 1,
            ],
            [
                'schoolname' => "Pampanga State Agricultural University",
                'academiccycle' => "Semester",
                'highestgwa' => 1,
            ],
            [
                'schoolname' => "Philippine Normal University",
                'academiccycle' => "Trimester",
                'highestgwa' => 1,
            ],
            [
                'schoolname' => "Polytechnic University of the Philippines",
                'academiccycle' => "Semester",
                'highestgwa' => 1,
            ],
            [
                'schoolname' => "Saint Louis University",
                'academiccycle' => "Semester",
                'highestgwa' => 1,
            ],
            [
                'schoolname' => "San Beda University",
                'academiccycle' => "Semester",
                'highestgwa' => 1,
            ],
            [
                'schoolname' => "Silliman University",
                'academiccycle' => "Semester",
                'highestgwa' => 1,
            ],
            [
                'schoolname' => "Technological Institute of the Philippines",
                'academiccycle' => "Semester",
                'highestgwa' => 1,
            ],
            [
                'schoolname' => "Technological University of the Philippines",
                'academiccycle' => "Semester",
                'highestgwa' => 1,
            ],
            [
                'schoolname' => "Universidad de Manila",
                'academiccycle' => "Semester",
                'highestgwa' => 1,
            ],
            [
                'schoolname' => "University of Asia and the Pacific",
                'academiccycle' => "Semester",
                'highestgwa' => 1,
            ],
            [
                'schoolname' => "University of Mindanao",
                'academiccycle' => "Semester",
                'highestgwa' => 1,
            ],
            [
                'schoolname' => "University of San Carlos",
                'academiccycle' => "Semester",
                'highestgwa' => 1,
            ],
            [
                'schoolname' => "University of Santo Tomas",
                'academiccycle' => "Semester",
                'highestgwa' => 1,
            ],
            [
                'schoolname' => "University of Southeastern Philippines",
                'academiccycle' => "Semester",
                'highestgwa' => 1,
            ],
            [
                'schoolname' => "University of the East",
                'academiccycle' => "Semester",
                'highestgwa' => 1,
            ],
            [
                'schoolname' => "University of the Philippines",
                'academiccycle' => "Semester",
                'highestgwa' => 1,
            ],
            [
                'schoolname' => "West Visayas State University",
                'academiccycle' => "Semester",
                'highestgwa' => 1,
            ],
            [
                'schoolname' => "Xavier University Ateneo de Cagayan",
                'academiccycle' => "Semester",
                'highestgwa' => 4,
            ],
            [
                'schoolname' => "Zamboanga Peninsula Polytechnic State University",
                'academiccycle' => "Semester",
                'highestgwa' => 1,
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
