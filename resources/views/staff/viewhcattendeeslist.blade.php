<!DOCTYPE html>

<html lang="en">

<head>
    <title>Humanities Class</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/table.css') }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script>
        window.onerror = function(message, source, lineno, colno, error) {
            alert('Error: ' + message + '\nSource: ' + source + '\nLine: ' + lineno);
        };
    </script>
</head>

<body>
    <!-- PAGE HEADER -->
    @include('partials._pageheader')

    <div class="ctnmain">
        <span class="pagetitle">Humanities Class Attendees</span>
        <div class="groupA">
            <form action="#" class="searchbar">
                <input type="search" placeholder="Search" id="insearch" required>
                <button type="submit" id="btnsearch"><i class="fas fa-magnifying-glass"></i></button>
            </form>
            <a id="btngoback" href="{{ url()->previous() }}">Go back</a>
        </div>

        {{-- @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert" id="success-alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif --}}

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert" id="error-alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div>
            <span class="label">Topic: </span><span class="data">{{ $event->topic }}</span><br>
            <span class="label">Date: </span><span class="data">{{ $event->hcdate }}</span><br>
            <span class="label">Start Time: </span><span class="data">{{ $event->hcstarttime }}</span><br>
            <span class="label">End Time: </span><span class="data">{{ $event->hcendtime }}</span><br>
            <span class="label">Total Attendees: </span><span class="data">{{ $event->totalattendees }}</span>
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
                        <th class="text-center align-middle">Action</th>
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
                            <td class="text-center align-middle">
                                <button>Check-out</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <script src="{{ asset('js/headercontrol.js') }}"></script>
</body>

</html>
