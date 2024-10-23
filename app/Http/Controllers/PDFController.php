<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\scholarshipinfo;
use App\Models\ScEducation;
use App\Models\communityservice;
use App\Models\csattendance;
use App\Models\renewal;
use App\Models\lte;
use App\Models\penalty;
use Barryvdh\DomPDF\Facade\Pdf;

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

        $pdf = Pdf::loadView('staff.scholarship-report', $data);
        return $pdf->stream("scholarship-report-{$date}.pdf");
    }
}
