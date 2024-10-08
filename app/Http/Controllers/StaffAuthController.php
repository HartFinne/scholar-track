<?php

namespace App\Http\Controllers;

use App\Models\staccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class StaffAuthController extends Controller
{
    public function createAccount(Request $request)
    {
        try {
            // Validate the input data
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|max:255|unique:staccounts,email',
                'role' => 'required|string|max:25',
                'area' => 'required|string|max:25',
                'mobileno' => 'nullable|string|max:11|unique:staccounts,mobileno',
            ]);

            // Mobileno default value
            $mobileno = null;

            // Status default value
            $status = "Active";

            // Generate the password
            $nameParts = explode(' ', strtolower($request->name));
            $password = end($nameParts) . '.scholartrack';

            // Create the account
            Staccount::create([
                'name' => $request->name,
                'email' => $request->email,
                'mobileno' => $mobileno,
                'area' => $request->area,
                'role' => $request->role,
                'status' => $status,
                'password' => Hash::make($password), // hash the password
            ]);

            return redirect()->route('users-staff')->with('success', 'Account created successfully.');
        } catch (\Exception $e) {
            return redirect()->route('users-staff')->with('error', 'Account creation was unsuccessful. ' . $e->getMessage());
        }
    }
}
