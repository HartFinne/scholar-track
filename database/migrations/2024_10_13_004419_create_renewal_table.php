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
            $table->string('caseCode', 15)->charset('utf8mb4')->collation('utf8mb4_unicode_ci')->unique();
            $table->date('datesubmitted')->default(DB::raw('CURRENT_DATE'));
            $table->binary('picture');
            $table->binary('reportcard');
            $table->binary('utilitybill');
            $table->binary('sketchaddress');
            $table->binary('reflectionpaper');
            $table->string('status', 25);
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
        Schema::dropIfExists('renewal');
    }
};
