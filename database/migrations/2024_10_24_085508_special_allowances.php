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
        Schema::create('allowancetranspo', function (Blueprint $table) {
            $table->increments('id');
            $table->string('caseCode', 15)->charset('utf8mb4')->collation('utf8mb4_unicode_ci');
            $table->float('totalprice');
            $table->string('purpose', 100);
            $table->string('staffname', 255);
            $table->string('transpoform', 255);
            $table->string('status', 20)->default('Pending');
            $table->date('releasedate')->default(NULL);
            $table->timestamps();

            // Define foreign key constraint for 'caseCode' column
            $table->foreign('caseCode') // Column in the child table
                ->references('caseCode') // Column in the parent table (sc_addressinfo)
                ->on('users') // Parent table
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });

        Schema::create('allowancebook', function (Blueprint $table) {
            $table->increments('id');
            $table->string('caseCode', 15)->charset('utf8mb4')->collation('utf8mb4_unicode_ci');
            $table->string('booktitle', 255);
            $table->string('author', 255);
            $table->float('price');
            $table->string('certification', 255);
            $table->string('acknowledgement', 255);
            $table->string('purchaseproof', 255);
            $table->string('liquidation', 255);
            $table->string('status', 20)->default('Pending');
            $table->date('releasedate')->default(NULL);
            $table->timestamps();

            // Define foreign key constraint for 'caseCode' column
            $table->foreign('caseCode') // Column in the child table
                ->references('caseCode') // Column in the parent table (sc_addressinfo)
                ->on('users') // Parent table
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });

        Schema::create('allowancethesis', function (Blueprint $table) {
            $table->increments('id');
            $table->string('caseCode', 15)->charset('utf8mb4')->collation('utf8mb4_unicode_ci');
            $table->string('thesistitle', 255);
            $table->float('totalprice');
            $table->string('titlepage', 255);
            $table->string('acknowledgement', 255);
            $table->string('purchaseproof', 255);
            $table->string('liquidation', 255);
            $table->string('status', 20)->default('Pending');
            $table->date('releasedate')->default(NULL);
            $table->timestamps();

            // Define foreign key constraint for 'caseCode' column
            $table->foreign('caseCode') // Column in the child table
                ->references('caseCode') // Column in the parent table (sc_addressinfo)
                ->on('users') // Parent table
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });

        Schema::create('allowanceproject', function (Blueprint $table) {
            $table->increments('id');
            $table->string('caseCode', 15)->charset('utf8mb4')->collation('utf8mb4_unicode_ci');
            $table->string('subject', 255);
            $table->float('totalprice');
            $table->string('certification', 255);
            $table->string('acknowledgement', 255);
            $table->string('purchaseproof', 255);
            $table->string('liquidation', 255);
            $table->string('status', 20)->default('Pending');
            $table->date('releasedate')->default(NULL);
            $table->timestamps();

            // Define foreign key constraint for 'caseCode' column
            $table->foreign('caseCode') // Column in the child table
                ->references('caseCode') // Column in the parent table (sc_addressinfo)
                ->on('users') // Parent table
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });

        Schema::create('allowanceuniform', function (Blueprint $table) {
            $table->increments('id');
            $table->string('caseCode', 15)->charset('utf8mb4')->collation('utf8mb4_unicode_ci');
            $table->string('uniformtype', 8);
            $table->float('totalprice');
            $table->string('certificate', 255);
            $table->string('acknowledgement', 255);
            $table->string('uniformpic', 255);
            $table->string('liquidation', 255);
            $table->string('status', 20)->default('Pending');
            $table->date('releasedate')->default(NULL);
            $table->timestamps();

            // Define foreign key constraint for 'caseCode' column
            $table->foreign('caseCode') // Column in the child table
                ->references('caseCode') // Column in the parent table (sc_addressinfo)
                ->on('users') // Parent table
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });

        Schema::create('allowancegraduation', function (Blueprint $table) {
            $table->increments('id');
            $table->string('caseCode', 15)->charset('utf8mb4')->collation('utf8mb4_unicode_ci');
            $table->float('totalprice');
            $table->string('listofgraduates', 255);
            $table->string('acknowledgement', 255);
            $table->string('liquidation', 255);
            $table->string('status', 20)->default('Pending');
            $table->date('releasedate')->default(NULL);
            $table->timestamps();

            // Define foreign key constraint for 'caseCode' column
            $table->foreign('caseCode') // Column in the child table
                ->references('caseCode') // Column in the parent table (sc_addressinfo)
                ->on('users') // Parent table
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });

        Schema::create('allowanceevent', function (Blueprint $table) {
            $table->increments('id');
            $table->string('caseCode', 15)->charset('utf8mb4')->collation('utf8mb4_unicode_ci');
            $table->string('eventtype', 50);
            $table->string('eventloc', 255);
            $table->float('totalprice');
            $table->string('memo', 255);
            $table->string('waiver', 255);
            $table->string('acknowledgement', 255);
            $table->string('liquidation', 255);
            $table->string('status', 20)->default('Pending');
            $table->date('releasedate')->default(NULL);
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
        Schema::dropIfExists('allowancetranspo');
        Schema::dropIfExists('allowancebook');
        Schema::dropIfExists('allowancethesis');
        Schema::dropIfExists('allowanceproject');
        Schema::dropIfExists('allowanceuniform');
        Schema::dropIfExists('allowancegraduation');
        Schema::dropIfExists('allowanceevent');
    }
};
