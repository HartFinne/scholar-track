<!DOCTYPE html>
<html lang="en">

<head>
    <title>Home</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="{{ asset('css/home.css') }}">
    <link rel="stylesheet" href="{{ asset('css/partial.css') }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
</head>

<body>
    @if (session()->has('id'))
        <p>Session ID: {{ session()->getId() }}</p>
    @endif

    <!-- Include Sidebar -->
    @include('partials._sidebar')

    <!-- Include Navbar -->
    @include('partials._navbar')

    <!-- MAIN CONTENT -->
    <div class="header">
        <div class="container">
            <div class="text-center">
                <div class="logo-header">
                    <img src="{{ asset('images/logo.png') }}" alt="">
                    <div class="logo-title">
                        <h1>Tzu Chi Philippines</h1>
                        <h1>Educational Assistance Program</h1>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="ctn-main">
        <div class="mb-3">
            <h2 id="announce-title" class="fw-bold announce-title">ANNOUNCEMENTS</h2>
            <div class="status">
                <div class="filter">
                    <button class="filter-btn">All</button>
                    <button class="filter-btn">Latest</button>
                    <button class="filter-btn">Humanities Class</button>
                    <button class="filter-btn">Community Service</button>
                </div>
                <div class="search-container">
                    <input type="search" class="search-input" placeholder="Search">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </div>
            </div>

        </div>
        <div class="home-main">
            <div class="announcement">
                <div class="card-announcement">
                    <div class="card-header">
                        <img src="{{ asset('images/account.png') }}" alt="Profile Image" class="profile-img">
                        <div>
                            <h6 class="fw-bold mb-0">Name</h6>
                            <small class="text-muted">Date/Time</small>
                        </div>
                    </div>
                    <div class="card-body">
                        <p class="card-text">Lorem ipsum...</p>
                        <img src="{{ asset('images/home-bg.png') }}" class="card-img-top" alt="Activity Image">
                    </div>
                    <div>
                        <i class="heart-icon fas fa-heart"></i>
                    </div>
                </div>
            </div>

            <!-- <div class="card-notif">
                    <h5 id="notif-title" class="fw-bold">NOTIFICATIONS</h5>
                </div> -->
        </div>
    </div>

    <script src="{{ asset('js/scholar.js') }}"></script>
</body>

</html>
