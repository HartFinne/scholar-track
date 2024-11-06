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
    public function up()
    {
        Schema::create('staccounts', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255);
            $table->string('email', 255)->unique();
            $table->string('mobileno', 11)->nullable()->unique();
            $table->string('area', 25);
            $table->string('role', 25);
            $table->string('status', 25);
            $table->string('password', 255);
            $table->timestamps();
        });

        DB::table('staccounts')->insert([
            [
                'name' => 'Ivy D. Cruz',
                'email' => 'icruz@gmail.com',
                'mobileno' => null,
                'area' => 'Not Applicable',
                'role' => 'System Admin',
                'status' => 'Active',
                'password' => '$2y$12$2TRizcqJOlHpv4/4RgWZc.xGa2aPycfrhQCso4OwYUT.IZHeFxxUa',
                'created_at' => '2024-10-10 04:25:25',
                'updated_at' => '2024-10-10 04:25:25'
            ],
            [
                'name' => 'Danna G. Ferrer',
                'email' => 'dferrer@gmail.com',
                'mobileno' => null,
                'area' => 'Minxi',
                'role' => 'Social Worker',
                'status' => 'Active',
                'password' => '$2y$12$PmZAVxACAaVals9ORKD8s.JlY6KYKihNH3dWM1UYtUTQpMQ3LvQ0G',
                'created_at' => '2024-10-10 04:25:25',
                'updated_at' => '2024-10-10 04:25:25'
            ]
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('staccounts');
    }
};
