<!DOCTYPE html>

<html lang="en">

<head>
    <title>Humanities Class</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/table.css') }}">
    <link rel="stylesheet" href="{{ asset('css/hcdetails.css') }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body>
    <!-- PAGE HEADER -->
    @include('partials._pageheader')
    <x-alert />

    <div class="ctnmain">
        <span class="pagetitle">Humanities Class Attendees</span>
        <div class="groupA">
            <form action="#" class="searchbar">
                <input type="search" placeholder="Search" id="insearch" required>
                <button type="submit" id="btnsearch"><i class="fas fa-magnifying-glass"></i></button>
            </form>
            <div>
                @if ($event->status == 'On Going')
                    <a id="btnsave" href="{{ route('savehc', $event->hcid) }}">Mark as Done</a>
                    <a id="btngoback" href="{{ route('attendancesystem', $event->hcid) }}">Go back</a>
                @else
                    <a id="btngoback" href="{{ route('humanitiesclass') }}">Go back</a>
                @endif
            </div>
        </div>

        <div class="column">
            <div class="row">
                <span class="col-md-2">Topic</span>
                <span class="col-md-9">: <strong>{{ $event->topic }}</strong></span>
            </div>
            <div class="row">
                <span class="col-md-2">Date</span>
                <span class="col-md-9">: <strong>{{ $event->hcdate }}</strong></span>
            </div>
            <div class="row">
                <span class="col-md-2">Start Time</span>
                <span class="col-md-9">: <strong>{{ $event->hcstarttime }}</strong></span>
            </div>
            <div class="row">
                <span class="col-md-2">End Time</span>
                <span class="col-md-9">: <strong>{{ $event->hcendtime }}</strong></span>
            </div>
            <div class="row">
                <span class="col-md-2">Total Attendees</span>
                <span class="col-md-9">: <strong>{{ $event->totalattendees }}</strong></span>
            </div>
        </div>

        <div class="ctntable table-responsive">
            <table class="table table-bordered" id="tblpenalty">
                <thead>
                    <tr>
                        <th class="text-center align-middle">#</th>
                        <th class="text-center align-middle">Name</th>
                        <th class="text-center align-middle">Time In</th>
                        <th class="text-center align-middle">Time Out</th>
                        <th class="text-center align-middle">Tardiness Duration (Minutes)</th>
                        <th class="text-center align-middle">Status</th>
                        @if ($event->status == 'On Going')
                            <th class="text-center align-middle">Action</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @foreach ($attendees as $index => $attendee)
                        <tr>
                            <td class="text-center align-middle">{{ $index + 1 }}</td>
                            <td class="text-center align-middle">{{ $attendee->basicInfo->scLastname }},
                                {{ $attendee->basicInfo->scFirstname }} {{ $attendee->basicInfo->scMiddlename }}</td>
                            <td class="text-center align-middle">{{ $attendee->timein }}</td>
                            <td class="text-center align-middle">{{ $attendee->timeout }}</td>
                            <td class="text-center align-middle">{{ $attendee->tardinessduration }}</td>
                            <td class="text-center align-middle">{{ $attendee->hcastatus }}</td>
                            @if ($event->status == 'On Going')
                                <td class="text-center align-middle">
                                    <a href="{{ route('checkouthc', $attendee->hcaid) }} "
                                        class="btn btn-danger">Check-out</a>
                                </td>
                            @endif
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <script src="{{ asset('js/headercontrol.js') }}"></script>
</body>

</html>
