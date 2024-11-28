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
        Schema::create('special_allowance_summaries', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('totalrequests')->default(0);
            $table->integer('pending')->default(0);
            $table->integer('completed')->default(0);
            $table->integer('accepted')->default(0);
            $table->integer('rejected')->default(0);
            $table->timestamps();
        });

        DB::table('special_allowance_summaries')->insert([
            [
                'totalrequests' => 0,
                'pending' => 0,
                'completed' => 0,
                'accepted' => 0,
                'rejected' => 0,
            ]
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('special_allowance_summaries');
    }
};
