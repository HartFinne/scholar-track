<!DOCTYPE html>
<html lang="en">

<head>
    <title>Community Service - Attendance</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="{{ asset('css/sccommunity.css') }}">
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
    <x-alert />

    <!-- MAIN -->
    <div class="ctn-main">
        <a href="{{ route('schome') }}" class="goback">&lt Go back</a>
        <h5 class="text-center fw-bold">Community Service</h5>
        <h1 class="text-center title">ATTENDANCE FORM</h1>

        <div class="cs-form">

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <form action="{{ route('csform.post') }}" method="post" enctype="multipart/form-data">
                @csrf
                <fieldset class="custom-fieldset">
                    <legend>Scholar's Information</legend>
                    <div class="row">
                        <div class="column">
                            <label for="fullName">Full Name</label>
                            <input type="text" id="fullName" name="fullName"
                                value="{{ old('basicInfo', $data->basicInfo->scFirstname . ' ' . $data->basicInfo->scLastname) }}"
                                readonly>
                        </div>
                        <div class="column">
                            <label for="district">District</label>
                            <input type="text" id="district" name="district"
                                value="{{ old('scholarshipinfo', $data->scholarshipinfo->area) }}" readonly>
                        </div>
                    </div>
                    <div class="row">
                        <div class="column">
                            <label for="school">School</label>
                            <input type="text" id="school" name="schoolName"
                                value="{{ old('education', $data->education->scSchoolName) }}" readonly>
                        </div>
                        <div class="column">
                            <label for="yearLevel">Year Level</label>
                            <input type="text" id="yearLevel" name="schoolYear"
                                value="{{ old('education', $data->education->scYearGrade) }}" readonly>
                        </div>
                    </div>
                </fieldset>

                <fieldset class="custom-fieldset">
                    <legend>Community Service</legend>
                    <div class="row">
                        <div class="column">
                            <label for="activity">Activity</label>
                            <select name="csid" id="activity" required>
                                <option value="" disabled selected hidden>Select Activity</option>
                                @foreach ($csRecord as $cs)
                                    <option value="{{ $cs->csid }}" data-location="{{ $cs->eventloc }}"
                                        data-facilitator="{{ $cs->facilitator }}" data-date="{{ $cs->eventdate }}"
                                        data-starttime="{{ $cs->starttime }}">
                                        {{ $cs->title }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="column">
                            <label for="location">Location</label>
                            <input type="text" id="location" value="" readonly>
                        </div>
                    </div>
                    <div class="row">
                        <div class="column">
                            <label for="facilitator">Facilitator's Name</label>
                            <input type="text" id="facilitator" value="" readonly>
                        </div>
                        <div class="column">
                            <label for="date">Date</label>
                            <input type="text" id="date" value="" readonly>
                        </div>
                    </div>
                </fieldset>

                <script>
                    document.getElementById('activity').addEventListener('change', function() {
                        var selectedOption = this.options[this.selectedIndex];
                        var location = selectedOption.getAttribute('data-location');
                        var facilitator = selectedOption.getAttribute('data-facilitator');
                        var date = selectedOption.getAttribute('data-date');

                        document.getElementById('location').value = location;
                        document.getElementById('facilitator').value = facilitator;
                        document.getElementById('date').value = date;
                    });
                </script>


                <fieldset class="custom-fieldset">
                    <legend>Attendance Information</legend>
                    <div class="row">
                        <div class="column">
                            <label for="timeIn">Time in</label>
                            <input type="time" id="timeIn" name="timeIn" required>
                        </div>
                        <div class="column">
                            <label for="timeOut">Time out</label>
                            <input type="time" id="timeOut" name="timeOut" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="column">
                            <label for="attendanceStatus">Attendance Status</label>
                            <input name="attendanceStatus" id="attendanceStatus" value='' placeholder="--"
                                readonly>
                        </div>
                        <div class="column">
                            <label for="hrSpent">Total Hours Spent</label>
                            <input type="text" id="hrSpent" name="hrSpent" readonly>
                        </div>
                    </div>
                    <div class="row">
                        <div class="column">
                            <label for="proofImg">Proof of Attendance</label>
                            <input type="file" id="proofImg" name="proofImg" accept="image/jpeg, image/png"
                                required>
                            <small class="fst-italic text-muted">Accepted file types: JPG, JPEG, or PNG.</small>
                        </div>
                    </div>
                </fieldset>

                <script>
                    function calculateHours() {
                        var timeIn = document.getElementById('timeIn').value;
                        var timeOut = document.getElementById('timeOut').value;
                        var hrSpent = document.getElementById('hrSpent');
                        var attendanceStatus = document.getElementById('attendanceStatus');

                        var activitySelect = document.getElementById('activity');
                        var starttime = activitySelect.selectedOptions[0].dataset.starttime;

                        if (!timeIn || !timeOut || !starttime) {
                            hrSpent.value = '';
                            attendanceStatus.value = '--';
                            return;
                        }

                        // Parse start, timeIn, and timeOut values as Date objects for comparison
                        var scheduledStartTime = new Date('1970-01-01T' + starttime + 'Z');
                        var actualTimeIn = new Date('1970-01-01T' + timeIn + 'Z');
                        var actualTimeOut = new Date('1970-01-01T' + timeOut + 'Z');
                        var scheduledEndTime = new Date(scheduledStartTime.getTime() + 2 * 60 * 60 * 1000);

                        // Calculate time difference in milliseconds for hours spent
                        var diff = actualTimeOut - scheduledStartTime;
                        var hours = Math.floor(diff / (1000 * 60 * 60)); // Rounded down to the nearest whole number

                        hrSpent.value = hours > 0 ? hours : 0;

                        // Determine attendance status
                        if (actualTimeIn > scheduledStartTime) {
                            attendanceStatus.value = "Late";
                        } else if (actualTimeOut < scheduledEndTime) {
                            attendanceStatus.value = "Left Early";
                        } else {
                            attendanceStatus.value = "Present";
                        }
                    }

                    // Event listeners for changes in timeIn and timeOut fields
                    document.getElementById('timeIn').addEventListener('change', calculateHours);
                    document.getElementById('timeOut').addEventListener('change', calculateHours);
                </script>

                <div class="submit text-center">
                    <button type="submit" class="btn-submit fw-bold">Submit</button>
                </div>
            </form>
        </div>
    </div>

    <script src="{{ asset('js/scholar.js') }}"></script>
</body>

</html>
