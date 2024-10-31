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



class HomeController extends Controller
{
    //
    function registerScholar(Request $request)
    {
        $ScholarShipStatus = 'Continuing';
        $scStatus = 'Active';

        $isEmailValid = $this->verifyEmail($request->emailAddress);

        if ($isEmailValid) {
            $request->validate(
                [
                    'startdate' => 'required|date',
                    'enddate' => 'required|date',
                    'firstName' => 'required|string|max:50',
                    'middleName' => 'required|string|max:50',
                    'lastName' => 'required|string|max:50',
                    'chineseName' => 'required|string|max:255',
                    'birthdate' => 'required|date',
                    'sex' => 'required|in:Male,Female',
                    'shoes' => 'required|integer|min:6|max:12',
                    'slippers' => 'required|integer|min:6|max:12',
                    'isIndigenous' => 'required|in:Yes,No',
                    'indigenousGroup' => 'required_if:isIndigenous,yes|string|max:100',
                    'emailAddress' => 'required|email|max:255',
                    'phoneNumber' => 'required|regex:/^[0-9]{11}$/',
                    'homeAddress' => 'required|string|max:255',
                    'barangay' => 'required|string|max:50',
                    'city' => 'required|string|max:50',
                    'permanentAddress' => 'required|string|max:255',
                    'nameOfSchool' => 'required|string|max:255',
                    'courseSection' => 'required|string|max:50',
                    'acadyear' => 'required|string|max:9',
                    'guardianName' => 'required|string|max:50',
                    'relationToGuardian' => 'required|string|max:50',
                    'guardianEmailAddress' => 'required|email|max:100',
                    'guardianPhoneNumber' => 'required|regex:/^[0-9]{11}$/',
                    'password' => 'required|string|min:8|confirmed',
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

            // Adjust phone numbers
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

            try {
                // Insert data into sc_account
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
                    'scholartype' => $request->scholartype,
                    'startdate' => $request->startdate,
                    'enddate' => $request->enddate,
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
                    'scSex' => $request->sex,
                    'scGuardianName' => $request->guardianName,
                    'scRelationToGuardian' => $request->relationToGuardian,
                    'scGuardianEmailAddress' => $request->guardianEmailAddress,
                    'scGuardianPhoneNumber' => $guardianPhoneNumber,
                    'scIsIndigenous' => $request->isIndigenous,
                    'scIndigenousgroup' => $request->isIndigenous == 'Yes' ? $request->indigenousGroup : 'Not Applicable',
                ]);

                // Insert data into sc_addressinfo
                $scAddressInfo = ScAddressInfo::create([
                    'caseCode' => $User->caseCode, // Foreign key from sc_account
                    'scResidential' => $request->homeAddress, // homeAddress -> scResidential
                    'scBarangay' => $request->barangay, // barangay -> scBarangay
                    'scCity' => $request->city, // city -> scCity
                    'scPermanent' => $request->permanentAddress, // permanentAddress -> scPermanent
                ]);

                // Insert data into sc_clothingsize
                $scClothingSize = ScClothingSize::create([
                    'caseCode' => $User->caseCode, // Foreign key from sc_account
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
                    'scCourseStrandSec' => $request->courseSection,
                    'scAcademicYear' => $request->acadyear,
                ]);

                // If everything is successful, commit the transaction
                DB::commit();

                // Redirect with a success message
                return $this->showregiconfirmation($User->caseCode, $request->password);
            } catch (\Exception $e) {
                DB::rollBack();

                // Dump the error message directly to the screen for debugging
                dd($e->getMessage());

                return redirect()->route('registration')->with('failure', 'Registration failed. Please try again.');
            }
        } else {
            return redirect()->route('registration')->with('failure', 'Registration failed. Invalid email address');
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
