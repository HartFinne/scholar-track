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
            $table->string('status', 6);
            $table->timestamps();
        });

        DB::table('applicationforms')->insert([
            [
                'formname' => 'College',
                'status' => 'Open',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'formname' => 'High School',
                'status' => 'Closed',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'formname' => 'Elementary',
                'status' => 'Closed',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'formname' => 'Renewal',
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
