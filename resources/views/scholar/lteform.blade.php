<!DOCTYPE html>
<html lang="en">

<head>
    <title>Scholarship Overview</title>
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

        <div class="lte-form">
            <div class="lteform-title text-center">
                <h3 class="form-heading fw-bold">LTE Submission</h3>
                <p class="form-desc"><i>You must submit within 3 days after the receipt of the letter</i></p>
            </div>
            <div class="form-body">
                <div class="richtextarea">
                    <p>Explanation Letter</p>
                    
                </div>
                <p><b>Select your reason:</b></p>
                <div class="lte-reason">
                    <div class="reason1">
                        <div class="rad">
                            <input type="radio" id="medical" name="reason" value="Medical" onclick="toggleFileInputs('medical')">
                            <label for="medical">Medical</label>
                        </div>
                        <div class="rad">
                            <input type="radio" id="academic" name="reason" value="Academic Activity" onclick="toggleFileInputs('academic')">
                            <label for="academic">Academic Activity</label>
                        </div>
                    </div>
                    <div class="reason2">
                        <div class="rad">
                            <input type="radio" id="death" name="reason" value="Death of an Immediate Family Member" onclick="toggleFileInputs('death')">
                            <label for="death">Death of an Immediate Family Member</label>
                        </div>
                        <div class="rad">
                            <input type="radio" id="disaster" name="reason" value="Natural and Human induced disasters" onclick="toggleFileInputs('disaster')">
                            <label for="disaster">Natural and Human induced disasters</label>
                        </div>
                    </div>
                </div>

                <p><b>Please upload here the necessary document:</b></p>
                <div class="file-upload">
                    <div class="file1">
                        <div class="medfile">
                            <label for="medical-file">Photocopy of Medical or Doctor's Certificate</label><br>
                            <input type="file" id="medical-file" disabled>
                        </div>
                        <div class="acadfile">
                            <label for="academic-file">Duly Signed Letter<br>(School Official/Chairperson/Professor)</label><br>
                            <input type="file" id="academic-file" disabled>
                        </div>
                    </div>
                    <div class="file2">
                        <div class="deathfile">
                            <label for="death-file">Photocopy of Death Certificate</label><br>
                            <input type="file" id="death-file" disabled>
                        </div>
                        <div class="disfile">
                            <label for="disaster-file">Proof of Calamity<br>(Photo/News Clipping/LGU Declaration)</label><br>
                            <input type="file" id="disaster-file" disabled>
                        </div>
                    </div>
                </div>
            </div>
            <div class="agreement">
                <input type="checkbox" id="agreement">
                <label for="agreement"><i>I hereby attest that the information I have provided is true and correct. 
                    I also give my consent to Tzu Chi Foundation to obtain, retain and verify my letter.</i></label>
            </div>
            
            <div class="submit-lte text-center">
                <button type="submit" class="btn-submit fw-bold m-4">Submit</button>
            </div>
        </div>

        
    </div>
    
    <script src="toggleprofile.js"></script>

    <script>
        function toggleFileInputs(reason) {
            // Disable all file inputs initially
            document.getElementById('medical-file').disabled = true;
            document.getElementById('academic-file').disabled = true;
            document.getElementById('death-file').disabled = true;
            document.getElementById('disaster-file').disabled = true;
    
            // Enable file inputs based on the selected reason
            if (reason === 'medical') {
                document.getElementById('medical-file').disabled = false;
            } else if (reason === 'academic') {
                document.getElementById('academic-file').disabled = false;
            } else if (reason === 'death') {
                document.getElementById('death-file').disabled = false;
            } else if (reason === 'disaster') {
                document.getElementById('disaster-file').disabled = false;
            }
        }
    </script>
</body>

</html>