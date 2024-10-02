<!DOCTYPE html>
<html lang="en">

<head>
    <title>Community Service - Dashboard</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="{{ asset('css/scholar2.css') }}">
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
        <h1 class="title">Community Service</h1>
        <p class="title-desc">Welcome, Scholar! Monitor your community service effectively.</p>
        <hr>
        <div class="cs-dashboard">
            <div class="complete-hrs">
                <h5><i class="fa-solid fa-circle-check"></i>TOTAL HOURS COMPLETED</h5>
                <h4>0</h4>
            </div>
            <div class="remaining-hrs">
                <h5><i class="fa-solid fa-clock-rotate-left"></i>REMAINING HOURS</h5>
                <h4>0</h4>
            </div>
        </div>

        <div class="cshours-graph">
            <div class="card1">
                <p>Number of Hours Completed per Month</p>
            </div>
            <div class="card2">
                <p>Number of Hours Completed per Activity</p>
            </div>
        </div>

        <hr>
        <p class="table-title">My Scheduled Volunteer Commitments</p>
        <div class="ctn-table table-responsive">
            <table class="table table-bordered" id="table">
                <thead>
                    <tr>
                        <th class="text-center align-middle" style="width: 35%">Activity</th>
                        <th class="text-center align-middle" style="width: 35%">Location</th>
                        <th class="text-center align-middle" style="width: 20%">Schedule</th>
                        <th class="text-center align-middle">Action</th>

                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Lorem ipsum, dolor sit amet consectetur adipisicing elit.</td>
                        <td class="text-start"><b>Meeting Place:</b> Lorem ipsum, dolor sit amet consectetur adipisicing
                            elit.<br><br>
                            <b>Activity Place:</b> Lorem ipsum, dolor sit amet consectetur adipisicing elit.
                        </td>
                        <td class="text-start"><b>Call Time:</b> 08:00 AM<br><br>
                            <b>Date:</b> 10/1/2024<br>
                            <b>Time:</b> 10:00 AM
                        </td>
                        <td><a href="#" id="cancel">Cancel</a></td>
                </tbody>
            </table>
        </div>
    </div>

    <script src="{{ asset('js/scholar.js') }}"></script>
</body>

</html>
