<!DOCTYPE html>

<html lang="en">

<head>
    <title>Humanities Class</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/table.css') }}">
    <link rel="stylesheet" href="{{ asset('css/humanityclass.css') }}">
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
        <span class="pagetitle">Manage Humanities Class</span>
        <div class="groupA">
            <form action="#" class="searchbar">
                <input type="search" placeholder="Search" id="insearch" required>
                <button type="submit" id="btnsearch"><i class="fas fa-magnifying-glass"></i></button>
            </form>
            <button id="btncreatehc" onclick="toggleform()">Create an Event</button>
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

        <div class="ctntable table-responsive">
            <table class="table table-bordered" id="tblpenalty">
                <thead>
                    <tr>
                        <th class="text-center align-middle">#</th>
                        <th class="text-center align-middle">Topic</th>
                        <th class="text-center align-middle">Date</th>
                        <th class="text-center align-middle">Number of Attendees</th>
                        <th class="text-center align-middle">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($classes as $index => $class)
                        <tr>
                            <td class="text-center align-middle">{{ $index + 1 }}</td>
                            <td class="text-center align-middle">{{ $class->topic }}</td>
                            <td class="text-center align-middle">{{ $class->hcdate }}</td>
                            <td class="text-center align-middle">{{ $class->totalattendees }}</td>
                            <td class="text-center align-middle">
                                <a href="{{ route('attendancesystem', $class->hcid) }} " class="btn btn-primary">Open
                                    Attendance System</a>
                                <a href="{{ route('viewattendeeslist', $class->hcid) }}" class="btn btn-primary">View
                                    Details</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <form id="ctncreatehc" method="POST" action="{{ route('createhc') }}">
        @csrf
        <div class="formheader">
            <span>Create new event</span>
            <button type="button" id="btncloseform" onclick="toggleform()"><i class="fas fa-xmark"></i></button>
        </div>
        <div class="groupB">
            <span class="label">Title</span>
            <input type="text" class="data" name="topic" placeholder="ex: 1st Humanities Class" required>
        </div>
        <div class="groupB">
            <span class="label">Location</span>
            <input type="text" class="data" name="hclocation" required>
        </div>
        <div class="groupB">
            <span class="label">Start Time</span>
            <input type="time" class="data" name="hcstarttime" required>
        </div>
        <div class="groupB">
            <span class="label">End Time</span>
            <input type="time" class="data" name="hcendtime" required>
        </div>
        <button type="submit" id="btnattendance">Create</button>
    </form>

    <script src="{{ asset('js/headercontrol.js') }}"></script>
    <script>
        function toggleform() {
            var ctn = document.getElementById('ctncreatehc');

            if (ctn.style.display === 'flex') {
                ctn.style.display = 'none';
            } else {
                ctn.style.display = 'flex';
            }
        }
    </script>
</body>

</html>
