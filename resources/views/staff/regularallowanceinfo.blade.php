<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Request</title>
    <link href="https://cdn.lineicons.com/4.0/lineicons.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
        integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ asset('css/special.css') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body>
    <!-- PAGE HEADER -->
    @include('partials._pageheader')
    <x-alert />

    <div class="ctnmain">
        <div class="container mt-5">
            <div class="col-md-1 mb-2" style="margin-left: auto;">
                <a href="{{ route('allowancerequests-special') }}" class="btn btn-success w-100">Go back</a>
            </div>
            <div class="row" id="confirmmsg">
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert" id="success-alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert" id="error-alert">
                        {!! session('error') !!}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
            </div>
            <div class="card p-3 bg-light">
                <div class="border bg-success text-white p-3">
                    <h4 class="mb-0"><strong>REGULAR ALLOWANCES REQUEST</strong></h4>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('update-requests-regular.post', ['id' => $id]) }}">>
                        @csrf
                        <div class="mb-3 row">
                            <label class="col-sm-4 col-form-label"><strong>Update Request Status</strong></label>
                            <div class="col-sm-8">
                                <select name="status" class="form-select">
                                    <option value="Pending" {{ $requests->status == 'Pending' ? 'selected' : '' }}>
                                        Pending</option>
                                    <option value="Accepted" {{ $requests->status == 'Accepted' ? 'selected' : '' }}>
                                        Accepted</option>
                                    <option value="Rejected" {{ $requests->status == 'Rejected' ? 'selected' : '' }}>
                                        Rejected</option>
                                    <option value="Completed" {{ $requests->status == 'Completed' ? 'selected' : '' }}>
                                        Completed</option>
                                </select>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label class="col-sm-4 col-form-label"><strong>Set Release Date</strong></label>
                            <div class="col-sm-8">
                                <input type="date" name="releasedate" class="form-control"
                                    value="{{ $requests->date_of_release ? $requests->date_of_release : '' }}"
                                    min="{{ date('Y-m-d', strtotime('+1 day')) }}">
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <div class="col-md-10"></div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-success w-100">Save Update</button>
                            </div>
                        </div>
                    </form>
                    <div class="row mb-3">
                        <label class="col-sm-4 col-form-label">Date of Request</label>
                        <div class="col-sm-8">
                            <p class="form-control-plaintext">: {{ $requests->created_at->format('F d, Y') }}</p>
                        </div>
                    </div>
                    {{-- SCHOLAR INFO --}}
                    <div class="row mb-3">
                        <label class="col-sm-4 col-form-label">Area</label>
                        <div class="col-sm-8">
                            <p class="form-control-plaintext">: {{ $requests->area }}</p>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-4 col-form-label">Name</label>
                        <div class="col-sm-8">
                            <p class="form-control-plaintext">: {{ $requests->scLastname }},
                                {{ $requests->scFirstname }}, {{ $requests->scMiddlename }}</p>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-4 col-form-label">School</label>
                        <div class="col-sm-8">
                            <p class="form-control-plaintext">: {{ $requests->scSchoolName }}</p>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-4 col-form-label">Year Level</label>
                        <div class="col-sm-8">
                            <p class="form-control-plaintext">: {{ $requests->scYearGrade }}</p>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-4 col-form-label">Course</label>
                        <div class="col-sm-8">
                            <p class="form-control-plaintext">: {{ $requests->scCourseStrandSec }}</p>
                        </div>
                    </div>
                    {{-- REQUEST INFO --}}
                    <div class="row mb-3">
                        <label class="col-sm-4 col-form-label">Class Schedule</label>
                        <div class="col-sm-8">
                            <a href="{{ url('storage/' . $regularAllowance->classReference->registration_form) }}"
                                target="_blank">View File</a>
                        </div>
                    </div>
                    <div class="row mb-3">
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
                    <label class="col-sm-4 col-form-label">Travel Itinerary</label>
                    <div class="row mb-3">
                        <table>
                            <thead>
                                <tr>
                                    <th>From</th>
                                    <th>To</th>
                                    <th>Estimated Travel Time</th>
                                    <th>Type of Vehicle</th>
                                    <th>Student Fare Rate</th>
                                    <th>Total Costs per day</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($regularAllowance->travelItinerary->travelLocations as $location)
                                    <tr>
                                        <td>{{ $location->travel_from ?? '--' }}</td>
                                        <td>{{ $location->travel_to ?? '--' }}</td>
                                        <td>{{ $location->estimated_time ?? '--' }}</td>
                                        <td>{{ $location->vehicle_type ?? '--' }}</td>
                                        <td>{{ $location->fare_rate ?? '--' }}</td>
                                        <td>
                                            @php
                                                $totalCost = $regularAllowance->travelItinerary->travelLocations->sum(
                                                    'fare_rate',
                                                );
                                            @endphp
                                            {{ $totalCost > 0 ? number_format($totalCost, 2) : '--' }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" style="text-align: center;">No travel itinerary data
                                            available</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <label class="col-sm-4 col-form-label">Lodging Info</label>
                    <div class="row mb-3">
                        <label class="col-sm-4 col-form-label">Owner Name</label>
                        <div class="col-sm-8">
                            <p class="form-control-plaintext">:
                                {{ $regularAllowance->lodgingInfo->name_owner ?? '--' }}</p>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-4 col-form-label">Contact number</label>
                        <div class="col-sm-8">
                            <p class="form-control-plaintext">:
                                {{ $regularAllowance->lodgingInfo->contact_no_owner ?? '--' }}</p>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-4 col-form-label">Monthly rent</label>
                        <div class="col-sm-8">
                            <p class="form-control-plaintext">:
                                {{ $regularAllowance->lodgingInfo->monthly_rent ?? '--' }}</p>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-4 col-form-label">Type of lodging</label>
                        <div class="col-sm-8">
                            <p class="form-control-plaintext">:
                                {{ $regularAllowance->lodgingInfo->lodging_type ?? '--' }}</p>
                        </div>
                    </div>
                    <label class="col-sm-4 col-form-label">OJT Travel Itinerary Info</label>
                    <div class="row mb-3">
                        <label class="col-sm-4 col-form-label">Photocopy of Endorsement</label>
                        <div class="col-sm-8">
                            @if ($regularAllowance->ojtTravelItinerary && $regularAllowance->ojtTravelItinerary->endorsement)
                                <a href="{{ url('storage/' . $regularAllowance->ojtTravelItinerary->endorsement) }}"
                                    target="_blank">View File</a>
                            @else
                                <span>No file available</span>
                            @endif
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-4 col-form-label"> Letter of Acceptance</label>
                        <div class="col-sm-8">
                            @if ($regularAllowance->ojtTravelItinerary && $regularAllowance->ojtTravelItinerary->acceptance)
                                <a href="{{ url('storage/' . $regularAllowance->ojtTravelItinerary->acceptance) }}"
                                    target="_blank">View File</a>
                            @else
                                <span>No file available</span>
                            @endif
                        </div>
                    </div>
                    <div class="row mb-3">
                        <table>
                            <thead>
                                <tr>
                                    <th>From</th>
                                    <th>To</th>
                                    <th>Estimated Travel Time</th>
                                    <th>Type of Vehicle</th>
                                    <th>Student Fare Rate</th>
                                    <th>Total Costs per day</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($regularAllowance->travelItinerary->travelLocations as $location)
                                    <tr>
                                        <td>{{ $location->ojt_from ?? '--' }}</td>
                                        <td>{{ $location->ojt_to ?? '--' }}</td>
                                        <td>{{ $location->ojt_estimated_time ?? '--' }}</td>
                                        <td>{{ $location->ojt_vehicle_type ?? '--' }}</td>
                                        <td>{{ $location->ojt_fare_rate ?? '--' }}</td>
                                        <td>
                                            @php
                                                $totalCost = $regularAllowance->ojtTravelItinerary
                                                    ? $regularAllowance->ojtTravelItinerary->ojtLocations->sum(
                                                        'ojt_fare_rate',
                                                    )
                                                    : 0;
                                            @endphp
                                            {{ $totalCost > 0 ? number_format($totalCost, 2) : '--' }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" style="text-align: center;">No travel itinerary data
                                            available</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="{{ asset('js/headercontrol.js') }}"></script>
</body>

</html>
