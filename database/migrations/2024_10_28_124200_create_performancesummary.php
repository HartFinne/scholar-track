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
        Schema::create('summarycollege', function (Blueprint $table) {
            $table->increments('id');
            $table->string('caseCode', 15)->charset('utf8mb4')->collation('utf8mb4_unicode_ci');
            $table->string('acadcycle', 50);
            $table->date('startcontract');
            $table->date('endcontract');
            $table->float('gwasem1')->nullable();
            $table->float('gwasem2')->nullable();
            $table->float('gwasem3')->nullable();
            $table->integer('cshours');
            $table->integer('hcabsentcount');
            $table->integer('penaltycount');
            $table->string('remark', 50);
            $table->timestamps();

            // Define foreign key constraint for 'caseCode' column
            $table->foreign('caseCode') // Column in the child table
                ->references('caseCode') // Column in the parent table (sc_addressinfo)
                ->on('users') // Parent table
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });

        Schema::create('summaryshs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('caseCode', 15)->charset('utf8mb4')->collation('utf8mb4_unicode_ci');
            $table->string('acadcycle', 50);
            $table->date('startcontract');
            $table->date('endcontract');
            $table->float('gwasem1')->nullable();
            $table->float('gwasem2')->nullable();
            $table->float('gwasem3')->nullable();
            $table->integer('hcabsentcount');
            $table->integer('penaltycount');
            $table->string('remark', 50);
            $table->timestamps();

            // Define foreign key constraint for 'caseCode' column
            $table->foreign('caseCode') // Column in the child table
                ->references('caseCode') // Column in the parent table (sc_addressinfo)
                ->on('users') // Parent table
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });

        Schema::create('summaryjhs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('caseCode', 15)->charset('utf8mb4')->collation('utf8mb4_unicode_ci');
            $table->string('acadcycle', 50);
            $table->date('startcontract');
            $table->date('endcontract');
            $table->float('quarter1')->nullable();
            $table->float('quarter2')->nullable();
            $table->float('quarter3')->nullable();
            $table->float('quarter4')->nullable();
            $table->integer('hcabsentcount');
            $table->integer('penaltycount');
            $table->string('remark', 50);
            $table->timestamps();

            // Define foreign key constraint for 'caseCode' column
            $table->foreign('caseCode') // Column in the child table
                ->references('caseCode') // Column in the parent table (sc_addressinfo)
                ->on('users') // Parent table
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });

        Schema::create('summaryelem', function (Blueprint $table) {
            $table->increments('id');
            $table->string('caseCode', 15)->charset('utf8mb4')->collation('utf8mb4_unicode_ci');
            $table->string('acadcycle', 50);
            $table->date('startcontract');
            $table->date('endcontract');
            $table->float('quarter1')->nullable();
            $table->float('quarter2')->nullable();
            $table->float('quarter3')->nullable();
            $table->float('quarter4')->nullable();
            $table->integer('hcabsentcount');
            $table->integer('penaltycount');
            $table->string('remark', 50);
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
        Schema::dropIfExists('summarycollege');
        Schema::dropIfExists('summaryshs');
        Schema::dropIfExists('summaryjhs');
        Schema::dropIfExists('summaryelem');
    }
};
