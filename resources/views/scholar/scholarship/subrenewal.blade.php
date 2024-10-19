<!DOCTYPE html>
<html lang="en">

<head>
    <title>View Submitted Scholarship Renewal</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="{{ asset('css/partial.css') }}">
    <link rel="stylesheet" href="{{ asset('css/home.css') }}">
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
        <a href="" class="goback">&lt Go back</a>
        <div class="text-center">
            <h1 class="sub-title">Scholarship Renewal</h1>
        </div>

        <div class="ren-view">
            <div class="ren-status">
                <h5 class="ren-stat">PENDING</h5>
            </div>

            <div class="ren-info">
                <h4>PERSONAL INFORMATION</h4>
                <div class="info">
                    <div class="label">Date Submitted</div>
                    <div class="value">: <span>MM/DD/YYYY</span></div>

                    <div class="label">School Year</div>
                    <div class="value">: <span>--</span></div>

                    <div class="label">Full Name</div>
                    <div class="value">: <span>--</span></div>

                    <div class="label">Gender</div>
                    <div class="value">: <span>--</span></div>

                    <div class="label">Age</div>
                    <div class="value">: <span>--</span></div>

                    <div class="label">Birthdate</div>
                    <div class="value">: <span>--</span></div>

                    <div class="label">Email Address</div>
                    <div class="value">: <span>--</span></div>

                    <div class="label">Cellphone No./Landline</div>
                    <div class="value">: <span>--</span></div>

                    <div class="label">Occupation</div>
                    <div class="value">: <span>--</span></div>

                    <div class="label">Income</div>
                    <div class="value">: <span>--</span></div>

                    <div class="label">Facebook Name</div>
                    <div class="value">: <span>--</span></div>

                    <div class="label">Indigenous Member</div>
                    <div class="value">: <span>--</span></div>
                </div>

                <h4>ADDRESS INFORMATION</h4>
                <div class="info">
                    <div class="label">Home Address</div>
                    <div class="value">: <span>--</span></div>

                    <div class="label">Barangay</div>
                    <div class="value">: <span>--</span></div>

                    <div class="label">City</div>
                    <div class="value">: <span>--</span></div>
                </div>

                <h4>EDUCATIONAL BACKGROUND</h4>
                <div class="info">
                    <div class="label">Name of University</div>
                    <div class="value">: <span>--</span></div>

                    <div class="label">College Department</div>
                    <div class="value">: <span>--</span></div>

                    <div class="label">Incoming Year Level</div>
                    <div class="value">: <span>--</span></div>

                    <div class="label">Course</div>
                    <div class="value">: <span>--</span></div>

                    <div class="label">General Average Last Sem</div>
                    <div class="value">: <span>--</span></div>
                </div>

                <h4>FAMILY INFORMATION</h4>
                <div class="info">

                </div>

                <h4>OTHER INFORMATION</h4>
                <div class="info other">
                    <div class="label">Grant/Assistance from other Government and Non-Government scholarships, School
                        Discount (How much per sem?):</div>
                    <div class="value"><span>Lorem ipsum dolor sit amet consectetur, adipisicing elit. Quaerat, cumque
                            dolores! Fugit, illum commodi ea saepe sed velit debitis quos quae consectetur, ut in
                            veritatis odit earum natus corrupti? Beatae!</span></div>

                    <div class="label">Talents & Skills/ Honor and Recognition/ Extracurricular/Community
                        Involvement/Employment:</div>
                    <div class="value"><span>--</span></div>

                    <div class="label">What are your expectations from Tzu Chi Foundation?:</div>
                    <div class="value"><span>--</span></div>
                </div>

                <h4>Requirements Submission</h4>
                <div class="info">
                    <div class="label">1x1 ID Picture (Format: JPG or JPEG)</div>
                    <div class="value">: <span>file.pdf</span></div>

                    <div class="label">Scanned copy of latest Report Card</div>
                    <div class="value">: <span>--</span></div>

                    <div class="label">Scanned copy of latest Registration Form</div>
                    <div class="value">: <span>--</span></div>

                    <div class="label">Autobiography</div>
                    <div class="value">: <span>--</span></div>

                    <div class="label">Family Picture (Format: JPG or JPEG)</div>
                    <div class="value">: <span>--</span></div>

                    <div class="label">Picture of the inside and outside of the house (Format: JPG or JPEG)</div>
                    <div class="value">: <span>--</span></div>

                    <div class="label">Scanned copy of latest Utility Bills</div>
                    <div class="value">: <span>--</span></div>

                    <div class="label">Detailed Sketch Map of Home Address</div>
                    <div class="value">: <span>--</span></div>

                    <div class="label">Scanned copy latest ITR/ Official Pay Slip of parent/s (if applicable)</div>
                    <div class="value">: <span>--</span></div>

                    <div class="label">Barangay Certificate of Indigency</div>
                    <div class="value">: <span>--</span></div>
                </div>
            </div>
        </div>
    </div>


    <script src="{{ asset('js/scholar.js') }}"></script>
</body>

</html>
