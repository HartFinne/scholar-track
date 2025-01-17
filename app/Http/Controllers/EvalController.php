<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\scholarshipinfo;
use App\Models\ScEducation;
use App\Models\renewal;
use App\Models\lte;
use App\Models\penalty;
use App\Models\grades;
use App\Models\summarycollege;
use App\Models\summaryshs;
use App\Models\summaryjhs;
use App\Models\summaryelem;
use App\Models\criteria;
use App\Models\institutions;
use App\Models\communityservice;
use App\Models\csattendance;
use App\Models\hcattendance;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\File;

class EvalController extends Controller
{


    public function evaluatescholars()
    {
        try {
            DB::table('summarycollege')->truncate();
            DB::table('summaryshs')->truncate();
            DB::table('summaryjhs')->truncate();
            DB::table('summaryelem')->truncate();
            $this->evaluatecollege();
            $this->evaluateshs();
            $this->evaluatejhs();
            $this->evaluateelem();
            return redirect()->back()->with('success', 'Successfully generate scholars\' performance summary report.');
        } catch (\Exception $e) {
            return redirect()->back()->with('failure', 'An error has occurred. ' . $e->getMessage());
        }
    }

    private function evaluatecollege()
    {
        $users = User::with(['scholarshipinfo', 'education', 'basicInfo'])
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


            $hcabsentcount = hcattendance::where('caseCode', $user->caseCode)
                ->where('hcastatus', 'Absent')
                ->whereBetween('created_at', [$startdate, $enddate])
                ->count();

            $penaltycount = penalty::where('caseCode', $user->caseCode)
                ->distinct('condition')
                ->count('condition');

            $remark = $this->evaluateRemark($gwasem1, $gwasem2, $gwasem3, $curriculum->highestgwa, $criteria->cgwa, $curriculum->acadcycle);

            if ($remark == 'Good Academic Performance') {
                if ($cshours < $criteria->cshours) {
                    $remark = 'Incomplete CS Hours';
                } elseif ($hcabsentcount > 0) {
                    $remark = 'Incomplete HC Attendance';
                } else {
                    $remark = 'Satisfactory Performance';
                }
            }

            summarycollege::create([
                'caseCode' => $user->caseCode,
                'acadcycle' => $curriculum->academiccycle,
                'startcontract' => $startdate,
                'endcontract' => $enddate,
                'gwasem1' => $gwasem1,
                'gwasem2' => $gwasem2,
                'gwasem3' => $gwasem3,
                'cshours' => $cshours,
                'hcabsentcount' => $hcabsentcount,
                'penaltycount' => $penaltycount,
                'remark' => $remark,
            ]);
        }
    }

    private function evaluateshs()
    {
        $users = User::with(['scholarshipinfo', 'education', 'basicInfo'])
            ->whereHas('education', fn($query) => $query->where('scSchoolLevel', 'Senior High'))
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


            $hcabsentcount = hcattendance::where('caseCode', $user->caseCode)
                ->where('hcastatus', 'Absent')
                ->whereBetween('created_at', [$startdate, $enddate])
                ->count();
            $penaltycount = penalty::where('caseCode', $user->caseCode)
                ->distinct('condition')
                ->count('condition');

            $remark = $this->evaluateRemark($gwasem1, $gwasem2, $gwasem3, $curriculum->highestgwa, $criteria->shsgwa, $curriculum->acadcycle);

            if ($remark == 'Good Academic Performance') {
                if ($hcabsentcount > 0) {
                    $remark = 'Incomplete HC Attendance';
                } else {
                    $remark = 'Satisfactory Performance';
                }
            }

            summaryshs::create([
                'caseCode' => $user->caseCode,
                'acadcycle' => $curriculum->academiccycle,
                'startcontract' => $startdate,
                'endcontract' => $enddate,
                'gwasem1' => $gwasem1,
                'gwasem2' => $gwasem2,
                'gwasem3' => $gwasem3,
                'hcabsentcount' => $hcabsentcount,
                'penaltycount' => $penaltycount,
                'remark' => $remark,
            ]);
        }
    }

    private function evaluateRemark($gwasem1, $gwasem2, $gwasem3, $highestgwa, $cgwa, $acadcycle)
    {
        if (is_null($gwasem1) || is_null($gwasem2) || ($acadcycle === 'Trimester' && is_null($gwasem3))) {
            return 'Incomplete Grades';
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
        } elseif ($highestgwa == 100) {
            if ($gwasem1 < $cgwa) {
                return 'Failed GWA on 1st Sem';
            } elseif ($gwasem2 < $cgwa) {
                return 'Failed GWA on 2nd Sem';
            } elseif ($acadcycle === 'Trimester' && $gwasem3 < $cgwa) {
                return 'Failed GWA on 3rd Sem';
            }
        }

        return 'Good Academic Performance';
    }

    private function evaluatejhs()
    {
        $users = User::with(['scholarshipinfo', 'education', 'basicInfo'])
            ->whereHas('education', fn($query) => $query->where('scSchoolLevel', 'Junior High'))
            ->get();

        $criteria = criteria::first();

        foreach ($users as $user) {
            $curriculum = institutions::where('schoolname', $user->education->scSchoolName)
                ->where('schoollevel', $user->education->scSchoolLevel)
                ->first();

            if (!$curriculum) {
                continue;
            }

            $enddate = $user->scholarshipinfo->enddate;
            $enddate = Carbon::parse($enddate);
            $startdate = $enddate->copy()->subYear();

            $schoolYear = $startdate->format('Y') . '-' . $enddate->format('Y');

            $gradeInfo = grades::where('caseCode', $user->caseCode)
                ->where('schoolyear', $schoolYear)
                ->first();

            $gwa = $gradeInfo->GWA ?? NULL;

            $hcabsentcount = hcattendance::where('caseCode', $user->caseCode)
                ->where('hcastatus', 'Absent')
                ->whereBetween('created_at', [$startdate, $enddate])
                ->count();
            $penaltycount = penalty::where('caseCode', $user->caseCode)
                ->distinct('condition')
                ->count('condition');

            $remark = $this->evaluaterem($gwa, $curriculum->highestgwa, $criteria->jhsgwa);

            if ($remark == 'Good Academic Performance') {
                if ($hcabsentcount > 0) {
                    $remark = 'Incomplete HC Attendance';
                } else {
                    $remark = 'Satisfactory Performance';
                }
            }

            summaryjhs::create([
                'caseCode' => $user->caseCode,
                'acadcycle' => $curriculum->academiccycle,
                'startcontract' => $startdate,
                'endcontract' => $enddate,
                'gwa' => $gradeInfo->GWA ?? NULL,
                'hcabsentcount' => $hcabsentcount,
                'penaltycount' => $penaltycount,
                'remark' => $remark,
            ]);
        }
    }

    private function evaluateelem()
    {
        $users = User::with(['scholarshipinfo', 'education', 'basicInfo'])
            ->whereHas('education', fn($query) => $query->where('scSchoolLevel', 'Elementary'))
            ->get();

        $criteria = criteria::first();

        foreach ($users as $user) {
            $curriculum = institutions::where('schoolname', $user->education->scSchoolName)
                ->where('schoollevel', $user->education->scSchoolLevel)
                ->first();

            if (!$curriculum) {
                continue;
            }

            $enddate = $user->scholarshipinfo->enddate;
            $enddate = Carbon::parse($enddate);
            $startdate = $enddate->copy()->subYear();

            // dd($startdate . "-" . $enddate);

            $schoolYear = $startdate->format('Y') . '-' . $enddate->format('Y');

            $gradeInfo = grades::where('caseCode', $user->caseCode)
                ->where('schoolyear', $schoolYear)
                ->first();

            $gwa = $gradeInfo->GWA ?? NULL;

            $hcabsentcount = hcattendance::where('caseCode', $user->caseCode)
                ->where('hcastatus', 'Absent')
                ->whereBetween('created_at', [$startdate, $enddate])
                ->count();

            $penaltycount = penalty::where('caseCode', $user->caseCode)
                ->distinct('condition')
                ->count('condition');

            $remark = $this->evaluaterem($gwa, $curriculum->highestgwa, $criteria->jhsgwa);

            if ($remark == 'Good Academic Performance') {
                if ($hcabsentcount > 0) {
                    $remark = 'Incomplete HC Attendance';
                } else {
                    $remark = 'Satisfactory Performance';
                }
            }

            summaryelem::create([
                'caseCode' => $user->caseCode,
                'acadcycle' => $curriculum->academiccycle,
                'startcontract' => $startdate,
                'endcontract' => $enddate,
                'gwa' => $gradeInfo->GWA ?? NULL,
                'hcabsentcount' => $hcabsentcount,
                'penaltycount' => $penaltycount,
                'remark' => $remark,
            ]);
        }
    }

    private function evaluaterem($gwa, $highestgwa, $genave)
    {
        if (is_null($gwa)) {
            return 'Incomplete Grades';
        }

        if ($highestgwa == 5) {
            if ($gwa < $genave) {
                return 'Failed General Average';
            }
        } elseif ($highestgwa == 1) {
            if ($gwa > $genave) {
                return 'Failed General Average';
            }
        } elseif ($highestgwa == 100) {
            if ($gwa < $genave) {
                return 'Failed General Average';
            }
        }

        return 'Good Academic Performance';
    }

    // public function showevalresults()
    // {
    //     $startdate = Carbon::now();
    //     $enddate = $startdate->copy()->addYear();

    //     // Generate the academic year
    //     $acadyear = $startdate->format('Y') . '-' . $enddate->format('Y');
    //     $colleges = summarycollege::with('basicInfo', 'education', 'scholarshipinfo')
    //         ->join('scholarshipinfo', 'scholarshipinfo.caseCode', '=', 'summarycollege.caseCode')
    //         ->orderByRaw("remark = 'Satisfactory Performance' DESC") // New sorting priority
    //         ->orderByRaw("
    //         CASE 
    //             WHEN scholarshipinfo.scholarshipstatus = 'Continuing' THEN 1
    //             WHEN scholarshipinfo.scholarshipstatus = 'On-Hold' THEN 2
    //             WHEN scholarshipinfo.scholarshipstatus = 'Terminated' THEN 3
    //             ELSE 4 
    //         END
    //     ")
    //         ->orderBy('endcontract', 'ASC')
    //         ->select('summarycollege.*')
    //         ->get();

    //     $shs = summaryshs::with('basicInfo', 'education', 'scholarshipinfo')
    //         ->join('scholarshipinfo', 'scholarshipinfo.caseCode', '=', 'summaryshs.caseCode')
    //         ->orderByRaw("remark = 'Satisfactory Performance' DESC") // New sorting priority
    //         ->orderByRaw("
    //         CASE 
    //             WHEN scholarshipinfo.scholarshipstatus = 'Continuing' THEN 1
    //             WHEN scholarshipinfo.scholarshipstatus = 'On-Hold' THEN 2
    //             WHEN scholarshipinfo.scholarshipstatus = 'Terminated' THEN 3
    //             ELSE 4 
    //         END
    //     ")
    //         ->orderBy('endcontract', 'ASC')
    //         ->select('summaryshs.*')
    //         ->get();

    //     $jhs = summaryjhs::with('basicInfo', 'education', 'scholarshipinfo')
    //         ->join('scholarshipinfo', 'scholarshipinfo.caseCode', '=', 'summaryjhs.caseCode')
    //         ->orderByRaw("remark = 'Satisfactory Performance' DESC") // New sorting priority
    //         ->orderByRaw("
    //         CASE 
    //             WHEN scholarshipinfo.scholarshipstatus = 'Continuing' THEN 1
    //             WHEN scholarshipinfo.scholarshipstatus = 'On-Hold' THEN 2
    //             WHEN scholarshipinfo.scholarshipstatus = 'Terminated' THEN 3
    //             ELSE 4 
    //         END
    //     ")
    //         ->orderBy('endcontract', 'ASC')
    //         ->select('summaryjhs.*')
    //         ->get();

    //     $elem = summaryelem::with('basicInfo', 'education', 'scholarshipinfo')
    //         ->join('scholarshipinfo', 'scholarshipinfo.caseCode', '=', 'summaryelem.caseCode')
    //         ->orderByRaw("remark = 'Satisfactory Performance' DESC") // New sorting priority
    //         ->orderByRaw("
    //         CASE 
    //             WHEN scholarshipinfo.scholarshipstatus = 'Continuing' THEN 1
    //             WHEN scholarshipinfo.scholarshipstatus = 'On-Hold' THEN 2
    //             WHEN scholarshipinfo.scholarshipstatus = 'Terminated' THEN 3
    //             ELSE 4 
    //         END
    //     ")
    //         ->orderBy('endcontract', 'ASC')
    //         ->select('summaryelem.*')
    //         ->get();

    //     return view('staff.scholarsevaluation', compact('colleges', 'shs', 'jhs', 'elem', 'acadyear'));
    // }
}
