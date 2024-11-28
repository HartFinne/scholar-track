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
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body>
    <!-- Include Sidebar -->
    @include('partials._pageheader')
    <x-alert />

    <div class="ctnmain">
        <div class="container-fluid col-md-8 mx-auto border-none">
            <div class="row">
                <form class="col-md-10" method="post"
                    action="{{ route('updateappointmentstatus', $appointment->id) }}">
                    @csrf
                    <div class="row">
                        <div class="col-md-9">
                            <select class="form-select" name="status"
                                {{ $appointment->status == 'Cancelled' ? 'disabled' : '' }}>
                                <option value="Pending" {{ $appointment->status == 'Pending' ? 'selected' : '' }}>
                                    Pending
                                </option>
                                <option value="Accepted" {{ $appointment->status == 'Accepted' ? 'selected' : '' }}>
                                    Accepted
                                </option>
                                <option value="Rejected" {{ $appointment->status == 'Rejected' ? 'selected' : '' }}>
                                    Rejected
                                </option>
                                <option value="Completed" {{ $appointment->status == 'Completed' ? 'selected' : '' }}>
                                    Completed
                                </option>
                                <option value="Cancelled" {{ $appointment->status == 'Cancelled' ? 'selected' : '' }}>
                                    Cancelled
                                </option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            @if ($appointment->status != 'Cancelled')
                                <button type="submit" class="btn btn-warning">Update</button>
                            @endif
                        </div>
                    </div>
                </form>
                <div class="col-md-2">
                    <a href="{{ route('appointments') }}" class="btn btn-success w-100">Go back</a>
                </div>
            </div>
        </div>

        <div class="card col-md-8 mx-auto mt-4 shadow">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0">Scheduled Appointment</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p class="mb-2"><strong>Name:</strong> {{ $appointment->basicInfo->scLastname }},
                            {{ $appointment->basicInfo->scFirstname }}
                            {{ $appointment->basicInfo->scMiddlename }}</p>
                        <p class="mb-2"><strong>School:</strong> {{ $appointment->education->scSchoolName }}</p>
                        <p class="mb-2"><strong>Year Level:</strong> {{ $appointment->education->scYearGrade }}</p>
                        <p class="mb-2"><strong>Course/Strand:</strong>
                            {{ $appointment->education->scCourseStrandSec }}</p>
                    </div>
                    <div class="col-md-6">
                        <p class="mb-2"><strong>Agenda:</strong> {{ $appointment->reason }}</p>
                        <p class="mb-2"><strong>Date:</strong>
                            {{ \Carbon\Carbon::parse($appointment->date)->format('F d, Y') }}</p>
                        <p class="mb-2"><strong>Time:</strong> {{ $appointment->time }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="{{ asset('js/headercontrol.js') }}"></script>
</body>

</html>
