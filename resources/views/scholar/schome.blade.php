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
    <!-- Include Sidebar -->
    @include('partials._sidebar')

    <!-- Include Navbar -->
    @include('partials._navbarhome')
    <x-alert />

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
        <div class="mt-3 mb-3">
            <h2 id="announce-title" class="fw-bold announce-title">ANNOUNCEMENTS</h2>
            <div class="status" id="filter-announce">
                <div class="filter">
                    <form action="{{ route('schome') }}" method="GET" id="filter-form">
                        <button type="submit" name="filter" value="all"
                            class="filter-btn {{ request()->input('filter') === 'all' || !request()->has('filter') ? 'active' : '' }}">All</button>
                        <button type="submit" name="filter" value="latest"
                            class="filter-btn {{ request()->input('filter') == 'latest' ? 'active' : '' }}">Latest</button>
                        <button type="submit" name="filter" value="humanities"
                            class="filter-btn {{ request()->input('filter') == 'humanities' ? 'active' : '' }}">Humanities</button>
                        <button type="submit" name="filter" value="community_service"
                            class="filter-btn {{ request()->input('filter') == 'community_service' ? 'active' : '' }}">Community
                            Service</button>
                    </form>
                </div>
                <div class="search-container">
                    <form action="{{ route('schome') }}" method="GET">
                        <input type="search" name="search" class="search-input" placeholder="Search"
                            value="{{ request()->input('search') }}">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </form>
                </div>
            </div>

        </div>
        <div class="home-main">
            @foreach ($announcements as $announcement)
                <div class="announcement">
                    <div class="card-announcement">
                        <div class="card-header">
                            <img src="{{ asset('images/account.png') }}" alt="Profile Image" class="profile-img">
                            <div>
                                <h6 class="fw-bold mb-0">{{ $announcement->author }}</h6>
                                <small
                                    class="text-muted">{{ \Carbon\Carbon::parse($announcement->created_at)->format('F d, Y h:i a') }}</small>
                            </div>
                        </div>
                        <div class="card-body">
                            <p class="card-text fw-bold">{{ $announcement->title }}</p>
                            <p class="card-text" style="white-space: pre-wrap;">{{ $announcement->description }}</p>
                        </div>
                        {{-- <div>
                            <i class="heart-icon fas fa-heart"></i>
                        </div> --}}
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <script src="{{ asset('js/scholar.js') }}"></script>
</body>

</html>
