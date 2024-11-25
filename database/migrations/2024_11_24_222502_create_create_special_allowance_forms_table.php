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
        Schema::create('create_special_allowance_forms', function (Blueprint $table) {
            $table->increments('csafid');
            $table->string('formname', 200);
            $table->string('formcode', 25);
            $table->string('requestor', 255);
            $table->text('instruction');
            $table->string('downloadablefiles', 255);
            $table->string('database', 255);
            $table->timestamps();
        });

        Schema::create('special_allowance_forms_structure', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('csafid');
            $table->string('fieldname', 255);
            $table->string('fieldtype', 25);
            $table->timestamps();

            // Define foreign key constraint for 'caseCode' column
            $table->foreign('csafid') // Column in the child table
                ->references('csafid') // Column in the parent table (sc_addressinfo)
                ->on('create_special_allowance_forms') // Parent table
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('special_allowance_forms_structure');
        Schema::dropIfExists('create_special_allowance_forms');
    }
};
