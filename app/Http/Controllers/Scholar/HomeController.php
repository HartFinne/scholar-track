<?php

namespace App\Http\Controllers\Scholar;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\ScAddressInfo;
use App\Models\ScBasicInfo;
use App\Models\ScClothingSize;
use App\Models\ScEducation;
use App\Models\scholarshipinfo;
use App\Models\Email;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use Illuminate\Validation\Rules\Password;
use Carbon\Carbon;

class HomeController extends Controller
{
    public function showRegistration()
    {
        // Retrieve grade/year levels and courses from the database
        $yearLevels = [
            'Elementary' => ['Grade 1', 'Grade 2', 'Grade 3', 'Grade 4', 'Grade 5', 'Grade 6'],
            'Junior High' => ['Grade 7', 'Grade 8', 'Grade 9', 'Grade 10'],
            'Senior High' => ['Grade 11', 'Grade 12'],
            'College' => ['First Year', 'Second Year', 'Third Year', 'Fourth Year', 'Fifth Year']
        ];

        $institutions = [
            'Elementary' => DB::table('institutions')->where('schoollevel', 'Elementary')->get(),
            'Junior High' => DB::table('institutions')->where('schoollevel', 'Junior High')->get(),
            'Senior High' => DB::table('institutions')->where('schoollevel', 'Senior High')->get(),
            'College' => DB::table('institutions')->where('schoollevel', 'College')->get()
        ];

        // Retrieve courses based on school levels
        $courses = [
            'Senior High' => DB::table('courses')->where('level', 'Senior High')->orderBy('coursename', 'ASC')->get(),
            'College' => DB::table('courses')->where('level', 'College')->orderBy('coursename', 'ASC')->get()
        ];

        return view('registration', compact('yearLevels', 'courses', 'institutions'));
    }

