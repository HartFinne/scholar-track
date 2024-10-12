<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/Reg.css') }}">

</head>

<body>
    <div class="registration-form">
        <a href="">
            <a class="btn-back fw-bold" style="text-decoration: none" href="{{ route('scholar-login') }}">&lt Go
                back</a>
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


        {{-- show if the registration is success --}}
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
            <form action="{{ route('registerScholar') }}" method="POST">
                @csrf
                <fieldset class="custom-fieldset">
                    <legend>PERSONAL INFORMATION</legend>
                    <div class="row">
                        <label for="caseCode">Case Code</label>
                        <input type="text" class="reg-input" id="caseCode" name="caseCode" required>
                    </div>
                    <div class="row">
                        <label for="area">Assigned Area</label>
                        <select class="" aria-label="area" name="assignedArea" required>
                            <option value="Mindong">Mindong</option>
                            <option value="Minxi">Minxi</option>
                            <option value="Minzhong">Minzhong</option>
                        </select>
                    </div>
                    <div class="row">
                        <label for="fname">First Name</label>
                        <input type="text" id="fname" class="reg-input" placeholder="" name="firstName" required>
                    </div>
                    <div class="row">
                        <label for="mname">Middle Name</label>
                        <input type="text" id="mname" class="reg-input" placeholder="" name="middleName"
                            required>
                    </div>
                    <div class="row">
                        <label for="lname">Last Name</label>
                        <input type="text" id="lname" class="reg-input" placeholder="" name="lastName" required>
                    </div>
                    <div class="row">
                        <label for="date">Date of Birth</label>
                        <input type="date" id="date" class="reg-input" placeholder="" name="birthdate" required>
                    </div>
                    <div class="row">
                        <label for="sex">Sex</label>
                        <select class="" aria-label="sex" name="sex" required>
                            <option value="Female">Female</option>
                            <option value="Male">Male</option>
                        </select>
                    </div>
                    <div class="row">
                        <label for="tshirt">T-Shirt Size</label>
                        <select class="" aria-label="tshirt" name="tshirt" required>
                            <option value="" disabled selected hidden>Select size</option>
                            <option value="XS">XS</option>
                            <option value="S">S</option>
                            <option value="M">M</option>
                            <option value="L">L</option>
                            <option value="XL">XL</option>
                            <option value="2XL">2XL</option>
                            <option value="3XL">3XL</option>
                            <option value="4XL">4XL</option>
                        </select>
                    </div>
                    <div class="row">
                        <label for="shoes">Shoe Size</label>
                        <input type="number" id="shoes" name="shoes" min="6" max="12" step="0.5"
                            placeholder="6-12" required>
                    </div>
                    <div class="row">
                        <label for="slippers">Slippers Size</label>
                        <input type="number" id="slippers" name="slippers" min="6" max="12"
                            step="0.5" placeholder="6-12" required>
                    </div>
                    <div class="row">
                        <label for="pants">Pants Size</label>
                        <select class="" aria-label="pants" name="pants" required>
                            <option value="" disabled selected hidden>Select size</option>
                            <option value="XS">XS</option>
                            <option value="S">S</option>
                            <option value="M">M</option>
                            <option value="L">L</option>
                            <option value="XL">XL</option>
                            <option value="2XL">2XL</option>
                            <option value="3XL">3XL</option>
                            <option value="4XL">4XL</option>
                        </select>
                    </div>
                    <div class="row">
                        <label for="joggingPants">Jogging Pants Size</label>
                        <select class="" aria-label="joggingPants" name="joggingPants" required>
                            <option value="" disabled selected hidden>Select size</option>
                            <option value="XS">XS</option>
                            <option value="S">S</option>
                            <option value="M">M</option>
                            <option value="L">L</option>
                            <option value="XL">XL</option>
                            <option value="2XL">2XL</option>
                            <option value="3XL">3XL</option>
                            <option value="4XL">4XL</option>
                        </select>
                    </div>
                    <p>Are you a member of any indigenous group?</p>
                    <div class="row-checkbox">
                        <input type="checkbox" name="isIndigenous" id="indigenousCheck" value="yes"
                            onclick="toggleInput()"> Yes
                        <input type="text" name="indigenousGroup" id="indigenousInput"
                            placeholder="If Yes, please specify" disabled>
                        <input type="checkbox" name="isIndigenous" id="noCheck" value="no"
                            onclick="disableInput()"> No
                    </div>
                    <p class="description"><i>If the scholar does not have any personal email or phone number, please
                            provide the contact information of the parent or guardian.</i>
                    </p>
                    <div class="row">
                        <label for="email">Email Address</label>
                        <input type="email" id="email" class="reg-input" placeholder="name@example.com"
                            name="emailAddress" required>
                    </div>
                    <div class="row">
                        <label for="phoneNum">Phone Number</label>
                        <input type="tel" id="phoneNum" class="reg-input" placeholder="" name="phoneNumber"
                            required>
                    </div>
                </fieldset>

                <fieldset class="custom-fieldset">
                    <legend>ADDRESS INFORMATION</legend>
                    <div class="row">
                        <label for="resAddress">Home Address</label>
                        <input type="text" id="resAddress"
                            placeholder="House #/Unit #/Floor/Bldg. Name/Street Name" name="homeAddress" required>
                    </div>
                    <div class="row">
                        <label for="brgy">Barangay</label>
                        <input type="text" id="brgy" placeholder="" name="barangay" required>
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
                        <input type="text" id="permAddress" placeholder="" name="permanentAddress" required>
                    </div>
                </fieldset>

                <fieldset class="custom-fieldset">
                    <legend>EDUCATIONAL BACKGROUND</legend>
                    <div class="row">
                        <label for="schoolLevel">School Level</label>
                        <select class="" aria-label="schoolLevel" name="schoolLevel" required>
                            {{-- <option value="" disabled selected hidden>Select school level</option>
                            <option value="Elementary">Elementary</option>
                            <option value="High School">High School</option> --}}
                            <option value="College">College</option>
                        </select>
                    </div>
                    <div class="row">
                        <label for="school">Name of Elementary/High School/University</label>
                        <input type="text" id="school" placeholder="" name="nameOfSchool" required>
                    </div>
                    <div class="row">
                        <label for="yrLevel">Year Level</label>
                        <input type="text" id="yrLevel" placeholder="Grade 6 or Grade 11 or First Year"
                            name="yearLevel" required>
                    </div>
                    <div class="row">
                        <label for="course">Course/Strand & Section</label>
                        <input type="text" id="course"
                            placeholder="Ruby or Stem or Bachelor of Science in Information Technology"
                            name="courseSection" required>
                    </div>
                    <div class="row">
                        <label for="academicYear">Academic Year</label>
                        <input type="text" id="academicYear" placeholder="2021-2022" name="academicYear"
                            required>
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
                        <input type="text" id="relation" placeholder="" name="relationToGuardian" required>
                    </div>
                    <div class="row">
                        <label for="guardianEmail">Guardian's Email Address</label>
                        <input type="email" id="guardianEmail" placeholder="" name="guardianEmailAddress"
                            required>
                    </div>
                    <div class="row">
                        <label for="guardianNum">Guardian's Phone Number</label>
                        <input type="tel" id="guardianNum" placeholder="" name="guardianPhoneNumber" required>
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
                        <input type="password" id="password_confirmation" placeholder=""
                            name="password_confirmation" required>
                    </div>
                </fieldset>

                <div class="agreement">
                    <input type="checkbox" value="on" id="agreement" name="agreement">
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
