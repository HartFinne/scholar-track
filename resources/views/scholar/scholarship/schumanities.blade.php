<!DOCTYPE html>
<html lang="en">

<head>
    <title>Humanities Class</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="{{ asset('css/schumanities.css') }}">
    <link rel="stylesheet" href="{{ asset('css/partial.css') }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
</head>

<body>
    <!-- Include Sidebar -->
    @include('partials._sidebar')

    <!-- Include Navbar -->
    @include('partials._navbar')


    <!-- MAIN -->
    <div class="ctn-main">
        <a href="{{ route('schome') }}" class="goback">&lt Go back</a>
        <h1 class="title">Humanities Class</h1>
        <p class="title-desc">Welcome! Track your attendance diligently in your Humanities Class.</p>
        <hr>
        <div class="attendance-dashboard">
            <div class="total-attendance">
                <h5>TOTAL ATTENDANCE</h5>
                <h4>{{ $totalattendance }}</h4>
            </div>
            <div class="total-tardiness">
                <h5>TOTAL TARDINESS</h5>
                <h4>{{ $totaltardiness }}</h4>
            </div>
            <div class="total-absences">
                <h5>TOTAL ABSENCES</h5>
                <h4>{{ $totalabsences }}</h4>
            </div>
        </div>

        <div class="status">
            <div class="filter-status">
                <p class="attendance-title">Attendance Status</p>
                <div class="filter" id="filter-hc">
                    <form action="{{ route('schumanities') }}" method="GET" id="filter-form">
                        <button type="submit" name="attendance_status" value="all" class="filter-btn {{ request('attendance_status', 'all') == 'all' ? 'active' : '' }}">All</button>
                        <button type="submit" name="attendance_status" value="Present" class="filter-btn {{ request('attendance_status') == 'Present' ? 'active' : '' }}">Present</button>
                        <button ctype="submit" name="attendance_status" value="Late" class="filter-btn {{ request('attendance_status') == 'Late' ? 'active' : '' }}">Late</button>
                        <button type="submit" name="attendance_status" value="Left Early" class="filter-btn {{ request('attendance_status') == 'Left Early' ? 'active' : '' }}">Left Early</button>
                        <button type="submit" name="attendance_status" value="Absent" class="filter-btn {{ request('attendance_status') == 'Absent' ? 'active' : '' }}">Absent</button>
                    </form>
                </div>
            </div>
            <div class="search-container">
                <form action="{{ route('schumanities') }}" method="GET">
                    <input type="search" name="search" class="search-input" placeholder="Search" value="{{ request()->input('search') }}">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </form>
            </div>
        </div>
        @foreach ($classes as $class)
            @foreach ($class->hcattendance as $attendance)
                <div class="attendance-card">
                    <div class="attendance-details">
                        <div class="topic">
                            <h5>{{ $class->topic }}</h5>
                            <hr>
                        </div>
                        <div class="details">
                            <div class="date-time">
                                <p>Date: {{ $class->hcdate }}</p>
                                <p>Time: {{ $class->hcstarttime }}</p>
                            </div>
                            <div class="attendance-stat">
                                <p>Time in: {{ $attendance->timein }}</p> <!-- Corrected from timeout to timein -->
                                <p>Attendance Status: <b>{{ $attendance->hcastatus }}</b></p>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        @endforeach
    </div>

    <script src="{{ asset('js/scholar.js') }}"></script>
</body>

</html>
