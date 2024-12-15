<?php

namespace App\Http\Controllers;

use App\Models\apceducation;
use App\Models\apeheducation;
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
use App\Models\RnwCaseDetails;
use App\Models\RnwEducation;
use App\Models\RnwFamilyInfo;
use App\Models\RnwOtherInfo;
use App\Models\staccount;
use App\Models\RegularAllowance;
use App\Models\csattendance;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
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

        if ($iscollege) {
            $form = applicationforms::where('formname', 'College')->first();
        } else {
            $level = apeheducation::where('casecode', $casecode)->pluck('schoollevel')->first();
            $form = applicationforms::where('formname', $level)->first();
        }

        $cityApi = "https://psgc.gitlab.io/api/regions/{$applicant->region}/cities-municipalities/";
        $city = Http::get($cityApi)->collect()->firstWhere('code', $applicant->city)['name'] ?? 'Unknown City/Municipality';

        $barangayApi = "https://psgc.gitlab.io/api/cities-municipalities/{$applicant->city}/barangays/";
        $barangay = Http::get($barangayApi)->collect()->firstWhere('code', $applicant->barangay)['name'] ?? 'Unknown Barangay';

        $needs = ['Financial', 'Medical', 'Food', 'Material', 'Education'];

        $data = [
            'applicant' => $applicant,
            'father' => $father,
            'mother' => $mother,
            'siblings' => $siblings,
            'iscollege' => $iscollege,
            'form' => $form,
            'needs' => $needs,
            'city' => $city,
            'barangay' => $barangay,
        ];

        $pdf = Pdf::loadView('application-form', $data)
            ->setPaper([0, 0, 576, 936])
            ->set_option('defaultFont', 'Arial');

        return $pdf->stream("application-form-{$casecode}.pdf");
    }

    public function generateRenewalForm($id)
    {
        $applicant = renewal::with('grade', 'casedetails', 'otherinfo')->where('rid', $id)->first();
        $user = User::with(
            'basicInfo',
            'addressinfo',
            'education',
            'scholarshipinfo'
        )->where('caseCode', $applicant->caseCode)
            ->first();

        $iscollege = ScEducation::where('scSchoolLevel', 'College')->where('caseCode', $user->caseCode)->exists();

        $father = RnwFamilyInfo::where('caseCode', $user->caseCode)->where('relationship', 'Father')->first();
        $mother = RnwFamilyInfo::where('caseCode', $user->caseCode)->where('relationship', 'Mother')->first();
        $siblings = RnwFamilyInfo::where('caseCode', $user->caseCode)->where('relationship', 'Sibling')->get();

        $form = applicationforms::where('formname', 'Renewal')->first();

        $needs = ['Financial', 'Medical', 'Food', 'Material', 'Education'];
        $cityApi = "https://psgc.gitlab.io/api/regions/{$user->addressinfo->scRegion}/cities-municipalities/";
        $city = Http::get($cityApi)->collect()->firstWhere('code', $user->addressinfo->scCity)['name'] ?? 'Unknown City/Municipality';

        $barangayApi = "https://psgc.gitlab.io/api/cities-municipalities/{$user->addressinfo->scCity}/barangays/";
        $barangay = Http::get($barangayApi)->collect()->firstWhere('code', $user->addressinfo->scBarangay)['name'] ?? 'Unknown Barangay';

        $data = [
            'applicant' => $applicant,
            'user' => $user,
            'father' => $father,
            'mother' => $mother,
            'siblings' => $siblings,
            'iscollege' => $iscollege,
            'form' => $form,
            'needs' => $needs,
            'city' => $city,
            'barangay' => $barangay,
        ];

        $pdf = Pdf::loadView('renewal-form', $data)
            ->setPaper([0, 0, 576, 936])
            ->set_option('defaultFont', 'Arial');

        return $pdf->stream("Renewal-Form-{$user->caseCode}.pdf");
    }

    public function regularAllowanceForm($id)
    {
        $req = RegularAllowance::with([
            'classReference.classSchedules',
            'travelItinerary.travelLocations',
            'lodgingInfo',
            'ojtTravelItinerary.ojtLocations'
        ])->findOrFail($id);

        $data = User::with(['basicInfo', 'education', 'scholarshipinfo', 'addressinfo'])
            ->where('caseCode', $req->caseCode)
            ->first();

        $worker = staccount::where('area', $data->scholarshipinfo->area)->first();

        $data = [
            'req' => $req,
            'data' => $data,
            'worker' => $worker,
        ];

        $pdf = Pdf::loadView('regularallowance-form', $data)
            ->setPaper('letter', 'portrait');
        $pdf->setOption('margin-top', '50');

        return $pdf->stream("Regular-Allowance-Form-{$req->caseCode}.pdf");
        // return view('regularallowance-form', compact('data', 'req', 'worker'));
    }
}
