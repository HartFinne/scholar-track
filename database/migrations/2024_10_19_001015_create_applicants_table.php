<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('applicants', function (Blueprint $table) {
            $table->increments('apid');
            $table->string('name', 255);
            $table->string('chinesename', 255);
            $table->string('sex', 6);
            $table->integer('age');
            $table->date('birthdate');
            $table->string('homeaddress', 255);
            $table->string('barangay', 50);
            $table->string('city', 50);
            $table->string('email', 255);
            $table->string('phonenum', 11);
            $table->string('occupation', 100);
            $table->integer('income');
            $table->text('fblink');
            $table->string('isIndigenous', 3);
            $table->string('Indigenousgroup', 100)->nullable();
            $table->string('applicationstatus', 25);
            $table->timestamps();
        });

        Schema::create('apceducation', function (Blueprint $table) {
            $table->increments('apcid');
            $table->unsignedInteger('apid')->unique();
            $table->string('univname', 255);
            $table->string('collegedept', 255);
            $table->string('inyear', 10);
            $table->string('course', 255);
            $table->float('gwa');
            $table->timestamps();
        });

        Schema::create('apeheducation', function (Blueprint $table) {
            $table->increments('apehid');
            $table->unsignedInteger('apid')->unique();
            $table->string('schoolname', 255);
            $table->string('ingrade', 255);
            $table->string('strand', 150)->nullable();
            $table->string('section', 10);
            $table->float('gwa');
            $table->string('gwaconduct', 50);
            $table->float('chinesegwa')->nullable();
            $table->string('chinesegwaconduct', 50)->nullable();
            $table->timestamps();
        });

        Schema::create('apfather', function (Blueprint $table) {
            $table->increments('apfid');
            $table->unsignedInteger('apid')->unique();
            $table->string('fathername', 255);
            $table->integer('age');
            $table->string('sex', 6);
            $table->date('birthdate');
            $table->string('religion', 100);
            $table->string('educattainment', 100);
            $table->string('occupation', 100);
            $table->string('company', 100);
            $table->integer('income');
            $table->timestamps();
        });

        Schema::create('apmother', function (Blueprint $table) {
            $table->increments('apmid');
            $table->unsignedInteger('apid')->unique();
            $table->string('mothername', 255);
            $table->integer('age');
            $table->string('sex', 6);
            $table->date('birthdate');
            $table->string('religion', 100);
            $table->string('educattainment', 100);
            $table->string('occupation', 100);
            $table->string('company', 100);
            $table->integer('income');
            $table->timestamps();
        });

        Schema::create('apsiblings', function (Blueprint $table) {
            $table->increments('apsid');
            $table->unsignedInteger('apid');
            $table->string('siblingname', 255);
            $table->integer('age');
            $table->string('sex', 6);
            $table->date('birthdate');
            $table->string('religion', 100);
            $table->string('educattainment', 100);
            $table->string('occupation', 100);
            $table->string('company', 100);
            $table->integer('income');
            $table->timestamps();
        });

        Schema::create('apotherinfo', function (Blueprint $table) {
            $table->increments('apoid');
            $table->unsignedInteger('apid')->unique();
            $table->string('grant', 255);
            $table->string('talent', 255);
            $table->string('expectatiions', 255);
            $table->timestamps();
        });

        Schema::create('aprequirements', function (Blueprint $table) {
            $table->increments('aprid');
            $table->unsignedInteger('apid')->unique();
            $table->binary('idpic');
            $table->binary('reportcard');
            $table->binary('regiform');
            $table->binary('autobio');
            $table->binary('familypic');
            $table->binary('houseinside');
            $table->binary('houseoutside');
            $table->binary('utilitybill');
            $table->binary('sketchmap');
            $table->binary('payslip');
            $table->binary('indigencycert');
            $table->timestamps();
        });

        Schema::create('apcasedetails', function (Blueprint $table) {
            $table->increments('apcdid');
            $table->unsignedInteger('apid')->unique();
            $table->string('natureofneeds', 50);
            $table->string('problemstatement', 50);
            $table->string('receivedby', 255);
            $table->date('datereceived');
            $table->string('district', 50);
            $table->string('volunteer', 255);
            $table->string('referredby', 255);
            $table->string('referphonenum', 11);
            $table->string('relationship', 50);
            $table->binary('applicantsign');
            $table->date('datereported');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('applicants');
        Schema::dropIfExists('apceducation');
        Schema::dropIfExists('apeheducation');
        Schema::dropIfExists('apfather');
        Schema::dropIfExists('apmother');
        Schema::dropIfExists('apsiblings');
        Schema::dropIfExists('apotherinfo');
        Schema::dropIfExists('aprequirements');
        Schema::dropIfExists('apdetails');
    }
};
