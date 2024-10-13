<?php

namespace App\Http\Controllers\Scholar;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Db;
use App\Models\User;
use App\Models\ScAddressInfo;
use App\Models\ScBasicInfo;
use App\Models\ScClothingSize;
use App\Models\ScEducation;
use App\Models\scholarshipinfo;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;


class HomeController extends Controller
{
    //
    function registerScholar(Request $request)
    {

        $sizeOptions = 'XS,S,M,L,XL,2XL,3XL,4XL';
        $ScholarShipStatus = 'Continuing';
        $scStatus = 'Active';

        $request->validate(
            [
                // Basic validation for different fields
                'caseCode' => 'required|regex:/^[0-9]{4}-[0-9]{5}-[A-Z]{2}$/',
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
                'phoneNumber' => 'required|regex:/^[0-9]{10,11}$/',
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
                'guardianPhoneNumber' => 'required|regex:/^[0-9]{10,11}$/',
                'password' => 'required|string|min:8|confirmed',
                'agreement' => 'accepted',
            ],
            [
                // Custom error messages
                'caseCode.required' => 'The case code is required.',
                'caseCode.regex' => 'The case code format is invalid.',
                'isIndigenous.in' => 'Invalid selection for indigenous group.',
                'indigenousGroup.required_if' => 'Please specify the indigenous group if you selected Yes.',
                'phoneNumber.regex' => 'Phone number must be 10 to 11 digits.',
                'guardianPhoneNumber.regex' => 'Guardian’s phone number must be 10 to 11 digits.',
                'password.min' => 'Password must be at least 8 characters long.',
                'password.confirmed' => 'Password confirmation does not match.',
                'agreement.accepted' => 'You must agree to the terms and conditions before proceeding.',
            ]
        );

        // Start a database transaction
        DB::beginTransaction();

        try {
            // Insert data into sc_account
            $User = User::create([
                'caseCode' => $request->caseCode,
                'scEmail' => $request->emailAddress,
                'password' => Hash::make($request->password),
                'scPhoneNum' => $request->phoneNumber,
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
                'scGuardianPhoneNumber' => $request->guardianPhoneNumber,
                'scIsIndigenous' => $request->isIndigenous == 'Yes' ? $request->indigenousGroup : 'No',
                'scIndigenousgroup' => $request->indigenousGroup,
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
            return redirect()->route('registration')->with('success', 'Thank you for registering!');
        } catch (\Exception $e) {
            DB::rollBack();

            // Dump the error message directly to the screen for debugging
            dd($e->getMessage());

            return redirect()->route('registration')->with('failure', 'Registration failed. Please try again.');
        }
    }
}
