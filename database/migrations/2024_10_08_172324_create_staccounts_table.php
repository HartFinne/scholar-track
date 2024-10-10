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

        // Insert initial data
        DB::table('staccounts')->insert([
            [
                'id' => 1,
                'name' => 'System Admin',
                'email' => 'scholartracksa@gmail.com',
                'mobileno' => null,
                'area' => 'Not Applicable',
                'role' => 'System Admin',
                'status' => 'Active',
                // password: admin.scholartrack
                'password' => '$2y$12$SRbHoV5XqspYZE3SBw1q/O/k0sRmqGzYQrKp5gGW0/vvoNRRSJn16',
                'created_at' => '2024-10-10 07:31:14',
                'updated_at' => '2024-10-10 07:31:14'
            ],
            [
                'id' => 2,
                'name' => 'Social Worker',
                'email' => 'scholartracksw@gmail.com',
                'mobileno' => null,
                'area' => 'Minxi',
                'role' => 'Social Worker',
                'status' => 'Active',
                // password: worker.scholartrack
                'password' => '$2y$12$G.QSKZR4.iwju9bnfjtzF.sjZJeU2D4Ip3oqrkfINMty.5x822Kcy',
                'created_at' => '2024-10-10 07:31:25',
                'updated_at' => '2024-10-10 07:31:25'
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
