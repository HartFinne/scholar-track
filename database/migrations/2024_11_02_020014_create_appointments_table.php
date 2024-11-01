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
        Schema::create('appointments', function (Blueprint $table) {
            $table->increments('id');
            $table->string('caseCode', 15)->charset('utf8mb4')->collation('utf8mb4_unicode_ci');
            $table->string('reason', 75);
            $table->date('date');
            $table->string('time', 25);
            $table->string('status', 25)->default('Pending');
            $table->string('updatedby', 255)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};
