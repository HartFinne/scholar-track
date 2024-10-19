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
    <link rel="stylesheet" href="{{ asset('css/appointment.css') }}">
    <link rel="stylesheet" href="{{ asset('css/partial.css') }}">
</head>

<body>
    <!-- Include Sidebar -->
    @include('partials._sidebar')

    <!-- Include Navbar -->
    @include('partials._navbar')

        <div class="ctn-main">
            <a href="" class="goback">&lt Go back</a>
            <h1 class="title">Appointment System</h1>

            <!-- APPOINTMENT FORM -->
            <div class="appointmentform">
                <div class="form-content">
                    <div class="form-header">
                        <h6>Request Appointment</h6>
                    </div>
            
                    <div class="form-body">
                        <form id="appointmentForm">
                            <div class="form-group">
                                <label for="subject">Reason</label>
                                <select id="subject" required>
                                    <option value="" disabled selected hidden>Select a reason</option>
                                    <option value="Submission of Grades">Submission of Grades</option>
                                    <option value="Submission of Renewal Requirements">Submission of Renewal Requirements</option>
                                    <option value="Submission of LTE Requirements">Submission of LTE Requirements</option>
                                    <option value="Submission of Regular Allowance Form & Requirements">Submission of Regular Allowance Form & Requirements</option>
                                    <option value="Submission of Special Allowance Requirements">Submission of Special Allowance Requirements</option>
                                </select>
                            </div>
            
                            <div class="form-group">
                                <label for="date">Date</label>
                                <input type="date" name="date" id="date" required>
                            </div>
    
                            <label class="fw-bold">Time</label>
                            <div class="time-selection">
                                <div class="time">
                                    <input type="radio" id="7-8" name="time" value="7-8">
                                    <label for="7-8"> 7:00 AM - 8:00 AM</label>
                                </div>
                                <div class="time">
                                    <input type="radio" id="8-9" name="time" value="8-9">
                                    <label for="8-9"> 8:00 AM - 9:00 AM</label>
                                </div>                                 
                            </div>
                            <div class="time-selection">
                                <div class="time">
                                    <input type="radio" id="9-10" name="time" value="9-10">
                                    <label for="9-10"> 9:00 AM - 10:00 AM</label>
                                </div>
                                <div class="time">
                                    <input type="radio" id="10-11" name="time" value="10-11">
                                    <label for="10-11"> 10:00 AM - 11:00 AM</label>
                                </div>
                            </div>
                        
                            <div class="time-selection">                              
                                <div class="time">
                                    <input type="radio" id="1-2" name="time" value="1-2">
                                    <label for="1-2"> 1:00 PM - 2:00 PM</label>
                                </div>
                                <div class="time">
                                    <input type="radio" id="2-3" name="time" value="2-3">
                                    <label for="2-3"> 2:00 PM - 3:00 PM</label>
                                </div>                                   
                            </div>
                            <div class="time-selection">
                                <div class="time">
                                    <input type="radio" id="3-4" name="time" value="3-4">
                                    <label for="3-4"> 3:00 PM - 4:00 PM</label>
                                </div>
                                <div class="time">
                                    <input type="radio" id="4-5" name="time" value="4-5">
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
                    <button class="filter-btn">All</button>
                    <button class="filter-btn">Pending</button>
                    <button class="filter-btn">Approved</button>
                    <button class="filter-btn">Completed</button>
                    <button class="filter-btn">Rejected</button>
                    <button class="filter-btn">Cancelled</button>
                </div>
            </div>   
        
            <div class="ctn-table table-responsive">
                <table class="table table-bordered">
                    <thead class="table-success">
                        <tr>
                            <th class="text-center align-middle">ID</th>
                            <th class="text-center align-middle">Appointment</th>
                            <th class="text-center align-middle">Schedule</th>
                            <th class="text-center align-middle">Status</th>
                            <th class="text-center align-middle">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td>--</td>
                            <td>MM/DD/YYYY<br>TIME</td>
                            <td>Completed</td>
                            <td><a href="{{ route('appointmentinfo') }}" class="btn-view">View</a><br>
                                <button class="btn-cancel" id="cancel" onclick="showDialog('confirmDialog')">Cancel</button></td>
                        </tr>
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
                <div class="dialog-actions">
                    <button id="noBtn" onclick="closeDialog('confirmDialog')">No</button>
                    <button id="yesBtn" onclick="showCancelDialog()">Yes</button>
                </div>
            </div>
        </div>

        <div id="cancelDialog" class="dialog hidden">
            <div class="dialog-content">
                <span class="close-btn" onclick="closeDialog('cancelDialog')"><i class="fa-solid fa-x"></i></span>
                <i class="fa-solid fa-circle-check"></i>
                <h2>Your appointment has been successfully canceled.</p>
            </div>
        </div>

        

    <script src="{{ asset('js/scholar.js') }}"></script>
    <script>
        // Function to show the dialog
        function showDialog(dialogId) {
            document.getElementById(dialogId).classList.remove('hidden');
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
