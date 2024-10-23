<!DOCTYPE html>
<html lang="en">

<head>
    <title>Scholarship Renewal</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="{{ asset('css/partial.css') }}">
    <link rel="stylesheet" href="{{ asset('css/screnewal.css') }}">
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
                            <label for="fullName">Name</label>
                            <input type="text" id="fullName" name="fullName" value="Last Name, First Name, Middle Name" disabled>
                        </div>
                        <div class="column">
                            <label for="cName">Chinese Name</label>
                            <input type="text" id="cName" name="cName" value="" disabled>
                        </div>
                    </div>
                    <div class="row">
                        <div class="column">
                            <label for="gender">Gender</label>
                            <input type="text" id="gender" name="gender" required>
                        </div>
                        <div class="column">
                            <label for="age">Age</label>
                            <input type="number" id="age" name="age" required>
                        </div>
                        <div class="column">
                            <label for="birthDate">Birthdate</label>
                            <input type="text" id="birthDate" name="birthDate" value="MM/DD/YYYY" disabled>
                        </div>
                    </div>
                    <div class="row">
                        <div class="column">
                            <label for="resAddress">Home Address</label>
                            <input type="text" id="resAddress" name="resAddress" value="House #/Unit #/Floor/Bldg. Name/Street Name"  disabled>
                        </div>
                    </div>
                    <div class="row">
                        <div class="column">
                            <label for="brgy">Barangay</label>
                        <input type="text" id="brgy" name="brgy" disabled>
                        </div>
                        <div class="column">
                            <label for="city">City</label>
                            <input type="text" id="city" name="city" disabled>
                        </div>                        
                    </div>
                    <div class="row">
                        <div class="column">
                            <label for="email">Email Address</label>
                            <input type="text" id="email" name="email" disabled>
                        </div>
                        <div class="column">
                            <label for="phoneNum">Cellphone No./Landline</label>
                            <input type="tel" id="phoneNum" name="phoneNum" disabled>
                        </div>
                    </div>
                    <div class="row">
                        <div class="column">
                            <label for="occupation">Occupation</label>
                            <input type="text" id="occupation" name="occupation" required>
                        </div>
                        <div class="column">
                            <label for="income">Income</label>
                            <input type="number" id="income" name="income" placeholder="If none, input number zero" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="column">
                            <label for="fbLink">Facebook Link</label>
                            <input type="text" id="fbLink" name="fbLink" required>
                        </div>
                        <div class="column">
                            <p>Are you a member of any indigenous group?</p>
                            <div class="row-radio">
                                <input type="radio" id="indigenousCheck" name="indigenous" value="yes" onclick="toggleInput()">
                                <label for="indigenousCheck">Yes</label>
                                <input type="radio" id="noCheck" name="indigenous" value="no" onclick="disableInput()">
                                <label for="noCheck">No</label>
                            </div>
                            <input type="text" id="indigenousInput" placeholder="If Yes, please specify" disabled>
                        </div>
                    </div>

                </fieldset>

                <fieldset class="custom-fieldset">
                    <legend>Educational Background</legend>
                    <div class="row">
                        <div class="column">
                            <label for="school">Name of University</label>
                            <input type="text" id="school" name="school" value="School" disabled>
                        </div>
                        <div class="column">
                            <label for="collegeDept">College Department</label>
                            <input type="text" id="collegeDept" name="collegeDept" value="College Department"  disabled>
                        </div>
                    </div>
                    <div class="row">
                        <div class="column">
                            <label for="yrLevel">Incoming Year Level</label>
                            <select name="yrLevel" id="yrLevel" required>
                                <option value="" selected hidden></option>
                                <option value="First Year">First Year</option>
                                <option value="Second Year">Second Year</option>
                                <option value="Third Year">Third Year</option>
                                <option value="Fourth Year">Fourth Year</option>
                                <option value="Fifth Year">Fifth Year</option>
                            </select>
                        </div>
                        <div class="column">
                            <label for="course">Course</label>
                            <input type="text" id="course" name="course" value="Course" disabled>
                        </div>
                    </div>
                    <div class="row">
                        <div class="column">
                            <label for="gwa">General Average Last Sem</label>
                            <input type="text" id="gwa" name="gwa" required>
                        </div>
                    </div>
                </fieldset>

                <fieldset class="custom-fieldset">
                    <legend>Family Information</legend>
                    <p class="family">FATHER INFORMATION</p>
                    <div class="row">
                        <div class="column">
                            <label for="fName">Name (Last Name, First Name)</label>
                            <input type="text" id="fName" name="fName" required>
                        </div>
                        <div class="column">
                            <label for="fAge">Age</label>
                            <input type="number" id="fAge" name="fAge" required>
                        </div>
                        <div class="column">
                        <label for="fSex">Sex</label>
                            <select name="fSex" id="fSex">
                                <option value="" disabled selected hidden></option>
                                <option value="F">F</option>
                                <option value="M">M</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="column">
                            <label for="fBirthdate">Birthdate</label>
                            <input type="date" id="fBirthdate" name="fBirthdate" required>
                        </div>
                        <div class="column">
                            <label for="fRelationship">Relationship</label>
                            <input type="text" id="fRelationship" name="fRelationship" required>
                        </div>
                        <div class="column">
                            <label for="fReligion">Religion</label>
                            <input type="text" id="fReligion" name="fReligion" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="column">
                            <label for="fAttainment">Educational Attainment</label>
                            <input type="text" id="fAttainment" name="fAttainment" required>
                        </div>
                        <div class="column">
                            <label for="fSchoolOcc">School/Occupation</label>
                            <input type="text" id="fSchoolOcc" name="fSchoolOcc" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="column">
                            <label for="fCompany">Company</label>
                            <input type="text" id="fCompany" name="fCompany" required>
                        </div>
                        <div class="column">
                            <label for="fIncome">Income</label>
                            <input type="number" id="fIncome" name="fIncome" placeholder="If none, input number zero" required>
                        </div>
                    </div>

                    <p class="family">MOTHER INFORMATION</p>
                    <div class="row">
                        <div class="column">
                            <label for="fName">Name (Last Name, First Name)</label>
                            <input type="text" id="fName" name="fName" required>
                        </div>
                        <div class="column">
                            <label for="fAge">Age</label>
                            <input type="number" id="fAge" name="fAge" required>
                        </div>
                        <div class="column">
                        <label for="fSex">Sex</label>
                            <select name="fSex" id="fSex">
                                <option value="" disabled selected hidden></option>
                                <option value="F">F</option>
                                <option value="M">M</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="column">
                            <label for="fBirthdate">Birthdate</label>
                            <input type="date" id="fBirthdate" name="fBirthdate" required>
                        </div>
                        <div class="column">
                            <label for="fRelationship">Relationship</label>
                            <input type="text" id="fRelationship" name="fRelationship" required>
                        </div>
                        <div class="column">
                            <label for="fReligion">Religion</label>
                            <input type="text" id="fReligion" name="fReligion" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="column">
                            <label for="fAttainment">Educational Attainment</label>
                            <input type="text" id="fAttainment" name="fAttainment" required>
                        </div>
                        <div class="column">
                            <label for="fSchoolOcc">School/Occupation</label>
                            <input type="text" id="fSchoolOcc" name="fSchoolOcc" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="column">
                            <label for="fCompany">Company</label>
                            <input type="text" id="fCompany" name="fCompany" required>
                        </div>
                        <div class="column">
                            <label for="fIncome">Income</label>
                            <input type="number" id="fIncome" name="fIncome" placeholder="If none, input number zero" required>
                        </div>
                    </div>

                    <div id="siblings-container">
                        <div class="siblingsinfo">
                            <p class="family">SIBLING INFORMATION</p>
                            <div class="row">
                                <div class="column">
                                    <label for="fName">Name (Last Name, First Name)</label>
                                    <input type="text" id="fName" name="fName" required>
                                </div>
                                <div class="column">
                                    <label for="fAge">Age</label>
                                    <input type="number" id="fAge" name="fAge" required>
                                </div>
                                <div class="column">
                                    <label for="fSex">Sex</label>
                                    <select name="" id="fSex">
                                        <option value="" disabled selected hidden></option>
                                        <option value="">F</option>
                                        <option value="">M</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="column">
                                    <label for="fBirthdate">Birthdate</label>
                                    <input type="date" id="fBirthdate" name="fBirthdate" required>
                                </div>
                                <div class="column">
                                    <label for="fRelationship">Relationship</label>
                                    <input type="text" id="fRelationship" name="fRelationship" readonly>
                                </div>
                                <div class="column">
                                    <label for="fReligion">Religion</label>
                                    <input type="text" id="fReligion" name="fReligion" required>
                                </div>                        
                            </div>
                            <div class="row">
                                <div class="column">
                                    <label for="fAttainment">Educational Attainment</label>
                                    <input type="text" id="fAttainment" name="fAttainment" required>
                                </div>
                                <div class="column">
                                    <label for="fSchoolOcc">School/Occupation</label>
                                    <input type="text" id="fSchoolOcc" name="fSchoolOcc" required>
                                </div>                        
                            </div>
                            <div class="row">
                                <div class="column">
                                    <label for="fCompany">Company</label>
                                    <input type="text" id="fCompany" name="fCompany" required>
                                </div>
                                <div class="column">
                                    <label for="fIncome">Income</label>
                                    <input type="number" id="fIncome" name="fIncome" placeholder="If none, input number zero" required>
                                </div>                        
                            </div>
                        </div>
                    </div>
                    <button id="addSibling">Add Sibling</button>
                </fieldset>

                <fieldset class="custom-fieldset">
                    <legend>Other Information</legend>
                    <div class="row">
                        <div class="column">
                            <label for="grant">Grant/Assistance from other Government and Non-Government
                                scholarships, School Discount (How much per sem?)</label>
                            <textarea id="grant" name="grant" rows="2" cols="50" placeholder="Input your answer here..." required></textarea>
                        </div>
                    </div>
                    <div class="row">
                        <div class="column">
                            <label for="talents">Talents & Skills/ Honor and Recognition/ Extracurricular/Community
                                Involvement/Employment</label>
                            <textarea id="talents" name="talents" rows="2" cols="50" placeholder="Input your answer here..." required></textarea>
                        </div>
                    </div>
                    <div class="row">
                        <div class="column">
                            <label for="talents">What are your expectations from Tzu Chi Foundation?</label>
                            <textarea id="expectations" name="expectations" rows="2" cols="50" placeholder="Input your answer here..." required></textarea>
                        </div>
                    </div>
                </fieldset>

                <fieldset class="custom-fieldset">
                    <legend>Requirements Submission</legend>
                    <div class="row">
                        <div class="column">
                            <label for="idPic">1x1 ID Picture (Format: JPG or JPEG)</label>
                            <input type="file" id="idPic" name="idPic" required>
                        </div>
                        <div class="column">
                            <label for="grades">Scanned copy of latest Report Card</label>
                            <input type="file" id="grades" name="grades" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="column">
                            <label for="regForm">Scanned copy of latest Registration Form</label>
                            <input type="file" id="regForm" name="regForm" required>
                        </div>
                        <div class="column">
                            <label for="autobiography">Autobiography</label>
                            <input type="file" id="autobiography" name="autobiography" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="column">
                            <label for="famPic">Family Picture (Format: JPG or JPEG)</label>
                            <input type="file" id="famPic" name="famPic" required>
                        </div>
                        <div class="column">
                            <label for="insidePic">Picture of the inside of the house (Format: JPG or JPEG)</label>
                            <input type="file" id="insidePic" name="insidePic" required>
                        </div>
                    </div>
                    <div class="row">
                    <div class="column">
                            <label for="outsidePic">Picture of the outside of the house (Format: JPG or JPEG)</label>
                            <input type="file" id="outsidePic" name="outsidePic" required>
                        </div>
                        <div class="column">
                            <label for="utility">Scanned copy of latest Utility Bills</label>
                            <input type="file" id="utility" name="utility" required>
                        </div>                      
                    </div>
                    <div class="row">
                    <div class="column">
                            <label for="sketchMap">Detailed Sketch Map of Home Address</label>
                            <input type="file" id="sketchMap" name="sketchMap" required>
                        </div>
                        <div class="column">
                            <label for="paySlip">Scanned copy latest ITR/ Official Pay Slip of parent/s (if
                                applicable)</label>
                            <input type="file" id="paySlip" name="paySlip" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="column">
                            <label for="brgyCert">Barangay Certificate of Indigency</label>
                            <input type="file" id="brgyCert" name="brgyCert" required>
                        </div>
                    </div>
                </fieldset>

                <div class="agreement">
                    <input type="checkbox" value="" id="agreement">
                    <label for="agreement">
                        <i>I hereby attest that the information I have provided is true and correct. I also 
                            consents Tzu Chi Foundation to obtain and retain my personal information for the purpose of this application.</i>
                    </label>
                </div>
                <div class="submit text-center">
                    <button type="submit" class="btn-submit fw-bold">Submit</button>
                </div>
            </form>
            
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
