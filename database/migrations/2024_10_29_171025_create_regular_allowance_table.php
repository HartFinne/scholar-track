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
        Schema::create('regular_allowance', function (Blueprint $table) {
            $table->increments('regularID');
            $table->string('caseCode', 15)->charset('utf8mb4')->collation('utf8mb4_unicode_ci');
            $table->string('schoolyear', 10);
            $table->string('semester', 25);
            $table->date('start_of_semester');
            $table->date('end_of_semester');
            $table->date('date_of_release')->nullable();
            $table->string('status', 20);
            $table->timestamps();

            // Define foreign key constraint for 'caseCode' column
            $table->foreign('caseCode') // Column in the child table
                ->references('caseCode') // Column in the parent table
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
        Schema::dropIfExists('regular_allowance');
    }
};
