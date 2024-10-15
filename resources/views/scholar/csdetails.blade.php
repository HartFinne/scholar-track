<!DOCTYPE html>
<html lang="en">

<head>
    <title>Community Service - Activities</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="{{ asset('css/partial.css') }}">
    <link rel="stylesheet" href="{{ asset('css/sccommunity.css') }}">
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
        <a href="{{ route('csactivities') }}" class="goback">&lt Go back</a>
        <h1 class="title">TITLE</h1>

        <div class="activity-details-container">
            <div class="cs-img">
                <img src="{{ asset('images/tzu-chi-bg.jpg') }}" alt="">
            </div>

            <div class="card-details-content">
                <p><i class="fa-solid fa-location-dot"></i>Activity Place</p>
                <p><i class="fa-solid fa-calendar-days"></i>Date</p>
                <p><i class="fa-solid fa-clock"></i>Time</p>
                <p><i class="fa-solid fa-user"></i>Facilitator</p><br>
                <p><i>Meeting Place & Call Time:</i></p>
                <p><i class="fa-solid fa-map-pin"></i>Meeting Place</p>
                <p><i class="fa-regular fa-clock"></i>Call Time</p>

                <div class="cs-status">
                    <p class="text-center fw-bold cs-stat">Open</p>
                    <p class="text-center fw-bold no-vol">No. of volunteers</p>
                </div>
                <div class="btn-reg">
                    <button class="fw-bold" onclick="showDialog('confirmDialog')">Register</button>
                </div>
            </div>
        </div>
        <div class="activity-desc">
            <p>Lorem, ipsum dolor sit amet consectetur adipisicing elit. Ipsam distinctio ex rem incidunt
                numquam nesciunt vero asperiores est mollitia dicta, exercitationem blanditiis in, odio eum
                voluptates velit eligendi molestiae nam.</p>
            <p>Lorem, ipsum dolor sit amet consectetur adipisicing elit. Ipsam distinctio ex rem incidunt
                numquam nesciunt vero asperiores est mollitia dicta, exercitationem blanditiis in, odio eum
                voluptates velit eligendi molestiae nam.</p>
        </div>

        <!-- Register Dialog -->
        <div id="confirmDialog" class="register dialog hidden">
            <div class="dialog-content">
                <span class="close-btn" onclick="closeDialog('confirmDialog')"><i class="fa-solid fa-x"></i></span>
                <i class="fa-solid fa-circle-check"></i>
                <h2>Your registration has been submitted successfully.<br> Thank you for volunteering!</h2>
                <p>Go to dashboard to view your scheduled community service activities.</p>
                <p><strong>REMINDER:</strong> You have <span id="remhrs" class="fw-bold">2 hours</span> of community
                    service left.</p>
            </div>
        </div>

        <div id="confirmDialog2" class="register dialog hidden">
            <div class="dialog-content">
                <span class="close-btn" onclick="closeDialog('confirmDialog')"><i class="fa-solid fa-x"></i></span>
                <i class="fa-solid fa-circle-check"></i>
                <h2>Your registration has been submitted successfully.<br> Thank you for volunteering!</h2>
                <p>Go to dashboard to view your scheduled community service activities.</p>
                <p><strong>REMINDER:</strong> You have already completed the required hours of community service for
                    this academic year.</p>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/scholar.js') }}"></script>
</body>

</html>
