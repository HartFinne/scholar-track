<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('sc_hcattendance', function (Blueprint $table) {
            $table->increments('hcaID')->primary();
            $table->integer('hcID');
            $table->string('caseCode', 15)->charset('utf8mb4')->collation('utf8mb4_unicode_ci')->unique();
            $table->time('timeIN');
            $table->time('timeOUT');
            $table->integer('tardinessDuration');
            $table->string('attendanceStatus', 50);

            // Define foreign key constraint for 'caseCode' column
            $table->foreign('caseCode') // Column in the child table
                ->references('caseCode') // Column in the parent table (sc_addressinfo)
                ->on('users') // Parent table
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('sc_hcattendance');
    }
};
