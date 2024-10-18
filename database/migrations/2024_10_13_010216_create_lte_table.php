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
        Schema::create('lte', function (Blueprint $table) {
            $table->increments('lid');
            $table->string('caseCode', 15)->charset('utf8mb4')->collation('utf8mb4_unicode_ci');
            $table->unsignedInteger('conditionid');
            $table->string('eventtype', 50);
            $table->date('dateissued');
            $table->date('deadline');
            $table->date('datesubmitted')->nullable();
            $table->string('reason', 150)->nullable();
            $table->text('explanation')->nullable();
            $table->binary('proof')->nullable();
            $table->string('ltestatus', 25)->default('Pending');
            $table->string('workername', 255);
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
    public function down(): void
    {
        Schema::dropIfExists('lte');
    }
};
