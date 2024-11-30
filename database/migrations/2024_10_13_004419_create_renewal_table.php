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
        Schema::create('renewal', function (Blueprint $table) {
            $table->increments('rid');
            $table->string('caseCode', 15)->charset('utf8mb4')->collation('utf8mb4_unicode_ci');
            $table->date('datesubmitted');
            $table->string('idpic', 255);
            $table->string('reportcard', 255);
            $table->string('regicard', 255);
            $table->string('autobio', 255);
            $table->string('familypic', 255);
            $table->string('houseinside', 255);
            $table->string('houseoutside', 255);
            $table->string('utilitybill', 255);
            $table->string('sketchmap', 255);
            $table->string('payslip', 255)->nullable();
            $table->string('indigencycert', 255);
            $table->string('status', 25);
            $table->unsignedInteger('prioritylevel');
            $table->timestamps();

            // Define foreign key constraint for 'caseCode' column
            $table->foreign('caseCode') // Column in the child table
                ->references('caseCode') // Column in the parent table (sc_addressinfo)
                ->on('users') // Parent table
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });

        Schema::create('rnwfamilyinfo', function (Blueprint $table) {
            $table->increments('rfiid');
            $table->string('caseCode', 15)->charset('utf8mb4')->collation('utf8mb4_unicode_ci');
            $table->string('name', 255);
            $table->integer('age');
            $table->string('sex', 15);
            $table->date('birthdate');
            $table->string('religion', 50);
            $table->string('relationship', 50);
            $table->string('educattainment', 100);
            $table->string('occupation', 100);
            $table->float('income');
            $table->timestamps();

            // Define foreign key constraint for 'caseCode' column
            $table->foreign('caseCode') // Column in the child table
                ->references('caseCode') // Column in the parent table (sc_addressinfo)
                ->on('users') // Parent table
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });

        Schema::create('rnwotherinfo', function (Blueprint $table) {
            $table->increments('roiid');
            $table->unsignedInteger('rid')->unique();
            $table->string('grant', 255);
            $table->string('talent', 255);
            $table->string('expectation', 255);
            $table->timestamps();

            // Define foreign key constraint for 'caseCode' column
            $table->foreign('rid') // Column in the child table
                ->references('rid') // Column in the parent table (sc_addressinfo)
                ->on('renewal') // Parent table
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });

        Schema::create('rnweducinfo', function (Blueprint $table) {
            $table->increments('reiid');
            $table->unsignedInteger('rid')->unique();
            $table->string('schoolyear', 10);
            $table->float('gwa');
            $table->string('gwaconduct', 25)->nullable();
            $table->float('chinesegwa')->nullable();
            $table->string('chinesegwaconduct', 25)->nullable();
            $table->timestamps();

            // Define foreign key constraint for 'caseCode' column
            $table->foreign('rid') // Column in the child table
                ->references('rid') // Column in the parent table (sc_addressinfo)
                ->on('renewal') // Parent table
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });

        Schema::create('rnwcasedetails', function (Blueprint $table) {
            $table->increments('rcdid');
            $table->unsignedInteger('rid')->unique();
            $table->string('natureofneeds', 50);
            $table->string('problemstatement', 255);
            $table->string('receivedby', 255);
            $table->date('datereceived');
            $table->string('district', 50);
            $table->string('volunteer', 255);
            $table->string('referredby', 255);
            $table->string('referphonenum', 12);
            $table->string('relationship', 50);
            $table->date('datereported');
            $table->timestamps();

            // Define foreign key constraint for 'caseCode' column
            $table->foreign('rid') // Column in the child table
                ->references('rid') // Column in the parent table (sc_addressinfo)
                ->on('renewal') // Parent table
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rnwcasedetails');
        Schema::dropIfExists('rnweducinfo');
        Schema::dropIfExists('rnwfamilyinfo');
        Schema::dropIfExists('rnwotherinfo');
        Schema::dropIfExists('renewal');
    }
};
