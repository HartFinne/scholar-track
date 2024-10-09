<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

abstract class Controller
{
    public function registerScholar(Request $request)
    {
        // First, validate the incoming request
        $validated = $request->validate([
            'caseCode' => 'required|regex:/^[0-9]{4}-[0-9]{5}-[A-Z]{2}$/',
            'assignedArea' => 'required|string|max:255',
            'firstName' => 'required|string|max:50',
            'middleName' => 'required|string|max:50',
            'lastName' => 'required|string|max:50',
            'birthdate' => 'required|date',
            'sex' => 'required|in:Male,Female',
            'tshirt' => 'required|in:XS,S,M,L,XL,2XL,3XL,4XL',
            'shoes' => 'required|numeric|min:6|max:12',
            'slippers' => 'required|numeric|min:6|max:12',
            'pants' => 'required|in:XS,S,M,L,XL,2XL,3XL,4XL',
            'joggingPants' => 'required|in:XS,S,M,L,XL,2XL,3XL,4XL',
            'isIndigenous' => 'required|in:yes,no',
            'indigenousGroup' => 'required_if:isIndigenous,yes|string|max:255',
            'emailAddress' => 'required|email|max:255',
            'phoneNumber' => 'required|regex:/^[0-9]{10,11}$/',
            'homeAddress' => 'required|string|max:255',
            'barangay' => 'required|string|max:50',
            'city' => 'required|string|max:255',
            'province' => 'required|string|max:255',
            'region' => 'required|string|max:10',
            'permanentAddress' => 'required|string|max:255',
            'schoolLevel' => 'required|in:Elementary,Highschool,College',
            'nameOfSchool' => 'required|string|max:255',
            'yearLevel' => 'required|string|max:50',
            'courseSection' => 'required|string|max:50',
            'guardianName' => 'required|string|max:100',
            'relationToGuardian' => 'required|string|max:100',
            'guardianEmailAddress' => 'required|email|max:255',
            'guardianPhoneNumber' => 'required|regex:/^[0-9]{10,11}$/',
            'password' => 'required|string|min:8|confirmed',
            'agreement' => 'accepted',
        ]);

        // Insert data into sc_account
        $scAccount = ScAccount::create([
            'scholarID' => $validated['caseCode'],    // scholarID -> caseCode
            'scEmail' => $validated['emailAddress'],  // emailAddress -> scEmail
            'scPassword' => bcrypt($validated['password']), // password -> scPassword (hashed)
            'scPhoneNum' => $validated['phoneNumber'], // phoneNumber -> scPhoneNum
            'scAgreement' => $validated['agreement'], // agreement -> scAgreement
        ]);

        // Insert data into sc_basicinfo
        $scBasicInfo = ScBasicInfo::create([
            'scholarID' => $scAccount->scholarID,  // Foreign key from sc_account
            'scFirstname' => $validated['firstName'],
            'scLastname' => $validated['lastName'],
            'scMiddlename' => $validated['middleName'],
            'scDateOfBirth' => $validated['birthdate'],
            'scSex' => $validated['sex'],
            'scGuardianName' => $validated['guardianName'],
            'scRelationToGuardian' => $validated['relationToGuardian'],
            'scGuardianEmailAddress' => $validated['guardianEmailAddress'],
            'scGuardianPhoneNumber' => $validated['guardianPhoneNumber'],
            'scIsIndigenous' => $validated['isIndigenous'] == 'yes' ? $validated['indigenousGroup'] : 'no', // Handle indigenous group condition
        ]);

        // Insert data into sc_addressinfo
        $scAddressInfo = ScAddressInfo::create([
            'scholarID' => $scAccount->scholarID,  // Foreign key from sc_account
            'scArea' => $validated['assignedArea'],  // assignedArea -> scArea
            'scResidential' => $validated['homeAddress'], // homeAddress -> scResidential
            'scBarangay' => $validated['barangay'], // barangay -> scBarangay
            'scCity' => $validated['city'], // city -> scCity
            'scProvince' => $validated['province'], // province -> scProvince
            'scRegion' => $validated['region'], // region -> scRegion
            'scPermanent' => $validated['permanentAddress'], // permanentAddress -> scPermanent
        ]);

        // Insert data into sc_clothingsize
        $scClothingSize = ScClothingSize::create([
            'scholarID' => $scAccount->scholarID,  // Foreign key from sc_account
            'scTShirtSize' => $validated['tshirt'], // tshirt -> scTShirtSize
            'scShoesSize' => $validated['shoes'], // shoes -> scShoesSize
            'scSlipperSize' => $validated['slippers'], // slippers -> scSlipperSize
            'scPantsSize' => $validated['pants'], // pants -> scPantsSize
            'scJoggingPantSize' => $validated['joggingPants'], // joggingPants -> scJoggingPantSize
        ]);

        // Insert data into sc_education
        $scEducation = ScEducation::create([
            'scholarID' => $scAccount->scholarID,  // Foreign key from sc_account
            'scSchoolLevel' => $validated['schoolLevel'], // schoolLevel -> scSchoolLevel
            'scSchoolName' => $validated['nameOfSchool'], // nameOfSchool -> scSchoolName
            'scYearLevel' => $validated['yearLevel'], // yearLevel -> scYearLevel
            'scCourseStrand' => $validated['courseSection'], // courseSection -> scCourseStrand
        ]);

        // Return success message and redirect
        return redirect()->route('registration')->with('success', 'Thank you for registering!');
    }
}
