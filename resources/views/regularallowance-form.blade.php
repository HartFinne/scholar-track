<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Allowance Request - Regular</title>
    <style>
        * {
            font-family: Calibri, sans-serif;
            padding: 0;
            margin: 0;
        }

        body {
            width: 100%;
            padding: 80px 25px 25px 25px;
            max-width: 770px;
            page-break-after: always;
        }

        #header {
            position: fixed;
            width: 100%;
            top: 25px;
            left: 0;
            right: 0;
        }

        #pageheader {
            max-width: 100%;
        }

        .bg-light {
            background: #e4e4e4;
        }

        table {
            margin: 15px 0;
            border-collapse: collapse;
            width: 100%;
            word-wrap: break-word;
        }

        .tbl tr td {
            border: 1px solid #000;
            padding: 2px;
        }

        .tbl tr th {
            font-weight: bold;
            background: #D9D9D9;
            border: 1px solid #000;
            padding: 2px;
            vertical-align: top;
        }

        .divider {
            border: 2px dashed #000;
            margin-bottom: 15px;
        }
    </style>
</head>

<body>
    @php
        use Carbon\Carbon;
    @endphp
    <header id="header">
        <img src="{{ public_path('images/requestform-header.png') }}" alt="page_header" id="pageheader">
    </header>
    <main class="content">
        <p style="text-align: center; margin: 20px 0;"><strong>Educational Assistance Program</strong></p>
        <p style="text-align: center; margin: 10px 0;"><strong>ALLOWANCE REQUEST FORM</strong></p>
        <p style="text-align: center">{{ $data->education->scCollegedept ?? 'Failed to load' }}</p>
        <p style="text-align: center">{{ $req->semester ?? 'Failed to load' }} | SY:
            {{ $req->schoolyear ?? 'Failed to load' }}</p>

        <table class="tbl">
            <tr>
                <td class="bg-light">Name</td>
                <td>{{ $data->basicInfo->scFirstname }} {{ $data->basicInfo->scLastname }}</td>
                <td class="bg-light">Date Submitted</td>
                <td>{{ Carbon::parse($req->created_at)->format('F j, Y') }}</td>
            </tr>
            <tr>
                <td class="bg-light">School</td>
                <td colspan="3">{{ $data->education->scSchoolName }}</td>
            </tr>
            <tr>
                <td class="bg-light">School Category</td>
                <td>{{ $data->education->scSchoolLevel }}</td>
                <td class="bg-light">Contact No.</td>
                <td>{{ $data->scPhoneNum }}</td>
            </tr>
            <tr>
                <td class="bg-light">Year Level</td>
                <td>{{ $data->education->scYearGrade }}</td>
                <td class="bg-light">Course</td>
                <td>{{ $data->education->scCourseStrandSec }}</td>
            </tr>
            <tr>
                <td class="bg-light">Home Address</td>
                <td>{{ $data->addressinfo->scResidential }}</td>
                <td class="bg-light">Boarding House Address</td>
                <td>{{ $data->lodgingInfo->address ?? 'Not Applicable' }}</td>
            </tr>
            <tr>
                <td class="bg-light">Start of Semester</td>
                <td>{{ Carbon::parse($req->start_of_semester)->format('F j, Y') }}</td>
                <td class="bg-light">End of Semester</td>
                <td>{{ Carbon::parse($req->end_of_semester)->format('F j, Y') }}</td>
            </tr>
            <tr>
                <td class="bg-light">Start of OJT</td>
                <td>{{ optional($req->ojtTravelItinerary)->start_of_ojt ? Carbon::parse($req->ojtTravelItinerary->start_of_ojt)->format('F j, Y') : 'Not Applicable' }}
                </td>
                <td class="bg-light">End of OJT</td>
                <td>{{ optional($req->ojtTravelItinerary)->end_of_ojt ? Carbon::parse($req->ojtTravelItinerary->end_of_ojt)->format('F j, Y') : 'Not Applicable' }}
                </td>
            </tr>
        </table>

        <p><strong>CLASS SCHEDULE (REFERENCE ONLY)</strong></p>
        <p>* Attached the photocopy of the official class schedule from the Registrar (Registration Form)</p>
        <p>* Attached duly accomplished daily travel itinerary.</p>

        <table class="tbl" style="text-align: center">
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
                @if ($req->classReference && $req->classReference->classSchedules->isNotEmpty())
                    @foreach ($req->classReference->classSchedules as $schedule)
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
                        <td colspan="8">No class schedule available</td>
                    </tr>
                @endif
            </tbody>
        </table>

        <div class="divider"></div>

        <p><strong>TO BE FILLED BY TC-CHARITY STAFF</strong></p>
        <p style="margin: 5px 0;"><i>*Important note: The number of school days per month is 20 regardless of the
                scholars' class schedule
                for the computation of transportation and food allowances.</i></p>

        <table class="tbl">
            <tr>
                <th colspan="3">MONTHLY LIVING ALLOWANCES (MLA)</th>
            </tr>
            <tr>
                <th>ALLOWANCE DESCRIPTION</th>
                <th>COMPUTATION</th>
                <th>TOTAL</th>
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
                <th colspan="3">SPECIAL ALLOWANCES (SA)</th>
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
            <tr style="background: #D9D9D9">
                <td>On-the-Job or Practicum Allowance</td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td colspan="2" style="text-align: center;">TOTAL</td>
                <td></td>
            </tr>
        </table>

        <p><strong><u>Reminder:</u></strong></p>
        <p>1. Students who are on OJT must attach a photocopy of the endorsement as well as the letter of
            acceptance from the company. </p>

        <table class="tbl">
            <tr>
                <td>Requested and submitted by:</td>
                <td>Noted by:</td>
            </tr>
            <tr>
                <td style="padding-top: 30px; text-align: center; font-size: 18px; font-weight: bold">
                    {{ $data->basicInfo->scFirstname }}
                    {{ collect(explode(' ', $data->basicInfo->scMiddlename))->map(fn($word) => substr($word, 0, 1))->implode('') }}.
                    {{ $data->basicInfo->scLastname }}</td>
                <td style="padding-top: 30px; text-align: center; font-size: 18px; font-weight: bold">
                    {{ $req->name ?? '' }}</td>
            </tr>
            <tr>
                <td>Signature Over Printed Name</td>
                <td>Signature Over Printed Name</td>
            </tr>
        </table>

        <p style="text-align: center; font-weight: bold; margin: 20px 0;">DAILY TRAVEL ITINERARY FORM (FROM HOUSE TO
            SCHOOL
            AND VICE VERSA)
        </p>

        <table class="tbl">
            <tr>
                <td colspan="2" style="background: #e4e4e4; text-align: center;">Destination (Vice Versa)</td>
                <td rowspan="2" style="background: #e4e4e4; text-align: center;">Estimated Travel Time</td>
                <td rowspan="2" style="background: #e4e4e4; text-align: center;">Type of Vehicle</td>
                <td rowspan="2" style="background: #e4e4e4; text-align: center;">Student Fare Rate</td>
            </tr>
            <tr>
                <td style="background: #e4e4e4; text-align: center;">From</td>
                <td style="background: #e4e4e4; text-align: center;">To</td>
            </tr>

            @forelse ($req->travelItinerary->travelLocations as $location)
                <tr>
                    <td>{{ $location->travel_from ?? '--' }}</td>
                    <td>{{ $location->travel_to ?? '--' }}</td>
                    <td style="text-align: center;">{{ $location->estimated_time ?? '--' }}</td>
                    <td style="text-align: center;">{{ $location->vehicle_type ?? '--' }}</td>
                    <td style="text-align: center;">{{ $location->fare_rate ?? '--' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" style="text-align: center;">No travel itinerary data available</td>
                </tr>
            @endforelse

            <tr>
                <td colspan="4" style="text-align: center;">Total Costs per day</td>
                <td style="text-align: center;">
                    @php
                        $totalCost = $req->travelItinerary->travelLocations->sum('fare_rate') * 2;
                    @endphp
                    {{ $totalCost > 0 ? number_format($totalCost, 2) : '--' }}
                </td>
            </tr>
        </table>

        <p style="text-align: center; font-weight: bold; margin: 20px 0;">LODGING INFORMATION</p>

        <table class="tbl">
            <tr>
                <td width="40%" style="vertical-align: top">Name of owner/landlady/landlord</td>
                <td>{{ $req->lodgingInfo->name_owner ?? 'Not Applicable' }}</td>
            </tr>
            <tr>
                <td width="40%" style="vertical-align: top">Contact number</td>
                <td>{{ $req->lodgingInfo->contact_no_owner ?? 'Not Applicable' }}</td>
            </tr>
            <tr>
                <td width="40%" style="vertical-align: top">Monthly rent</td>
                <td>{{ $req->lodgingInfo->monthly_rent ?? 'Not Applicable' }}</td>
            </tr>
            <tr>
                <td width="40%" style="vertical-align: top">Type of lodging</td>
                <td>{{ $req->lodgingInfo->lodging_type }}</td>
            </tr>
        </table>

        <p style="text-align: center; font-weight: bold; margin: 20px 0;">OJT DAILY TRAVEL ITINERARY FORM (FROM HOUSE TO
            ASSIGNED COMPANY AND VICE VERSA)</p>

        <table class="tbl">
            <tr>
                <td colspan="2" style="background: #e4e4e4; text-align: center;">Destination (Vice Versa)</td>
                <td rowspan="2" style="background: #e4e4e4; text-align: center;">Estimated Travel Time</td>
                <td rowspan="2" style="background: #e4e4e4; text-align: center;">Type of Vehicle</td>
                <td rowspan="2" style="background: #e4e4e4; text-align: center;">Student Fare Rate</td>
            </tr>
            <tr>
                <td style="background: #e4e4e4; text-align: center;">From</td>
                <td style="background: #e4e4e4; text-align: center;">To</td>
            </tr>

            @if ($req->ojtTravelItinerary && $req->ojtTravelItinerary->ojtLocations->isNotEmpty())
                @foreach ($req->ojtTravelItinerary->ojtLocations as $location)
                    <tr>
                        <td>{{ $location->ojt_from ?? '--' }}</td>
                        <td>{{ $location->ojt_to ?? '--' }}</td>
                        <td style="text-align: center;">{{ $location->ojt_estimated_time ?? '--' }}</td>
                        <td style="text-align: center;">{{ $location->ojt_vehicle_type ?? '--' }}</td>
                        <td style="text-align: center;">{{ $location->ojt_fare_rate ?? '--' }}</td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="5" style="text-align: center;">No travel itinerary data available</td>
                </tr>
            @endif

            <tr>
                <td colspan="4" style="text-align: center;">Total Costs per day</td>
                <td style="text-align: center;">
                    @php
                        $totalCost = $req->ojtTravelItinerary
                            ? $req->ojtTravelItinerary->ojtLocations->sum('ojt_fare_rate')
                            : 0;
                    @endphp
                    {{ $totalCost > 0 ? number_format($totalCost, 2) : '--' }}
                </td>
            </tr>
        </table>

        <p><em>I HEREBY CERTIFY that the information provided in this form is complete, true, and correct to the
                best of my knowledge.</em></p>
        <div width="100%">
            <table>
                <tr>
                    <td width="30%">Submitted by:</td>
                    <td width="40%"></td>
                    <td width="30%">Noted by:</td>
                </tr>
                <tr>
                    <td width="30%"
                        style="padding-top: 30px; text-align: center; font-size: 18px; font-weight: bold; border-bottom: 1px solid #000">
                        {{ $data->basicInfo->scFirstname }}
                        {{ collect(explode(' ', $data->basicInfo->scMiddlename))->map(fn($word) => substr($word, 0, 1))->implode('') }}.
                        {{ $data->basicInfo->scLastname }}</td>
                    <td width="40%"></td>
                    <td width="30%"
                        style="padding-top: 30px; text-align: center; font-size: 18px; font-weight: bold; border-bottom: 1px solid #000">
                        {{ $req->name ?? '' }}</td>
                </tr>
                <tr>
                    <td width="30%">Signature over printed name of the scholar</td>
                    <td width="40%"></td>
                    <td width="30%">Signature over printed name of assigned Tzu Chi staff</td>
                </tr>
            </table>
        </div>
    </main>
</body>

</html>
