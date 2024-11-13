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
        Schema::create('scholarshipinfo', function (Blueprint $table) {
            $table->increments('sid');
            $table->string('caseCode', 15)->charset('utf8mb4')->collation('utf8mb4_unicode_ci')->unique();
            $table->string('scholartype', 25);
            $table->string('area', 25);
            $table->date('startdate')->nullable();
            $table->date('enddate')->nullable();
            $table->string('scholarshipstatus', 25);
            $table->timestamps();

            // Define foreign key constraint for 'caseCode' column
            $table->foreign('caseCode') // Column in the child table
                ->references('caseCode') // Column in the parent table (sc_addressinfo)
                ->on('users') // Parent table
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });

        DB::unprepared('
            CREATE TRIGGER after_scholarship_status_update
            AFTER UPDATE ON scholarshipinfo
            FOR EACH ROW
            BEGIN
                IF NEW.scholarshipstatus = "Terminated" THEN
                    UPDATE users
                    SET scStatus = "Inactive"
                    WHERE caseCode = NEW.caseCode;
                END IF;
            END
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('scholarshipinfo');
        DB::unprepared('DROP TRIGGER IF EXISTS after_scholarship_status_update');
    }
};
