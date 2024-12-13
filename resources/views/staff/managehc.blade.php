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
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body>
    <!-- PAGE HEADER -->
    @include('partials._pageheader')
    <x-alert />

    <div class="ctnmain">
        <span class="text-success fw-bold h2">Manage Humanities Class</span>
        <div class="row d-flex justify-content-between align-items-center">
            <div class="col-3">
                <input type="search" placeholder="Search" id="search"
                    class="form-control border border-success text-success" aria-label="Search">
            </div>
            <div class="col-auto">
                <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#createEventModal">
                    Create an Event
                </button>
            </div>
        </div>

        <div class="ctntable table-responsive">
            <table class="table table-bordered" id="tblpenalty">
                <thead>
                    <tr>
                        <th class="text-center align-middle">#</th>
                        <th class="text-center align-middle">Topic</th>
                        <th class="text-center align-middle">Date</th>
                        <th class="text-center align-middle">Attendees</th>
                        <th class="text-center align-middle">Absentees</th>
                        <th class="text-center align-middle">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($classes as $index => $class)
                        <tr>
                            <td class="text-center align-middle">{{ $index + 1 }}</td>
                            <td class="text-center align-middle">{{ $class->topic }}</td>
                            <td class="text-center align-middle">
                                {{ Carbon\Carbon::parse($class->hcdate)->format('F j, Y') }}</td>
                            <td class="text-center align-middle">{{ $class->totalattendees }}</td>
                            <td class="text-center align-middle">{{ $class->totalabsentees }}</td>
                            <td class="text-center align-middle fw-bold">
                                <a href="{{ $class->status == 'On Going' ? route('attendancesystem', $class->hcid) : '#' }}"
                                    class="btn btn-sm text-white 
                                           {{ $class->status == 'On Going' ? 'btn-warning' : 'btn-secondary' }} 
                                           border-0" title="Open Attendance System"
                                    @if ($class->status != 'On Going') style="pointer-events: none;" @endif>
                                    <i class="fas fa-calendar-check"></i>
                                </a>
                                <a href="{{ route('viewattendeeslist', $class->hcid) }}"
                                    class="btn btn-sm btn-success border-0" title="View List of Attendees">
                                    <i class="fas fa-users"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination Links -->
        <div class="d-flex justify-content-center mt-3">
            {{ $classes->links('pagination::bootstrap-4') }}
        </div>
    </div>

    <div class="modal fade" id="createEventModal" tabindex="-1" aria-labelledby="createEventModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <form id="ctncreatehc" method="POST" action="{{ route('createhc') }}">
                @csrf
                <div class="modal-content">
                    <div class="modal-header bg-success text-white">
                        <h5 class="modal-title" id="createEventModalLabel">Create New Event</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <!-- Title -->
                        <div class="mb-3">
                            <label for="topic" class="form-label">Title</label>
                            <input type="text" class="form-control" name="topic" id="topic"
                                placeholder="ex: 1st Humanities Class" required>
                        </div>

                        <!-- Location -->
                        <div class="mb-3">
                            <label for="hclocation" class="form-label">Location</label>
                            <input type="text" class="form-control" name="hclocation" id="hclocation" required>
                        </div>

                        <!-- Start Time -->
                        <div class="mb-3">
                            <label for="hcstarttime" class="form-label">Start Time</label>
                            <input type="time" class="form-control" name="hcstarttime" id="hcstarttime" required>
                        </div>

                        <!-- End Time -->
                        <div class="mb-3">
                            <label for="hcendtime" class="form-label">End Time</label>
                            <input type="time" class="form-control" name="hcendtime" id="hcendtime" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success" id="btnattendance">Create</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script src="{{ asset('js/headercontrol.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Get the search input element and the table rows
            const searchInput = document.getElementById('search');
            const table = document.getElementById('tblpenalty');
            const rows = table.getElementsByTagName('tr'); // All rows in the table (including header)

            // Add an event listener for the search input
            searchInput.addEventListener('input', function() {
                const query = searchInput.value
                    .toLowerCase(); // Convert to lowercase to make search case-insensitive

                // Loop through the table rows
                for (let i = 1; i < rows.length; i++) { // Start from 1 to skip the header row
                    const cells = rows[i].getElementsByTagName('td');
                    let found = false;

                    // Loop through the cells of each row to see if any match the query
                    for (let j = 0; j < cells.length; j++) {
                        if (cells[j].innerText.toLowerCase().includes(query)) {
                            found = true; // If any cell contains the query, mark as found
                            break; // No need to check further cells
                        }
                    }

                    // If the row matches the search query, show it, otherwise hide it
                    if (found) {
                        rows[i].style.display = '';
                    } else {
                        rows[i].style.display = 'none';
                    }
                }
            });
        });
    </script>
</body>

</html>
