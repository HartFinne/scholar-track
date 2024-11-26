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
    <link rel="stylesheet" href="{{ asset('css/special.css') }}">
    <link rel="stylesheet" href="{{ asset('css/partial.css') }}">
</head>

<body>
    <!-- Include Sidebar -->
    @include('partials._sidebar')

    <!-- Include Navbar -->
    @include('partials._navbar')
    <x-alert />

    <div class="ctn-main">
        <a href="{{ route('scspecial') }}" class="goback">&lt Go back</a>

        <div class="request-view">
            <div class="request-status">
                @php
                    $statusClasses = [
                        'Pending' => 'bg-warning',
                        'Accepted' => 'bg-success',
                        'Completed' => 'bg-success',
                        'Rejected' => 'bg-danger',
                    ];

                    $statusClass = $statusClasses[$request->status] ?? 'bg-secondary text-white';
                @endphp

                <h6 class="request-stat {{ $statusClass }} text-white">{{ $request->status }}</h6>
            </div>

            <div class="request-info">
                <h5>FIELD TRIP, TRAINING, SEMINAR ALLOWANCE REQUEST</h5>
                <div class="info">
                    <div class="label">Date of Request:</div>
                    <div class="value">
                        <span>{{ \Carbon\Carbon::parse($request->created_at)->format('F d, Y') }}</span>
                    </div>

                    <div class="label">Date of Release:</div>
                    <div class="value">
                        <span>{{ $request->releasedate ? \Carbon\Carbon::parse($request->releasedate)->format('F d, Y') : '--' }}</span>
                    </div>

                    <div class="label">Area:</div>
                    <div class="value"><span>{{ $scholar->scholarshipinfo->area }}</span></div>

                    <div class="label">Name:</div>
                    <div class="value"><span>{{ $scholar->basicInfo->scLastname }},
                            {{ $scholar->basicInfo->scFirstname }}, {{ $scholar->basicInfo->scMiddlename }}</span>
                    </div>

                    <div class="label">School:</div>
                    <div class="value"><span>{{ $scholar->education->scSchoolName }}</span></div>

                    <div class="label">Year Level:</div>
                    <div class="value"><span>{{ $scholar->education->scYearGrade }}</span></div>

                    <div class="label">Course:</div>
                    <div class="value"><span>{{ $scholar->education->scCourseStrandSec }}</span></div>

                    <div class="label">Type of Event:</div>
                    <div class="value"><span>{{ $request->eventtype }}</span></div>

                    <div class="label">Event Location:</div>
                    <div class="value"><span>{{ $request->eventloc }}</span></div>

                    <div class="label">Total Price:</div>
                    <div class="value"><span>{{ $request->totalprice }}</span></div>

                    <div class="label">Official Memo from Subject Professor:</div>
                    <div class="value">
                        <span>
                            <a href="{{ asset('storage/' . $request->memo) }}" target="_blank"
                                rel="noopener noreferrer" class="link-success" role="button">Click here to
                                view</a>
                        </span>
                    </div>

                    <div class="label">Parent or Guardian Waiver:</div>
                    <div class="value">
                        <span>
                            <a href="{{ asset('storage/' . $request->waiver) }}" target="_blank"
                                rel="noopener noreferrer" class="link-success" role="button">Click here to
                                view</a>
                        </span>
                    </div>

                    <div class="label">Receipt or Acknowledgement Receipt:</div>
                    <div class="value">
                        <span>
                            <a href="{{ asset('storage/' . $request->acknowledgement) }}" target="_blank"
                                rel="noopener noreferrer" class="link-success" role="button">Click here to
                                view</a>
                        </span>
                    </div>

                    <div class="label">Liquidation Form:</div>
                    <div class="value">
                        <span>
                            <a href="{{ asset('storage/' . $request->liquidation) }}" target="_blank"
                                rel="noopener noreferrer" class="link-success" role="button">Click here to
                                view</a>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="{{ asset('js/scholar.js') }}"></script>
</body>

</html>
