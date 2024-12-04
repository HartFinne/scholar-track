<?php

namespace App\Http\Controllers;

use App\Models\apceducation;
use App\Models\apfamilyinfo;
use App\Models\applicants;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\scholarshipinfo;
use App\Models\ScEducation;
use App\Models\renewal;
use App\Models\lte;
use App\Models\penalty;
use App\Models\applicationforms;
use App\Models\grades;
use App\Models\datasets;
use App\Models\evalresults;
use App\Models\criteria;
use App\Models\institutions;
use App\Models\communityservice;
use App\Models\csattendance;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\File;
use Barryvdh\DomPDF\Facade\Pdf;
use Spatie\Browsershot\Browsershot;
use Illuminate\Http\Response;

class PDFController extends Controller
{
    public function generatescholarshipreport()
    {
        $date = today()->format('Y-m-d');
        $data = [
            'scholars' => user::all()->count(),
            // scholars per type
            'scpertype' => scholarshipinfo::selectRaw('scholartype, COUNT(*) as sccount')
                ->groupBy('scholartype')
                ->get(),
            // scholars per area
            'scperarea' => scholarshipinfo::selectRaw('area, COUNT(*) as sccount')
                ->groupBy('area')
                ->get(),
            // scholars per level
            'scperlevel' => ScEducation::selectRaw('scSchoolLevel, COUNT(*) as sccount')
                ->groupBy('scSchoolLevel')
                ->get(),
            // scholars per school
            'scperschool' => ScEducation::selectRaw('scSchoolName, COUNT(*) as sccount')
                ->groupBy('scSchoolName')
                ->get(),
            // scholars per course
            'scpercourse' => ScEducation::selectRaw('scCourseStrandSec, COUNT(*) as sccount')
                ->where('scSchoolLevel', 'College')
                ->groupBy('scCourseStrandSec')
                ->get(),
            // scholars per strand
            'scperstrand' => ScEducation::selectRaw('scCourseStrandSec, COUNT(*) as sccount')
                ->where('scSchoolLevel', 'Senior High')
                ->groupBy('scCourseStrandSec')
                ->get(),
            // renewals
            'renewals' => renewal::selectRaw('status, COUNT(*) as sccount')
                ->groupBy('status')
                ->get(),
            // ltes
            'ltes' => lte::selectRaw('ltestatus, COUNT(*) as sccount')
                ->groupBy('ltestatus')
                ->get(),
            // penalties
            'penalties' => penalty::selectRaw('remark, COUNT(*) as sccount')
                ->groupBy('remark')
                ->get(),
        ];

        $pdf = Pdf::loadView('staff.reports.scholarship-report', $data);
        return $pdf->stream("scholarship-report-{$date}.pdf");
    }


    public function generateapplicantform($casecode)
    {
        $applicant = applicants::with('educcollege', 'educelemhs', 'otherinfo', 'requirements', 'casedetails')
            ->where('casecode', $casecode)
            ->first();
        $father = apfamilyinfo::where('casecode', $casecode)
            ->where('relationship', 'Father')->first();
        $mother = apfamilyinfo::where('casecode', $casecode)
            ->where('relationship', 'Mother')->first();
        $siblings = apfamilyinfo::where('casecode', $casecode)
            ->where('relationship', 'Sibling')->get();
        $iscollege = apceducation::where('casecode', $casecode)->exists();

        $data = [
            'title' => 'Application Form',
            'applicant' => $applicant,
            'father' => $father,
            'mother' => $mother,
            'siblings' => $siblings,
            'iscollege' => $iscollege
        ];

        // Render the Blade view to HTML
        $template = view('pdf-template', ['data' => $data])->render();


        // Generate the PDF using Browsershot and save it
        $pdf = Browsershot::html($template)
            ->setOption('args', ['--no-sandbox', '--disable-setuid-sandbox'])
            ->ignoreHttpsErrors()
            ->setNodeBinary('/usr/bin/node')
            ->setNpmBinary('/usr/bin/npm')
            ->showBackground()
            ->margins(2, 4, 10, 4)
            ->format('Letter')
            ->logErrors()
            ->pdf();

        return new Response($pdf, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="' . $applicant->name . '.pdf"', // Correct string interpolation
            'Content-Length' => strlen($pdf)
        ]);
    }
    // {
    //     $applicant = applicants::with('educcollege', 'educelemhs', 'otherinfo', 'requirements', 'casedetails')
    //         ->where('casecode', $casecode)
    //         ->first();
    //     $father = apfamilyinfo::where('casecode', $casecode)
    //         ->where('relationship', 'Father')->first();
    //     $mother = apfamilyinfo::where('casecode', $casecode)
    //         ->where('relationship', 'Mother')->first();
    //     $siblings = apfamilyinfo::where('casecode', $casecode)
    //         ->where('relationship', 'Sibling')->get();
    //     $iscollege = apceducation::where('casecode', $casecode)->exists();

    //     if ($iscollege) {
    //         $form = applicationforms::where('formname', 'College')->first();
    //     } else {
    //         if ($applicant->educelemhs->schoollevel == 'Elementary') {
    //             $form = applicationforms::where('formname', 'College')->first();
    //         } else {
    //             $form = applicationforms::where('formname', 'High School')->first();
    //         }
    //     }

    //     $data = [
    //         'applicant' => $applicant,
    //         'father' => $father,
    //         'mother' => $mother,
    //         'siblings' => $siblings,
    //         'iscollege' => $iscollege,
    //         'form' => $form,
    //     ];

    //     $pdf = Pdf::loadView('application-form', $data);
    //     return $pdf->stream("application-form-{$casecode}.pdf");
    //     // return view('application-form', compact('data'));
    // }
}
