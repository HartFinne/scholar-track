<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ScAccount;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;

class ScholarController extends Controller
{
    // for sclogin
    public function scholarLogin(Request $request)
    {
        // Validate the incoming request
        $validated = $request->validate([
            'caseCode' => 'required|regex:/^[0-9]{4}-[0-9]{5}-[A-Z]{2}$/',
            'password' => 'required|min:8'
        ], [
            'caseCode.required' => 'The case code is required.',
            'caseCode.regex' => 'The case code format is invalid.',
            'password.min' => 'Password must be at least 8 characters long.',
        ]);

        // Log for debugging
        Log::info('Attempting login with caseCode: ' . $validated['caseCode']);
        Log::info('Attempting login with password: ' . $validated['password']);  // O


        Log::info('Session ID before login: ' . session()->getId());

        $scholar = ScAccount::where('caseCode', $validated['caseCode'])->first();

        if ($scholar && Hash::check($validated['password'], $scholar->scPassword)) {
            Auth::login($scholar);
            $request->session()->regenerate();

            // Debug: Check if the user is authenticated
            if (Auth::check()) {
                Log::info('User is authenticated after login.');
                Log::info('Session ID after login: ' . session()->getId());
            } else {
                Log::error('User is NOT authenticated after login.');
            }
            Log::info('Session after login:', $request->session()->all());  // Log session data after login

            return redirect()->intended('scholar/schome');
        } else {
            return back()->withErrors([
                'caseCode' => 'The provided case code does not match our records.',
                'password' => 'The provided password is incorrect.',
            ])->onlyInput('caseCode');
        }
    }

    // for the show of basic info in manageprofile

    public function showInfo()
    {
        // Get the currently authenticated scholar
        $scholar = Auth::user(); // This will automatically retrieve the logged-in scholar’s details

        // Pass the scholar data to the 'manageprofile' view
        return view('scholar.manageprofile2', compact('scholar'));
    }
}