    function registerScholar(Request $request)
    {
        $alreadyRegistered = User::where('scEmail', $request->emailAddress)->exists();
        // dd($alreadyRegistered);
        if ($alreadyRegistered) {
            return redirect()->route('registration')->with('failure', 'Registration failed. Email address is already registered')->withInput();
        }

        try {
            $ScholarShipStatus = 'Continuing';
            $scStatus = 'Active';

            // $isEmailValid = $this->verifyEmail($request->emailAddress);
            // if (!$isEmailValid) {
            //     return redirect()->route('registration')->with('failure', 'Registration failed. Invalid email address')->withInput();
            // }

            $request->validate(
                [
                    'assignedArea' => 'required|string|max:25',
                    'startdate' => 'required|date',
                    'firstName' => 'required|string|max:50',
                    'middleName' => 'required|string|max:50',
                    'lastName' => 'required|string|max:50',
                    'chineseName' => 'required|string|max:255',
                    'birthdate' => 'required|date',
                    'sex' => 'required|in:Male,Female',
                    'occupation' => 'required|string|max:100',
                    'income' => 'required|numeric|min:0',
                    'shoes' => 'required|integer|min:6|max:12',
                    'slippers' => 'required|integer|min:6|max:12',
                    'isIndigenous' => 'required|in:Yes,No',
                    'indigenousGroup' => 'required_if:isIndigenous,yes|string|max:100',
                    'emailAddress' => 'required|email|max:255',
                    'phoneNumber' => 'digits_between:11,12',
                    'homeAddress' => 'required|string|max:255',
                    'region' => 'required|string|max:50',
                    'city' => 'required|string|max:50',
                    'barangay' => 'required|string|max:50',
                    'schoolLevel' => 'required|string|max:255',
                    'nameOfSchool' => 'required|string|max:255',
                    'courseStrand' => 'required_if:schoolLevel,College,Senior High',
                    'section' => 'required_if:schoolLevel,Elementary,Junior High',
                    'collegedept' => 'required_if:schoolLevel,College',
                    'guardianName' => 'required|string|max:50',
                    'relationToGuardian' => 'required|string|max:50',
                    'guardianEmailAddress' => 'required|email|max:100',
                    'guardianPhoneNumber' => 'digits_between:11,12',
                    'password' => [
                        'required',
                        'string',
                        'confirmed',
                        Password::min(8)
                            ->mixedCase()
                            ->numbers()
                            ->symbols()
                    ],
                    'agreement' => 'accepted',
                ],
                [
                    'isIndigenous.in' => 'Invalid selection for indigenous group.',
                    'indigenousGroup.required_if' => 'Please specify the indigenous group if you selected Yes.',
                    'password.min' => 'Password must be at least 8 characters long.',
                    'password.confirmed' => 'Password confirmation does not match.',
                    'agreement.accepted' => 'You must agree to the terms and conditions before proceeding.',
                ]
            );

            $startdate = Carbon::parse($request->startdate);

            if ($startdate->year == today()->year) {
                $scholartype = 'New Scholar';
            } else {
                $scholartype = 'Old Scholar';
            }

            $currentYear = Carbon::now()->year;

            // Set the target end date in the current year
            $enddate = Carbon::create($currentYear, $startdate->month, $startdate->day);

            // Check if the target end date has already passed in the current year
            if ($enddate->isPast()) {
                // If it has passed, set end date to the same month and day in the following year
                $enddate->addYear();
            }

            // Create the academic year string in the format YYYY-YYYY
            $academicYear = ($enddate->year - 1) . '-' . $enddate->year;
            $age = \Carbon\Carbon::parse($request->birthdate)->age;
            $phoneNumber = $request->input('phoneNumber');
            $guardianPhoneNumber = $request->input('guardianPhoneNumber');

            if (str_starts_with($phoneNumber, '0')) {
                $phoneNumber = '63' . substr($phoneNumber, 1);
            }

            if (str_starts_with($guardianPhoneNumber, '0')) {
                $guardianPhoneNumber = '63' . substr($guardianPhoneNumber, 1);
            }

            $casecode = $this->generatecasecode($request->startdate, $request->assignedArea);

            // Start a database transaction
            DB::beginTransaction();

            $User = User::create([
                'caseCode' => $casecode,
                'scEmail' => $request->emailAddress,
                'password' => Hash::make($request->password),
                'scPhoneNum' => $phoneNumber,
                'scStatus' => $scStatus,
            ]);

            // Insert data into scholarshipinfo
            $scholarshipinfo = scholarshipinfo::create([
                'caseCode' => $User->caseCode, // Foreign key from sc_account
                'area' => $request->assignedArea,
                'scholartype' => $scholartype,
                'startdate' => $request->startdate,
                'enddate' => $enddate,
                'scholarshipstatus' => $ScholarShipStatus,
            ]);

            // Insert data into sc_basicinfo
            $scBasicInfo = ScBasicInfo::create([
                'caseCode' => $User->caseCode, // Foreign key from sc_account
                'scFirstname' => $request->firstName,
                'scLastname' => $request->lastName,
                'scMiddlename' => $request->middleName,
                'scChinesename' => $request->chineseName,
                'scDateOfBirth' => $request->birthdate,
                'scAge' => $age,
                'scSex' => $request->sex,
                'scOccupation' => $request->occupation,
                'scIncome' => $request->income,
                'scFblink' => $request->fblink,
                'scGuardianName' => $request->guardianName,
                'scRelationToGuardian' => $request->relationToGuardian,
                'scGuardianEmailAddress' => $request->guardianEmailAddress,
                'scGuardianPhoneNumber' => $guardianPhoneNumber,
                'scIsIndigenous' => $request->isIndigenous,
                'scIndigenousgroup' => $request->isIndigenous == 'Yes' ? $request->indigenousGroup : 'Not Applicable',
            ]);

            // Insert data into sc_addressinfo
            $scAddressInfo = ScAddressInfo::create([
                'caseCode' => $User->caseCode,
                'scResidential' => $request->homeAddress,
                'scRegion' => $request->region,
                'scCity' => $request->city,
                'scBarangay' => $request->barangay,
            ]);

            // Insert data into sc_clothingsize
            $scClothingSize = ScClothingSize::create([
                'caseCode' => $User->caseCode,
                'scTShirtSize' => $request->tshirt,
                'scShoesSize' => $request->shoes,
                'scSlipperSize' => $request->slippers,
                'scPantsSize' => $request->pants,
                'scJoggingPantSize' => $request->joggingPants,
            ]);

            // Insert data into sc_education
            $scEducation = ScEducation::create([
                'caseCode' => $User->caseCode, // Foreign key from sc_account
                'scSchoolLevel' => $request->schoolLevel,
                'scSchoolName' => $request->nameOfSchool,
                'scYearGrade' => $request->yearLevel,
                'scCourseStrandSec' => in_array($request->schoolLevel, ['College', 'Senior High']) ? $request->courseStrand : $request->section,
                'scCollegedept' => $request->schoolLevel === 'College' ? $request->collegedept : null, // Only set if College
                'scAcademicYear' => $academicYear,
            ]);


            // If everything is successful, commit the transaction
            DB::commit();

            // Redirect with a success message
            return $this->showregiconfirmation($User->caseCode, $request->password);
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()->with('failure', 'Registration failed. Please try again. ' . $e->getMessage())->withInput();
        }
    }

    private function verifyEmail($email)
    {
        $isEmailValid = Email::where('email', $email)->exists();

        return ($isEmailValid);
    }

    private function generatecasecode($startdate, $area)
    {
        $date = new \DateTime($startdate);
        $startyear = $date->format('y');
        $nextyear = $date->modify('+1 year')->format('y');

        $areacode = '';

        if ($area == 'Mindong') {
            $areacode = 'MD';
        } elseif ($area == 'Minxi') {
            $areacode = 'MX';
        } elseif ($area == 'Minzhong') {
            $areacode = 'MZ';
        } elseif ($area == 'Bicol') {
            $areacode = 'BC';
        } elseif ($area == 'Davao') {
            $areacode = 'DV';
        } elseif ($area == 'Iloilo') {
            $areacode = 'ILO';
        } elseif ($area == 'Palo') {
            $areacode = 'PL';
        } elseif ($area == 'Zamboanga') {
            $areacode = 'ZB';
        }

        $latestCase = DB::table('users')
            ->where('caseCode', 'like', "{$startyear}{$nextyear}-%")
            ->orderBy('caseCode', 'desc')
            ->first();

        $sequenceNumber = 1;

        if ($latestCase) {
            $latestSequence = intval(explode('-', $latestCase->caseCode)[1]);
            $sequenceNumber = $latestSequence + 1;
        }

        $formattedSequence = str_pad($sequenceNumber, 5, '0', STR_PAD_LEFT);

        return "{$startyear}{$nextyear}-{$formattedSequence}-{$areacode}";
    }

    private function showregiconfirmation($casecode, $password)
    {
        return view('registersuccess', compact('casecode', 'password'));
    }
}
