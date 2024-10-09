<!DOCTYPE html>
<html lang="en">

<head>
    <title>Profile</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="{{ asset('css/scholar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/partial.css') }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
</head>

<body>

    @if (Auth::check())
        <p>Your Scholar ID (Case Code): {{ Auth::user()->caseCode }}</p>
    @else
        <p>You are not logged in.</p>
    @endif
    <!-- Include Sidebar -->
    @include('partials._sidebar')

    <!-- Include Navbar -->
    @include('partials._navbar')

    <!-- MAIN CONTENT -->
    <div class="ctn-main">
        <a href="{{ route('schome') }}" class="goback">&lt Go back</a>
        <div class="text-center profile">
            <h1 class="title">My Profile</h1>
            <button type="" class="btn-edit fw-bold">Edit Profile</button>
        </div>
        <div class="info">
            <div class="label">Case Code</div>
            <div class="value">: <span>--</span></div>

            <div class="label">Assigned Area</div>
            <div class="value">: <span>Mindong</span></div>

            <div class="label">Name</div>
            <div class="value">: <span>Dela Cruz, Juan Santos</span></div>

            <div class="label">Date of Birth</div>
            <div class="value">: <span>January 1, 2002</span></div>

            <div class="label">Sex</div>
            <div class="value">: <span>Male</span></div>

            <div class="label">Start of Scholarship</div>
            <div class="value">: <span>MM/DD/YYYY</span></div>

            <div class="label">T-Shirt Size</div>
            <div class="value">: <span>M</span></div>

            <div class="label">Shoe Size</div>
            <div class="value">: <span>9</span></div>

            <div class="label">Slippers Size</div>
            <div class="value">: <span>9</span></div>

            <div class="label">Pants Size</div>
            <div class="value">: <span>L</span></div>

            <div class="label">Jogging Pants Size</div>
            <div class="value">: <span>L</span></div>

            <div class="label">Email Address</div>
            <div class="value">: <span>example@gmail.com</span></div>

            <div class="label">Contact Number</div>
            <div class="value">: <span>09XXXXXXXXX</span></div>
        </div>
    </div>
    <div class="profile-info">
        <h4>ACCOUNT INFORMATION</h4>
        <div class="info">
            <div class="label">Account Number</div>
            <div class="value">: <span>--</span></div>

            <div class="label">Card Number</div>
            <div class="value">: <span>--</span></div>
        </div>
    </div>
    <div class="profile-info">
        <h4>ADDRESS INFORMATION</h4>
        <div class="info">
            <div class="label">Home Address</div>
            <div class="value">: <span>--</span></div>

            <div class="label">Area Code</div>
            <div class="value">: <span>--</span></div>

            <div class="label">Permanent Address</div>
            <div class="value">: <span>--</span></div>
        </div>
    </div>
    <div class="profile-info">
        <h4>EMERGENCY CONTACT</h4>
        <div class="info">
            <div class="label">Name</div>
            <div class="value">: <span>--</span></div>

            <div class="label">Relation</div>
            <div class="value">: <span>Mother</span></div>

            <div class="label">Email Address</div>
            <div class="value">: <span>example@gmail.com</span></div>

            <div class="label">Contact Number</div>
            <div class="value">: <span>09XXXXXXXXX</span></div>
        </div>
    </div>
    <div class="profile-info">
        <h4>EDUCATIONAL BACKGROUND</h4>
        <div class="info">
            <div class="label">School</div>
            <div class="value">: <span>--</span></div>

            <div class="label">College Department</div>
            <div class="value">: <span>--</span></div>

            <div class="label">Grade/Year Level</div>
            <div class="value">: <span>--</span></div>

            <div class="label">Course/Strand</div>
            <div class="value">: <span>--</span></div>
        </div>
    </div>
    </div>
    </div>


    <script src="{{ asset('js/scholar.js') }}"></script>
</body>

</html>
