<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appointment System</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="{{ asset('css/appointment.css') }}">
    <link rel="stylesheet" href="{{ asset('css/partial.css') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body>
    <!-- Include Sidebar -->
    @include('partials._sidebar')

    <!-- Include Navbar -->
    @include('partials._navbar')

    <div class="ctn-main">
        <h1 class="title">Appointment System</h1>

        <div class="row">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
        </div>

        <!-- APPOINTMENT FORM -->
        <div class="appointmentform">
            <div class="form-content">
                <div class="form-header">
                    <h6>Request Appointment</h6>
                </div>
                <div class="form-body">
                    <form id="appointmentForm" method="post" action="{{ route('makeappointment', $user->caseCode) }}">
                        @csrf
                        <div class="form-group">
                            <label for="subject">Reason</label>
                            <select id="subject" name="reason" required>
                                <option value="" disabled selected hidden>Select a reason</option>
                                <option value="Submission of Grades">Submission of Grades</option>
                                <option value="Submission of Renewal Requirements">Submission of Renewal Requirements
                                </option>
                                <option value="Submission of LTE Requirements">Submission of LTE Requirements</option>
                                <option value="Submission of Regular Allowance Form & Requirements">Submission of
                                    Regular Allowance Form & Requirements</option>
                                <option value="Submission of Special Allowance Requirements">Submission of Special
                                    Allowance Requirements</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="date">Date</label>
                            <input type="date" name="date" id="date" required
                                min="{{ date('Y-m-d', strtotime('+1 day')) }}">
                        </div>

                        <label class="fw-bold">Time</label>
                        <div class="time-selection">
                            <div class="time">
                                <input type="radio" id="7-8" name="time" value="7:00 AM - 8:00 AM">
                                <label for="7-8"> 7:00 AM - 8:00 AM</label>
                            </div>
                            <div class="time">
                                <input type="radio" id="8-9" name="time" value="8:00 AM - 9:00 AM">
                                <label for="8-9"> 8:00 AM - 9:00 AM</label>
                            </div>
                        </div>
                        <div class="time-selection">
                            <div class="time">
                                <input type="radio" id="9-10" name="time" value="9:00 AM - 10:00 AM">
                                <label for="9-10"> 9:00 AM - 10:00 AM</label>
                            </div>
                            <div class="time">
                                <input type="radio" id="10-11" name="time" value="10:00 AM - 11:00 AM">
                                <label for="10-11"> 10:00 AM - 11:00 AM</label>
                            </div>
                        </div>

                        <div class="time-selection">
                            <div class="time">
                                <input type="radio" id="1-2" name="time" value="1:00 PM - 2:00 PM">
                                <label for="1-2"> 1:00 PM - 2:00 PM</label>
                            </div>
                            <div class="time">
                                <input type="radio" id="2-3" name="time" value="2:00 PM - 3:00 PM">
                                <label for="2-3"> 2:00 PM - 3:00 PM</label>
                            </div>
                        </div>
                        <div class="time-selection">
                            <div class="time">
                                <input type="radio" id="3-4" name="time" value="3:00 PM - 4:00 PM">
                                <label for="3-4"> 3:00 PM - 4:00 PM</label>
                            </div>
                            <div class="time">
                                <input type="radio" id="4-5" name="time" value="4:00 PM - 5:00 PM">
                                <label for="4-5"> 4:00 PM - 5:00 PM</label>
                            </div>
                        </div>

                        <div class="form-footer">
                            <button type="submit" class="btn-submit" id="submitBtn">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- APPOINTMENTS -->
        <div class="status">
            <p class="table-title">APPOINTMENT STATUS: </p>
            <div class="filter">
                <form action="{{ route('appointment') }}" method="GET" id="filter-form">
                    <button type="submit" name="status" value="all" class="filter-btn {{ request('status', 'all') == 'all' ? 'active' : '' }}">All</button>
                    <button type="submit" name="status" value="Pending" class="filter-btn {{ request('status') == 'Pending' ? 'active' : '' }}">Pending</button>
                    <button type="submit" name="status" value="Approved" class="filter-btn {{ request('status') == 'Approved' ? 'active' : '' }}">Approved</button>
                    <button type="submit" name="status" value="Completed" class="filter-btn {{ request('status') == 'Completed' ? 'active' : '' }}">Completed</button>
                    <button type="submit" name="status" value="Rejected" class="filter-btn {{ request('status') == 'Rejected' ? 'active' : '' }}">Rejected</button>
                    <button ctype="submit" name="status" value="Cancelled" class="filter-btn {{ request('status') == 'Cancelled' ? 'active' : '' }}">Cancelled</button>
                </form>
            </div>
        </div>

        <div class="ctn-table table-responsive">
            <table class="table table-bordered">
                <thead class="table-success">
                    <tr>
                        <th class="text-center align-middle">ID</th>
                        <th class="text-center align-middle">Agenda</th>
                        <th class="text-center align-middle">Schedule</th>
                        <th class="text-center align-middle">Status</th>
                        <th class="text-center align-middle">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($userappointments as $index => $appointment)
                        <tr>
                            <td class="text-center align-middle">{{ $index + 1 }}</td>
                            <td class="text-center align-middle">{{ $appointment->reason }}</td>
                            <td class="text-center align-middle">
                                {{ \Carbon\Carbon::parse($appointment->date)->format('F d, Y') }}
                                <br>{{ $appointment->time }}
                            </td>
                            <td class="text-center align-middle">{{ $appointment->status }}</td>
                            <td class="text-center align-middle">
                                <a href="{{ route('appointmentinfo', $appointment->id) }}"
                                    class="btn-view">View</a><br>
                                @if ($appointment->status != 'Cancelled')
                                    <button class="btn-cancel" id="cancel"
                                        onclick="showDialog('confirmDialog', {{ $appointment->id }})">Cancel</button>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- CANCEL APPOINTMENT DIALOG -->
    <div id="confirmDialog" class="dialog hidden">
        <div class="dialog-content">
            <span class="close-btn" onclick="closeDialog('confirmDialog')"><i class="fa-solid fa-x"></i></span>
            <i class="fa-solid fa-circle-exclamation"></i>
            <h2>Are you sure?</h2>
            <p>Do you really want to cancel your appointment? This action cannot be undone.</p>
            <div class="row d-flex justify-content-around">
                <div class="col-md-4">
                    <a class="btn btn-dark w-100" onclick="closeDialog('confirmDialog')">No</a>
                </div>
                <div class="col-md-4">
                    <a id="confirmYes" class="btn btn-danger w-100">Yes</a> <!-- Dynamic link set in JavaScript -->
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/scholar.js') }}"></script>
    <script>
        // Function to show the dialog
        function showDialog(dialogId, id) {
            // Show the dialog
            document.getElementById(dialogId).classList.remove('hidden');

            // Set the "Yes" button's href to the route with the appointment ID
            const yesButton = document.getElementById('confirmYes');
            yesButton.href = `/scholar/cancel-appointment/${id}`; // Adjust this path as needed
        }

        // Function to close the dialog
        function closeDialog(dialogId) {
            document.getElementById(dialogId).classList.add('hidden');
        }

        // Function to handle cancel action and show second dialog
        function showCancelDialog() {
            closeDialog('confirmDialog');
            showDialog('cancelDialog');
        }
    </script>
</body>

</html>
