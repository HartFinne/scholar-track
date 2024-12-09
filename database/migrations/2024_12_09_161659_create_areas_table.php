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
        Schema::create('areas', function (Blueprint $table) {
            $table->increments('id');
            $table->string('areaname', 100);
            $table->string('areacode', 3);
            $table->timestamps();
        });

        DB::table('areas')->insert([
            [
                'areaname' => "Mindong",
                'areacode' => "MD",
            ],
            [
                'areaname' => "Minxi",
                'areacode' => "MZ",
            ],
            [
                'areaname' => "Minzhong",
                'areacode' => "MZ",
            ],
            [
                'areaname' => "Bicol",
                'areacode' => "BC",
            ],
            [
                'areaname' => "Davao",
                'areacode' => "DV",
            ],
            [
                'areaname' => "Iloilo",
                'areacode' => "ILO",
            ],
            [
                'areaname' => "Palo",
                'areacode' => "PL",
            ],
            [
                'areaname' => "Zamboanga",
                'areacode' => "ZB",
            ]
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('areas');
    }
};
