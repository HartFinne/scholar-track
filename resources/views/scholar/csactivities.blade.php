<!DOCTYPE html>
<html lang="en">

<head>
    <title>Community Service - Activities</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="{{ asset('css/cs.css') }}">
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
        <h1 class="title">Community Service Activities</h1>
        <p class="title-desc">Welcome, Scholar! Monitor your community service effectively.</p>
        <hr>
        <div class="search-container">
            <input type="search" class="search-input" placeholder="Search">
            <select class="cs-area" aria-label="area">
                <option value="" disabled selected hidden>Select Area</option>
                <option value="mindong">Mindong</option>
                <option value="minxi">Minxi</option>
                <option value="minzhong">Minzhong</option>
            </select>
        </div>

        <div class="activity-container">
            <div class="card">
                <a href="csdetails.html">
                    <img src="{{ asset('images/tzu-chi-bg.jpg') }}" alt="">
                    <div class="card-content">
                        <p class="text-center fw-bold cs-title">TITLE</p>
                        <p><i class="fa-solid fa-location-dot"></i>Activity Place</p>
                        <p><i class="fa-solid fa-calendar-days"></i>Date</p>
                        <p><i class="fa-solid fa-clock"></i>Time</p>
                        <p><i class="fa-solid fa-user"></i>Facilitator</p><br>
                        <p><i>Meeting Place & Call Time:</i></p>
                        <p><i class="fa-solid fa-map-pin"></i>Meeting Place</p>
                        <p><i class="fa-regular fa-clock"></i>Call Time</p>
                    </div>
                    <div class="num-vol">
                        <p class="text-center fw-bold">No. of volunteers needed</p>
                    </div>
                </a>
            </div>
            <div class="card">
                <a href="">
                    <img src="{{ asset('images/tzu-chi-bg.jpg') }}" alt="">
                    <div class="card-content">
                        <p class="text-center fw-bold cs-title">TITLE</p>
                        <p><i class="fa-solid fa-location-dot"></i>Activity Place</p>
                        <p><i class="fa-solid fa-calendar-days"></i>Date</p>
                        <p><i class="fa-solid fa-clock"></i>Time</p>
                        <p><i class="fa-solid fa-user"></i>Facilitator</p><br>
                        <p><i>Meeting Place & Call Time:</i></p>
                        <p><i class="fa-solid fa-map-pin"></i>Meeting Place</p>
                        <p><i class="fa-regular fa-clock"></i>Call Time</p>
                    </div>
                    <div class="num-vol">
                        <p class="text-center fw-bold">No. of volunteers needed</p>
                    </div>
                </a>
            </div>
            <div class="card">
                <a href="">
                    <img src="{{ asset('images/tzu-chi-bg.jpg') }}" alt="">
                    <div class="card-content">
                        <p class="text-center fw-bold cs-title">TITLE</p>
                        <p><i class="fa-solid fa-location-dot"></i>Activity Place</p>
                        <p><i class="fa-solid fa-calendar-days"></i>Date</p>
                        <p><i class="fa-solid fa-clock"></i>Time</p>
                        <p><i class="fa-solid fa-user"></i>Facilitator</p><br>
                        <p><i>Meeting Place & Call Time:</i></p>
                        <p><i class="fa-solid fa-map-pin"></i>Meeting Place</p>
                        <p><i class="fa-regular fa-clock"></i>Call Time</p>
                    </div>
                    <div class="num-vol">
                        <p class="text-center fw-bold">No. of volunteers needed</p>
                    </div>
                </a>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/scholar.js') }}"></script>
</body>

</html>
