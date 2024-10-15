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
                <h4>0</h4>
            </div>
            <div class="total-tardiness">
                <h5>TOTAL TARDINESS</h5>
                <h4>0</h4>
            </div>
            <div class="total-absences">
                <h5>TOTAL ABSENCES</h5>
                <h4>0</h4>
            </div>
        </div>

        <div class="status">
            <div class="filter-status">
                <p class="attendance-title">Attendance Status</p>
                <div class="filter">
                    <button class="filter-btn">All</button>
                    <button class="filter-btn">Pending</button>
                    <button class="filter-btn">Excused</button>
                    <button class="filter-btn">Unexcused</button>
                </div>
            </div>
            <div class="search-container">
                <input type="search" class="search-input" placeholder="Search">
                <i class="fa-solid fa-magnifying-glass"></i>
            </div>
        </div>

        <div class="attendance-card">
            <div class="class-img">
                <img src="{{ asset('images/account.png') }}" alt="">
            </div>
            <div class="attendance-details">
                <div class="topic">
                    <h5>TOPIC</h5>
                    <hr>
                </div>
                <div class="details">
                    <div class="date-time">
                        <p>Date:</p>
                        <p>Time:</p>
                    </div>
                    <div class="attendance-stat">
                        <p>Time in:</p>
                        <p>Attendance Status: <b>Present</b></p>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script src="{{ asset('js/scholar.js') }}"></script>
</body>

</html>
