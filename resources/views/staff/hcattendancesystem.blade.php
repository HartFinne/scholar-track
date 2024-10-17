<!DOCTYPE html>

<html lang="en">

<head>
    <title>Humanities Class | Attendance System</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" href="{{ asset('css/attendancesystem.css') }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script><!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script>
        window.onerror = function(message, source, lineno, colno, error) {
            alert('Error: ' + message + '\nSource: ' + source + '\nLine: ' + lineno);
        };
    </script>
</head>

<body style="background-color:#eaebea;">
    <!-- PAGE HEADER -->
    <button onclick="toggleexitdialog()" id="btnexit"><i class="fas fa-xmark"></i></button>
    <div class="header">
        <div class="ctnlogo">
            <img src="{{ asset('images/logo.png') }}" id="headerlogo" alt="logo.png">
        </div>
        <div class="headertitle">
            <span style="color: #2e7c55;">Tzu Chi Philippines</span>
            <span style="color: #1a5319;">Educational Assistance Program</span>
        </div>
    </div>
    <!-- MAIN CONTENT -->
    <div class="maincontent">
        <div class="ctnmaintitle">
            <span class="maintitle">HUMANITIES CLASS ATTENDANCE</span>
        </div>

        <div class="message">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert" id="success-alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert" id="error-alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
        </div>

        <div class="ctnform">
            <form method="POST" action="{{ route('savehcattendance', $event->hcid) }}">
                <div class="groupB">
                    <div class="groupB1">
                        <div class="groupB2">
                            <span class="infolabel">Topic</span>
                            <span>: <strong>{{ $event->topic }}</strong></span>
                        </div>
                        <div class="groupB2">
                            <span class="infolabel">Date</span>
                            <span>: <strong>{{ $event->hcdate }}</strong></span>
                        </div>
                    </div>
                    <div class="groupB1">
                        <div class="groupB2">
                            <span class="infolabel">Start Time</span>
                            <span>: <strong>{{ $event->hcstarttime }}</strong></span>
                        </div>
                        <div class="groupB2">
                            <span class="infolabel">End Time</span>
                            <span>: <strong>{{ $event->hcendtime }}</strong></span>
                        </div>
                    </div>
                </div>
                @csrf
                <div class="searchbar">
                    <span id="searchlabel">Search Name</span>
                    <select name="scholar" id="searchable-select" required>
                        <option value="" disabled selected hidden>Select your name</option>
                        @foreach ($scholars as $scholar)
                            <option value="{{ $scholar->caseCode }}">
                                {{ $scholar->caseCode }} | {{ $scholar->basicInfo->scLastname }},
                                {{ $scholar->basicInfo->scFirstname }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" id="btnconfirm">Confirm</button>
            </form>
        </div>
        <div class="ctnview">
            <button id="btnview" onclick="toggleviewdialog()">View List of Attendees</button>
        </div>
    </div>

    {{-- <!-- CONFIRMATION DIALOG -->
    <div class="ctndialog" id="confirmdialog" style="display: none;">
        <div class="groupA">
            <i class="dialogicon1 fas fa-circle-question"></i>
            <span id="label1">Are you sure this is you?</span>
            <span id="label2">"<span id="scholarname">JUAN DELA CRUZ</span>"</span>
            <span id="label3">This action cannot be undone.</span>

            <div class="groupA1">
                <button id="btnno" onclick="toggleconfirmationdialog()">No</button>
                <button id="btnyes">Yes</button>
            </div>
        </div>
    </div> --}}

    <!-- EXIT DIALOG -->
    <div class="ctndialog" id="exitdialog" style="display: none;">
        <div class="groupA">
            <form method="POST" action="{{ route('exitattendancesystem', ['hcid' => $event->hcid]) }}">
                @csrf
                <i class="dialogicon1 fas fa-circle-question"></i>
                <span id="label1">
                    Are you sure you want to exit?
                </span>
                <span id="label4">
                    Please enter your password to confirm.
                </span>
                <input type="password" placeholder="Password" name="password" class="inpassword" required>
                <span id="label3">This action cannot be undone.</span>

                <div class="groupA1">
                    <button type="button" id="btnno" onclick="toggleexitdialog()">No</button>
                    <button type="submit" id="btnyes">Yes</button>
                </div>
            </form>
        </div>
    </div>
    </div>

    <!-- VIEW ATTENDEES DIALOG -->
    <div class="ctndialog" id="savedialog" style="display: none;">
        <div class="groupA">
            <form method="POST" action="{{ route('viewhcattendees', ['hcid' => $event->hcid]) }}">
                @csrf
                <i class="dialogicon2 fas fa-circle-exclamation"></i>
                <span id="label2">
                    Restricted Section!
                </span>
                <span id="label4">This section is for authorized personnel only. Please enter your
                    password to continue.</span>
                <input type="password" placeholder="Password" name="password" class="inpassword" required>

                <div class="groupA1">
                    <button type="button" id="btncancel" onclick="toggleviewdialog()">Cancel</button>
                    <button type="submit" id="btnsubmit">Submit</button>
                </div>
            </form>
        </div>
    </div>

    <script src="{{ asset('js/hcattendancecontrol.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('#searchable-select').select2({
                placeholder: 'Last Name, First Name (Case Code)',
                allowClear: true
            });
        });

        document.addEventListener('DOMContentLoaded', function() {
            const successAlert = document.getElementById('success-alert');
            const errorAlert = document.getElementById('error-alert');

            if (successAlert) {
                setTimeout(() => {
                    successAlert.style.display = 'none';
                }, 3000);
            }

            if (errorAlert) {
                setTimeout(() => {
                    errorAlert.style.display = 'none';
                }, 3000);
            }
        });
    </script>
</body>

</html>
