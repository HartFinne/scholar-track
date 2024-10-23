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
            in each section with true and correct information. If a field does not apply to you, write <strong>Not Applicable</strong>.
        </p>
        
        <div class="form">
            <form action="" enctype="multipart/form-data">
                <fieldset class="custom-fieldset">
                    <legend>Scholar's Information</legend>
                    <div class="row">
                        <div class="column">
                            <label for="fullName">Name</label>
                            <input type="text" id="fullName" name="fullName" value="JUAN DELA CRUZ" disabled>
                        </div>
                        <div class="column">
                            <label for="dateSubmitted">Date Submitted</label>
                            <input type="date" id="dateSubmitted" name="dateSubmitted" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="column">
                            <label for="school">School</label>
                            <input type="text" id="school" name="school" value="POLYTECHNIC UNIVERSITY OF THE PHILIPPINES" disabled>
                        </div>
                    </div>
                    <div class="row">
                        <div class="column">
                            <label for="category">School Category</label>
                            <input type="text" id="category" name="category" required>
                        </div>
                        <div class="column">
                            <label for="contactNo">Contact No.</label>
                            <input type="tel" id="contactNo" name="contactNo" value="09123456789" disabled>
                        </div>
                    </div>
                    <div class="row">
                        <div class="column">
                            <label for="yrLevel">Year Level</label>
                            <input type="text" id="yrLevel" name="yrLevel" value="1st Year" disabled>
                        </div>
                        <div class="column">
                            <label for="course">Course</label>
                            <input type="text" id="course" name="course" value="BSIT" disabled>
                        </div>
                    </div>
                    <div class="row">
                        <div class="column">
                            <label for="homeAddress">Home Address</label>
                            <input type="text" id="homeAddress" name="homeAddress" value="" disabled>
                        </div>
                    </div>
                    <div class="row">
                        <div class="column">
                            <label for="boardAddress">Boarding House Address (If applicable)</label>
                            <input type="text" id="boardAddress" name="boardAddress" value="" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="column">
                            <label for="sem">Semester</label>
                            <select name="sem" id="sem" required>
                                <option value="" disabled hidden selected>Select semester</option>
                                <option value="1st Semester">1st Semester</option>
                                <option value="2nd Semester">2nd Semester</option>
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
                            <input type="date" id="startOjt" name="startOjt" value="" required>
                        </div>
                        <div class="column">
                            <label for="endOjt">End of OJT</label>
                            <input type="date" id="endOjt" name="endOjt" value="" required>
                        </div>
                    </div>
                </fieldset>

                <h5 class="fw-bold" style="color: #1a5319;">CLASS SCHEDULE (REFERENCE ONLY) </h5>
                <div class="row">
                    <div class="column">
                        <label for="regForm">Registration Form</label>
                        <input type="file" name="regForm" id="regForm" required>
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
                                <td><input type="text" id="time" name="time" placeholder="ex: 7:00 AM - 8:00 AM" required></td>
                                <td><input type="text" class="sub" id="mon" name="mon" placeholder="Course Code"></td>
                                <td><input type="text" class="sub" id="tue" name="tue" placeholder="Course Code"></td>
                                <td><input type="text" class="sub" id="wed" name="wed" placeholder="Course Code"></td>
                                <td><input type="text" class="sub" id="thu" name="thu" placeholder="Course Code"></td>
                                <td><input type="text" class="sub" id="fri" name="fri" placeholder="Course Code"></td>
                                <td><input type="text" class="sub" id="sat" name="sat" placeholder="Course Code"></td>
                                <td><input type="text" class="sub" id="sun" name="sun" placeholder="Course Code"></td>
                                <td><button class="removeRowBtn"><i class="fa-solid fa-xmark"></i></button></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <button id="addRowBtn">Add Row</button>

                <fieldset class="custom-fieldset">
                    <legend>DAILY TRAVEL ITINERARY FORM</legend>
                    <div id="destination-container">
                        <div class="destination-info">
                            <p class="text-center fw-bold" style="text-decoration: underline;">Destination (Vice Versa)</p>
                            <div class="row">
                                <div class="column">
                                    <label for="from">From</label>
                                    <input type="text" name="from" id="from" required>
                                </div>
                                <div class="column">
                                    <label for="to">To</label>
                                    <input type="text" name="to" id="to" required>
                                </div>
                            </div> 
                            <div class="row">
                                <div class="column">
                                    <label for="estimatedTime">Estimated Travel Time</label>
                                    <input type="text" name="estimatedTime" id="estimatedTime" required>
                                </div>
                                <div class="column">
                                    <label for="vehicleType">Type of Vehicle</label>
                                    <input type="text" name="vehicleType" id="vehicleType" required>
                                </div>
                                <div class="column">
                                    <label for="fareRate">Student Fare Rate</label>
                                    <input type="text" name="fareRate" id="fareRate" required>
                                </div>
                            </div>
                        </div>
                    </div>
                    <button id="addDestination">Add Destination</button>
                     
                    <div class="row">
                        <div class="column">
                            <label for="totalCosts">Total Costs per day</label>
                            <input type="text" name="totalCosts" id="totalCosts" required>
                        </div>
                    </div> 
                    
                </fieldset>
                <fieldset class="custom-fieldset">
                    <legend>LODGING INFORMATION</legend>
                    <div class="row">
                        <div class="column">
                            <label for="nameOwner">Name of owner/landlady/landlord</label>
                            <input type="text" name="nameOwner" id="nameOwner" required>
                        </div>
                        <div class="column">
                            <label for="contactNoOwner">Contact Number</label>
                            <input type="tel" id="contactNoOwner" name="contactNoOwner" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="column">
                            <label for="rent">Monthly Rent</label>
                            <input type="text" name="rent" id="rent" required>
                        </div>
                        <div class="column">
                            <label for="lodgingType">Type of Lodging</label>
                            <div class="radio-group">
                                <div class="row-radio">
                                    <input type="radio" id="dorm" name="lodgingType">
                                    <label for="dorm">Dorm (inside the campus)</label>
                                </div>
                                <div class="row-radio">
                                    <input type="radio" id="boarding" name="lodgingType">
                                    <label for="boarding">Boarding House</label>
                                </div>
                                <div class="row-radio">
                                    <input type="radio" id="bedSpace" name="lodgingType">
                                    <label for="bedSpace">Bed Space</label>
                                </div>
                            </div>                          
                        </div>
                    </div> 
                </fieldset>
                <fieldset class="custom-fieldset">
                    <legend>OJT DAILY TRAVEL ITINERARY FORM</legend>
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
                            <p class="text-center fw-bold" style="text-decoration: underline;">Destination (Vice Versa)</p>
                            <div class="row">
                                <div class="column">
                                    <label for="OJTfrom">From</label>
                                    <input type="text" name="OJTfrom" id="OJTfrom" required>
                                </div>
                                <div class="column">
                                    <label for="OJTto">To</label>
                                    <input type="text" name="OJTto" id="OJTto" required>
                                </div>
                            </div> 
                            <div class="row">
                                <div class="column">
                                    <label for="OJTestimatedTime">Estimated Travel Time</label>
                                    <input type="text" name="OJTestimatedTime" id="OJTestimatedTime" required>
                                </div>
                                <div class="column">
                                    <label for="OJTvehicleType">Type of Vehicle</label>
                                    <input type="text" name="OJTvehicleType" id="OJTvehicleType" required>
                                </div>
                                <div class="column">
                                    <label for="OJTfareRate">Student Fare Rate</label>
                                    <input type="text" name="OJTfareRate" id="OJTfareRate" required>
                                </div>
                            </div>
                        </div>
                    </div>
                    <button id="addDestination1">Add Destination</button>
                     
                    <div class="row">
                        <div class="column">
                            <label for="OJTtotalCosts">Total Costs per day  </label>
                            <input type="text" name="OJTtotalCosts" id="OJTtotalCosts" required>
                        </div>
                    </div>  
                    
                </fieldset>

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