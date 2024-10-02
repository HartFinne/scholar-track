<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/roleReg.css') }}">

</head>

<body>
    <div class="registration-form">
        <a href="">
            <button class="btn-back fw-bold">&lt Go back</button>
        </a>
        <div class="d-flex justify-content-center header">
            <img src="{{ asset('images/logo.png') }}" alt="" class="logo">
            <h6 class="fw-bold ps-3">Tzu Chi Philippines<br>Educational Assistance Program</h6>
        </div>

        <h1 class="text-center p-3 fw-bold">REGISTRATION FORM</h1>
        <p class="mt-4 mb-5 description">Welcome to Tzu Chi Scholarship Registration Form. Please fill out the required
            fields
            in each section with true and correct information to complete your registration. If a field does not apply
            to you, write <strong>N/A</strong>.
        </p>

        <div class="form">
            <form action="">
                <fieldset class="custom-fieldset">
                    <legend>PERSONAL INFORMATION</legend>
                    <div class="row">
                        <label for="caseCode">Case Code</label>
                        <input type="text" class="reg-input" id="caseCode" required>
                    </div>
                    <div class="row">
                        <label for="area">Assigned Area</label>
                        <select class="" aria-label="area" required>
                            <option value="1">Mindong</option>
                            <option value="2">Minxi</option>
                            <option value="3">Minzhong</option>
                        </select>
                    </div>
                    <div class="row">
                        <label for="fname">First Name</label>
                        <input type="text" id="fname" class="reg-input" placeholder="" name="fname" required>
                    </div>
                    <div class="row">
                        <label for="mname">Middle Name</label>
                        <input type="text" id="mname" class="reg-input" placeholder="" name="fname" required>
                    </div>
                    <div class="row">
                        <label for="lname">Last Name</label>
                        <input type="text" id="lname" class="reg-input" placeholder="" name="fname" required>
                    </div>
                    <div class="row">
                        <label for="date">Date of Birth</label>
                        <input type="date" id="date" class="reg-input" placeholder="" name="date" required>
                    </div>
                    <div class="row">
                        <label for="sex">Sex</label>
                        <select class="" aria-label="sex" required>
                            <option value="1">Female</option>
                            <option value="2">Male</option>
                        </select>
                    </div>
                    <div class="row">
                        <label for="fbName">Facebook Name</label>
                        <input type="text" id="fbName" class="reg-input" placeholder="" name="fbName" required>
                    </div>
                    <p>Are you a member of any indigenous group?</p>
                    <div class="row-checkbox">
                        <input type="checkbox" id="indigenousCheck" onclick="toggleInput()"> Yes
                        <input type="text" id="indigenousInput" placeholder="If Yes, please specify" disabled>
                        <input type="checkbox" id="noCheck" onclick="disableInput()"> No
                    </div>
                    <p class="description"><i>If the scholar does not have any personal email or phone number, please
                            provide the contact information of the parent or guardian.</i>
                    </p>
                    <div class="row">
                        <label for="email">Email Address</label>
                        <input type="email" id="email" class="reg-input" placeholder="name@example.com"
                            name="email" required>
                    </div>
                    <div class="row">
                        <label for="phoneNum">Phone Number</label>
                        <input type="tel" id="phoneNum" class="reg-input" placeholder="" name="phonenum" required>
                    </div>
                </fieldset>

                <fieldset class="custom-fieldset">
                    <legend>ADDRESS INFORMATION</legend>
                    <div class="row">
                        <label for="resAddress">Home Address</label>
                        <input type="text" id="resAddress"
                            placeholder="House #/Unit #/Floor/Bldg. Name/Street Name" name="residential" required>
                    </div>
                    <div class="row">
                        <label for="brgy">Barangay</label>
                        <input type="text" id="brgy" placeholder="" name="brgy" required>
                    </div>
                    <div class="row">
                        <label for="city">City/Municipality</label>
                        <input type="text" id="city" placeholder="" name="city" required>
                    </div>
                    <div class="row">
                        <label for="province">Province</label>
                        <input type="text" id="province" placeholder="" name="province" required>
                    </div>
                    <div class="row">
                        <label for="region">Region</label>
                        <input type="text" id="region" placeholder="" name="region" required>
                    </div>
                    <div class="row">
                        <label for="permAddress">Permanent Address</label>
                        <input type="text" id="permAddress" placeholder="" name="permadd" required>
                    </div>
                </fieldset>

                <fieldset class="custom-fieldset">
                    <legend>EDUCATIONAL BACKGROUND</legend>
                    <div class="row">
                        <label for="schoolLevel">School Level</label>
                        <select class="" aria-label="schoolLevel" required>
                            <option value="" disabled selected hidden>Select school level</option>
                            <option value="1">Elementary</option>
                            <option value="2">High School</option>
                            <option value="2">College</option>
                        </select>
                    </div>
                    <div class="row">
                        <label for="school">Name of Elementary/High School/University</label>
                        <input type="text" id="school" placeholder="" name="school" required>
                    </div>
                    <div class="row">
                        <label for="collegeDept">College Department</label>
                        <input type="text" id="collegeDept" placeholder="" name="collegeDept" required>
                    </div>
                    <div class="row">
                        <label for="yrLevel">Year Level</label>
                        <input type="text" id="yrLevel" placeholder="" name="yrLevel" required>
                    </div>
                    <div class="row">
                        <label for="course">Course/Strand & Section</label>
                        <input type="text" id="course" placeholder="" name="course" required>
                    </div>
                </fieldset>

                <fieldset class="custom-fieldset">
                    <legend>FAMILY INFORMATION</legend>
                    <div class="row">
                        <label for="guardianName">Guardian's Full Name</label>
                        <input type="text" id="guardianName" placeholder="" name="guardianName" required>
                    </div>
                    <div class="row">
                        <label for="relation">Relation to Guardian</label>
                        <input type="text" id="relation" placeholder="" name="relation" required>
                    </div>
                    <div class="row">
                        <label for="guardianEmail">Guardian's Email Address</label>
                        <input type="email" id="guardianEmail" placeholder="" name="uardianEmail" required>
                    </div>
                    <div class="row">
                        <label for="guardianNum">Guardian's Phone Number</label>
                        <input type="tel" id="guardianNum" placeholder="" name="guardianNum"" required>
                    </div>
                </fieldset>

                <fieldset class="custom-fieldset">
                    <legend>SET PASSWORD</legend>
                    <div class="row">
                        <label for="password">Password</label>
                        <input type="password"id="password" placeholder="" name="password" required>
                    </div>
                    <div class="row">
                        <label for="conPassword">Confirm Password</label>
                        <input type="password" id="conPassword" placeholder="" name="conPassword" required>
                    </div>
                </fieldset>

                <div class="agreement">
                    <input type="checkbox" value="" id="agreement">
                    <label for="agreement">
                        <i>I hereby attest that the information I have provided is true and correct.
                            I also give my consent to Tzu Chi Foundation to obtain, retain and verify
                            my personal information for the purpose of this registration.</i>
                    </label>
                </div>
                <div class="register">
                    <button type="submit" class="btn-register fw-bold">Register</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function toggleInput() {
            var indigenousCheck = document.getElementById("indigenousCheck");
            var indigenousInput = document.getElementById("indigenousInput");
            var noCheck = document.getElementById("noCheck");

            if (indigenousCheck.checked) {
                indigenousInput.disabled = false; // Enable input if Yes is checked
                noCheck.checked = false; // Uncheck No if Yes is checked
            } else {
                indigenousInput.disabled = true; // Disable input if Yes is unchecked
            }
        }

        function disableInput() {
            var noCheck = document.getElementById("noCheck");
            var indigenousCheck = document.getElementById("indigenousCheck");
            var indigenousInput = document.getElementById("indigenousInput");

            if (noCheck.checked) {
                indigenousInput.disabled = true; // Disable input if No is checked
                indigenousCheck.checked = false; // Uncheck Yes if No is checked
            }
        }
    </script>
</body>

</html>
