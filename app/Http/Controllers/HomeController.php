<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Db;
use App\Models\User;
use App\Models\ScAddressInfo;
use App\Models\ScBasicInfo;
use App\Models\ScClothingSize;
use App\Models\ScEducation;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;


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
                'assignedArea' => 'required|string|max:255',
                'firstName' => 'required|string|max:50',
                'middleName' => 'required|string|max:50',
                'lastName' => 'required|string|max:50',
                'birthdate' => 'required|date',
                'sex' => 'required|in:Male,Female',
                'tshirt' => "required|in:$sizeOptions",  // Use predefined size options
                'shoes' => 'required|integer|min:6|max:12',
                'slippers' => 'required|integer|min:6|max:12',
                'pants' => "required|in:$sizeOptions",
                'joggingPants' => "required|in:$sizeOptions",
                'isIndigenous' => 'required|in:yes,no',
                'indigenousGroup' => 'required_if:isIndigenous,yes|string|max:50',
                'emailAddress' => 'required|email|max:255',
                'phoneNumber' => 'required|regex:/^[0-9]{10,11}$/',
                'homeAddress' => 'required|string|max:255',
                'barangay' => 'required|string|max:50',
                'city' => 'required|string|max:50',
                'province' => 'required|string|max:50',
                'region' => 'required|string|max:10',
                'permanentAddress' => 'required|string|max:255',
                'schoolLevel' => 'required|in:Elementary,Highschool,College',
                'nameOfSchool' => 'required|string|max:255',
                'yearLevel' => 'required|string|max:50',
                'courseSection' => 'required|string|max:50',
                'semester' => 'required|in:1st Semester,2nd Semester',
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
                'sex.in' => 'Please select either Male or Female.',
                'isIndigenous.in' => 'Invalid selection for indigenous group.',
                'indigenousGroup.required_if' => 'Please specify the indigenous group if you selected Yes.',
                'phoneNumber.regex' => 'Phone number must be 10 to 11 digits.',
                'guardianPhoneNumber.regex' => 'Guardian’s phone number must be 10 to 11 digits.',
                'schoolLevel.in' => 'Please select a valid school level (Elementary, High School, or College).',
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
                'caseCode' => $request->caseCode,    // scholarID -> caseCode
                'scEmail' => $request->emailAddress,  // emailAddress -> scEmail
                'password' => Hash::make($request->password), // password -> scPassword (hashed)
                'scPhoneNum' => $request->phoneNumber, // phoneNumber -> scPhoneNum
                'scStatus' => $scStatus, // phoneNumber -> scPhoneNum
            ]);

            // Insert data into sc_basicinfo
            $scBasicInfo = ScBasicInfo::create([
                'caseCode' => $User->caseCode,  // Foreign key from sc_account
                'scFirstname' => $request->firstName,
                'scLastname' => $request->lastName,
                'scMiddlename' => $request->middleName,
                'scDateOfBirth' => $request->birthdate,
                'scSex' => $request->sex,
                'scGuardianName' => $request->guardianName,
                'scRelationToGuardian' => $request->relationToGuardian,
                'scGuardianEmailAddress' => $request->guardianEmailAddress,
                'scGuardianPhoneNumber' => $request->guardianPhoneNumber,
                'scIsIndigenous' => $request->isIndigenous == 'yes' ? $request->indigenousGroup : 'no',
                'scScholarshipStatus' => $ScholarShipStatus,
            ]);

            // Insert data into sc_addressinfo
            $scAddressInfo = ScAddressInfo::create([
                'caseCode' => $User->caseCode,  // Foreign key from sc_account
                'scArea' => $request->assignedArea,  // assignedArea -> scArea
                'scResidential' => $request->homeAddress, // homeAddress -> scResidential
                'scBarangay' => $request->barangay, // barangay -> scBarangay
                'scCity' => $request->city, // city -> scCity
                'scProvince' => $request->province, // province -> scProvince
                'scRegion' => $request->region, // region -> scRegion
                'scPermanent' => $request->permanentAddress, // permanentAddress -> scPermanent
            ]);

            // Insert data into sc_clothingsize
            $scClothingSize = ScClothingSize::create([
                'caseCode' => $User->caseCode,  // Foreign key from sc_account
                'scTShirtSize' => $request->tshirt, // tshirt -> scTShirtSize
                'scShoesSize' => $request->shoes, // shoes -> scShoesSize
                'scSlipperSize' => $request->slippers, // slippers -> scSlipperSize
                'scPantsSize' => $request->pants, // pants -> scPantsSize
                'scJoggingPantSize' => $request->joggingPants, // joggingPants -> scJoggingPantSize
            ]);

            // Insert data into sc_education
            $scEducation = ScEducation::create([
                'caseCode' => $User->caseCode,  // Foreign key from sc_account
                'scSchoolLevel' => $request->schoolLevel, // schoolLevel -> scSchoolLevel
                'scSchoolName' => $request->nameOfSchool, // nameOfSchool -> scSchoolName
                'scYearLevel' => $request->yearLevel, // yearLevel -> scYearLevel
                'scCourseStrand' => $request->courseSection, // courseSection -> scCourseStrand
                'scSemester' => $request->semester, // semester -> scSemester
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
