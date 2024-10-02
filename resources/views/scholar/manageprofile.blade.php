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

        <div class="profile-view">
            <div class="profile-info">
                <h4>BASIC INFORMATION</h4>
                <div class="info">
                    <div class="label">Case Code</div>
                    <div class="value">: <span>--</span></div>

                    <div class="label">Assigned Area</div>
                    <div class="value">: <span>Mindong</span></div>

                    <div class="label">Name</div>
                    <div class="value">: <span>Dela Cruz, Juan Santos</span></div>

                    <div class="label">Date of Birth</div>
                    <div class="value">: <span>January 1, 2002</span></div>

                    <div class="label">Email Address</div>
                    <div class="value">: <span>example@gmail.com</span></div>

                    <div class="label">Contact Number</div>
                    <div class="value">: <span>09XXXXXXXXX</span></div>
                </div>
            </div>
            <div class="profile-info">
                <h4>ADDRESS INFORMATION</h4>
                <div class="info">
                    <div class="label">Residential Address</div>
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

                    <div class="label">Year Level</div>
                    <div class="value">: <span>--</span></div>

                    <!-- <div class="label">Grade Level</div>
                    <div class="value">: <span>--</span></div> -->

                    <div class="label">Course/Strand</div>
                    <div class="value">: <span>--</span></div>

                    <!-- <div class="label">Strand</div>
                    <div class="value">: <span>--</span></div> -->
                </div>
            </div>
        </div>
    </div>


    <script src="{{ asset('js/scholar.js') }}"></script>
</body>

</html>
