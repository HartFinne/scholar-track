<?php

namespace App\Http\Controllers\Scholar;

use App\Http\Controllers\Controller;
use App\Models\grades;
use App\Models\User;
use App\Models\RegularAllowance;
use App\Models\ClassReference;
use App\Models\ClassSchedule;
use App\Models\TravelItinerary;
use App\Models\TravelLocation;
use App\Models\LodgingInfo;
use App\Models\OjtTravelItinerary;
use App\Models\OjtLocation;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class RegularAllowanceForm extends Controller
{
    //

    public function showSCRegular()
    {
        $user = Auth::user();
        $requests = RegularAllowance::join('grades', 'grades.gid', '=', 'regular_allowance.gid')
            ->where('grades.caseCode', $user->caseCode) // Filter by authenticated user ID
            ->get();
        return view('scholar.allowancerequest.scregular', compact('requests'));
    }


    public function showRegularFormInfo($id)
    {
        $user = Auth::user();

        // Load the related data with the user (eager loading)
        $data = User::with(['basicInfo', 'education', 'scholarshipinfo', 'addressinfo'])
            ->where('id', $user->id)
            ->first();


        // Retrieve the regular allowance request by ID with related information
        $regularAllowance = RegularAllowance::with([
            'grades',
            'classReference.classSchedules',
            'travelItinerary.travelLocations',
            'lodgingInfo',
            'ojtTravelItinerary.ojtLocations'
        ])->findOrFail($id);

        // dd($regularAllowance);

        return view('scholar.allowancerequest.regularforminfo', compact('data', 'regularAllowance'));
    }

    public function showRegularForm()
    {
        // Retrieve the authenticated user
        $user = Auth::user();

        // Load the related data with the user (eager loading)
        $data = User::with(['basicInfo', 'education', 'scholarshipinfo', 'addressinfo'])
            ->where('id', Auth::id())
            ->first();

        // Get the most recent academic year for the user
        $currentEducation = $data->education()->orderBy('created_at', 'desc')->first();
        $availableSemesters = [];

        if ($currentEducation) {
            // Fetch the latest semester (most recent based on `created_at`) for this academic year
            $latestSemester = grades::select('SemesterQuarter', 'gid')
                ->orderBy('created_at', 'desc')
                ->first();

            // Check if the latest semester already has a "Pending" or "Completed" regular allowance
            if ($latestSemester) {
                $hasExistingAllowance = RegularAllowance::where('gid', $latestSemester->gid)
                    ->where('status', '!=', 'Rejected') // Check for "Pending" or "Completed" statuses
                    ->exists();

                // Only include the latest semester if there’s no existing allowance with "Pending" or "Completed" status
                if (!$hasExistingAllowance) {
                    $availableSemesters[] = $latestSemester->toArray();
                }
            }
        }

        return view('scholar.allowancerequest.regularform', compact('data', 'availableSemesters'));
    }


    public function storeRegularForm(Request $request)
    {
        // dd($request);

        $validatedData = $request->validate([
            'boardAddress' => 'nullable|string|max:255',
            'sem' => 'required|exists:grades,gid',
            'startSem' => 'required|date',
            'endSem' => 'required|date|after_or_equal:startSem',


            // Class schedule
            'time' => 'required|array',
            'time.*' => [
                'required',
                'string',
                'max:50',
                'regex:/^\d{1,2}:\d{2} (AM|PM) - \d{1,2}:\d{2} (AM|PM)$/'
            ],

            'mon' => 'array',
            'mon.*' => 'string|nullable|max:50',
            'tue' => 'array',
            'tue.*' => 'string|nullable|max:50',
            'wed' => 'array',
            'wed.*' => 'string|nullable|max:50',
            'thu' => 'array',
            'thu.*' => 'string|nullable|max:50',
            'fri' => 'array',
            'fri.*' => 'string|nullable|max:50',
            'sat' => 'array',
            'sat.*' => 'string|nullable|max:50',
            'sun' => 'array',
            'sun.*' => 'string|nullable|max:50',

            // Travel itinerary
            'from' => 'required|array',
            'from.*' => 'string|max:100',
            'to' => 'required|array',
            'to.*' => 'string|max:100',
            'estimatedTime' => 'required|array',
            'estimatedTime.*' => 'string|max:100',
            'vehicleType' => 'required|array',
            'vehicleType.*' => 'string|max:100',
            'fareRate' => 'required|array',
            'fareRate.*' => 'integer|min:0',

            // Lodging information
            'nameOwner' => 'nullable|string|max:255',
            'contactNoOwner' => 'nullable|string|max:15',
            'rent' => 'nullable|integer|min:0',
            'lodgingType' => 'nullable|in:Dorm,Boarding House,Bed Space',

            // OJT Travel itinerary

            'startOjt' => 'nullable|date',
            'endOjt' => 'nullable|date|after_or_equal:startOjt',
            'OJTfrom' => 'nullable|array',
            'OJTfrom.*' => 'nullable|string|max:100',

            'OJTto' => 'nullable|array',
            'OJTto.*' => 'nullable|string|max:100',

            'OJTestimatedTime' => 'nullable|array',
            'OJTestimatedTime.*' => 'nullable|string|max:100',

            'OJTvehicleType' => 'nullable|array',
            'OJTvehicleType.*' => 'nullable|string|max:100',

            'OJTfareRate' => 'nullable|array',
            'OJTfareRate.*' => 'nullable|integer|min:0',


            // File uploads
            'regForm' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048', // max 2MB
            'endorsement' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048', // optional
            'acceptance' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048', // optional
        ], [
            'startOjt.required => If one OJT field is filled, all OJT fields are required.',
            'endOjt.required => If one OJT field is filled, all OJT fields are required.',
            'OJTfrom.required' => 'If one OJT field is filled, all OJT fields are required.',
            'OJTto.required' => 'If one OJT field is filled, all OJT fields are required.',
            'OJTestimatedTime.required' => 'If one OJT field is filled, all OJT fields are required.',
            'OJTvehicleType.required' => 'If one OJT field is filled, all OJT fields are required.',
            'OJTfareRate.required' => 'If one OJT field is filled, all OJT fields are required.',
            'acceptance.required' => 'If one OJT field is filled, all OJT fields are required.',
            'endorsement.required' => 'If one OJT field is filled, all OJT fields are required.',
        ]);




        try {
            // Step 1: Insert into regular_allowance table
            $regularAllowance = RegularAllowance::create([
                'gid' => $validatedData['sem'], // assuming gid is related to the authenticated user
                'start_of_semester' => $validatedData['startSem'],
                'end_of_semester' => $validatedData['endSem'],
                'status' => 'Pending', // default status or set based on your logic
            ]);



            // Ensure the authenticated user is retrieved
            $user = Auth::user();

            // Construct the directory path based on the user's full name and case code
            $directoryPath = 'scholars/'
                . $user->basicInfo->scLastname . ', '
                . $user->basicInfo->scFirstname . ' '
                . $user->basicInfo->scMiddlename . '_'
                . $user->caseCode . '/regular_allowance/' . $regularAllowance->regularID;

            // Ensure the directory exists
            if (!Storage::exists('public/' . $directoryPath)) {
                Storage::makeDirectory('public/' . $directoryPath);
            }

            // Store the proof image (regForm) in the specified directory
            $regFormPath = $request->file('regForm')->storeAs(
                'public/' . $directoryPath,
                'regForm.' . $request->file('regForm')->getClientOriginalExtension()
            );
            $regFormPathForDb = str_replace('public/', '', $regFormPath);

            // Store the endorsement file if it exists
            $endorsementPathForDb = null;
            if ($request->hasFile('endorsement')) {
                $endorsementPath = $request->file('endorsement')->storeAs(
                    'public/' . $directoryPath,
                    'endorsement.' . $request->file('endorsement')->getClientOriginalExtension()
                );
                $endorsementPathForDb = str_replace('public/', '', $endorsementPath);
            }

            // Store the acceptance file if it exists
            $acceptancePathForDb = null;
            if ($request->hasFile('acceptance')) {
                $acceptancePath = $request->file('acceptance')->storeAs(
                    'public/' . $directoryPath,
                    'acceptance.' . $request->file('acceptance')->getClientOriginalExtension()
                );
                $acceptancePathForDb = str_replace('public/', '', $acceptancePath);
            }





            // Step 2: Insert into class_reference table
            $classReference = ClassReference::create([
                'regularID' => $regularAllowance->regularID,
                'registration_form' => $regFormPathForDb, // assuming file upload and storage
            ]);


            // Step 3: Insert into class_schedule table
            foreach ($validatedData['time'] as $index => $time) {
                ClassSchedule::create([
                    'classID' => $classReference->classID,
                    'time_slot' => $time,
                    'mon' => $validatedData['mon'][$index] ?? null,
                    'tue' => $validatedData['tue'][$index] ?? null,
                    'wed' => $validatedData['wed'][$index] ?? null,
                    'thu' => $validatedData['thu'][$index] ?? null,
                    'fri' => $validatedData['fri'][$index] ?? null,
                    'sat' => $validatedData['sat'][$index] ?? null,
                    'sun' => $validatedData['sun'][$index] ?? null,
                ]);
            }

            // Step 4: Insert into travel_itinerary and travel_location tables
            $travelItinerary = TravelItinerary::create([
                'regularID' => $regularAllowance->regularID,
            ]);

            foreach ($validatedData['from'] as $index => $from) {
                TravelLocation::create([
                    'travelID' => $travelItinerary->travelID,
                    'travel_from' => $from,
                    'travel_to' => $validatedData['to'][$index],
                    'estimated_time' => $validatedData['estimatedTime'][$index],
                    'vehicle_type' => $validatedData['vehicleType'][$index],
                    'fare_rate' => $validatedData['fareRate'][$index],
                ]);
            }

            // Step 5: Insert into lodging_info table
            LodgingInfo::create([
                'regularID' => $regularAllowance->regularID,
                'address' => $validatedData['boardAddress'],
                'name_owner' => $validatedData['nameOwner'],
                'contact_no_owner' => $validatedData['contactNoOwner'],
                'monthly_rent' => $validatedData['rent'],
                'lodging_type' => $validatedData['lodgingType'],
            ]);

            // Step 6: Insert into ojt_travel_itinerary and ojt_location tables
            $ojtTravelItinerary = OjtTravelItinerary::create([
                'regularID' => $regularAllowance->regularID,
                'start_of_ojt' => $validatedData['startOjt'],
                'end_of_ojt' => $validatedData['endOjt'],
                'endorsement' => $endorsementPathForDb, // assuming file upload
                'acceptance' => $acceptancePathForDb,   // assuming file upload
            ]);

            foreach ($validatedData['OJTfrom'] as $index => $OJTfrom) {
                OjtLocation::create([
                    'ojtID' => $ojtTravelItinerary->ojtID,
                    'ojt_from' => $OJTfrom,
                    'ojt_to' => $validatedData['OJTto'][$index],
                    'ojt_estimated_time' => $validatedData['OJTestimatedTime'][$index],
                    'ojt_vehicle_type' => $validatedData['OJTvehicleType'][$index],
                    'ojt_fare_rate' => $validatedData['OJTfareRate'][$index],
                ]);
            }


            return redirect()->back()->with('success', 'Data successfully saved!');
        } catch (\Exception $e) {
            return redirect()->back()->with('failure', 'Error saving data: ' . $e->getMessage());
        }
    }
}
