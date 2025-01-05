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
        <a href="{{ route('schome') }}" class="goback">&lt Go back</a>
        <h1 class="title">Community Service Activities</h1>
        <p class="title-desc">Welcome, Scholar! Monitor your community service effectively.</p>
        <hr>
        <div class="search-container">
            <form action="{{ route('csactivities') }}" method="GET">
                <input type="search" name="search" class="search-input" placeholder="Search"
                    value="{{ request()->input('search') }}">

                <!-- <select class="cs-area" aria-label="area">
                    <option value="" disabled selected hidden>Select Area</option>
                    <option value="mindong">Mindong</option>
                    <option value="minxi">Minxi</option>
                    <option value="minzhong">Minzhong</option>
                </select> -->
            </form>
        </div>

        <div class="activity-container">
            @foreach ($filteredActivities as $activity)
                <div class="card">
                    @if (isset($registrations[$activity->csid]))
                        {{-- If the user is registered for this activity, check the registration status --}}
                        @if ($registrations[$activity->csid]['registatus'] === 'Cancelled')
                            {{-- If the registration is cancelled, show a message and make it non-clickable --}}
                            <div>
                                <img src="{{ asset('images/tzu-chi-bg.jpg') }}" alt="">
                                <div class="card-content">
                                    <p class="text-center fw-bold cs-title">{{ $activity->title }}</p>
                                    <p><i class="fa-solid fa-location-dot"></i>{{ $activity->eventloc }}</p>
                                    <p><i
                                            class="fa-solid fa-calendar-days"></i>{{ \Carbon\Carbon::parse($activity->eventdate)->format('F j, Y') }}
                                    </p>
                                    <p><i
                                            class="fa-solid fa-clock"></i>{{ \Carbon\Carbon::parse($activity->starttime)->format('g:i A') }}
                                    </p>
                                    <p><i class="fa-solid fa-user"></i>{{ $activity->facilitator }}</p><br>
                                    <p><i>Meeting Place & Call Time:</i></p>
                                    <p><i class="fa-solid fa-map-pin"></i>{{ $activity->meetingplace }}</p>
                                    <p><i
                                            class="fa-regular fa-clock"></i>{{ \Carbon\Carbon::parse($activity->calltime)->format('g:i A') }}
                                    </p>
                                </div>

                                <div class="num-vol">
                                    <p class="text-center fw-bold">You cancelled your registration for this activity and
                                        cannot register again.</p>
                                </div>
                            </div>
                        @else
                            {{-- If the user is actively registered, show a non-clickable card --}}
                            <div>
                                <img src="{{ asset('images/tzu-chi-bg.jpg') }}" alt="">
                                <div class="card-content">
                                    <p class="text-center fw-bold cs-title">{{ $activity->title }}</p>
                                    <p><i class="fa-solid fa-location-dot"></i>{{ $activity->eventloc }}</p>
                                    <p><i
                                            class="fa-solid fa-calendar-days"></i>{{ \Carbon\Carbon::parse($activity->eventdate)->format('F j, Y') }}
                                    </p>
                                    <p><i
                                            class="fa-solid fa-clock"></i>{{ \Carbon\Carbon::parse($activity->starttime)->format('g:i A') }}
                                    </p>
                                    <p><i class="fa-solid fa-user"></i>{{ $activity->facilitator }}</p><br>
                                    <p><i>Meeting Place & Call Time:</i></p>
                                    <p><i class="fa-solid fa-map-pin"></i>{{ $activity->meetingplace }}</p>
                                    <p><i
                                            class="fa-regular fa-clock"></i>{{ \Carbon\Carbon::parse($activity->calltime)->format('g:i A') }}
                                    </p>
                                </div>

                                <div class="num-vol">
                                    <p class="text-center fw-bold">You are already registered for this activity!</p>
                                </div>
                            </div>
                        @endif
                    @else
                        {{-- If the user is not registered, make the card clickable --}}
                        <a href="{{ route('csdetails', ['csid' => $activity->csid]) }}">
                            <img src="{{ asset('images/tzu-chi-bg.jpg') }}" alt="">
                            <div class="card-content">
                                <p class="text-center fw-bold cs-title">{{ $activity->title }}</p>
                                <p><i class="fa-solid fa-location-dot"></i>{{ $activity->eventloc }}</p>
                                <p><i
                                        class="fa-solid fa-calendar-days"></i>{{ \Carbon\Carbon::parse($activity->eventdate)->format('F j, Y') }}
                                </p>
                                <p><i
                                        class="fa-solid fa-clock"></i>{{ \Carbon\Carbon::parse($activity->starttime)->format('g:i A') }}
                                </p>
                                <p><i class="fa-solid fa-user"></i>{{ $activity->facilitator }}</p><br>
                                <p><i>Meeting Place & Call Time:</i></p>
                                <p><i class="fa-solid fa-map-pin"></i>{{ $activity->meetingplace }}</p>
                                <p><i
                                        class="fa-regular fa-clock"></i>{{ \Carbon\Carbon::parse($activity->calltime)->format('g:i A') }}
                                </p>
                            </div>

                            <div class="num-vol">
                                <p class="text-center fw-bold">No. of volunteers needed {{ $activity->slotnum }}</p>
                            </div>
                        </a>
                    @endif
                </div>
            @endforeach
        </div>


    </div>

    <script src="{{ asset('js/scholar.js') }}"></script>
</body>

</html>
