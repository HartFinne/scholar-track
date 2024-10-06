<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    //
    function registerScholar(Request $request)
    {
        $validated = $request->validate(
            [
                'caseCode' => 'required|regex:/^[0-9]{4}-[0-9]{5}-[A-Z]{2}$/',
                'assignedArea' => 'required|string|max:255',
                'firstName' => 'required|string|max:50',
                'middleName' => 'required|string|max:50',
                'lastName' => 'required|string|max:50',
                'birthdate' => 'required|date',
                'sex' => 'required|in:Male,Female',
                'facebookName' => 'required|string|max:100',
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
                'collegeDepartment' => 'required|string|max:255',
                'yearLevel' => 'required|string|max:50',
                'courseSection' => 'required|string|max:50',
                'guardianName' => 'required|string|max:100',
                'relationToGuardian' => 'required|string|max:100',
                'guardianEmailAddress' => 'required|email|max:255',
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





        // if (!$saved) {
        //     // Redirect back with a failure message
        //     return redirect()->route('registration')->with('failure', 'Registration failed. Please try again.');
        // }


        // Return the validated data for further processing (e.g., saving to the database)
        return redirect()->route('registration')->with('success', 'Thank you for registering!');
    }
}
