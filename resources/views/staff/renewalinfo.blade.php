<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Renewal Info</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/appformview.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body>
    <!-- PAGE HEADER -->
    @include('partials._pageheader')
    <div class="ctnmain">
        <div class="appformview">
            <div class="mx-auto mb-2" style="width: 8.5in">
                <div class="col-md-2">
                    <a href="{{ url()->previous() }}" class="btn btn-success w-100">Go back</a>
                </div>
            </div>
            <div class="appinfo">
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
                <div class="row my-2">
                    <span class="col-md-3 label">Applicant Name</span>
                    <span class="col-md-1 label">: </span>
                    <input class="col-md-8" style="max-width: 35%; padding: 2px 5px;" value="John Doe" readonly>
                </div>
                <div class="row my-2">
                    <span class="col-md-3 label">Applicant Case Code</span>
                    <span class="col-md-1 label">: </span>
                    <input class="col-md-8" style="max-width: 35%; padding: 2px 5px;" value="2223-00001-MD" readonly>
                </div>
                <form class="row my-2" method="POST" action="update_status.html">
                    <span class="col-md-3 label">Application Status</span>
                    <span class="col-md-1 label">: </span>
                    <select name="applicationstatus" class="col-md-8" style="max-width: 35%; padding: 2px 5px;">
                        <option value="UNDER REVIEW" selected>UNDER REVIEW</option>
                        <option value="ACCEPTED">ACCEPTED</option>
                        <option value="REJECTED">REJECTED</option>
                        <option value="WITHDRAWN">WITHDRAWN</option>
                    </select>
                    <button class="rounded border border-success mx-3" id="btnupdate">Save</button>
                </form>
            </div>
            <div class="page1">
                <div class="header">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo">
                    <p><strong>佛教慈濟慈善事業基金會菲律濱分會<br>Buddhist Compassion Relief Tzu Chi Foundation, Philippines</strong>
                    </p>
                </div>
                <div class="app-title">
                    <p>Educational Assistance Program</p>
                    <p><strong>APPLICATION FORM</strong></p>
                    <p id="schoolYear">S.Y. 2023-2024</p>
                </div>
                <div class="personal-info">
                    <div class="row">
                        <span class="col-md-4"><strong><u>PERSONAL INFORMATION</u></strong></span>
                        <span class="col-md-2">Status:</span>
                        <span class="col-md-3">Case Code:</span>
                        <span class="col-md-3">Form No.:</span>
                    </div>
                    <div class="row mb-2">
                        <span class="col-md-4"></span>
                        <span class="col-md-2"><strong>New</strong></span>
                        <span class="col-md-3"><strong>CASE12345</strong></span>
                        <span class="col-md-3"></span>
                    </div>
                    <div class="psec2">
                        <div class="table1">
                            <table class="table table-bordered" id="table">
                                <tr>
                                    <td colspan="2">
                                        <span class="flabel">Name: (Last Name, First Name, Middle Name)</span><br>
                                        <span class="fvalue" id="fullName">John Doe</span>
                                    </td>
                                    <td colspan="2">
                                        <span class="flabel">Chinese Name</span><br>
                                        <span class="fvalue" id="cName">李明</span>
                                    </td>
                                    <td>
                                        <span class="flabel">Gender</span><br>
                                        <span class="fvalue" id="gender">Male</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2">
                                        <span class="flabel">Home Address</span><br>
                                        <span class="fvalue" id="resAddress">1234 Sample St, City, Country</span>
                                    </td>
                                    <td>
                                        <span class="flabel">Barangay</span><br>
                                        <span class="fvalue" id="brgy">Sample Barangay</span>
                                    </td>
                                    <td>
                                        <span class="flabel">City</span><br>
                                        <span class="fvalue" id="city">Sample City</span>
                                    </td>
                                    <td>
                                        <span class="flabel">Age</span><br>
                                        <span class="fvalue" id="age">20</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <span class="flabel">Email address</span><br>
                                        <span class="fvalue" id="email">johndoe@example.com</span>
                                    </td>
                                    <td>
                                        <span class="flabel">Occupation and Income</span><br>
                                        <span class="fvalue" id="occupation">Student</span>,<br>
                                        <span class="fvalue" id="income">Php 0.00</span>
                                    </td>
                                    <td colspan="2">
                                        <span class="flabel">Cellphone No./Landline</span><br>
                                        <span class="fvalue" id="phoneNum">+63 912 345 6789</span>
                                    </td>
                                    <td>
                                        <span class="flabel">Birthdate</span><br>
                                        <span class="fvalue" id="birthDate">January 1, 2003</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <span class="flabel">Facebook name</span><br>
                                        <a href="#" class="fvalue" id="fbName">facebook.com/johndoe</a>
                                    </td>
                                    <td colspan="4">
                                        <span class="flabel">Are you a member of any indigenous group?</span><br>
                                        <span class="fvalue" id="indigenous">No</span>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <img src="images/idpic.png" alt="Applicant 1x1 Pic">
                        {{-- <img src="{{ asset('storage/' . $applicant->requirements->idpic) }}" alt="Applicant 1x1 Pic"> --}}
                    </div>
                </div>

                <div class="educbg">
                    <div class="esec1">
                        <span class="my-2"><strong><u>EDUCATIONAL BACKGROUND</u></strong></span>
                    </div>
                    <div class="d-flex flex-column mb-2">
                        <span class="text-center my-2"><strong>COLLEGE</strong></span>
                        <div class="column">
                            <div class="mb-1 small">
                                Name of University: <strong>Sample University</strong>
                            </div>
                            <div class="mb-1 small">
                                College Department: <strong>Department of Science</strong>
                            </div>
                            <div class="mb-1 small">
                                Incoming Year Level: <strong>2nd Year</strong>
                            </div>
                            <div class="mb-1 small">
                                Course: <strong>Bachelor of Science in Computer Science</strong>
                            </div>
                            <div class="mb-1 small">General Average Last Sem: <strong>90</strong></div>
                        </div>
                    </div>
                </div>

                <div class="fam-info">
                    <span class="my-2"><strong><u>FAMILY INFORMATION</u></strong></span>
                    <div class="table1">
                        <table class="table table-bordered" id="table">
                            <thead>
                                <tr>
                                    <th class="text-center">Name</th>
                                    <th class="text-center">Age</th>
                                    <th class="text-center">Sex</th>
                                    <th class="text-center">Birthdate</th>
                                    <th class="text-center">Relationship</th>
                                    <th class="text-center">Religion</th>
                                    <th class="text-center">Educational Attainment</th>
                                    <th class="text-center">School/Occupation</th>
                                    <th class="text-center">Company</th>
                                    <th class="text-center">Income</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>John Doe Sr.</td>
                                    <td>50</td>
                                    <td>Male</td>
                                    <td>January 1, 1973</td>
                                    <td>Father</td>
                                    <td>Christian</td>
                                    <td>College Graduate</td>
                                    <td>Engineer</td>
                                    <td>ABC Company</td>
                                    <td>Php 50,000.00</td>
                                </tr>
                                <tr>
                                    <td>Jane Doe</td>
                                    <td>48</td>
                                    <td>Female</td>
                                    <td>February 2, 1975</td>
                                    <td>Mother</td>
                                    <td>Christian</td>
                                    <td>College Graduate</td>
                                    <td>Teacher</td>
                                    <td>XYZ School</td>
                                    <td>Php 30,000.00</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="other-info">
                    <div class="row mt-2">
                        <strong>Grant/Assistance from other scholarships:</strong>
                    </div>
                    <div class="row px-3">
                        <span>None</span>
                    </div>
                    <div class="row mt-2">
                        <strong>Talents & Skills/ Honors:</strong>
                    </div>
                    <div class="row px-3">
                        <span>Music, Dance</span>
                    </div>
                    <div class="row mt-2">
                        <strong>Expectations from Tzu Chi Foundation:</strong>
                    </div>
                    <div class="row px-3">
                        <span>Financial support for education</span>
                    </div>
                </div>

                <div class="signature">
                    <p>I hereby attest that the information I have provided is true and correct...</p>
                    <div>
                        <p id="nameDate">John Doe, January 1, 2023</p>
                        <p class="sign">Applicant's Signature over Printed Name and Date</p>
                    </div>
                </div>
            </div>
        </div>
        <form action="" method="post">
            <div class="page2">
                <div class="header">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo">
                    <p><strong>佛教慈濟慈善事業基金會菲律濱分會<br>Buddhist Compassion Relief Tzu Chi Foundation, Philippines</strong>
                    </p>
                </div>
                <h3 class="text-center my-3">Educational Assistance Application Form</h3>
                <span class="row"><strong><u>Sketch of Home Address</u></strong></span>
                <div class="sketchimg">
                    <img src="images/sketchmap" alt="sketchmap">
                    {{-- <img src="{{ asset('storage/' . $applicant->requirements->sketchmap) }}"
                        alt="Sketch Map of Home Address" id="sketchmap"> --}}
                </div>
                <div class="instructions">
                    <ol>
                        <li>
                            The objective of Tzu Chi scholarship program is to provide financial assistance to deserving
                            students through tuition fee support,
                            monthly living allowance, as well as additional assistance for other school needs, should it
                            be deemed necessary. College students
                            are only authorized to enroll in partner schools and authorized courses.
                        </li>
                        <li>
                            Students with a failing grade on any subject, with <strong>general weighted average 82% both
                                English and Chinese</strong> or with a grade on
                            <strong>Conduct below B</strong> or with scholarship grant from other foundations or
                            organizations will not be accepted.
                        </li>
                        <li>Please fill up the Scholarship Application form completely and Draw Sketch of home address
                            use the back page if necessary. Any
                            misleading information may lead to disqualification.
                        </li>
                        <li>
                            Submit the following: <span id="reqs">Photocopy of Report Card or copy of grade form
                                last school year, Registration form, Accomplished Application
                                Form, Two (2) 1x1 ID Pictures, Autobiography, Family Picture, Copies of Utility Bills,
                                Detailed Sketch of Home Address, Certificate
                                of indigence from the Barangay, Pictures of House (Inside and outside), Payslip or
                                Income Tax Return of Both Parents (if working).</span>
                            <strong>Deadline of Submission is on</strong> <span id="deadline">DEADLINE</span>.
                        </li>
                        <li>
                            Late applicants will not be entertained.
                        </li>
                        <li>
                            All applications are subject for home visit and approval.
                        </li>
                        <li>
                            The applicants will be notified on the acceptance or rejection of the application.
                        </li>
                        <li>
                            For inquiries, you may contact the Educational Assistance Program Staff with number
                            09953066694.
                        </li>
                    </ol>
                </div>
                <div class="fillstaff">
                    <div class="heading">
                        <p>To be filled up by TZU CHI FOUNDATION</p>
                        <p>Case Details</p>
                    </div>
                </div>
                <div class="table3">
                    <table class="table" id="table">
                        <tr>
                            <td>
                                <span class="slabel">Nature of Needs</span><br>
                                <span class="svalue" id="natureOfneeds">
                                    <input type="radio" id="financial" name="needs" value="Financial">
                                    <label for="financial">Financial</label><br>
                                    <input type="radio" id="medical" name="needs" value="Medical">
                                    <label for="medical">Medical</label><br>
                                    <input type="radio" id="food" name="needs" value="Food">
                                    <label for="food">Food</label><br>
                                    <input type="radio" id="material" name="needs" value="Material">
                                    <label for="material">Material</label><br>
                                    <input type="radio" id="educ" name="needs" value="Education" checked>
                                    <label for="educ"> Education</label><br>
                                    <input type="radio" id="others" name="needs" value="Others">
                                    <label for="others"> Others</label><br>
                                </span>
                            </td>
                            <td style="width: 600px;">
                                <span class="slabel"><strong>Problem Statement</strong></span><br>
                                <textarea name="problemstatement" id="" rows="5" cols="6" placeholder="Type here..." required></textarea>
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="row">
                    <div class="column col-md-5">
                        <div class="row my-2 d-flex justify-content-between casedeets">
                            Received By:
                            <input class="casedeets-input text-center" style="width: 65% !important" type="text"
                                name="receiveby" required>
                        </div>
                        <div class="row my-2 d-flex justify-content-between casedeets">
                            Date Receive:
                            <input class="casedeets-input text-center" style="width: 50% !important" type="text"
                                name="datereceived" required placeholder="MM DD, YYYY">
                        </div>
                        <div class="row my-2 d-flex justify-content-between casedeets">
                            Assigned District:
                            <input class="casedeets-input text-center" style="width: 50% !important" type="text"
                                name="district" required>
                        </div>
                        <div class="row my-2 d-flex justify-content-between casedeets">
                            Assigned Volunteer:
                            <input class="casedeets-input text-center" style="width: 50% !important" type="text"
                                name="volunteer" required>
                        </div>
                    </div>
                    <div class="column col-md-7">
                        <div class="row">
                            <span class="note">
                                Note: Important information are essential to be able to assess and evaluate students'
                                situation. Also please note significant details for home visitation purposes. You may
                                use the back page if necessary.
                            </span>
                        </div>
                        <div class="column" style="padding: 0 10px">
                            <div class="row my-2 d-flex justify-content-between casedeets">
                                Case Referred By:
                                <input class="casedeets-input text-center" style="width: 45% !important"
                                    type="text" name="referredby" required>
                            </div>
                            <div class="row my-2 d-flex justify-content-between casedeets">
                                Referral Contact no.:
                                <input class="casedeets-input text-center" style="width: 45% !important"
                                    type="tel" name="referphonenum" required>
                            </div>
                            <div class="row my-2 d-flex justify-content-between casedeets">Relationship with
                                Beneficiary:
                                <input class="casedeets-input text-center" style="width: 45% !important"
                                    type="text" name="relationship" required>
                            </div>
                            <div class="row my-2 d-flex justify-content-between casedeets">
                                Applicant's Signature:
                                <span class="casedeets-input text-center" style="width: 45% !important"></span>
                            </div>
                            <div class="row my-2 d-flex justify-content-between casedeets">
                                Date Reported:
                                <input class="casedeets-input text-center" style="width: 45% !important"
                                    type="text" name="datereported" required placeholder="MM DD, YYYY">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <button type="submit" class="col-md-1 btn btn-success mx-auto">Save</button>
            </div>
        </form>
    </div>
    <script src="{{ asset('js/headercontrol.js') }}"></script>
</body>

</html>
