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
        Schema::create('sc_basicinfo', function (Blueprint $table) {
            $table->increments('bid');
            $table->string('caseCode', 15)->charset('utf8mb4')->collation('utf8mb4_unicode_ci')->unique();
            $table->string('scLastname', 50);
            $table->string('scFirstname', 50);
            $table->string('scMiddlename', 50);
            $table->string('scChinesename', 255);
            $table->date('scDateOfBirth');
            $table->integer('scAge');
            $table->string('scSex', 10);
            $table->string('scGuardianName', 255)->nullable();
            $table->string('scRelationToGuardian', 50)->nullable();
            $table->string('scGuardianEmailAddress', 255)->nullable();
            $table->string('scGuardianPhoneNumber', 12)->nullable();
            $table->string('scOccupation', 100);
            $table->float('scIncome');
            $table->text('scFblink');
            $table->string('scIsIndigenous', 3);
            $table->string('scIndigenousgroup', 100)->nullable();
            $table->timestamps();

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
        Schema::dropIfExists('sc_basicinfo');
    }
};
