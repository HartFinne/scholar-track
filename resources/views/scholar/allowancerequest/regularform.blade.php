<!DOCTYPE html>
<html lang="en">

<head>
    <title>Regular Allowance Form</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="{{ asset('css/regularform.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/partial.css') }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<body>
    <!-- Include Sidebar -->
    @include('partials._sidebar')

    <!-- Include Navbar -->
    @include('partials._navbar')

    <!-- MAIN -->
    <div class="ctn-main">
        <a href="" class="goback">&lt Go back</a>
        <h1 class="text-center title">ALLOWANCE REQUEST FORM</h1>
        <p class="mt-4 mb-5 description"> Please fill out the required fields
            in each section with true and correct information. If a field does not apply to you, write <strong>Not
                Applicable</strong>.
        </p>

        <x-alert />

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="form">
            <form action="{{ route('regularform.post') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <fieldset class="custom-fieldset">
                    <legend>Scholar's Information</legend>
                    <div class="row">
                        <div class="column">
                            <label for="fullName">Name</label>
                            <input type="text" id="fullName" name="fullName"
                                value="{{ $data->basicInfo->scFirstname }} {{ $data->basicInfo->scMiddlename }} {{ $data->basicInfo->scLastname }}"
                                readonly>
                        </div>
                        <div class="column">
                            <label for="dateSubmitted">Date Submitted</label>
                            <input type="date" id="dateSubmitted" name="dateSubmitted" readonly
                                value="<?php echo date('Y-m-d'); ?>">
                        </div>
                    </div>
                    <div class="row">
                        <div class="column">
                            <label for="school">School</label>
                            <input type="text" id="school" name="school"
                                value="{{ $data->education->scSchoolName }}" readonly>
                        </div>
                    </div>
                    <div class="row">
                        <div class="column">
                            <label for="category">School Category</label>
                            <input type="text" id="category" name="category"
                                value="{{ $data->education->scSchoolLevel }}" required readonly>
                        </div>
                        <div class="column">
                            <label for="contactNo">Contact No.</label>
                            <input type="tel" id="contactNo" name="contactNo" value="{{ $data->scPhoneNum }}"
                                readonly>
                        </div>
                    </div>
                    <div class="row">
                        <div class="column">
                            <label for="yrLevel">Year Level</label>
                            <input type="text" id="yrLevel" name="yrLevel"
                                value="{{ $data->education->scYearGrade }}" readonly>
                        </div>
                        <div class="column">
                            <label for="course">Course</label>
                            <input type="text" id="course" name="course"
                                value="{{ $data->education->scCourseStrandSec }}" readonly>
                        </div>
                    </div>
                    <div class="row">
                        <div class="column">
                            <label for="homeAddress">Home Address</label>
                            <input type="text" id="homeAddress" name="homeAddress"
                                value="{{ $data->addressinfo->scPermanent }}" readonly>
                        </div>
                    </div>
                    <div class="row">
                        <div class="column">
                            <label for="boardAddress">Boarding House Address (If applicable)</label>
                            <input type="text" id="boardAddress" name="boardAddress" value="">
                        </div>
                    </div>
                    <div class="row">
                        <div class="column">
                            <label for="sem">Semester</label>
                            <select name="sem" id="sem" required>
                                <option value="" selected hidden>Select semester</option>
                                @foreach ($availableSemesters as $semester)
                                    <option value="{{ $semester['gid'] }}">{{ $semester['SemesterQuarter'] }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="column">
                            <label for="startSem">Start of Semester</label>
                            <input type="date" id="startSem" name="startSem" value="" required>
                        </div>
                        <div class="column">
                            <label for="endSem">End of Semester</label>
                            <input type="date" id="endSem" name="endSem" value="" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="column">
                            <label for="startOjt">Start of OJT</label>
                            <input type="date" id="startOjt" name="startOjt" value="">
                        </div>
                        <div class="column">
                            <label for="endOjt">End of OJT</label>
                            <input type="date" id="endOjt" name="endOjt" value="">
                        </div>
                    </div>
                </fieldset>

                <h5 class="fw-bold" style="color: #1a5319;">CLASS SCHEDULE (REFERENCE ONLY) </h5>
                <div class="row">
                    <div class="column">
                        <label for="regForm">Registration Form</label>
                        <input type="file" class="file" name="regForm" id="regForm" required>
                    </div>
                </div>

                <div class="ctn-table table-responsive">
                    <table class="table table-bordered" id="table">
                        <thead>
                            <tr>
                                <th class="text-center align-middle">TIME</th>
                                <th class="text-center align-middle">MON</th>
                                <th class="text-center align-middle">TUE</th>
                                <th class="text-center align-middle">WED</th>
                                <th class="text-center align-middle">THU</th>
                                <th class="text-center align-middle">FRI</th>
                                <th class="text-center align-middle">SAT</th>
                                <th class="text-center align-middle">SUN</th>
                            </tr>
                        </thead>
                        <tbody id="tableBody">
                            <tr>
                                <td><input type="text" id="time" name="time[]"
                                        placeholder="ex: 7:00 AM - 8:00 AM" required></td>
                                <td><input type="text" class="sub" id="mon" name="mon[]"
                                        placeholder="Course Code"></td>
                                <td><input type="text" class="sub" id="tue" name="tue[]"
                                        placeholder="Course Code"></td>
                                <td><input type="text" class="sub" id="wed" name="wed[]"
                                        placeholder="Course Code"></td>
                                <td><input type="text" class="sub" id="thu" name="thu[]"
                                        placeholder="Course Code"></td>
                                <td><input type="text" class="sub" id="fri" name="fri[]"
                                        placeholder="Course Code"></td>
                                <td><input type="text" class="sub" id="sat" name="sat[]"
                                        placeholder="Course Code"></td>
                                <td><input type="text" class="sub" id="sun" name="sun[]"
                                        placeholder="Course Code"></td>
                                <td><button class="removeRowBtn"><i class="fa-solid fa-xmark"></i></button></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <button id="addRowBtn">Add Row</button>

                <script>
                    document.getElementById("addRowBtn").addEventListener("click", function() {
                        // Select the table body
                        const tableBody = document.getElementById("tableBody");

                        // Clone the first row
                        const newRow = tableBody.rows[0].cloneNode(true);

                        // Clear input values in the new row
                        Array.from(newRow.querySelectorAll("input")).forEach(input => {
                            input.value = "";
                        });

                        // Append the new row to the table body
                        tableBody.appendChild(newRow);
                    });

                    // Remove row functionality
                    document.getElementById("tableBody").addEventListener("click", function(event) {
                        if (event.target.closest(".removeRowBtn")) {
                            // Ensure there is more than one row before removing
                            if (tableBody.rows.length > 1) {
                                event.target.closest("tr").remove();
                            }
                        }
                    });
                </script>

                <fieldset class="custom-fieldset">
                    <legend>DAILY TRAVEL ITINERARY FORM</legend>
                    <span>Fill out this section only if you commute from home to school.</span>
                    <div id="destination-container">
                        <div class="destination-info">
                            <p class="text-center fw-bold" style="text-decoration: underline;">Destination (Vice
                                Versa)</p>
                            <div class="row">
                                <div class="column">
                                    <label for="from">From</label>
                                    <input type="text" name="from[]" required>
                                </div>
                                <div class="column">
                                    <label for="to">To</label>
                                    <input type="text" name="to[]" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="column">
                                    <label for="estimatedTime">Estimated Travel Time</label>
                                    <input type="text" name="estimatedTime[]" required>
                                </div>
                                <div class="column">
                                    <label for="vehicleType">Type of Vehicle</label>
                                    <input type="text" name="vehicleType[]" required>
                                </div>
                                <div class="column">
                                    <label for="fareRate">Student Fare Rate</label>
                                    <input type="text" name="fareRate[]" required>
                                </div>
                            </div>
                            <!-- Remove button will only be added to cloned sections -->
                            <hr>
                        </div>
                    </div>
                    <button type="button" id="addDestination">Add Destination</button>

                    <div class="row">
                        <div class="column">
                            <label for="totalCosts">Total Costs per day</label>
                            <input type="text" name="totalCosts" id="totalCosts" required>
                        </div>
                    </div>
                </fieldset>

                <script>
                    document.getElementById('addDestination').addEventListener('click', function() {
                        // Get the container where new destinations will be appended
                        const container = document.getElementById('destination-container');

                        // Clone the destination-info div
                        const newDestination = container.children[0].cloneNode(true);

                        // Clear input values in the new clone
                        newDestination.querySelectorAll('input').forEach(input => input.value = '');

                        // Add a "Remove" button only to the cloned section
                        const removeButton = document.createElement('button');
                        removeButton.type = 'button';
                        removeButton.className = 'removeDestination';
                        removeButton.textContent = 'Remove';

                        // Attach event listener to remove the cloned section when clicked
                        removeButton.addEventListener('click', function() {
                            newDestination.remove();
                        });

                        // Append the remove button to the new destination section
                        newDestination.appendChild(removeButton);

                        // Append the new destination section to the container
                        container.appendChild(newDestination);
                    });
                </script>

                <fieldset class="custom-fieldset">
                    <legend>LODGING INFORMATION</legend>
                    <span>Fill out this section only if you are renting a place while studying.</span>
                    <div class="row">
                        <div class="column">
                            <label for="nameOwner">Name of owner/landlady/landlord</label>
                            <input type="text" name="nameOwner" id="nameOwner">
                        </div>
                        <div class="column">
                            <label for="contactNoOwner">Contact Number</label>
                            <input type="tel" id="contactNoOwner" name="contactNoOwner">
                        </div>
                    </div>
                    <div class="row">
                        <div class="column">
                            <label for="rent">Monthly Rent</label>
                            <input type="text" name="rent" id="rent">
                        </div>
                        <div class="column">
                            <label for="lodgingType">Type of Lodging</label>
                            <div class="radio-group">
                                <div class="row-radio">
                                    <input type="radio" id="dorm" name="lodgingType" value="Dorm">
                                    <label for="dorm">Dorm (inside the campus)</label>
                                </div>
                                <div class="row-radio">
                                    <input type="radio" id="boarding" name="lodgingType" value="Boarding House">
                                    <label for="boarding">Boarding House</label>
                                </div>
                                <div class="row-radio">
                                    <input type="radio" id="bedSpace" name="lodgingType" value="Bed Space">
                                    <label for="bedSpace">Bed Space</label>
                                </div>
                                <div class="row-radio">
                                    <input type="radio" id="notApplicable" name="lodgingType"
                                        value="Not Applicable">
                                    <label for="notApplicable">Not Applicable</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </fieldset>


                <fieldset class="custom-fieldset">
                    <legend>OJT DAILY TRAVEL ITINERARY FORM</legend>
                    <span>Fill out this section only if you are currently undergoing On-the-Job Training (OJT).</span>
                    <div class="row">
                        <div class="column">
                            <label for="endorsement">Photocopy of Endorsement</label>
                            <input type="file" id="endorsement" name="endorsement">
                        </div>
                        <div class="column">
                            <label for="acceptance">Letter of Acceptance</label>
                            <input type="file" id="acceptance" name="acceptance">
                        </div>
                    </div>

                    <div id="destination-container1">
                        <div class="destination-info1">
                            <p class="text-center fw-bold" style="text-decoration: underline;">Destination (Vice
                                Versa)</p>
                            <div class="row">
                                <div class="column">
                                    <label for="OJTfrom">From</label>
                                    <input type="text" name="OJTfrom[]">
                                </div>
                                <div class="column">
                                    <label for="OJTto">To</label>
                                    <input type="text" name="OJTto[]">
                                </div>
                            </div>
                            <div class="row">
                                <div class="column">
                                    <label for="OJTestimatedTime">Estimated Travel Time</label>
                                    <input type="text" name="OJTestimatedTime[]">
                                </div>
                                <div class="column">
                                    <label for="OJTvehicleType">Type of Vehicle</label>
                                    <input type="text" name="OJTvehicleType[]">
                                </div>
                                <div class="column">
                                    <label for="OJTfareRate">Student Fare Rate</label>
                                    <input type="number" name="OJTfareRate[]" class="fareRate">
                                </div>
                            </div>
                            <hr>
                        </div>
                    </div>

                    <button type="button" id="addDestination1" class="btn btn-success">Add Destination</button>

                    <div class="row">
                        <div class="column">
                            <label for="OJTtotalCosts">Total Costs per day</label>
                            <input type="text" name="OJTtotalCosts" id="OJTtotalCosts" readonly>
                        </div>
                    </div>
                </fieldset>

                <!-- CSS for styling the Remove button -->
                <style>
                    .removeDestination1 {
                        background-color: #dc3545;
                        color: white;
                        border: none;
                        padding: 5px 10px;
                        margin-top: 10px;
                        cursor: pointer;
                        border-radius: 5px;
                    }

                    .removeDestination1:hover {
                        background-color: #c82333;
                    }

                    .destination-info1 {
                        position: relative;
                    }

                    .destination-info1 .removeDestination1 {
                        position: absolute;
                        right: 0;
                        top: -5px;
                    }
                </style>

                <script>
                    document.getElementById('addDestination1').addEventListener('click', function() {
                        const container = document.getElementById('destination-container1');
                        const newDestination = container.children[0].cloneNode(true);

                        newDestination.querySelectorAll('input').forEach(input => input.value = '');

                        // Create and add a Remove button only to the cloned section
                        const removeButton = document.createElement('button');
                        removeButton.type = 'button';
                        removeButton.className = 'removeDestination1';
                        removeButton.textContent = 'Remove';

                        removeButton.addEventListener('click', function() {
                            newDestination.remove();
                            calculateTotalCost();
                        });

                        newDestination.appendChild(removeButton);
                        container.appendChild(newDestination);
                    });

                    // Function to calculate total costs based on fare rates
                    function calculateTotalCost() {
                        let totalCost = 0;
                        document.querySelectorAll('.fareRate').forEach(input => {
                            const value = parseFloat(input.value) || 0;
                            totalCost += value;
                        });
                        document.getElementById('OJTtotalCosts').value = totalCost.toFixed(2);
                    }

                    // Add event listener to fare rate inputs for real-time calculation
                    document.addEventListener('input', function(event) {
                        if (event.target.classList.contains('fareRate')) {
                            calculateTotalCost();
                        }
                    });
                </script>


                <div class="agreement">
                    <input type="checkbox" value="" id="agreement">
                    <label for="agreement">
                        <i>I HEREBY CERTIFY that the information provided in this form is
                            complete, true, and correct to the best of my knowledge.</i>
                    </label>
                </div>
                <div class="submit text-center">
                    <button type="submit" class="btn-submit fw-bold">Submit</button>
                </div>
            </form>
        </div>
    </div>

    <script src="{{ asset('js/scholar.js') }}"></script>
</body>

</html>
