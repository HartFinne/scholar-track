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
use App\Models\grades;
use App\Models\datasets;
use App\Models\evalresults;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

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

    public function evaluatescholars()
    {
        try {
            // // always empty the datasets
            // DB::table('datasets')->truncate();
            // // populate datasets
            // $users = User::with(['scholarshipinfo', 'education'])
            //     ->whereHas('scholarshipinfo', function ($query) {
            //         $query->where('scholarshipstatus', 'Continuing');
            //     })
            //     ->whereHas('education', function ($query) {
            //         $query->where('scSchoolLevel', 'College');
            //     })
            //     ->get();

            // foreach ($users as $user) {
            //     $startdate = Carbon::parse($user->startdate);
            //     $schoolYear = $startdate->format('Y') . '-' . $startdate->copy()->addYear()->format('Y');

            //     datasets::create([
            //         'caseCode'      => $user->caseCode,
            //         'startcontract' => $startdate,
            //         'endcontract'   => $startdate->copy()->addYear(),
            //         'gwasem1'       => grades::where('caseCode', $user->caseCode)
            //             ->where('schoolyear', $schoolYear)
            //             ->where('SemesterQuarter', '1ST SEMESTER')
            //             ->first()?->grade,
            //         'gwasem2'       => grades::where('caseCode', $user->caseCode)
            //             ->where('schoolyear', $schoolYear)
            //             ->where('SemesterQuarter', '2ND SEMESTER')
            //             ->first()?->grade,
            //         'cshours'       => csattendance::where('caseCode', $user->caseCode)
            //             ->whereBetween('eventdate', [$startdate, $startdate->copy()->addYear()])
            //             ->sum('hoursspent'),
            //         'ltecount'      => lte::where('caseCode', $user->caseCode)
            //             ->where('ltestatus', 'Unexcused')
            //             ->count(),
            //         'penaltycount'  => penalty::where('caseCode', $user->caseCode)
            //             ->distinct('condition')
            //             ->count('condition'),
            //     ]);
            // }

            DB::table('evalresults')->truncate();

            $command = 'python ' . base_path('storage/app/python/evaluate_scholars.py');

            // Execute the command, capturing any output and error status
            exec($command . ' 2>&1', $output, $return_var);

            // Check if the script executed successfully
            if ($return_var !== 0) {
                // If an error occurred, handle it (e.g., log it or return an error response)
                $errorMessage = "Failed to execute Python script. Error: " . implode("\n", $output);

                // Optionally, return an error response or redirect with an error message
                return redirect()->back()->with('error', 'Evaluation script failed to execute.');
            }

            // Fetch the results and order by 'acadyear' in ascending order
            $results = evalresults::orderBy('acadyear', 'ASC')->get();

            $acadyears = evalresults::selectRaw('acadyear')->distinct()->get();

            // Pass the results to the view
            return view('staff.scholarsevaluation', compact('results', 'acadyears'));
        } catch (\Exception $e) {
            return view('staff.scholarsevaluation')->with('error', 'An error has occurred. ' . $e->getMessage());
        }
    }
}
