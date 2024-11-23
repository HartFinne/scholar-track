<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

use function Ramsey\Uuid\v1;

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
            $table->string('schoolname', 255);
            $table->string('schoollevel', 25);
            $table->string('academiccycle', 25);
            $table->float('highestgwa');
            $table->timestamps();
        });

        Schema::create('courses', function (Blueprint $table) {
            $table->increments('coid');
            $table->string('level', 15);
            $table->string('coursename', 255)->unique();
            $table->timestamps();
        });

        DB::table('criteria')->insert([
            [
                'cshours' => 8,
                'cgwa' => 2.25,
                'shsgwa' => 82,
                'jhsgwa' => 82,
                'elemgwa' => 82,
                'fincome' => 10000,
                'mincome' => 10000,
                'sincome' => 10000,
                'aincome' => 10000,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);

        DB::table('institutions')->insert([
            [
                'schoolname' => "Polytechnic University of the Philippines",
                'schoollevel' => "College",
                'academiccycle' => "Semester",
                'highestgwa' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'schoolname' => "Polytechnic University of the Philippines",
                'schoollevel' => "Senior High",
                'academiccycle' => "Quarter",
                'highestgwa' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'schoolname' => "Polytechnic University of the Philippines",
                'schoollevel' => "Junior High",
                'academiccycle' => "Quarter",
                'highestgwa' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'schoolname' => "Polytechnic University of the Philippines",
                'schoollevel' => "Elementary",
                'academiccycle' => "Quarter",
                'highestgwa' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'schoolname' => "University of the Philippines",
                'schoollevel' => "College",
                'academiccycle' => "Semester",
                'highestgwa' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'schoolname' => "Pamantasan ng Lungsod ng Maynila",
                'schoollevel' => "College",
                'academiccycle' => "Semester",
                'highestgwa' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'schoolname' => "Technological University of the Philippines",
                'schoollevel' => "College",
                'academiccycle' => "Semester",
                'highestgwa' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'schoolname' => "Universidad De Manila",
                'schoollevel' => "College",
                'academiccycle' => "Semester",
                'highestgwa' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'schoolname' => "Philippine Normal University",
                'schoollevel' => "College",
                'academiccycle' => "Semester",
                'highestgwa' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);

        DB::table('courses')->insert([
            [
                'level' => 'College',
                'coursename' => 'Bachelor of Science in Information Technology',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'level' => 'College',
                'coursename' => 'Bachelor of Science in Civil Engineering',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'level' => 'College',
                'coursename' => 'Bachelor of Science in Mechanical Engineering',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'level' => 'College',
                'coursename' => 'Bachelor of Science in Electrical Engineering',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'level' => 'College',
                'coursename' => 'Bachelor of Science in Electronics Engineering',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'level' => 'College',
                'coursename' => 'Bachelor of Science in Chemical Engineering',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'level' => 'College',
                'coursename' => 'Bachelor of Science in Computer Engineering',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'level' => 'College',
                'coursename' => 'Bachelor of Science in Industrial Engineering',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'level' => 'College',
                'coursename' => 'Bachelor of Science in Social Work',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'level' => 'College',
                'coursename' => 'Bachelor of Science in Education',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'level' => 'College',
                'coursename' => 'Bachelor of Arts in Communication',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'level' => 'College',
                'coursename' => 'Bachelor of Arts in Journalism',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'level' => 'College',
                'coursename' => 'Bachelor of Science in Agriculture',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'level' => 'College',
                'coursename' => 'Bachelor of Science in Physical Therapy',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'level' => 'College',
                'coursename' => 'Bachelor of Science in Medical Technology',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'level' => 'College',
                'coursename' => 'Bachelor of Science in Radiologic Technology',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'level' => 'College',
                'coursename' => 'Bachelor of Science in Business Administration, Major in Financial Management',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'level' => 'College',
                'coursename' => 'Bachelor of Science in Business Administration, Major in Management',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'level' => 'College',
                'coursename' => 'Bachelor of Science in Business Administration, Major in Economics',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'level' => 'Senior High',
                'coursename' => 'Science, Technology, Engineering, and Mathematics',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'level' => 'Senior High',
                'coursename' => 'Accountancy, Business, and Management',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'level' => 'Senior High',
                'coursename' => 'Humanities and Social Sciences',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'level' => 'Senior High',
                'coursename' => 'Information and Communication Technology',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
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
