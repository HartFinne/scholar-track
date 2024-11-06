<!DOCTYPE html>
<html lang="en">

<head>
    <title>Community Service - Attendance</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="{{ asset('css/sccommunity.css') }}">
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

    <div class="ctn-profilemenu" id="profilemenu" style="display: none;">
        <a href="manageprofile.html"><i class="fa-solid fa-user"></i>Profile</a><br>
        <a href="changepass.html"><i class="fa-solid fa-key"></i>Change Password</a><br>
        <span><i class="fa-solid fa-language"></i>Language</span></a>
        <button class="toggle-btn active">English</button>
        <button class="toggle-btn">Tagalog</button><br>
        <span><i class="fa-solid fa-bell"></i>Notification</span>
        <button class="toggle-btn active">SMS</button>
        <button class="toggle-btn">Email</button><br>
        <hr>
        <a href="" id="btn-signout"><i class="fa-solid fa-right-from-bracket"></i>Sign out</a>
    </div>

    <!-- MAIN -->
    <div class="ctn-main">
        <a href="{{ route('schome') }}" class="goback">&lt Go back</a>
        <h5 class="text-center fw-bold">Community Service</h5>
        <h1 class="text-center title">ATTENDANCE HISTORY</h1>

        <div class="attendance-dashboard">
            <div class="total-attendance">
                <h5>TOTAL ATTENDANCE</h5>
                <h4>{{ $totalAttendance }}</h4>
            </div>
            <div class="total-tardiness">
                <h5>TOTAL TARDINESS</h5>
                <h4>{{ $totalTardiness }}</h4>
            </div>
            <div class="total-absences">
                <h5>TOTAL ABSENCES</h5>
                <h4>{{ $totalAbsences }}</h4>
            </div>
        </div>

        <div class="status">
            <div class="filter-status">
                <p class="attendance-title">Attendance Status</p>
                <div class="filter" id="filter-cs">
                    <form action="{{ route('csattendance') }}" method="GET" id="filter-form">
                        <button type="submit" name="attendance_status" value="all" class="filter-btn {{ request('attendance_status', 'all') == 'all' ? 'active' : '' }}">All</button>
                        <button type="submit" name="attendance_status" value="Present" class="filter-btn {{ request('attendance_status') == 'Present' ? 'active' : '' }}">Present</button>
                        <button ctype="submit" name="attendance_status" value="Late" class="filter-btn {{ request('attendance_status') == 'Late' ? 'active' : '' }}">Late</button>
                        <button type="submit" name="attendance_status" value="Left Early" class="filter-btn {{ request('attendance_status') == 'Left Early' ? 'active' : '' }}">Left Early</button>
                        <button type="submit" name="attendance_status" value="Absent" class="filter-btn {{ request('attendance_status') == 'Absent' ? 'active' : '' }}">Absent</button>
                    </form>
                </div>
            </div>
            <div class="submit-attendance">
                <button class="fw-bold" type="button" onclick="window.location.href='{{ route('csform') }}';">Submit an
                    attendance</button>
            </div>
        </div>

        @foreach ($attendances as $attendance)
            <div class="attendance-card">
                <div class="class-img">
                    <img src="{{ asset('images/account.png') }}" alt="">
                </div>
                <div class="attendance-details">
                    <div class="topic">
                        <h5>ACTIVITY: {{ $attendance->activity_title }}</h5>
                        <hr>
                    </div>
                    <div class="details">
                        <div class="date-time">
                            <p>Location: {{ $attendance->location }}</p>
                            <p>Date: {{ \Carbon\Carbon::parse($attendance->date)->format('F j, Y') }}</p>
                            <p>Time: {{ \Carbon\Carbon::parse($attendance->time)->format('H:i') }}</p>
                            <p>Facilitator: {{ $attendance->facilitator }}</p>
                            <p>Hours Completed: {{ $attendance->hoursspent }} hours</p>
                        </div>
                        <div class="attendance-stat">
                            <p>Time in: {{ $attendance->timein }}</p>
                            <p>Time out: {{ $attendance->timeout }}</p><br><br>
                            <p>Attendance Status: <b>{{ $attendance->csastatus }}</b></p>
                            <p>Status: <b>{{ $attendance->status }}</b></p>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach


    </div>
    <script src="{{ asset('js/scholar.js') }}"></script>
</body>

</html>
