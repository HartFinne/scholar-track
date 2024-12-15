<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Request</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link href="{{ asset('css/regularform.css') }}" rel="stylesheet">
    <style>
        .border-dash {
            border-style: dashed !important;
        }
    </style>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body>
    <!-- PAGE HEADER -->
    @include('partials._pageheader')
    <x-alert />
    @php
        use Carbon\Carbon;
    @endphp

    <div class="ctnmain">
        <div class="container p-0" style="width: 8.5in">
            <div class="row fw-bold h2 text-success mb-3">
                <div class="col-12 text-center">Regular Allowance Form</div>
            </div>
            <div class="row justify-content-between align-items-center">
                <div class="col" hidden>
                    <button class="btn btn-warning fw-bold">Update Request Detail</button>
                </div>
                <div class="col">
                    <a class="btn btn-success fw-bold" target="_blank"
                        href="{{ route('regularAllowanceForm', ['id' => $req->regularID]) }}">Download Form</a>
                </div>
                <div class="col text-end">
                    <a href="{{ route('allowancerequests-regular') }}" class="btn btn-success">Go back</a>
                </div>
            </div>
        </div>
        <div class="regular-form mt-0">
            <div class="page1">
                <div class="heading">
                    <p><strong>佛教慈濟慈善事業基金會菲律濱分會<br>
                            Buddhist Compassion Relief Tzu Chi Foundation, Philippines</strong></p>
                </div>
                <div class="subheader">
                    <p><strong>Educational Assistance Program</strong></p>
                </div>
                <div>
                    <p class="form-title"><strong>ALLOWANCE REQUEST FORM</strong></p>
                    <div class="subtitle">
                        <div>{{ $data->education->scCollegedept ?? 'Failed to load' }}</div>
                        <div>{{ $req->semester ?? 'Failed to load' }} | SY:
                            {{ $req->schoolyear ?? 'Failed to load' }}</div>
                    </div>
                </div>

                <div class="table1">
                    <table class="table-bordered border-dark">
                        <tr>
                            <td class="gray">Name</td>
                            <td>{{ $data->basicInfo->scFirstname }} {{ $data->basicInfo->scLastname }}
                            </td>
                            <td class="gray">Date Submitted</td>
                            <td>{{ Carbon::parse($req->created_at)->format('F j, Y') }}</td>
                        </tr>
                        <tr>
                            <td class="gray">School</td>
                            <td colspan="4">{{ $data->education->scSchoolName }}</td>
                        </tr>
                        <tr>
                            <td class="gray">School Category</td>
                            <td>{{ $data->education->scSchoolLevel }}</td>
                            <td class="gray">Contact No.</td>
                            <td>{{ $data->scPhoneNum }}</td>
                        </tr>
                        <tr>
                            <td class="gray">Year Level</td>
                            <td>{{ $data->education->scYearGrade }}</td>
                            <td class="gray">Course</td>
                            <td>{{ $data->education->scCourseStrandSec }}</td>
                        </tr>
                        <tr>
                            <td class="gray">Home Address</td>
                            <td>{{ $data->addressinfo->scResidential }}</td>
                            <td class="gray">Boarding House Address</td>
                            <td>{{ $data->lodgingInfo->address ?? 'Not Applicable' }}</td>
                        </tr>
                        <tr>
                            <td class="gray">Start of Semester</td>
                            <td>{{ Carbon::parse($req->start_of_semester)->format('F j, Y') }}</td>
                            <td class="gray">End of Semester</td>
                            <td>{{ Carbon::parse($req->end_of_semester)->format('F j, Y') }}
                            </td>
                        </tr>
                        <tr>
                            <td class="gray">Start of OJT</td>
                            <td>{{ optional($req->ojtTravelItinerary)->start_of_ojt ? Carbon::parse($req->ojtTravelItinerary->start_of_ojt)->format('F j, Y') : 'Not Applicable' }}
                            </td>
                            </td>
                            <td class="gray">End of OJT</td>
                            <td>{{ optional($req->ojtTravelItinerary)->end_of_ojt ? Carbon::parse($req->ojtTravelItinerary->end_of_ojt)->format('F j, Y') : 'Not Applicable' }}
                            </td>
                            </td>
                        </tr>
                    </table>
                </div>

                <div class="section">
                    <div class="sec-title">CLASS SCHEDULE (REFERENCE ONLY)</div>
                    <div>* Attached the photocopy of the official class schedule from the Registrar (Registration
                        Form)<br>
                        * Attached duly accomplished daily travel itinerary.</div>
                    <div class="table2 mt-2">
                        <table class="table-bordered border-dark">
                            <thead>
                                <tr>
                                    <th class="text-center align-middle">TIME</th>
                                    <th class="text-center align-middle">MON</th>
                                    <th class="text-center align-middle">TUE</th>
                                    <th class="text-center align-middle">WED</th>
                                    <th class="text-center align-middle">THU</th>
                                    <th class="text-center align-middle">FRI</th>
                                    <th class="text-center align-middle">SAT</th>
                                    <th class="text-center align-middle">SUN</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($req->classReference && $req->classReference->classSchedules->isNotEmpty())
                                    @foreach ($req->classReference->classSchedules as $schedule)
                                        <tr>
                                            <td class="text-center align-middle">{{ $schedule->time_slot }}</td>
                                            <td class="text-center align-middle">{{ $schedule->mon ?? '--' }}</td>
                                            <td class="text-center align-middle">{{ $schedule->tue ?? '--' }}</td>
                                            <td class="text-center align-middle">{{ $schedule->wed ?? '--' }}</td>
                                            <td class="text-center align-middle">{{ $schedule->thu ?? '--' }}</td>
                                            <td class="text-center align-middle">{{ $schedule->fri ?? '--' }}</td>
                                            <td class="text-center align-middle">{{ $schedule->sat ?? '--' }}</td>
                                            <td class="text-center align-middle">{{ $schedule->sun ?? '--' }}</td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="8" class="text-center">No class schedule available</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>

                </div>
                <div class="border border-2 border-dash border-dark"></div>
                <div class="section">
                    <div class="sec-title">TO BE FILLED BY TC-CHARITY STAFF</div>
                    <div><em>*Important note: The number of school days per month is 20 regardless
                            of the scholars' class schedule for the computation of transportation and food allowances.
                        </em>
                    </div>
                    <div class="table3 mt-2">
                        <table class="table-bordered border-dark">
                            <tr>
                                <th colspan="3"
                                    class="table-heading text-center align-middle text-center align-middle">MONTHLY
                                    LIVING
                                    ALLOWANCES (MLA)</th>
                            </tr>
                            <tr>
                                <th class="table-subheading text-center">ALLOWANCE DESCRIPTION</th>
                                <th class="table-subheading text-center">COMPUTATION</th>
                                <th class="table-subheading text-center">TOTAL</th>
                            </tr>
                            <tr>
                                <td>Transportation</td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Meal</td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Lodging</td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Mobile Data</td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <th colspan="3" class="table-heading text-center align-middle">SPECIAL ALLOWANCES
                                    (SA)</th>
                            </tr>
                            <tr>
                                <td>Book</td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Thesis</td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Project</td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Uniform</td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Graduation Fee</td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Field Trips, Trainings, and Seminars</td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Summer Tuition and Allowance</td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr class="table-heading">
                                <td>On-the-Job or Practicum Allowance</td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td colspan="2" style="text-align: center;">TOTAL</td>
                                <td></td>
                            </tr>
                        </table>
                    </div>
                    <div class="reminder mb-3">
                        <div style="text-decoration: underline; font-weight: bold">Reminder:</div>
                        <div>1. Students who are on OJT must attach a photocopy of the endorsement
                            as well as the letter of acceptance from the company. </div>
                    </div>
                    <div class="table4">
                        <table class="table-bordered border-dark">
                            <tr>
                                <td>Requested and submitted by:</td>
                                <td>Noted by:</td>
                            </tr>
                            <tr>
                                <td class="pt-4 text-center h6">{{ $data->basicInfo->scFirstname }}
                                    {{ collect(explode(' ', $data->basicInfo->scMiddlename))->map(fn($word) => substr($word, 0, 1))->implode('') }}.
                                    {{ $data->basicInfo->scLastname }}</td>
                                <td class="pt-4 text-center h6">{{ $req->name ?? '' }}</td>
                            </tr>
                            <tr>
                                <td>Signature Over Printed Name</td>
                                <td>Signature Over Printed Name</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>

            <div class="page2">
                <div class="heading">
                    <p><strong>佛教慈濟慈善事業基金會菲律濱分會<br>
                            Buddhist Compassion Relief Tzu Chi Foundation, Philippines</strong></p>
                </div>
                <p class="sec-title" style="text-align: center;">DAILY TRAVEL ITINERARY FORM (FROM HOUSE TO SCHOOL AND
                    VICE
                    VERSA)</p>
                <div class="table5">
                    <table class="table-bordered border-dark">
                        <tr class="header">
                            <td colspan="2">Destination (Vice Versa)</td>
                            <td rowspan="2">Estimated Travel Time</td>
                            <td rowspan="2">Type of Vehicle</td>
                            <td rowspan="2">Student Fare Rate</td>
                        </tr>
                        <tr class="header">
                            <td style="width: 150px;">From</td>
                            <td style="width: 150px;">To</td>
                        </tr>

                        {{-- Loop through each travel location --}}
                        @forelse ($req->travelItinerary->travelLocations as $location)
                            <tr>
                                <td class="text-left align-top">{{ $location->travel_from ?? '--' }}</td>
                                <td class="text-left align-top">{{ $location->travel_to ?? '--' }}</td>
                                <td class="text-center align-middle">{{ $location->estimated_time ?? '--' }}</td>
                                <td class="text-center align-middle">{{ $location->vehicle_type ?? '--' }}</td>
                                <td class="text-center align-middle">{{ $location->fare_rate ?? '--' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" style="text-align: center;">No travel itinerary data available</td>
                            </tr>
                        @endforelse

                        {{-- Calculate the total fare rate --}}
                        <tr>
                            <td colspan="4" class="text-center align-middle">Total Costs per day</td>
                            <td class="text-center align-middle">
                                @php
                                    $totalCost = $req->travelItinerary->travelLocations->sum('fare_rate') * 2;
                                @endphp
                                {{ $totalCost > 0 ? number_format($totalCost, 2) : '--' }}
                            </td>
                        </tr>
                    </table>
                </div>

                <p class="sec-title" style="text-align: center;">LODGING INFORMATION</p>
                <div class="table6">
                    <table class="table-bordered border-dark">
                        <tr>
                            <td style="width: 200px">Name of owner/landlady/landlord</td>
                            <td class="text-left align-middle">{{ $req->lodgingInfo->name_owner ?? 'Not Applicable' }}
                            </td>
                        </tr>
                        <tr>
                            <td>Contact number</td>
                            <td class="text-left align-middle">
                                {{ $req->lodgingInfo->contact_no_owner ?? 'Not Applicable' }}</td>
                        </tr>
                        <tr>
                            <td>Monthly rent</td>
                            <td class="text-left align-middle">
                                {{ $req->lodgingInfo->monthly_rent ?? 'Not Applicable' }}</td>
                        </tr>
                        <tr>
                            <td>Type of lodging</td>
                            <td class="text-left align-middle">{{ $req->lodgingInfo->lodging_type }}</td>
                        </tr>
                    </table>
                </div>


                <p class="sec-title" style="text-align: center;">OJT DAILY TRAVEL ITINERARY FORM (FROM HOUSE TO
                    ASSIGNED
                    COMPANY AND VICE VERSA)</p>
                <div class="table7">
                    <table class="table-bordered border-dark">
                        <tr class="header">
                            <td colspan="2">Destination (Vice Versa)</td>
                            <td rowspan="2">Estimated Travel Time</td>
                            <td rowspan="2">Type of Vehicle</td>
                            <td rowspan="2">Student Fare Rate</td>
                        </tr>
                        <tr class="header">
                            <td style="width: 150px;">From</td>
                            <td style="width: 150px;">To</td>
                        </tr>

                        {{-- Check if ojtTravelItinerary exists and has ojtLocations --}}
                        @if ($req->ojtTravelItinerary && $req->ojtTravelItinerary->ojtLocations->isNotEmpty())
                            @foreach ($req->ojtTravelItinerary->ojtLocations as $location)
                                <tr>
                                    <td class="text-left align-top">{{ $location->ojt_from ?? '--' }}</td>
                                    <td class="text-left align-top">{{ $location->ojt_to ?? '--' }}</td>
                                    <td class="text-center align-middle">{{ $location->ojt_estimated_time ?? '--' }}
                                    </td>
                                    <td class="text-center align-middle">{{ $location->ojt_vehicle_type ?? '--' }}
                                    </td>
                                    <td class="text-center align-middle">{{ $location->ojt_fare_rate ?? '--' }}</td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="5" class="text-center align-middle">No travel itinerary data available
                                </td>
                            </tr>
                        @endif

                        {{-- Calculate and display the total fare rate if ojtLocations exist --}}
                        <tr>
                            <td colspan="4" class="text-center align-middle">Total Costs per day</td>
                            <td class="text-center align-middle">
                                @php
                                    $totalCost = $req->ojtTravelItinerary
                                        ? $req->ojtTravelItinerary->ojtLocations->sum('ojt_fare_rate')
                                        : 0;
                                @endphp
                                {{ $totalCost > 0 ? number_format($totalCost, 2) : '--' }}
                            </td>
                        </tr>
                    </table>
                </div>


                <p><em>I HEREBY CERTIFY that the information provided in this form is complete, true,
                        and correct to the best of my knowledge.</em></p>
                <div class="table-responsive">
                    <table class="table-sm border-0">
                        <tr>
                            <td width="30%" class="text-left align-middle">Submitted by:</td>
                            <td width="40%" class="text-left align-middle"></td>
                            <td width="30%" class="text-left align-middle">Noted by:</td>
                        </tr>
                        <tr>
                            <td width="30%" class="pt-4 text-center h6">{{ $data->basicInfo->scFirstname }}
                                {{ collect(explode(' ', $data->basicInfo->scMiddlename))->map(fn($word) => substr($word, 0, 1))->implode('') }}.
                                {{ $data->basicInfo->scLastname }}</td>
                            <td width="40%" class="text-left align-middle"></td>
                            <td width="30%" class="pt-4 text-center h6">{{ $req->name ?? '' }}</td>
                        </tr>
                        <tr>
                            <td width="30%" class="text-left align-middle border-top border-dark">Signature over
                                printed name of the
                                scholar</td>
                            <td width="40%" class="text-left align-middle"></td>
                            <td width="30%" class="text-left align-middle border-top border-dark">Signature over
                                printed name of assigned
                                Tzu Chi staff</td>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="container p-4 border border-dark mx-auto shadow mt-4" style="width: 8.5in">
                <!-- Registration Form Row -->
                <div class="row mb-3 align-items-center justify-content-center">
                    <div class="col-md-6">
                        <strong>Registration Form :</strong>
                    </div>
                    <div class="col-md-6 text-center fw-bold h6">
                        <a href="{{ url('storage/' . $req->classReference->registration_form) }}" target="_blank"
                            class="link-success">View File</a>
                    </div>
                </div>

                <!-- Photocopy of Endorsement Row -->
                <div class="row mb-3 align-items-center justify-content-center">
                    <div class="col-md-6">
                        <strong>Photocopy of Endorsement :</strong>
                    </div>
                    <div class="col-md-6 text-center fw-bold h6">
                        @if ($req->ojtTravelItinerary && $req->ojtTravelItinerary->endorsement)
                            <a href="{{ url('storage/' . $req->ojtTravelItinerary->endorsement) }}" target="_blank"
                                class="link-success">View File</a>
                        @else
                            <span class="text-danger">No file available</span>
                        @endif
                    </div>
                </div>

                <!-- Letter of Acceptance Row -->
                <div class="row mb-3 align-items-center justify-content-center">
                    <div class="col-md-6">
                        <strong>Letter of Acceptance :</strong>
                    </div>
                    <div class="col-md-6 text-center fw-bold h6">
                        @if ($req->ojtTravelItinerary && $req->ojtTravelItinerary->acceptance)
                            <a href="{{ url('storage/' . $req->ojtTravelItinerary->acceptance) }}" target="_blank"
                                class="link-success">View File</a>
                        @else
                            <span class="text-danger">No file available</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="{{ asset('js/headercontrol.js') }}"></script>
</body>

</html>
