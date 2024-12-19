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
        <span class="text-success fw-bold h2">Humanities Class Attendees</span>
        <div class="row justify-content-between align-items-center">
            <div class="col-md-3">
                <input type="search" class="form-control border-success" placeholder="Search">
            </div>
            <div class="col-auto">
                <div class="row">
                    @if ($event->status == 'On Going')
                        <div class="col-auto">
                            <button class="btn btn-success" data-bs-toggle="modal"
                                data-bs-target="#markAsDoneModal">Mark as
                                Done</button>
                        </div>
                        <div class="col-auto">
                            <a class="btn btn-success" href="{{ route('attendancesystem', $event->hcid) }}">Go back</a>
                        </div>
                    @else
                        <a class="btn btn-success" href="{{ route('humanitiesclass') }}">Go back</a>
                    @endif
                </div>
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
                                        class="btn btn-sm btn-danger">Check-out</a>
                                </td>
                            @endif
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal for Mark as Done Confirmation -->
    <div class="modal fade" id="markAsDoneModal" tabindex="-1" aria-labelledby="markAsDoneModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-success text-white fw-bold">
                    <h5 class="modal-title" id="markAsDoneModalLabel">Confirm Mark as Done</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to mark this event as done? This action cannot be undone.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <form method="POST" action="{{ route('savehc', $event->hcid) }}">
                        @csrf
                        @method('GET')
                        <button type="submit" class="btn btn-success">Yes</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/headercontrol.js') }}"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Search filter function
            const searchInput = document.querySelector('input[type="search"]');
            const table = document.getElementById('tblpenalty');
            const rows = table.querySelectorAll('tbody tr');

            searchInput.addEventListener('input', function() {
                const filter = searchInput.value.toLowerCase();

                rows.forEach(row => {
                    const nameCell = row.cells[1].textContent.toLowerCase();
                    const timeInCell = row.cells[2].textContent.toLowerCase();
                    const timeOutCell = row.cells[3].textContent.toLowerCase();
                    const statusCell = row.cells[5].textContent.toLowerCase();

                    // Check if any of the relevant columns match the search query
                    if (nameCell.includes(filter) || timeInCell.includes(filter) || timeOutCell
                        .includes(filter) || statusCell.includes(filter)) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            });
        });
    </script>
</body>

</html>
