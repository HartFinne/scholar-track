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
        // Create the application_instructions table
        Schema::create('application_instructions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('schoollevel', 25);
            $table->text('applicants')->nullable();
            $table->text('qualifications')->nullable();
            $table->text('requireddocuments')->nullable();
            $table->text('applicationprocess')->nullable();
            $table->timestamps(); // Laravel will automatically handle created_at and updated_at
        });

        // Insert initial records for the application instructions
        DB::table('application_instructions')->insert([
            [
                'schoollevel' => 'College',
                'applicants' => '<p>The scholarship application is open to all <strong>incoming 2nd year college ONLY</strong>.</p>',
                'qualifications' => '<ul><li>Must belong to an INDIGENT or ECONOMICALLY CHALLENGED family.</li><li>Metro Manila Residents ONLY</li><li>Must have a GWA of 2.25 (82%) and above with no failing grades in any subject.</li><li>With Good Moral Character.</li><li>Must be enrolled in partner State University, Colleges, or partner Chinese Schools.</li></ul>',
                'requireddocuments' => '<ul><li>Scanned copy of latest Report Card</li><li>Scanned copy of latest Registration Form</li><li>1x1 inch ID Picture (Format: JPG or JPEG)</li><li>Autobiography</li><li>Family Picture (Format: JPG or JPEG)</li><li>Picture of the inside and outside of the house (Format: JPG or JPEG)</li><li>Scanned copy of latest Utility Bills</li><li>Scanned copy of latest ITR/ Official Pay Slip of parent/s (if applicable)</li><li>Scanned copy of Barangay Certificate of Indigency</li><li>Scanned copy of Detailed Sketch of Home Address. <span class="text-tiny"><i>Please draw your exact location legibly and indicate name of landmarks. Eq. name of street, church, sari-sari store, etc.</i></span></li></ul>',
                'applicationprocess' => '<p><strong>STEP 01:</strong> Fill out the Application Form.</p><p><strong>STEP 02:</strong> Submit the complete required documents.</p><p><strong>STEP 03:</strong> Initial Interview</p><p><strong>STEP 04:</strong> Panel Interview</p><p><strong>STEP 05:</strong> Virtual Home Visit</p><p><strong>STEP 06:</strong> Education Committee Deliberation</p><p><strong>STEP 07:</strong> Education Committee Decision</p>',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'schoollevel' => 'Junior High',
                'applicants' => '<p>The scholarship application is open to all <strong>incoming 2nd year college ONLY</strong>.</p>',
                'qualifications' => '<ul><li>Must belong to an INDIGENT or ECONOMICALLY CHALLENGED family.</li><li>Metro Manila Residents ONLY</li><li>Must have a GWA of 2.25 (82%) and above with no failing grades in any subject.</li><li>With Good Moral Character.</li><li>Must be enrolled in partner State University, Colleges, or partner Chinese Schools.</li></ul>',
                'requireddocuments' => '<ul><li>Scanned copy of latest Report Card</li><li>Scanned copy of latest Registration Form</li><li>1x1 inch ID Picture (Format: JPG or JPEG)</li><li>Autobiography</li><li>Family Picture (Format: JPG or JPEG)</li><li>Picture of the inside and outside of the house (Format: JPG or JPEG)</li><li>Scanned copy of latest Utility Bills</li><li>Scanned copy of latest ITR/ Official Pay Slip of parent/s (if applicable)</li><li>Scanned copy of Barangay Certificate of Indigency</li><li>Scanned copy of Detailed Sketch of Home Address. <span class="text-tiny"><i>Please draw your exact location legibly and indicate name of landmarks. Eq. name of street, church, sari-sari store, etc.</i></span></li></ul>',
                'applicationprocess' => '<p><strong>STEP 01:</strong> Fill out the Application Form.</p><p><strong>STEP 02:</strong> Submit the complete required documents.</p><p><strong>STEP 03:</strong> Initial Interview</p><p><strong>STEP 04:</strong> Panel Interview</p><p><strong>STEP 05:</strong> Virtual Home Visit</p><p><strong>STEP 06:</strong> Education Committee Deliberation</p><p><strong>STEP 07:</strong> Education Committee Decision</p>',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'schoollevel' => 'Senior High',
                'applicants' => '<p>The scholarship application is open to all <strong>incoming 2nd year college ONLY</strong>.</p>',
                'qualifications' => '<ul><li>Must belong to an INDIGENT or ECONOMICALLY CHALLENGED family.</li><li>Metro Manila Residents ONLY</li><li>Must have a GWA of 2.25 (82%) and above with no failing grades in any subject.</li><li>With Good Moral Character.</li><li>Must be enrolled in partner State University, Colleges, or partner Chinese Schools.</li></ul>',
                'requireddocuments' => '<ul><li>Scanned copy of latest Report Card</li><li>Scanned copy of latest Registration Form</li><li>1x1 inch ID Picture (Format: JPG or JPEG)</li><li>Autobiography</li><li>Family Picture (Format: JPG or JPEG)</li><li>Picture of the inside and outside of the house (Format: JPG or JPEG)</li><li>Scanned copy of latest Utility Bills</li><li>Scanned copy of latest ITR/ Official Pay Slip of parent/s (if applicable)</li><li>Scanned copy of Barangay Certificate of Indigency</li><li>Scanned copy of Detailed Sketch of Home Address. <span class="text-tiny"><i>Please draw your exact location legibly and indicate name of landmarks. Eq. name of street, church, sari-sari store, etc.</i></span></li></ul>',
                'applicationprocess' => '<p><strong>STEP 01:</strong> Fill out the Application Form.</p><p><strong>STEP 02:</strong> Submit the complete required documents.</p><p><strong>STEP 03:</strong> Initial Interview</p><p><strong>STEP 04:</strong> Panel Interview</p><p><strong>STEP 05:</strong> Virtual Home Visit</p><p><strong>STEP 06:</strong> Education Committee Deliberation</p><p><strong>STEP 07:</strong> Education Committee Decision</p>',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'schoollevel' => 'Elementary',
                'applicants' => '<p>The scholarship application is open to all <strong>incoming 2nd year college ONLY</strong>.</p>',
                'qualifications' => '<ul><li>Must belong to an INDIGENT or ECONOMICALLY CHALLENGED family.</li><li>Metro Manila Residents ONLY</li><li>Must have a GWA of 2.25 (82%) and above with no failing grades in any subject.</li><li>With Good Moral Character.</li><li>Must be enrolled in partner State University, Colleges, or partner Chinese Schools.</li></ul>',
                'requireddocuments' => '<ul><li>Scanned copy of latest Report Card</li><li>Scanned copy of latest Registration Form</li><li>1x1 inch ID Picture (Format: JPG or JPEG)</li><li>Autobiography</li><li>Family Picture (Format: JPG or JPEG)</li><li>Picture of the inside and outside of the house (Format: JPG or JPEG)</li><li>Scanned copy of latest Utility Bills</li><li>Scanned copy of latest ITR/ Official Pay Slip of parent/s (if applicable)</li><li>Scanned copy of Barangay Certificate of Indigency</li><li>Scanned copy of Detailed Sketch of Home Address. <span class="text-tiny"><i>Please draw your exact location legibly and indicate name of landmarks. Eq. name of street, church, sari-sari store, etc.</i></span></li></ul>',
                'applicationprocess' => '<p><strong>STEP 01:</strong> Fill out the Application Form.</p><p><strong>STEP 02:</strong> Submit the complete required documents.</p><p><strong>STEP 03:</strong> Initial Interview</p><p><strong>STEP 04:</strong> Panel Interview</p><p><strong>STEP 05:</strong> Virtual Home Visit</p><p><strong>STEP 06:</strong> Education Committee Deliberation</p><p><strong>STEP 07:</strong> Education Committee Decision</p>',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('application_instructions');
    }
};
