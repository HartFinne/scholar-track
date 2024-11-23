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
        Schema::create('applicationforms', function (Blueprint $table) {
            $table->increments('afid');
            $table->string('formname', 50);
            $table->date('deadline')->nullable();
            $table->date('enddate')->nullable();
            $table->string('status', 6);
            $table->timestamps();
        });

        DB::table('applicationforms')->insert([
            [
                'formname' => 'College',
                'deadline' => '2025-01-31',
                'enddate' => '2024-12-31',
                'status' => 'Open',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'formname' => 'Senior High',
                'deadline' => NULL,
                'enddate' => NULL,
                'status' => 'Closed',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'formname' => 'Junior High',
                'deadline' => NULL,
                'enddate' => NULL,
                'status' => 'Closed',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'formname' => 'Elementary',
                'deadline' => NULL,
                'enddate' => NULL,
                'status' => 'Closed',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'formname' => 'Renewal',
                'deadline' => NULL,
                'enddate' => NULL,
                'status' => 'Closed',
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
        Schema::dropIfExists('applicationforms');
    }
};
