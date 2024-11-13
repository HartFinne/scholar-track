<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Appointment</title>
    <link href="https://cdn.lineicons.com/4.0/lineicons.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
        integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ asset('css/appointment.css') }}">
    <link rel="stylesheet" href="{{ asset('css/partial.css') }}">
</head>

<body>
    <!-- Include Sidebar -->
    @include('partials._sidebar')

    <!-- Include Navbar -->
    @include('partials._navbar')

    <div class="ctn-main">

        <div class="container mt-5">
            <div class="card border-0 mx-auto" style="max-width: 75%">
                <div class="row d-flex justify-content-between">
                    <div class="col-md-2 mb-2">
                        <a href="{{ route('appointment') }}" class="btn btn-success w-100">&lt Go back</a>
                    </div>
                    <div class="col-md-2">
                        <span class="h6 appointment-stat w-100">{{ $appointment->status }}</span>
                    </div>
                </div>
            </div>

            <div class="appointment-info mx-auto" style="max-width: 75%">
                <h5>SCHEDULED APPOINTMENT</h5>
                <div class="info">
                    <div class="label">Name:</div>
                    <div class="value"><span>{{ $user->basicInfo->scLastname }}, {{ $user->basicInfo->scFirstname }}
                            {{ $user->basicInfo->scMiddlename }}</span></div>

                    <div class="label">School:</div>
                    <div class="value"><span>{{ $user->education->scSchoolName }}</span></div>

                    <div class="label">Year Level:</div>
                    <div class="value"><span>{{ $user->education->scYearGrade }}</span></div>

                    <div class="label">Course/Strand:</div>
                    <div class="value"><span>{{ $user->education->scCourseStrandSec }}</span></div>

                    <div class="label">Agenda:</div>
                    <div class="value"><span>{{ $appointment->reason }}</span></div>

                    <div class="label">Date:</div>
                    <div class="value"><span>{{ \Carbon\Carbon::parse($appointment->date)->format('F d, Y') }}</span>
                    </div>
                    <div class="label">Time:</div>
                    <div class="value"><span>{{ $appointment->time }}</span></div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
