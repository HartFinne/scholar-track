<?php

namespace App\Http\Controllers\Scholar;

use App\Http\Controllers\Controller;
use App\Models\{
    grades,
    User,
    RegularAllowance,
    ClassReference,
    ClassSchedule,
    TravelItinerary,
    TravelLocation,
    LodgingInfo,
    OjtTravelItinerary,
    OjtLocation
};
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\{
    Auth,
    DB,
    Log,
    Storage
};

class RegularAllowanceForm extends Controller
{
    public function showSCRegular()
    {
        $requests = RegularAllowance::with('grades')
            ->whereRelation('grades', 'caseCode', Auth::user()->caseCode)
            ->get();
        return view('scholar.allowancerequest.scregular', compact('requests'));
    }

    public function showRegularFormInfo($id)
    {
        $data = User::with(['basicInfo', 'education', 'scholarshipinfo', 'addressinfo'])
            ->where('id', Auth::id())
            ->first();

        $regularAllowance = RegularAllowance::with([
            'grades',
            'classReference.classSchedules',
            'travelItinerary.travelLocations',
            'lodgingInfo',
            'ojtTravelItinerary.ojtLocations'
        ])->findOrFail($id);

        return view('scholar.allowancerequest.regularforminfo', compact('data', 'regularAllowance'));
    }

    public function showRegularForm()
    {
        $user = Auth::user();
        $data = User::with(['basicInfo', 'education', 'scholarshipinfo', 'addressinfo'])
            ->where('id', $user->id)
            ->first();

        $latestSemester = grades::select('SemesterQuarter', 'gid')
            ->latest('created_at')
            ->first();

        $availableSemesters = [];
        if ($latestSemester && !$this->hasExistingAllowance($latestSemester->gid)) {
            $availableSemesters[] = $latestSemester->toArray();
        }

        return view('scholar.allowancerequest.regularform', compact('data', 'availableSemesters'));
    }

