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
        Schema::create('sc_clothingsize', function (Blueprint $table) {
            $table->increments('scClothingID')->primary();
            $table->string('caseCode', 15)->charset('utf8mb4')->collation('utf8mb4_unicode_ci')->unique();
            $table->string('scTShirtSize', 5);
            $table->integer('scShoesSize');
            $table->integer('scSlipperSize');
            $table->string('scPantsSize', 5);
            $table->string('scJoggingPantSize', 5);

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
        Schema::dropIfExists('sc_clothingsize');
    }
};
