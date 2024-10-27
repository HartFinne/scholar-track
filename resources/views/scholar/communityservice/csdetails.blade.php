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
</head>

<body>
    <!-- Include Sidebar -->
    @include('partials._sidebar')

    <!-- Include Navbar -->
    @include('partials._navbar')

    <!-- MAIN -->
    <div class="ctn-main">

        <a href="{{ route('csactivities') }}" class="goback">&lt Go back</a>
        <h1 class="title">{{ $activity->title }}</h1>

        <div class="activity-details-container">
            <div class="cs-img">
                <img src="{{ asset('images/tzu-chi-bg.jpg') }}" alt="">
            </div>

            <div class="card-details-content">
                <p><i class="fa-solid fa-location-dot"></i>{{ $activity->eventloc }}</p>
                <p><i class="fa-solid fa-calendar-days"></i>{{ $activity->eventdate }}</p>
                <p><i class="fa-solid fa-clock"></i>{{ $activity->starttime }}</p>
                <p><i class="fa-solid fa-user"></i>{{ $activity->facilitator }}</p><br>
                <p><i>Meeting Place & Call Time:</i></p>
                <p><i class="fa-solid fa-map-pin"></i>{{ $activity->meetingplace }}</p>
                <p><i class="fa-regular fa-clock"></i>{{ $activity->calltime }}</p>

                <div class="cs-status">
                    <p class="text-center fw-bold cs-stat">Open</p>
                    <p class="text-center fw-bold no-vol">No. of volunteers {{ $activity->slotnum }}</p>

                </div>
                <form action="{{ route('csdetails.post', ['csid' => $activity->csid]) }}" method="POST"
                    id="registrationForm">
                    @csrf
                    <div class="btn-reg">
                        @if ($isRegistered)
                            <!-- If the user is already registered, show "Registered" and disable the button -->
                            <button type="button" class="fw-bold" disabled>Registered</button>
                        @else
                            <!-- If the user is not registered, show the "Register" button -->
                            <button type="submit" class="fw-bold">Register</button>
                        @endif
                    </div>
                </form>
            </div>
        </div>

        <!-- Register Dialog with remaining hours -->
        <div id="confirmDialog" class="register dialog hidden">
            <div class="dialog-content">
                <span class="close-btn" onclick="closeDialog('confirmDialog')"><i class="fa-solid fa-x"></i></span>
                <i class="fa-solid fa-circle-check"></i>
                <h2>Your registration has been submitted successfully.<br> Thank you for volunteering!</h2>
                <p>Go to dashboard to view your scheduled community service activities.</p>
                <p><strong>REMINDER:</strong> You have <span id="remhrs" class="fw-bold">{{ $remainingHours }}
                        {{ $remainingHours == 1 ? 'hour' : 'hours' }}</span> of community
                    service left.</p>
            </div>
        </div>


        <!-- Register Dialog with completed hours -->
        <div id="confirmDialog2" class="register dialog hidden">
            <div class="dialog-content">
                <span class="close-btn" onclick="closeDialog('confirmDialog2')"><i class="fa-solid fa-x"></i></span>
                <i class="fa-solid fa-circle-check"></i>
                <h2>Your registration has been submitted successfully.<br> Thank you for volunteering!</h2>
                <p>Go to dashboard to view your scheduled community service activities.</p>
                <p><strong>REMINDER:</strong> You have already completed the required hours of community service for
                    this academic year.</p>
            </div>
        </div>

        <!-- JavaScript to handle dialogs -->
        <script>
            function showRegistrationDialog() {
                const remainingHours = parseInt(document.getElementById('remhrs').textContent);

                if (remainingHours > 0) {
                    openDialog('confirmDialog');
                } else {
                    openDialog('confirmDialog2');
                }
            }

            function openDialog(dialogId) {
                const dialog = document.getElementById(dialogId);
                dialog.classList.remove('hidden'); // Show the dialog
            }

            function closeDialog(dialogId) {
                const dialog = document.getElementById(dialogId);
                dialog.classList.add('hidden'); // Hide the dialog
            }
        </script>

        <!-- Trigger dialog if registration was successful -->
        @if (session('success'))
            <script>
                document.addEventListener("DOMContentLoaded", function() {
                    showRegistrationDialog();
                });
            </script>
        @endif

        <script src="{{ asset('js/scholar.js') }}"></script>
</body>

</html>
