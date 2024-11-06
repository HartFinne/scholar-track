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
            $table->string('casecode', 15)->charset('utf8mb4')->collation('utf8mb4_unicode_ci')->unique();
            $table->string('password', 255);
            $table->string('name', 255);
            $table->string('chinesename', 255);
            $table->string('sex', 6);
            $table->integer('age');
            $table->date('birthdate');
            $table->string('homeaddress', 255);
            $table->string('barangay', 50);
            $table->string('city', 50);
            $table->string('email', 255)->unique();
            $table->string('phonenum', 12);
            $table->string('occupation', 100);
            $table->integer('income');
            $table->text('fblink');
            $table->string('isIndigenous', 3);
            $table->string('Indigenousgroup', 100)->nullable();
            $table->string('applicationstatus', 25);
            $table->unsignedTinyInteger('prioritylevel');
            $table->string('accountstatus', 10)->default('Active');
            $table->string('remember_token', 100)->nullable();
            $table->timestamps();
        });

        Schema::create('apceducation', function (Blueprint $table) {
            $table->increments('apcid');
            $table->string('casecode', 15)->charset('utf8mb4')->collation('utf8mb4_unicode_ci')->unique();
            $table->string('univname', 255);
            $table->string('collegedept', 255);
            $table->string('inyear', 15);
            $table->string('course', 255);
            $table->float('gwa');
            $table->timestamps();

            $table->foreign('casecode')
                ->references('casecode')
                ->on('applicants')
                ->onDelete('CASCADE')
                ->onUpdate('CASCADE');
        });

        Schema::create('apeheducation', function (Blueprint $table) {
            $table->increments('apehid');
            $table->string('casecode', 15)->charset('utf8mb4')->collation('utf8mb4_unicode_ci')->unique();
            $table->string('schoolname', 255);
            $table->string('ingrade', 255);
            $table->string('strand', 150)->nullable();
            $table->string('section', 10);
            $table->float('gwa');
            $table->string('gwaconduct', 50);
            $table->float('chinesegwa')->nullable();
            $table->string('chinesegwaconduct', 50)->nullable();
            $table->timestamps();

            $table->foreign('casecode')
                ->references('casecode')
                ->on('applicants')
                ->onDelete('CASCADE')
                ->onUpdate('CASCADE');
        });

        Schema::create('apfamilyinfo', function (Blueprint $table) {
            $table->increments('apfid');
            $table->string('casecode', 15)->charset('utf8mb4')->collation('utf8mb4_unicode_ci');
            $table->string('name', 255);
            $table->integer('age');
            $table->string('sex', 6);
            $table->date('birthdate');
            $table->string('relationship', 25);
            $table->string('religion', 100);
            $table->string('educattainment', 100);
            $table->string('occupation', 100);
            $table->string('company', 100);
            $table->integer('income');
            $table->timestamps();

            $table->foreign('casecode')
                ->references('casecode')
                ->on('applicants')
                ->onDelete('CASCADE')
                ->onUpdate('CASCADE');
        });

        Schema::create('apotherinfo', function (Blueprint $table) {
            $table->increments('apoid');
            $table->string('casecode', 15)->charset('utf8mb4')->collation('utf8mb4_unicode_ci')->unique();
            $table->string('grant', 255);
            $table->string('talent', 255);
            $table->string('expectations', 255);
            $table->timestamps();

            $table->foreign('casecode')
                ->references('casecode')
                ->on('applicants')
                ->onDelete('CASCADE')
                ->onUpdate('CASCADE');
        });

        Schema::create('aprequirements', function (Blueprint $table) {
            $table->increments('aprid');
            $table->string('casecode', 15)->charset('utf8mb4')->collation('utf8mb4_unicode_ci')->unique();
            $table->string('idpic', 100);
            $table->string('reportcard', 100);
            $table->string('regiform', 100);
            $table->string('autobio', 100);
            $table->string('familypic', 100);
            $table->string('houseinside', 100);
            $table->string('houseoutside', 100);
            $table->string('utilitybill', 100);
            $table->string('sketchmap', 100);
            $table->string('payslip', 100)->nullable();
            $table->string('indigencycert', 100);
            $table->timestamps();

            $table->foreign('casecode')
                ->references('casecode')
                ->on('applicants')
                ->onDelete('CASCADE')
                ->onUpdate('CASCADE');
        });

        Schema::create('apcasedetails', function (Blueprint $table) {
            $table->increments('apcdid');
            $table->string('casecode', 15)->charset('utf8mb4')->collation('utf8mb4_unicode_ci')->unique();
            $table->string('natureofneeds', 50);
            $table->string('problemstatement', 50);
            $table->string('receivedby', 255);
            $table->date('datereceived');
            $table->string('district', 50);
            $table->string('volunteer', 255);
            $table->string('referredby', 255);
            $table->string('referphonenum', 11);
            $table->string('relationship', 50);
            $table->date('datereported');
            $table->timestamps();

            $table->foreign('casecode')
                ->references('casecode')
                ->on('applicants')
                ->onDelete('CASCADE')
                ->onUpdate('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('apceducation');
        Schema::dropIfExists('apeheducation');
        Schema::dropIfExists('apfamilyinfo');
        Schema::dropIfExists('apotherinfo');
        Schema::dropIfExists('aprequirements');
        Schema::dropIfExists('apcasedetails');
        Schema::dropIfExists('applicants');
    }
};
