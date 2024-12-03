<!DOCTYPE html>

<html lang="en">

<head>
    <title>Community Service</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/table.css') }}">
    <link rel="stylesheet" href="{{ asset('css/communityservice.css') }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body>
    <!-- PAGE HEADER -->
    @include('partials._pageheader')
    <x-alert />

    <div class="ctnmain">
        <span class="text-success fw-bold h2">Community Service Overview</span>
        <div class="groupA">
            <div class="groupA1">
                <span class="label">Total CS Events</span>
                <span class="data" id="totalevents">{{ $totalevents }}</span>
            </div>
            <div class="groupA1">
                <span class="label">Open Events</span>
                <span class="data" id="totalevents">{{ $openevents }}</span>
            </div>
            <div class="groupA1">
                <span class="label">Closed Events</span>
                <span class="data" id="totalevents">{{ $closedevents }}</span>
            </div>
        </div>
        <div class="groupA">
            <div class="groupA2">
                <span class="label">Scholar's Community Service</span>
                <canvas id="myChart1"></canvas>
            </div>
        </div>
        <div class="divider"></div>
        <span class="text-success fw-bold h2">List of Activities</span>
        <div class="row gx-0 align-items-center">
            <div class="col-md-1">
                <button class="filter btn btn-sm btn-success w-100" id="toggleAll">All</button>
            </div>
            <div class="col-md-1 mx-1">
                <button class="filter btn btn-sm btn-outline-success w-100" id="toggleOpen">Open</button>
            </div>
            <div class="col-md-1">
                <button class="filter btn btn-sm btn-outline-success w-100" id="toggleClosed">Closed</button>
            </div>
        </div>
        <div class="ctn" id="allEvents">
            <div class="ctntable table-responsive">
                <table class="table table-bordered" id="tblcsevents">
                    <thead>
                        <tr>
                            <th class="text-center align-middle">Activity</th>
                            <th class="text-center align-middle">Date</th>
                            <th class="text-center align-middle">Registered Volunteers</th>
                            <th class="text-center align-middle">Status</th>
                            <th class="text-center align-middle">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($events['all'] as $event)
                            <tr>
                                <td class="text-center align-middle">{{ $event->title }}</td>
                                <td class="text-center align-middle">
                                    {{ \Carbon\Carbon::parse($event->eventdate)->format('F j, Y') }}</td>
                                <td class="text-center align-middle">{{ $event->volunteersnum }}</td>
                                <td class="text-center align-middle">{{ $event->eventstatus }}</td>
                                <td class="text-center align-middle">
                                    <a href="{{ route('viewcseventinfo', $event->csid) }}"
                                        class="btn btn-sm btn-success"><i class="fas fa-eye"></i></a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td class="text-center align-middle" colspan="5">No Records Found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <!-- Pagination Links -->
            <div class="d-flex justify-content-center mt-3">
                {{ $events['all']->links('pagination::bootstrap-4') }}
            </div>
        </div>
        <div class="ctn" id="openEvents" style="display: none">
            <div class="ctntable table-responsive">
                <table class="table table-bordered" id="tblcsevents">
                    <thead>
                        <tr>
                            <th class="text-center align-middle">Activity</th>
                            <th class="text-center align-middle">Date</th>
                            <th class="text-center align-middle">Registered Volunteers</th>
                            <th class="text-center align-middle">Status</th>
                            <th class="text-center align-middle">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($events['open'] as $event)
                            <tr>
                                <td class="text-center align-middle">{{ $event->title }}</td>
                                <td class="text-center align-middle">
                                    {{ \Carbon\Carbon::parse($event->eventdate)->format('F j, Y') }}</td>
                                <td class="text-center align-middle">{{ $event->volunteersnum }}</td>
                                <td class="text-center align-middle">{{ $event->eventstatus }}</td>
                                <td class="text-center align-middle">
                                    <a href="{{ route('viewcseventinfo', $event->csid) }}"
                                        class="btn btn-sm btn-success"><i class="fas fa-eye"></i></a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td class="text-center align-middle" colspan="5">No Records Found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <!-- Pagination Links -->
            <div class="d-flex justify-content-center mt-3">
                {{ $events['open']->links('pagination::bootstrap-4') }}
            </div>
        </div>
        <div class="ctn" id="closedEvents" style="display: none">
            <div class="ctntable table-responsive">
                <table class="table table-bordered" id="tblcsevents">
                    <thead>
                        <tr>
                            <th class="text-center align-middle">Activity</th>
                            <th class="text-center align-middle">Date</th>
                            <th class="text-center align-middle">Registered Volunteers</th>
                            <th class="text-center align-middle">Status</th>
                            <th class="text-center align-middle">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($events['closed'] as $event)
                            <tr>
                                <td class="text-center align-middle">{{ $event->title }}</td>
                                <td class="text-center align-middle">
                                    {{ \Carbon\Carbon::parse($event->eventdate)->format('F j, Y') }}</td>
                                <td class="text-center align-middle">{{ $event->volunteersnum }}</td>
                                <td class="text-center align-middle">{{ $event->eventstatus }}</td>
                                <td class="text-center align-middle">
                                    <a href="{{ route('viewcseventinfo', $event->csid) }}"
                                        class="btn btn-sm btn-success"><i class="fas fa-eye"></i></a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td class="text-center align-middle" colspan="5">No Records Found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <!-- Pagination Links -->
            <div class="d-flex justify-content-center mt-3">
                {{ $events['closed']->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </div>

    <div class="groupC" id="ctneventform" style="display: none;">
        <div class="groupC1">
            <span class="formtitle">Add new activity</span>
            <button id="btnclose" onclick="toggleeventform()"><i class="fas fa-xmark"></i></button>
        </div>
        <form method="POST" action="{{ route('createcsevent') }}" enctype="multipart/form-data">
            @csrf
            <div class="groupC2">
                <div class="groupC3">
                    <label>Title</label>
                    <input type="text" name="title" required>
                </div>
                <div class="groupC3">
                    <label>Evect Location</label>
                    <input type="text" name="eventloc" required>
                </div>
                <div class="groupC3">
                    <label>Event Date</label>
                    <input type="date" name="eventdate" required>
                </div>
                <div class="groupC3">
                    <label>Start Time</label>
                    <input type="time" name="starttime" required>
                </div>
                <div class="groupC3">
                    <label>Meeting Place</label>
                    <input type="text" name="meetingplace" required>
                </div>
                <div class="groupC3">
                    <label>Call Time</label>
                    <input type="time" name="calltime" required>
                </div>
                <div class="groupC3">
                    <label>Facilitator Name</label>
                    <input type="text" name="facilitator" required>
                </div>
                <div class="groupC3">
                    <label>Number of Volunteers Needed</label>
                    <input type="number" name="slotnum" required>
                </div>
                <button type="submit" id="btnpost">Post</button>
            </div>
        </form>
    </div>

    <script src="{{ asset('js/headercontrol.js') }}"></script>
    <script src="{{ asset('js/toggleeventform.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const scholarsWithCompletedHours = @json($scholarsWithCompletedHours);
            const scholarsWithRemainingHours = @json($scholarsWithRemainingHours);

            new Chart("myChart1", {
                type: "bar",
                data: {
                    labels: ['With Completed Hours', 'With Remaining Hours'], // Bar labels
                    datasets: [{
                        backgroundColor: ['#1a5319', '#4caf50'], // Different shades of green
                        data: [scholarsWithCompletedHours, scholarsWithRemainingHours], // Bar data
                    }]
                },
                options: {
                    plugins: {
                        legend: {
                            display: false // Hide the legend completely
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true // Ensure the y-axis starts from 0
                        }
                    }
                }
            });
        });


        $(document).ready(function() {
            function toggleSection(buttonId, containerId) {
                $(buttonId).click(function() {
                    // Fade out all containers, then fade in the selected one
                    $('.ctn').not(containerId).fadeOut('fast', function() {
                        $(containerId).fadeIn('slow');
                    });

                    // Update button classes to reflect active/inactive states
                    $('.filter').not(buttonId).removeClass('btn-success').addClass('btn-outline-success');
                    $(buttonId).removeClass('btn-outline-success').addClass('btn-success');
                });
            }

            // Attach events to buttons
            toggleSection('#toggleAll', '#allEvents');
            toggleSection('#toggleOpen', '#openEvents');
            toggleSection('#toggleClosed', '#closedEvents');
        });
    </script>
</body>

</html>
