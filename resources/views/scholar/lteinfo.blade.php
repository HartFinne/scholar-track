<!DOCTYPE html>
<html lang="en">

<head>
    <title>View LTE</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="styles/sidebar.css" rel="stylesheet">
    <link href="styles/overview.css" rel="stylesheet">
    <link href="styles/lteinfo.css" rel="stylesheet">
    <link rel="stylesheet" href="styles/navbar.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
</head>

<body>
    <!-- SIDEBAR -->
    <button id="btn-showmenu" onclick="showsidebar()"><i class="fas fa-bars"></i></button>
    <div class="ctn-sidebar" id="sidebar">
        <div class="ctn-options">
            <button id="btn-closemenu" onclick="hidesidebar()"><i class="fas fa-xmark"></i></button>
            <div class="menuoptions">
                <!-- Home -->
                <button type="button" onclick="window.location.href='schome.html';">Home</button>
                <!-- Scholarship -->
                <button type="button" onclick="togglesubopt1()" id="btnscholarship">Scholarship<i
                        class="fa-solid fa-caret-right"></i></button>
                <div class="subopt" id="subopt1" style="display: none;">
                    <a href="overview.html">Overview</a>
                    <a href="gradesub.html">Grades Submission</a>
                    <a href="sclte.html">Letter of Explanation</a>
                </div>
                <!-- HC -->
                <button type="button" onclick="window.location.href='schumanities.html';">Humanities Class</button>
                <!-- CS -->
                <button type="button" onclick="togglesubopt2()" id="btncs">Community 
                    Service<i class="fa-solid fa-caret-right"></i></button>
                <div class="subopt" id="subopt2" style="display: none;">
                    <a href="csdashboard.html">Dashboard</a>
                    <a href="csactivities.html">Activities</a>
                    <a href="csattendance.html">Attendance</a>
                </div>
                <!-- Allowance Requests -->
                <button type="button" onclick="togglesubopt3()" id="btnrequests">Allowance Requests<i
                    class="fa-solid fa-caret-right"></i></button>
                <div class="subopt" id="subopt3" style="display: none;">
                    <a href="scregular.html">Regular Allowance</a>
                    <a href="scspecial.html">Special Allowance</a>
                </div>
            </div>
        </div>
    </div>
    <script src="togglesidebar.js" defer></script>

    <!-- NAVBAR -->
    <div class="ctn-navbar">
        <div class="logo">
            <img src="/imgs/logo.png" alt="Logo">
            <h6 class="fw-bold">Tzu Chi Philippines<br>Educational Assistance Program</h6>
        </div>
        <button id="showprofmenu" onclick="showprofilemenu()"><i class="fas fa-user"></i></button>
    </div>

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
        <a href="" class="goback">&lt Go back</a>
        <div class="text-center">
            <h1 class="sub-title">Letter of Explanation</h1>
            
        </div>

        <div class="lte">
            <h5 class="text-center fw-bold">Buddhist Compassion Relief Tzu Chi Foundation Philippines, Inc.</h5>
            <p class="text-center">Educational Assistance Program</p>
            <p class="date" id="lte-date">Date</p>

            <div class="receipient">
                <p id="name">NAME</p>
                <p id="casecode">CASE CODE</p>
                <p id="school">SCHOOL</p>
            </div>

            <div class="lte-subject">
                <p>Subject: <b>NOTICE TO EXPLAIN</b> </p>
            </div>

            <div class="salutation-lte">
                <p>Greetings!</p>
            </div>
            <div class="lte-body">
                <p>
                    Last [DATE], was the [EVENT] that took place in the [LOCATION]. Upon checking the attendance,
                    we noticed that you did not participate despite the Foundation's effort to inform you beforehand.
                </p>
                <p>
                    In connection with this, you are advised to <b>submit your written explanation letter within
                    three (3) days of receipt of this notice.</b>
                </p><br>
                <p>Kindly give this matter your priority attention.</p><br>
            </div>

            <div class="closing-lte">
                <div class="closing-1">
                    <p>Sincerely,</p>
                    <div class="signature">
                        <p>SIGNATURE</p>
                    </div>
                    <p><b>Social Worker's Name</b><br>Social Welfare Officer</p>
                </div>
                <div class="closing-2">
                    <p>Noted by:</p>
                    <div class="signature">
                        <p>SIGNATURE</p>
                    </div>
                    <p><b>MARIA CRISTINA N. PASION, RSW</b><br>Department Head</p>
                </div>
            </div>
        </div>

        <div class="submit-lte text-center">
            <button type="button" class="btn-submit" onclick="window.location.href='lteform.html';">Submit your response here</button>
        </div>
    </div>
    
    <script src="toggleprofile.js"></script>
</body>

</html>