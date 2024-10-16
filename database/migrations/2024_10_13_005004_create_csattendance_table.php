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
        Schema::create('csattendance', function (Blueprint $table) {
            $table->increments('csaid');
            $table->unsignedInteger('csid');
            $table->string('caseCode', 15)->charset('utf8mb4')->collation('utf8mb4_unicode_ci');
            $table->time('timein');
            $table->time('timeout');
            $table->tinyInteger('tardinessduration')->default(0);
            $table->tinyInteger('hoursspent');
            $table->string('csastatus');
            $table->binary('attendanceproof');
            $table->timestamps();

            // Define foreign key constraint for 'caseCode' column
            $table->foreign('csid') // Column in the child table
                ->references('csid') // Column in the parent table (sc_addressinfo)
                ->on('communityservice') // Parent table
                ->onDelete('cascade')
                ->onUpdate('cascade');

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
    public function down(): void
    {
        Schema::dropIfExists('csattendance');
    }
};
