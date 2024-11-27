<!DOCTYPE html>
<html lang="en">

<head>
    <title>Community Service - Dashboard</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="{{ asset('css/overview.css') }}">
    <link rel="stylesheet" href="{{ asset('css/sccommunity.css') }}">
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
    <x-alert />

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
                <h4>{{ $totalHoursSpent }}</h4>
            </div>
            <div class="remaining-hrs">
                <h5><i class="fa-solid fa-clock-rotate-left"></i>REMAINING HOURS</h5>
                <h4>{{ $remainingHours }}</h4>
            </div>
        </div>

        <div class="cshours-graph">
            <div class="card1">
                <p>Number of Hours Completed per Month</p>
                <canvas id="myChart1"></canvas>
            </div>
            <div class="card2">
                <p>Number of Hours Completed per Activity</p>
                <canvas id="myChart"></canvas>
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
                        <th class="text-center align-middle">Status</th>
                        <th class="text-center align-middle">Action</th>

                    </tr>
                </thead>
                <tbody>
                    @foreach ($registrations as $registration)
                        <tr>
                            <td>{{ $registration->title }}</td>
                            <td class="text-start"><b>Meeting Place:</b> {{ $registration->meetingplace }}<br><br>
                                <b>Activity Place:</b> {{ $registration->eventloc }}
                            </td>
                            <td class="text-start"><b>Call Time:</b>
                                {{ \Carbon\Carbon::parse($registration->calltime)->format('h:i A') }}<br><br>
                                <b>Date:</b> {{ \Carbon\Carbon::parse($registration->eventdate)->format('m/d/Y') }}<br>
                                <b>Time:</b> {{ \Carbon\Carbon::parse($registration->starttime)->format('h:i A') }}
                            </td>
                            <td>
                                {{ $registration->registatus }}
                            </td>
                            <td>{{-- Cancel button form --}}
                                @if ($registration->registatus == 'Going')
                                    <form action="{{ route('csdashboard.cancel', ['csid' => $registration->csid]) }}"
                                        method="POST" id="cancel-form-{{ $registration->csid }}">
                                        @csrf
                                        <button type="button" class="btn btn-danger"
                                            onclick="openConfirmDialog('{{ $registration->csid }}')">Cancel</button>
                                    </form>
                                @else
                                @endif
                            </td>

                        </tr>
                    @endforeach

                </tbody>
            </table>
        </div>

        <!-- Cancel registration confirmation dialog -->
        <div id="confirmDialog" class="dialog hidden">
            <div class="dialog-content">
                <span class="close-btn" onclick="closeDialog('confirmDialog')"><i class="fa-solid fa-x"></i></span>
                <i class="fa-solid fa-circle-exclamation"></i>
                <h2>Are you sure?</h2>
                <p>Do you really want to cancel your registration? This action cannot be undone.</p>
                <div class="dialog-actions">
                    <button id="noBtn" onclick="closeDialog('confirmDialog')">No</button>
                    <button id="yesBtn">Yes</button>
                </div>
            </div>
        </div>

        <!-- Success dialog -->
        <div id="cancelDialog" class="dialog hidden">
            <div class="dialog-content">
                <span class="close-btn" onclick="closeDialog('cancelDialog')"><i class="fa-solid fa-x"></i></span>
                <i class="fa-solid fa-circle-check"></i>
                <h2>Your registration has been canceled.<br>Your attendance will be marked as absent.</h2>
                <p>Kindly submit a Letter of Explanation about your absence. Thank You.</p>
            </div>
        </div>

        <script>
            function openConfirmDialog(csid) {
                // Show the confirmation dialog
                const confirmDialog = document.getElementById('confirmDialog');
                confirmDialog.classList.remove('hidden');

                // Handle the Yes button to submit the form
                const yesBtn = document.getElementById('yesBtn');
                yesBtn.onclick = function() {
                    // Submit the cancel form for the specific registration
                    document.getElementById(`cancel-form-${csid}`).submit();
                };
            }

            function closeDialog(dialogId) {
                // Hide the dialog by adding the 'hidden' class
                const dialog = document.getElementById(dialogId);
                dialog.classList.add('hidden');
            }

            function showCancelDialog() {
                // Close the confirmation dialog
                closeDialog('confirmDialog');

                // Show the success dialog
                const cancelDialog = document.getElementById('cancelDialog');
                cancelDialog.classList.remove('hidden');
            }
        </script>

        @if (session('success'))
            <script>
                document.addEventListener("DOMContentLoaded", function() {
                    // Automatically show the success dialog after page load if session success exists
                    showCancelDialog();
                });
            </script>
        @endif

        <script src="{{ asset('js/scholar.js') }}"></script>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

        <script>
            const ctx1 = document.getElementById('myChart1').getContext('2d');
            const months = @json($hoursPerMonth->pluck('month'));
            const monthlyHoursData = @json($hoursPerMonth->pluck('total_hours'));

            new Chart(ctx1, {
                type: 'bar',
                data: {
                    labels: months, // Month names on the x-axis (e.g., 'January', 'February')
                    datasets: [{
                        label: 'Hours',
                        data: monthlyHoursData, // Hours data for each month
                        borderWidth: 1,
                        borderColor: 'darkblue', // Bar border color changed to dark blue
                        backgroundColor: 'rgba(0, 0, 139, 0.8)', // Bar fill color set to dark blue with transparency
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,

                            }
                        },
                        x: {
                            title: {
                                display: true,

                            }
                        }
                    }
                }
            });
        </script>

        <script>
            const ctx = document.getElementById('myChart').getContext('2d');
            const activityTitles = @json($hoursPerActivity->pluck('title'));
            const hoursData = @json($hoursPerActivity->pluck('total_hours'));

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: activityTitles, // Activity titles on the x-axis
                    datasets: [{
                        label: 'Hours',
                        data: hoursData, // Hours data for each activity
                        borderWidth: 1,
                        borderColor: 'darkgreen', // Bar border color changed to dark green
                        backgroundColor: 'rgba(0, 100, 0, 0.8)', // Bar fill color set to a dark green with transparency
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,

                            }
                        },
                        x: {
                            title: {
                                display: true,
                            }
                        }
                    }
                }
            });
        </script>
</body>

</html>
