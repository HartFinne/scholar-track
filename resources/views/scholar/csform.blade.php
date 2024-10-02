<!DOCTYPE html>
<html lang="en">

<head>
    <title>Community Service - Attendance</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="{{ asset('css/cs.css') }}">
    <link rel="stylesheet" href="{{ asset('css/partial.css') }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <!-- Include Sidebar -->
    @include('partials._sidebar')

    <!-- Include Navbar -->
    @include('partials._navbar')

    <!-- MAIN -->
    <div class="ctn-main">
        <a href="{{ route('schome') }}" class="goback">&lt Go back</a>
        <h5 class="text-center fw-bold">Community Service</h5>
        <h1 class="text-center title">ATTENDANCE FORM</h1>

        <div class="cs-form">
            <form action="">
                <fieldset class="custom-fieldset">
                    <legend>Scholar's Information</legend>
                    <div class="row">
                        <div class="column">
                            <label for="fullName">Full Name</label>
                            <input type="text" id="fullName" value="JUAN DELA CRUZ" readonly>
                        </div>
                        <div class="column">
                            <label for="district">District</label>
                            <input type="text" id="district" value="MINDONG" readonly>
                        </div>
                    </div>
                    <div class="row">
                        <div class="column">
                            <label for="school">School</label>
                            <input type="text" id="school" value="POLYTECHNIC UNIVERSITY OF THE PHILIPPINES"
                                readonly>
                        </div>
                        <div class="column">
                            <label for="yearLevel">Year Level</label>
                            <input type="text" id="yearLevel" value="1ST YEAR" readonly>
                        </div>
                    </div>
                </fieldset>

                <fieldset class="custom-fieldset">
                    <legend>Community Service</legend>
                    <div class="row">
                        <div class="column">
                            <label for="activity">Activity</label>
                            <select name="" id="activity">
                                <option value="" disabled selected hidden>Select Activity</option>
                                <option value="1">Activity 1</option>
                                <option value="2">Activity 2</option>
                                <option value="3">Activity 3</option>
                            </select>
                        </div>
                        <div class="column">
                            <label for="location">Location</label>
                            <input type="text" id="location" value="Lorem Ipsum" readonly>
                        </div>
                    </div>
                    <div class="row">
                        <div class="column">
                            <label for="facilitator">Facilitator's Name</label>
                            <input type="text" id="facilitator" value="Lorem Ipsum" readonly>
                        </div>
                        <div class="column">
                            <label for="date">Date</label>
                            <input type="text" id="date" value="MM/DD/YYY" readonly>
                        </div>
                    </div>
                </fieldset>

                <fieldset class="custom-fieldset">
                    <legend>Attendance Information</legend>
                    <div class="row">
                        <div class="column">
                            <label for="timeIn">Time in</label>
                            <input type="time" id="timeIn" required>
                        </div>
                        <div class="column">
                            <label for="timeOut">Time out</label>
                            <input type="time" id="timeOut" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="column">
                            <label for="attendanceStatus">Attendance Status</label>
                            <select name="" id="attendanceStatus">
                                <option value="" disabled selected hidden>Select Status</option>
                                <option value="1">Present</option>
                                <option value="2">Late</option>
                                <option value="3">Left the Activity Early</option>
                            </select>
                        </div>
                        <div class="column">
                            <label for="hrSpent">Total Hours Spent</label>
                            <input type="text" id="hrSpent" value="2hrs" readonly>
                        </div>
                    </div>
                    <div class="row">
                        <div class="column">
                            <label for="proofImg">Proof of Attendance</label>
                            <input type="file" id="proofImg" required>
                        </div>
                    </div>
                </fieldset>
                <div class="submit text-center">
                    <button type="submit" class="btn-submit fw-bold">Submit</button>
                </div>
            </form>
        </div>
    </div>

    <script src="{{ asset('js/scholar.js') }}"></script>
</body>

</html>
