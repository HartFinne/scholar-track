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
use Illuminate\Support\Facades\File;

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
            DB::table('datasets')->truncate();

            $users = User::with('scholarshipinfo', 'education', 'basicInfo')
                ->whereHas('scholarshipinfo', fn($query) => $query->where('scholarshipstatus', 'Continuing'))
                ->whereHas('education', fn($query) => $query->where('scSchoolLevel', 'College'))
                ->get();

            $currentYear = Carbon::now()->year;

            foreach ($users as $user) {
                $startYear = Carbon::parse($user->scholarshipinfo->startdate)->year;
                for ($year = $startYear; $year <= $currentYear; $year++) {
                    $startdate = Carbon::create($year, 1, 1);
                    $enddate = $startdate->copy()->addYear();
                    $schoolYear = $startdate->format('Y') . '-' . $enddate->format('Y');

                    $gwasem1 = grades::where('caseCode', $user->caseCode)
                        ->where('schoolyear', $schoolYear)
                        ->where('SemesterQuarter', '1st Semester')
                        ->select('GWA as grade')
                        ->first()?->grade;

                    $gwasem2 = grades::where('caseCode', $user->caseCode)
                        ->where('schoolyear', $schoolYear)
                        ->where('SemesterQuarter', '2nd Semester')
                        ->select('GWA as grade')
                        ->first()?->grade;

                    if (is_null($gwasem1) && is_null($gwasem2)) continue;

                    $cshours = csattendance::where('caseCode', $user->caseCode)
                        ->whereHas('communityservice', fn($query) => $query->whereBetween('eventdate', [$startdate, $enddate]))
                        ->sum('hoursspent');

                    $ltecount = lte::where('caseCode', $user->caseCode)
                        ->where('ltestatus', 'Unexcused')
                        ->count();

                    $penaltycount = penalty::where('caseCode', $user->caseCode)
                        ->distinct('condition')
                        ->count('condition');

                    datasets::create([
                        'caseCode' => $user->caseCode,
                        'startcontract' => $startdate,
                        'endcontract' => $enddate,
                        'gwasem1' => $gwasem1,
                        'gwasem2' => $gwasem2,
                        'cshours' => $cshours,
                        'ltecount' => $ltecount,
                        'penaltycount' => $penaltycount,
                    ]);
                }
            }

            DB::table('evalresults')->truncate();

            $command = '/home/forge/my-python-envs/myenv/bin/python3 ' . base_path('storage/app/python/evaluate_scholars.py');
            exec($command . ' 2>&1', $output, $return_var);

            if ($return_var !== 0) {
                $errorMessage = implode("\n", $output);
                return redirect()->back()->with('error', 'Evaluation script failed to execute. Error: ' . $errorMessage);
            }

            return redirect()->route('showevalresults')->with('success', 'Evaluation completed successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error has occurred. ' . $e->getMessage());
        }
    }

    public function showevalresults()
    {
        $results = evalresults::with('basicInfo')->orderBy('acadyear', 'ASC')->get();
        $acadyears = evalresults::selectRaw('acadyear')->distinct()->get();

        return view('staff.scholarsevaluation', compact('results', 'acadyears'));
    }

    public function showMetrics()
    {
        $jsonString = File::get(storage_path('app/python/performance_metrics.json'));
        $data = json_decode($jsonString, true);

        return view('staff.metrics', compact('data'));
    }
}
