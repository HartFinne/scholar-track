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
        Schema::create('grades', function (Blueprint $table) {
            $table->increments('gid');
            $table->unsignedInteger('eid'); // Foreign key reference to sc_education.eids
            $table->string('SemesterQuarter');
            $table->float('GWA');
            $table->binary('ReportCard');
            $table->string('GradeStatus')->nullable();
            $table->timestamps();

            // Set up the foreign key constraint with cascading delete
            $table->foreign('eid')
                ->references('eid')
                ->on('sc_education')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grades');
    }
};
