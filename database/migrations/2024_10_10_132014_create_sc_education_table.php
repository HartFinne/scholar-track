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
        Schema::create('sc_education', function (Blueprint $table) {
            $table->increments('scEducationID')->primary();
            $table->string('caseCode', 15)->charset('utf8mb4')->collation('utf8mb4_unicode_ci')->unique();
            $table->string('scSchoolLevel', 20);
            $table->string('scSchoolName', 255);
            $table->string('scYearLevel', 50);
            $table->string('scCourseStrand', 50);
            $table->string('scSemester', 50);

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
        Schema::dropIfExists('sc_education');
    }
};
