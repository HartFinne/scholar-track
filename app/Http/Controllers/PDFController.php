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
use App\Models\criteria;
use App\Models\institutions;
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

            $users = User::with(['scholarshipinfo', 'education', 'basicInfo'])
                ->whereHas('scholarshipinfo', fn($query) => $query->where('scholarshipstatus', 'Continuing'))
                ->whereHas('education', fn($query) => $query->where('scSchoolLevel', 'College'))
                ->get();

            $criteria = criteria::first();

            foreach ($users as $user) {
                $curriculum = institutions::where('schoolname', $user->education->scSchoolName)
                    ->where('schoollevel', $user->education->scSchoolLevel)
                    ->first();

                if (!$curriculum) {
                    continue; // Skip if curriculum is not found
                }

                $enddate = $user->scholarshipinfo->enddate;
                $enddate = Carbon::parse($enddate);
                $startdate = $enddate->copy()->subYear();

                $schoolYear = $startdate->format('Y') . '-' . $enddate->format('Y');

                $gwas = grades::where('caseCode', $user->caseCode)
                    ->where('schoolyear', $schoolYear)
                    ->whereIn('SemesterQuarter', ['1st Semester', '2nd Semester', '3rd Semester'])
                    ->select('SemesterQuarter', 'GWA')
                    ->get()
                    ->pluck('GWA', 'SemesterQuarter');

                $gwasem1 = $gwas['1st Semester'] ?? null;
                $gwasem2 = $gwas['2nd Semester'] ?? null;
                $gwasem3 = $curriculum->academiccycle === 'Trimester' ? ($gwas['3rd Semester'] ?? null) : null;

                $cshours = csattendance::where('caseCode', $user->caseCode)
                    ->whereHas('communityservice', fn($query) => $query->whereBetween('eventdate', [$startdate, $enddate]))
                    ->sum('hoursspent');

                $penaltycount = penalty::where('caseCode', $user->caseCode)
                    ->distinct('condition')
                    ->count('condition');

                $remark = $this->evaluateRemark($gwasem1, $gwasem2, $gwasem3, $curriculum->highestgwa, $criteria->cgwa, $curriculum->acadcycle);

                datasets::create([
                    'caseCode' => $user->caseCode,
                    'acadcycle' => $curriculum->academiccycle,
                    'startcontract' => $startdate,
                    'endcontract' => $enddate,
                    'gwasem1' => $gwasem1,
                    'gwasem2' => $gwasem2,
                    'gwasem3' => $gwasem3,
                    'cshours' => $cshours,
                    'penaltycount' => $penaltycount,
                    'remark' => $remark,
                ]);
            }

            return redirect()->route('showevalresults')->with('success', 'Evaluation completed successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('failure', 'An error has occurred. ' . $e->getMessage());
        }
    }

    private function evaluateRemark($gwasem1, $gwasem2, $gwasem3, $highestgwa, $cgwa, $acadcycle)
    {
        if (is_null($gwasem1) || is_null($gwasem2) || ($acadcycle === 'Trimester' && is_null($gwasem3))) {
            return 'Incomplete Data';
        }

        if ($highestgwa == 5) {
            if ($gwasem1 < $cgwa) {
                return 'Failed GWA on 1st Sem';
            } elseif ($gwasem2 < $cgwa) {
                return 'Failed GWA on 2nd Sem';
            } elseif ($acadcycle === 'Trimester' && $gwasem3 < $cgwa) {
                return 'Failed GWA on 3rd Sem';
            }
        } elseif ($highestgwa == 1) {
            if ($gwasem1 > $cgwa) {
                return 'Failed GWA on 1st Sem';
            } elseif ($gwasem2 > $cgwa) {
                return 'Failed GWA on 2nd Sem';
            } elseif ($acadcycle === 'Trimester' && $gwasem3 > $cgwa) {
                return 'Failed GWA on 3rd Sem';
            }
        }

        return 'Passed';
    }

    public function showevalresults()
    {
        $results = datasets::with('basicInfo', 'education')->orderBy('endcontract', 'ASC')->get();
        // foreach ($results as $result) {
        //     $result['acadcycle'] = institutions::where('schoolname', $result->education->scSchoolName)
        //         ->where('schoollevel', $result->education->scSchoolLevel)->pluck('academiccycle');
        // }

        return view('staff.scholarsevaluation', compact('results'));
    }

    // public function showevalresults()
    // {
    //     $results = evalresults::with('basicInfo')->orderBy('acadyear', 'ASC')->get();
    //     $acadyears = evalresults::selectRaw('acadyear')->distinct()->get();

    //     $jsonString = File::get(storage_path('app/python/performance_metrics.json'));
    //     $data = json_decode($jsonString, true);

    //     return view('staff.scholarsevaluation', compact('results', 'acadyears', 'data'));
    // }

    public function showMetrics()
    {
        $jsonString = File::get(storage_path('app/python/performance_metrics.json'));
        $data = json_decode($jsonString, true);

        return view('staff.metrics', compact('data'));
    }
}
