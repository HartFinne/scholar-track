<!DOCTYPE html>

<html lang="en">

<head>
    <title>Community Service</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/table.css') }}">
    <link rel="stylesheet" href="{{ asset('css/communityservice.css') }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body>
    <!-- PAGE HEADER -->
    @include('partials._pageheader')

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

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert" id="success-alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert" id="error-alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="groupB">
            <div class="groupB1">
                <a href="{{ route('communityservice') }}" class="option"
                    style="color: #fff; background-color: #2e7c55; font-weight: bold;">All</a>
                <a href="{{ route('communityservice-open') }}" class="option">Open</a>
                <a href="{{ route('communityservice-closed') }}" class="option">Close</a>
            </div>
            <button id="btnaddevent" onclick="toggleeventform()">Create an Event</button>
        </div>
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
                    @foreach ($events as $event)
                        <tr>
                            <td class="text-center align-middle">{{ $event->title }}</td>
                            <td class="text-center align-middle">{{ $event->eventdate }}</td>
                            <td class="text-center align-middle">{{ $event->volunteersnum }}</td>
                            <td class="text-center align-middle">{{ $event->eventstatus }}</td>
                            <td class="text-center align-middle">
                                <a href="{{ route('viewcseventinfo', $event->csid) }}" class="btn btn-primary">View</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
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
        const ctx1 = document.getElementById('myChart1').getContext('2d');

        // Data for the chart based on the scholars
        const scholarsData = [{{ $scholarsWithCompletedHours }}, {{ $scholarsWithRemainingHours }}];

        new Chart(ctx1, {
            type: 'bar',
            data: {
                labels: ['With Completed Hours', 'With Remaining Hours'], // Labels for each bar
                datasets: [{
                    label: 'No. of Scholars',
                    data: scholarsData, // Number of scholars for each category
                    borderWidth: 1,
                    borderColor: 'darkgreen', // Bar border color set to dark green
                    backgroundColor: 'rgba(0, 100, 0, 0.8)', // Bar fill color set to dark green with transparency
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'No. of Scholars'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Status'
                        }
                    }
                },
                plugins: {
                    title: {
                        display: true,
                        text: 'Number of Scholars with Completed Hours and Remaining Hours in Community Service'
                    }
                }
            }
        });
    </script>
</body>

</html>