    public function storeRegularForm(Request $request)
    {
        $validatedData = $request->validate($this->validationRules(), $this->validationMessages());

        DB::beginTransaction();
        try {
            $regularAllowance = $this->createRegularAllowance($validatedData);
            $directoryPath = $this->createUserDirectory($regularAllowance);

            $classReference = $this->createClassReference($validatedData, $directoryPath, $regularAllowance);
            $this->createClassSchedules($validatedData, $classReference->classID);
            $this->createTravelItinerary($validatedData, $regularAllowance->regularID);
            $this->createLodgingInfo($validatedData, $regularAllowance->regularID);
            $this->createOjtTravelItinerary($validatedData, $directoryPath, $regularAllowance->regularID);

            DB::commit();
            return redirect()->back()->with('success', 'Data successfully saved!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('failure', 'Error saving data: ' . $e->getMessage());
        }
    }

    private function validationRules()
    {
        return [
            'boardAddress' => 'nullable|string|max:255',
            'sem' => 'required|exists:grades,gid',
            'startSem' => 'required|date',
            'endSem' => 'required|date|after_or_equal:startSem',
            'time' => 'required|array',
            'time.*' => [
                'required',
                'string',
                'max:50',
                'regex:/^\d{1,2}:\d{2}\s?(AM|PM)\s?-\s?\d{1,2}:\d{2}\s?(AM|PM)$/'
            ],
            'mon.*' => 'nullable|string|max:50',
            'tue.*' => 'nullable|string|max:50',
            'wed.*' => 'nullable|string|max:50',
            'thu.*' => 'nullable|string|max:50',
            'fri.*' => 'nullable|string|max:50',
            'sat.*' => 'nullable|string|max:50',
            'sun.*' => 'nullable|string|max:50',
            'from.*' => 'nullable|string|max:100',
            'to.*' => 'nullable|string|max:100',
            'estimatedTime.*' => 'nullable|string|max:100',
            'vehicleType.*' => 'nullable|string|max:100',
            'fareRate.*' => 'nullable|integer|min:0',
            'nameOwner' => 'nullable|string|max:255',
            'contactNoOwner' => 'nullable|string|max:15',
            'rent' => 'nullable|integer|min:0',
            'lodgingType' => 'nullable|in:Dorm,Boarding House,Bed Space',
            'startOjt' => 'nullable|date',
            'endOjt' => 'nullable|date|after_or_equal:startOjt',
            'OJTfrom.*' => 'nullable|string|max:100',
            'OJTto.*' => 'nullable|string|max:100',
            'OJTestimatedTime.*' => 'nullable|string|max:100',
            'OJTvehicleType.*' => 'nullable|string|max:100',
            'OJTfareRate.*' => 'nullable|integer|min:0',
            'regForm' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'endorsement' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'acceptance' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ];
    }

    private function validationMessages()
    {
        return [
            'startOjt.required' => 'If one OJT field is filled, all OJT fields are required.',
            'endOjt.required' => 'If one OJT field is filled, all OJT fields are required.',
        ];
    }

    private function hasExistingAllowance($gid)
    {
        return RegularAllowance::where('gid', $gid)
            ->where('status', '!=', 'Rejected')
            ->exists();
    }

    private function createRegularAllowance($data)
    {
        return RegularAllowance::create([
            'gid' => $data['sem'],
            'start_of_semester' => $data['startSem'],
            'end_of_semester' => $data['endSem'],
            'status' => 'Pending',
        ]);
    }

    private function createUserDirectory($regularAllowance)
    {
        $user = Auth::user();
        $directoryPath = 'scholars/' . "{$user->basicInfo->scLastname}, {$user->basicInfo->scFirstname} {$user->basicInfo->scMiddlename}_{$user->caseCode}/regular_allowance/{$regularAllowance->regularID}";

        if (!Storage::exists('public/' . $directoryPath)) {
            Storage::makeDirectory('public/' . $directoryPath);
        }
        return $directoryPath;
    }

    private function createClassReference($data, $directoryPath, $regularAllowance)
    {
        $regFormPath = $this->storeFile($data['regForm'], 'regForm', $directoryPath);
        return ClassReference::create([
            'regularID' => $regularAllowance->regularID,
            'registration_form' => $regFormPath,
        ]);
    }

    private function createClassSchedules($data, $classID)
    {
        $schedules = [];
        foreach ($data['time'] as $index => $time) {
            $schedules[] = [
                'classID' => $classID,
                'time_slot' => $time,
                'mon' => $data['mon'][$index] ?? null,
                'tue' => $data['tue'][$index] ?? null,
                'wed' => $data['wed'][$index] ?? null,
                'thu' => $data['thu'][$index] ?? null,
                'fri' => $data['fri'][$index] ?? null,
                'sat' => $data['sat'][$index] ?? null,
                'sun' => $data['sun'][$index] ?? null,
            ];
        }
        ClassSchedule::insert($schedules);
    }

    private function createTravelItinerary($data, $regularID)
    {
        $itinerary = TravelItinerary::create(['regularID' => $regularID]);
        $locations = [];
        foreach ($data['from'] as $index => $from) {
            $locations[] = [
                'travelID' => $itinerary->travelID,
                'travel_from' => $from,
                'travel_to' => $data['to'][$index],
                'estimated_time' => $data['estimatedTime'][$index],
                'vehicle_type' => $data['vehicleType'][$index],
                'fare_rate' => $data['fareRate'][$index],
            ];
        }
        TravelLocation::insert($locations);
    }

    private function createLodgingInfo($data, $regularID)
    {
        // Check if at least one lodging info field is provided
        if (!empty($data['boardAddress']) || !empty($data['nameOwner']) || !empty($data['contactNoOwner']) || !empty($data['rent']) || !empty($data['lodgingType'])) {
            LodgingInfo::create([
                'regularID' => $regularID,
                'address' => $data['boardAddress'],
                'name_owner' => $data['nameOwner'],
                'contact_no_owner' => $data['contactNoOwner'],
                'monthly_rent' => $data['rent'],
                'lodging_type' => $data['lodgingType'],
            ]);
        }
    }

    private function createOjtTravelItinerary($data, $directoryPath, $regularID)
    {
        if (!empty($data['startOjt']) && !empty($data['endOjt'])) {
            $endorsementPath = $data['endorsement'] ? $this->storeFile($data['endorsement'], 'endorsement', $directoryPath) : null;
            $acceptancePath = $data['acceptance'] ? $this->storeFile($data['acceptance'], 'acceptance', $directoryPath) : null;

            $ojtItinerary = OjtTravelItinerary::create([
                'regularID' => $regularID,
                'start_of_ojt' => $data['startOjt'],
                'end_of_ojt' => $data['endOjt'],
                'endorsement' => $endorsementPath,
                'acceptance' => $acceptancePath,
            ]);

            if (!empty($data['OJTfrom'])) {
                $locations = [];
                foreach ($data['OJTfrom'] as $index => $OJTfrom) {
                    if ($OJTfrom && $data['OJTto'][$index] && $data['OJTestimatedTime'][$index] && $data['OJTvehicleType'][$index] && $data['OJTfareRate'][$index]) {
                        $locations[] = [
                            'ojtID' => $ojtItinerary->ojtID,
                            'ojt_from' => $OJTfrom,
                            'ojt_to' => $data['OJTto'][$index],
                            'ojt_estimated_time' => $data['OJTestimatedTime'][$index],
                            'ojt_vehicle_type' => $data['OJTvehicleType'][$index],
                            'ojt_fare_rate' => $data['OJTfareRate'][$index],
                        ];
                    }
                }
                OjtLocation::insert($locations);
            }
        }
    }

    private function storeFile($file, $name, $directoryPath)
    {
        return str_replace('public/', '', $file->storeAs(
            'public/' . $directoryPath,
            "{$name}." . $file->extension()
        ));
    }
}
