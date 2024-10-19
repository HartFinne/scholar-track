<!DOCTYPE html>
<html lang="en">

<head>
    <title>Scholarship Renewal</title>
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
        <h1 class="title text-center">Scholarship Renewal Form</h1>
        <p class="mt-4 mb-5 description">Welcome to Tzu Chi Scholarship Renewal Form. Please fill out the required
            fields
            in each section with true and correct information to complete your registration. If a field does not apply,
            write <strong>N/A</strong>.
        </p>

        <div class="renewal-form">
            <form action="">
                <fieldset class="custom-fieldset">
                    <legend>Personal Information</legend>
                    <div class="row">
                        <div class="column">
                            <label for="fullName">Full Name</label>
                            <input type="text" id="fullName" value="JUAN DELA CRUZ" readonly>
                        </div>
                        <div class="column">
                            <label for="gender">Gender</label>
                            <input type="text" id="gender" value="" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="column">
                            <label for="age">Age</label>
                            <input type="text" id="age" value="" required>
                        </div>
                        <div class="column">
                            <label for="birthDate">Birthdate</label>
                            <input type="text" id="birthDate" value="DD/MM/YYYY" readonly>
                        </div>
                    </div>
                    <div class="row">
                        <div class="column">
                            <label for="email">Email Address</label>
                            <input type="text" id="email" value="example@gmail.com" readonly>
                        </div>
                        <div class="column">
                            <label for="phoneNum">Cellphone No./Landline</label>
                            <input type="tel" id="phoneNum" value="09123456789" readonly>
                        </div>
                    </div>
                    <div class="row">
                        <div class="column">
                            <label for="occupation">Occupation</label>
                            <input type="text" id="occupation" value="" required>
                        </div>
                        <div class="column">
                            <label for="income">Income</label>
                            <input type="text" id="income" value="" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="column">
                            <label for="fbName">Facebook Name</label>
                            <input type="text" id="fbName" value="" required>
                        </div>
                        <div class="column">
                            <p>Are you a member of any indigenous group?</p>
                            <div class="row-radio">
                                <input type="radio" id="indigenousCheck" name="indigenous" value="yes"
                                    onclick="toggleInput()">
                                <label for="indigenousCheck">Yes</label>
                                <input type="radio" id="noCheck" name="indigenous" value="no"
                                    onclick="disableInput()">
                                <label for="noCheck">No</label>
                            </div>
                            <input type="text" id="indigenousInput" placeholder="If Yes, please specify" disabled>
                        </div>
                    </div>

                </fieldset>

                <fieldset class="custom-fieldset">
                    <legend>Address Information</legend>
                    <div class="row">
                        <div class="column">
                            <label for="resAddress">Home Address</label>
                            <input type="text" id="resAddress" value="House #/Unit #/Floor/Bldg. Name/Street Name"
                                name="residential" readonly>
                        </div>
                    </div>
                    <div class="row">
                        <div class="column">
                            <label for="brgy">Barangay</label>
                            <input type="text" id="brgy" value="Barangay" name="brgy" readonly>
                        </div>
                        <div class="column">
                            <label for="city">City</label>
                            <input type="text" id="city" value="City" name="city" readonly>
                        </div>
                    </div>
                </fieldset>

                <fieldset class="custom-fieldset">
                    <legend>Educational Background</legend>
                    <div class="row">
                        <div class="column">
                            <label for="school">Name of University</label>
                            <input type="text" id="school" value="School" name="school" readonly>
                        </div>
                        <div class="column">
                            <label for="collegeDept">College Department</label>
                            <input type="text" id="collegeDept" value="College Department" name="collegeDept"
                                readonly>
                        </div>
                    </div>
                    <div class="row">
                        <div class="column">
                            <label for="yrLevel">Incoming Year Level</label>
                            <input type="text" id="yrLevel" value="" required>
                        </div>
                        <div class="column">
                            <label for="course">Course</label>
                            <input type="text" id="course" value="Course" readonly>
                        </div>
                    </div>
                    <div class="row">
                        <div class="column">
                            <label for="gwa">General Average Last Sem</label>
                            <input type="text" id="gwa" required>
                        </div>
                    </div>
                </fieldset>

                <fieldset class="custom-fieldset">
                    <legend>Family Information</legend>
                    <p class="family">FATHER INFORMATION</p>
                    <div class="row">
                        <div class="column">
                            <label for="fName">Name (Last Name, First Name)</label>
                            <input type="text" id="fName" value="" name="fName" required>
                        </div>
                        <div class="column">
                            <label for="fAge">Age</label>
                            <input type="text" id="fAge" value="" name="fAge" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="column">
                            <label for="fSex">Sex</label>
                            <input type="text" id="fSex" value="" name="fSex" required>
                        </div>
                        <div class="column">
                            <label for="fBirthdate">Birthdate</label>
                            <input type="date" id="fBirthdate" value="" name="fBirthdate" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="column">
                            <label for="fRelationship">Relationship</label>
                            <input type="text" id="fRelationship" value="" name="fRelationship" required>
                        </div>
                        <div class="column">
                            <label for="fReligion">Religion</label>
                            <input type="text" id="fReligion" value="" name="fReligion" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="column">
                            <label for="fAttainment">Educational Attainment</label>
                            <input type="text" id="fAttainment" value="" name="fAttainment" required>
                        </div>
                        <div class="column">
                            <label for="fSchoolOcc">School/Occupation</label>
                            <input type="text" id="fSchoolOcc" value="" name="fSchoolOcc" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="column">
                            <label for="fCompany">Company</label>
                            <input type="text" id="fCompany" value="" name="fCompany" required>
                        </div>
                        <div class="column">
                            <label for="fIncome">Income</label>
                            <input type="text" id="fIncome" value="" name="fIncome" required>
                        </div>
                    </div>

                    <p class="family">MOTHER INFORMATION</p>
                    <div class="row">
                        <div class="column">
                            <label for="fName">Name (Last Name, First Name)</label>
                            <input type="text" id="fName" value="" name="fName" required>
                        </div>
                        <div class="column">
                            <label for="fAge">Age</label>
                            <input type="text" id="fAge" value="" name="fAge" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="column">
                            <label for="fSex">Sex</label>
                            <input type="text" id="fSex" value="" name="fSex" required>
                        </div>
                        <div class="column">
                            <label for="fBirthdate">Birthdate</label>
                            <input type="date" id="fBirthdate" value="" name="fBirthdate" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="column">
                            <label for="fRelationship">Relationship</label>
                            <input type="text" id="fRelationship" value="" name="fRelationship" required>
                        </div>
                        <div class="column">
                            <label for="fReligion">Religion</label>
                            <input type="text" id="fReligion" value="" name="fReligion" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="column">
                            <label for="fAttainment">Educational Attainment</label>
                            <input type="text" id="fAttainment" value="" name="fAttainment" required>
                        </div>
                        <div class="column">
                            <label for="fSchoolOcc">School/Occupation</label>
                            <input type="text" id="fSchoolOcc" value="" name="fSchoolOcc" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="column">
                            <label for="fCompany">Company</label>
                            <input type="text" id="fCompany" value="" name="fCompany" required>
                        </div>
                        <div class="column">
                            <label for="fIncome">Income</label>
                            <input type="text" id="fIncome" value="" name="fIncome" required>
                        </div>
                    </div>
                </fieldset>

                <!-- <p class="familyinfo">Family Information</p>
                <div class="ctn-table table-responsive">
                    <table class="table table-bordered" id="table">
                        <thead>
                            <tr>
                                <th class="text-center align-middle col1">Name <br><span>(Last Name, First Name)</span></th>
                                <th class="text-center align-middle col2">Age</th>
                                <th class="text-center align-middle col3">Sex</th>
                                <th class="text-center align-middle col4">Birtdate <br><span>(MM/DD/YYY)</span></th>
                                <th class="text-center align-middle col5">Relationship</th>
                                <th class="text-center align-middle col6">Religion</th>
                                <th class="text-center align-middle col7">Educational Attainment</th>
                                <th class="text-center align-middle col8">School/Occupation</th>
                                <th class="text-center align-middle col9">Company</th>
                                <th class="text-center align-middle col10">Income</th>
                            </tr>
                        </thead>
                        <tbody id="familyTableBody">
                            <tr>
                                <td><input type="text" id="fName" required></td>
                                <td><input type="text" id="fAge" required></td>
                                <td><select name="" id="fSex">
                                        <option value="" disabled selected hidden></option>
                                        <option value="">F</option>
                                        <option value="">M</option>
                                    </select>
                                </td>
                                <td><input type="date" id="fBirthdate" required></td>
                                <td><input type="text" id="fRelationship" required></td>
                                <td><input type="text" id="fReligion" required></td>
                                <td><input type="text" id="fAttainment" required></td>
                                <td><input type="text" id="fSchoolOcc" required></td>
                                <td><input type="text" id="fCompany" required></td>
                                <td><input type="text" id="fIncome" required></td>
                            </tr>
                        </tbody>
                    </table>
                    <button id="addRowBtn">Add Row</button>
                </div> -->

                <fieldset class="custom-fieldset">
                    <legend>Other Information</legend>
                    <div class="row">
                        <div class="column">
                            <label for="grant">Grant/Assistance from other Government and Non-Government
                                scholarships, School Discount (How much per sem?)</label>
                            <textarea id="grant" name="grant" rows="4" cols="50" placeholder="Input your answer here..."></textarea>
                        </div>
                    </div>
                    <div class="row">
                        <div class="column">
                            <label for="talents">Talents & Skills/ Honor and Recognition/ Extracurricular/Community
                                Involvement/Employment</label>
                            <textarea id="talents" name="talents" rows="4" cols="50" placeholder="Input your answer here..."></textarea>
                        </div>
                    </div>
                    <div class="row">
                        <div class="column">
                            <label for="talents">What are your expectations from Tzu Chi Foundation?</label>
                            <textarea id="talents" name="talents" rows="4" cols="50" placeholder="Input your answer here..."></textarea>
                        </div>
                    </div>
                </fieldset>

                <fieldset class="custom-fieldset">
                    <legend>Requirements Submission</legend>
                    <div class="row">
                        <div class="column">
                            <label for="idPic">1x1 ID Picture (Format: JPG or JPEG)</label>
                            <input type="file" id="idPic" required>
                        </div>
                        <div class="column">
                            <label for="grades">Scanned copy of latest Report Card</label>
                            <input type="file" id="grades" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="column">
                            <label for="regForm">Scanned copy of latest Registration Form</label>
                            <input type="file" id="regForm" required>
                        </div>
                        <div class="column">
                            <label for="autobiography">Autobiography</label>
                            <input type="file" id="autobiography" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="column">
                            <label for="famPic">Family Picture (Format: JPG or JPEG)</label>
                            <input type="file" id="famPic" required>
                        </div>
                        <div class="column">
                            <label for="housePic">Picture of the inside and outside of the house (Format: JPG or
                                JPEG)</label>
                            <input type="file" id="famPic" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="column">
                            <label for="utility">Scanned copy of latest Utility Bills</label>
                            <input type="file" id="utility" required>
                        </div>
                        <div class="column">
                            <label for="sketchMap">Detailed Sketch Map of Home Address</label>
                            <input type="file" id="sketchMap" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="column">
                            <label for="paySlip">Scanned copy latest ITR/ Official Pay Slip of parent/s (if
                                applicable)</label>
                            <input type="file" id="paySlip">
                        </div>
                        <div class="column">
                            <label for="brgyCert">Barangay Certificate of Indigency</label>
                            <input type="file" id="brgyCert" required>
                        </div>
                    </div>
                </fieldset>
            </form>
            <div class="submit text-center">
                <button type="submit" class="btn-submit fw-bold">Submit</button>
            </div>
        </div>
    </div>


    <script src="{{ asset('js/scholar.js') }}"></script>
    <script>
        function toggleInput() {
            var indigenousCheck = document.getElementById("indigenousCheck");
            var indigenousInput = document.getElementById("indigenousInput");
            var noCheck = document.getElementById("noCheck");

            if (indigenousCheck.checked) {
                indigenousInput.disabled = false;
                noCheck.checked = false;
            } else {
                indigenousInput.disabled = true;
            }
        }

        function disableInput() {
            var noCheck = document.getElementById("noCheck");
            var indigenousCheck = document.getElementById("indigenousCheck");
            var indigenousInput = document.getElementById("indigenousInput");

            if (noCheck.checked) {
                indigenousInput.disabled = true;
                indigenousCheck.checked = false;
            }
        }
    </script>
</body>

</html>
