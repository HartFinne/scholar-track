<!DOCTYPE html>
<html lang="en">

<head>
    <title>View Request</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="{{ asset('css/regularform.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/partial.css') }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body>
    <!-- Include Sidebar -->
    @include('partials._sidebar')

    <!-- Include Navbar -->
    @include('partials._navbar')
    <x-alert />

    <!-- MAIN -->
    <div class="ctn-main">
        <a href="" class="goback">&lt Go back</a>

        <div class="regular-form">
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
                        <p>{{ $regularAllowance->grades->SemesterQuarter ?? '[Semester]' }}<br>
                            {{ $regularAllowance->education->scYearGrade ?? '[School Year]' }}</p>
                    </div>
                </div>

                <div class="table1">
                    <table>
                        <tr>
                            <td class="gray">Name</td>
                            <td colspan="2">{{ $data->basicInfo->scFirstname }} {{ $data->basicInfo->scLastname }}
                            </td>
                            <td class="gray">Date Submitted</td>
                            <td colspan="2">{{ $regularAllowance->created_at->format('m/d/Y') }}</td>
                        </tr>
                        <tr>
                            <td class="gray">School</td>
                            <td colspan="4">{{ $data->education->scSchoolName }}</td>
                        </tr>
                        <tr>
                            <td class="gray">School Category</td>
                            <td colspan="2">{{ $data->education->scSchoolLevel }}</td>
                            <td class="gray">Contact No.</td>
                            <td colspan="2">{{ $data->scPhoneNum }}</td>
                        </tr>
                        <tr>
                            <td class="gray">Year Level</td>
                            <td colspan="2">{{ $data->education->scYearGrade }}</td>
                            <td class="gray">Course</td>
                            <td colspan="2">{{ $data->education->scCourseStrandSec }}</td>
                        </tr>
                        @php
                            use Carbon\Carbon;
                        @endphp

                        <tr>
                            <td class="gray">Start of Semester</td>
                            <td colspan="2">
                                {{ Carbon::parse($regularAllowance->start_of_semester)->format('m/d/Y') }}</td>
                        </tr>
                        <tr>
                            <td class="gray">End of Semester</td>
                            <td colspan="2">{{ Carbon::parse($regularAllowance->end_of_semester)->format('m/d/Y') }}
                            </td>
                        </tr>
                    </table>
                </div>

                <div class="section">
                    <p class="sec-title">CLASS SCHEDULE (REFERENCE ONLY)</p>
                    <p>* Attached the photocopy of the official class schedule from the Registrar (Registration
                        Form)<br>
                        * Attached duly accomplished daily travel itinerary.</p>
                    <div class="table2">
                        <table>
                            <thead>
                                <tr>
                                    <th>TIME</th>
                                    <th>MON</th>
                                    <th>TUE</th>
                                    <th>WED</th>
                                    <th>THU</th>
                                    <th>FRI</th>
                                    <th>SAT</th>
                                    <th>SUN</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($regularAllowance->classReference && $regularAllowance->classReference->classSchedules->isNotEmpty())
                                    @foreach ($regularAllowance->classReference->classSchedules as $schedule)
                                        <tr>
                                            <td>{{ $schedule->time_slot }}</td>
                                            <td>{{ $schedule->mon ?? '--' }}</td>
                                            <td>{{ $schedule->tue ?? '--' }}</td>
                                            <td>{{ $schedule->wed ?? '--' }}</td>
                                            <td>{{ $schedule->thu ?? '--' }}</td>
                                            <td>{{ $schedule->fri ?? '--' }}</td>
                                            <td>{{ $schedule->sat ?? '--' }}</td>
                                            <td>{{ $schedule->sun ?? '--' }}</td>
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
                <hr>
                <div class="section">
                    <p class="sec-title">TO BE FILLED BY TC-CHARITY STAFF</p>
                    <p><em>*Important note: The number of school days per month is 20 regardless
                            of the scholars' class schedule for the computation of transportation and food allowances.
                        </em>
                    </p>
                    <div class="table3">
                        <table>
                            <tr>
                                <th colspan="3" class="table-heading">MONTHLY LIVING ALLOWANCES (MLA)</th>
                            </tr>
                            <tr>
                                <th class="table-subheading">ALLOWANCE DESCRIPTION</th>
                                <th class="table-subheading">COMPUTATION</th>
                                <th class="table-subheading">TOTAL</th>
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
                                <th colspan="3" class="table-heading">SPECIAL ALLOWANCES (SA)</th>
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
                            <tr>
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
                    <div class="reminder">
                        <p style="text-decoration: underline;">Reminder:</p>
                        <p>1. Students who are on OJT must attach a photocopy of the endorsement
                            as well as the letter of acceptance from the company. </p>
                    </div>
                    <div class="table4">
                        <table>
                            <tr>
                                <td>Requested and submitted by:</td>
                                <td>Noted by:</td>
                            </tr>
                            <tr>
                                <td>{{ $data->basicInfo->scFirstname }} {{ $data->basicInfo->scLastname }}</td>
                                <td>[Staff Name]</td>
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
                    <table>
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
                        @forelse ($regularAllowance->travelItinerary->travelLocations as $location)
                            <tr>
                                <td>{{ $location->travel_from ?? '--' }}</td>
                                <td>{{ $location->travel_to ?? '--' }}</td>
                                <td>{{ $location->estimated_time ?? '--' }}</td>
                                <td>{{ $location->vehicle_type ?? '--' }}</td>
                                <td>{{ $location->fare_rate ?? '--' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" style="text-align: center;">No travel itinerary data available</td>
                            </tr>
                        @endforelse

                        {{-- Calculate the total fare rate --}}
                        <tr>
                            <td colspan="4" style="text-align: center;">Total Costs per day</td>
                            <td>
                                @php
                                    $totalCost = $regularAllowance->travelItinerary->travelLocations->sum('fare_rate');
                                @endphp
                                {{ $totalCost > 0 ? number_format($totalCost, 2) : '--' }}
                            </td>
                        </tr>
                    </table>
                </div>



                <p class="sec-title" style="text-align: center;">LODGING INFORMATION</p>
                <div class="table6">
                    <table>
                        <tr>
                            <td style="width: 200px;">Name of owner/landlady/landlord</td>
                            <td>{{ $regularAllowance->lodgingInfo->name_owner ?? '--' }}</td>
                        </tr>
                        <tr>
                            <td>Contact number</td>
                            <td>{{ $regularAllowance->lodgingInfo->contact_no_owner ?? '--' }}</td>
                        </tr>
                        <tr>
                            <td>Monthly rent</td>
                            <td>{{ $regularAllowance->lodgingInfo->monthly_rent ?? '--' }}</td>
                        </tr>
                        <tr>
                            <td>Type of lodging</td>
                            <td>{{ $regularAllowance->lodgingInfo->lodging_type ?? '--' }}</td>
                        </tr>
                    </table>
                </div>


                <p class="sec-title" style="text-align: center;">OJT DAILY TRAVEL ITINERARY FORM (FROM HOUSE TO ASSIGNED
                    COMPANY AND VICE VERSA)</p>
                <div class="table7">
                    <table>
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
                        @if ($regularAllowance->ojtTravelItinerary && $regularAllowance->ojtTravelItinerary->ojtLocations->isNotEmpty())
                            @foreach ($regularAllowance->ojtTravelItinerary->ojtLocations as $location)
                                <tr>
                                    <td>{{ $location->ojt_from ?? '--' }}</td>
                                    <td>{{ $location->ojt_to ?? '--' }}</td>
                                    <td>{{ $location->ojt_estimated_time ?? '--' }}</td>
                                    <td>{{ $location->ojt_vehicle_type ?? '--' }}</td>
                                    <td>{{ $location->ojt_fare_rate ?? '--' }}</td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="5" style="text-align: center;">No travel itinerary data available</td>
                            </tr>
                        @endif

                        {{-- Calculate and display the total fare rate if ojtLocations exist --}}
                        <tr>
                            <td colspan="4" style="text-align: center;">Total Costs per day</td>
                            <td>
                                @php
                                    $totalCost = $regularAllowance->ojtTravelItinerary
                                        ? $regularAllowance->ojtTravelItinerary->ojtLocations->sum('ojt_fare_rate')
                                        : 0;
                                @endphp
                                {{ $totalCost > 0 ? number_format($totalCost, 2) : '--' }}
                            </td>
                        </tr>
                    </table>
                </div>


                <p><em>I HEREBY CERTIFY that the information provided in this form is complete, true,
                        and correct to the best of my knowledge.</em></p>
                <div class="signature">
                    <div class="group1">
                        <p>Submitted by:</p>
                        <div class="sign">
                            <p class="name">{{ $data->basicInfo->scFirstname }} {{ $data->basicInfo->scLastname }}
                            </p>
                            <p>Scholar</p>
                        </div>
                    </div>
                    <div class="group2">
                        <p>Noted by:</p>
                        <div class="sign">
                            <p class="name">[Staff Name]</p>
                            <p>Assigned Tzu Chi staff</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="file-info">
                <div class="info">
                    <div class="label">Registration Form</div>
                    <div class="value">: <a
                            href="{{ url('storage/' . $regularAllowance->classReference->registration_form) }}"
                            target="_blank">View File</a></div>

                    <div class="label">Photocopy of Endorsement</div>
                    <div class="value">:
                        @if ($regularAllowance->ojtTravelItinerary && $regularAllowance->ojtTravelItinerary->endorsement)
                            <a href="{{ url('storage/' . $regularAllowance->ojtTravelItinerary->endorsement) }}"
                                target="_blank">View File</a>
                        @else
                            <span>No file available</span>
                        @endif
                    </div>

                    <div class="label">Letter of Acceptance</div>
                    <div class="value">:
                        @if ($regularAllowance->ojtTravelItinerary && $regularAllowance->ojtTravelItinerary->acceptance)
                            <a href="{{ url('storage/' . $regularAllowance->ojtTravelItinerary->acceptance) }}"
                                target="_blank">View File</a>
                        @else
                            <span>No file available</span>
                        @endif
                    </div>

                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/scholar.js') }}"></script>
</body>

</html>
