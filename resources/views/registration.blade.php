<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/registration.css') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">

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
            to you, write <strong>Not Applicable</strong>.
        </p>


        {{-- show if the registration is success --}}
        <x-alert />


        <div class="form">
            <form action="{{ route('registerScholar') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <fieldset class="custom-fieldset">
                    <legend>SCHOLARSHIP INFORMATION</legend>

                    <div class="row">
                        <label for="area">Assigned Area</label>
                        <select class="" aria-label="area" name="assignedArea" required>
                            <option value="" hidden disabled {{ old('assignedArea') ? '' : 'selected' }}>Select
                                area</option>
                            <option value="Mindong" {{ old('assignedArea') == 'Mindong' ? 'selected' : '' }}>Mindong
                            </option>
                            <option value="Minxi" {{ old('assignedArea') == 'Minxi' ? 'selected' : '' }}>Minxi</option>
                            <option value="Minzhong" {{ old('assignedArea') == 'Minzhong' ? 'selected' : '' }}>Minzhong
                            </option>
                            <option value="Bicol" {{ old('assignedArea') == 'Bicol' ? 'selected' : '' }}>Bicol</option>
                            <option value="Davao" {{ old('assignedArea') == 'Davao' ? 'selected' : '' }}>Davao
                            </option>
                            <option value="Iloilo" {{ old('assignedArea') == 'Iloilo' ? 'selected' : '' }}>Iloilo
                            </option>
                            <option value="Palo" {{ old('assignedArea') == 'Palo' ? 'selected' : '' }}>Palo</option>
                            <option value="Zamboanga" {{ old('assignedArea') == 'Zamboanga' ? 'selected' : '' }}>
                                Zamboanga</option>
                        </select>
                        @error('assignedArea')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="row">
                        <label for="scholartype">Scholar Type</label>
                        <select class="" aria-label="scholartype" name="scholartype" required>
                            <option value="" hidden disabled {{ old('scholartype') ? '' : 'selected' }}>Select
                                type</option>
                            <option value="Old Scholar" {{ old('scholartype') == 'Old Scholar' ? 'selected' : '' }}>Old
                                Scholar</option>
                            <option value="New Scholar" {{ old('scholartype') == 'New Scholar' ? 'selected' : '' }}>New
                                Scholar</option>
                        </select>
                        @error('scholartype')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="row">
                        <label for="startdate">Start of Scholarship</label>
                        <input type="date" class="reg-input" name="startdate" value="{{ old('startdate') }}"
                            required>
                        @error('startdate')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="row">
                        <label for="enddate">End of Scholarship</label>
                        <input type="date" class="reg-input" name="enddate" value="{{ old('enddate') }}" required>
                        @error('enddate')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </fieldset>

                <fieldset class="custom-fieldset">
                    <legend>PERSONAL INFORMATION</legend>

                    <div class="row">
                        <label for="fname">First Name</label>
                        <input type="text" id="fname" class="reg-input" name="firstName"
                            value="{{ old('firstName') }}" required>
                        @error('firstName')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="row">
                        <label for="mname">Middle Name</label>
                        <input type="text" id="mname" class="reg-input" name="middleName"
                            value="{{ old('middleName') }}" required>
                        @error('middleName')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="row">
                        <label for="lname">Last Name</label>
                        <input type="text" id="lname" class="reg-input" name="lastName"
                            value="{{ old('lastName') }}" required>
                        @error('lastName')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="row">
                        <label for="cname">Chinese Name</label>
                        <input type="text" id="cname" class="reg-input"
                            placeholder="if none, input 'Not Applicable'" name="chineseName"
                            value="{{ old('chineseName') }}" required>
                        @error('chineseName')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="row">
                        <label for="date">Date of Birth</label>
                        <input type="date" id="date" class="reg-input" name="birthdate"
                            value="{{ old('birthdate') }}" required>
                        @error('birthdate')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="row">
                        <label for="sex">Sex</label>
                        <select name="sex" required>
                            <option value="Female" {{ old('sex') == 'Female' ? 'selected' : '' }}>Female</option>
                            <option value="Male" {{ old('sex') == 'Male' ? 'selected' : '' }}>Male</option>
                        </select>
                        @error('sex')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="row">
                        <label for="tshirt">T-Shirt Size</label>
                        <select name="tshirt" required>
                            <option value="" disabled selected hidden>Select size</option>
                            <option value="XS" {{ old('tshirt') == 'XS' ? 'selected' : '' }}>XS</option>
                            <option value="S" {{ old('tshirt') == 'S' ? 'selected' : '' }}>S</option>
                            <option value="M" {{ old('tshirt') == 'M' ? 'selected' : '' }}>M</option>
                            <option value="L" {{ old('tshirt') == 'L' ? 'selected' : '' }}>L</option>
                            <option value="XL" {{ old('tshirt') == 'XL' ? 'selected' : '' }}>XL</option>
                            <option value="2XL" {{ old('tshirt') == '2XL' ? 'selected' : '' }}>2XL</option>
                            <option value="3XL" {{ old('tshirt') == '3XL' ? 'selected' : '' }}>3XL</option>
                            <option value="4XL" {{ old('tshirt') == '4XL' ? 'selected' : '' }}>4XL</option>
                        </select>
                        @error('tshirt')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="row">
                        <label for="shoes">Shoe Size</label>
                        <input type="number" id="shoes" name="shoes" min="6" max="12"
                            step="0.5" placeholder="6-12" value="{{ old('shoes') }}" required>
                        @error('shoes')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="row">
                        <label for="slippers">Slippers Size</label>
                        <input type="number" id="slippers" name="slippers" min="6" max="12"
                            step="0.5" placeholder="6-12" value="{{ old('slippers') }}" required>
                        @error('slippers')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="row">
                        <label for="pants">Pants Size</label>
                        <select name="pants" required>
                            <option value="" disabled selected hidden>Select size</option>
                            <option value="XS" {{ old('pants') == 'XS' ? 'selected' : '' }}>XS</option>
                            <option value="S" {{ old('pants') == 'S' ? 'selected' : '' }}>S</option>
                            <option value="M" {{ old('pants') == 'M' ? 'selected' : '' }}>M</option>
                            <option value="L" {{ old('pants') == 'L' ? 'selected' : '' }}>L</option>
                            <option value="XL" {{ old('pants') == 'XL' ? 'selected' : '' }}>XL</option>
                            <option value="2XL" {{ old('pants') == '2XL' ? 'selected' : '' }}>2XL</option>
                            <option value="3XL" {{ old('pants') == '3XL' ? 'selected' : '' }}>3XL</option>
                            <option value="4XL" {{ old('pants') == '4XL' ? 'selected' : '' }}>4XL</option>
                        </select>
                        @error('pants')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="row">
                        <label for="joggingPants">Jogging Pants Size</label>
                        <select name="joggingPants" required>
                            <option value="" disabled selected hidden>Select size</option>
                            <option value="XS" {{ old('joggingPants') == 'XS' ? 'selected' : '' }}>XS</option>
                            <option value="S" {{ old('joggingPants') == 'S' ? 'selected' : '' }}>S</option>
                            <option value="M" {{ old('joggingPants') == 'M' ? 'selected' : '' }}>M</option>
                            <option value="L" {{ old('joggingPants') == 'L' ? 'selected' : '' }}>L</option>
                            <option value="XL" {{ old('joggingPants') == 'XL' ? 'selected' : '' }}>XL</option>
                            <option value="2XL" {{ old('joggingPants') == '2XL' ? 'selected' : '' }}>2XL</option>
                            <option value="3XL" {{ old('joggingPants') == '3XL' ? 'selected' : '' }}>3XL</option>
                            <option value="4XL" {{ old('joggingPants') == '4XL' ? 'selected' : '' }}>4XL</option>
                        </select>
                        @error('joggingPants')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <p>Are you a member of any indigenous group?</p>
                    <div class="row-checkbox">
                        <input type="checkbox" name="isIndigenous" id="indigenousCheck" value="Yes"
                            onclick="toggleInput()" style="cursor: pointer"
                            {{ old('isIndigenous') == 'Yes' ? 'checked' : '' }}>
                        <label for="indigenousCheck" style="cursor: pointer"> Yes</label>
                        <input type="text" name="indigenousGroup" id="indigenousInput"
                            placeholder="If Yes, please specify" value="{{ old('indigenousGroup') }}"
                            {{ old('isIndigenous') == 'Yes' ? '' : 'disabled' }}>
                        <input type="checkbox" name="isIndigenous" id="noCheck" value="No"
                            onclick="disableInput()" style="cursor: pointer"
                            {{ old('isIndigenous') == 'No' ? 'checked' : '' }}>
                        <label for="noCheck" style="cursor: pointer"> No</label>
                        @error('indigenousGroup')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <p class="description"><i>If the scholar does not have any personal email or phone number, please
                            provide the contact information of the parent or guardian.</i></p>

                    <div class="row">
                        <label for="email">Email Address</label>
                        <input type="email" id="email" class="reg-input" name="emailAddress"
                            placeholder="name@example.com" value="{{ old('emailAddress') }}" required>
                        @error('emailAddress')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="row">
                        <label for="phoneNum">Phone Number</label>
                        <input type="tel" id="phoneNum" class="reg-input" name="phoneNumber"
                            value="{{ old('phoneNumber') }}" required>
                        @error('phoneNumber')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </fieldset>



                <fieldset class="custom-fieldset">
                    <legend>ADDRESS INFORMATION</legend>

                    <div class="row">
                        <label for="resAddress">Home Address</label>
                        <input type="text" id="resAddress" placeholder="please provide complete address"
                            name="homeAddress" value="{{ old('homeAddress') }}" required>
                        @error('homeAddress')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="row">
                        <label for="brgy">Barangay</label>
                        <input type="text" id="brgy" placeholder="" name="barangay"
                            value="{{ old('barangay') }}" required>
                        @error('barangay')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="row">
                        <label for="city">City/Municipality</label>
                        <input type="text" id="city" placeholder="" name="city"
                            value="{{ old('city') }}" required>
                        @error('city')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="row">
                        <label for="permAddress">Permanent Address</label>
                        <input type="text" id="permAddress" placeholder="please provide complete address"
                            name="permanentAddress" value="{{ old('permanentAddress') }}" required>
                        @error('permanentAddress')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </fieldset>


                <fieldset class="custom-fieldset">
                    <legend>EDUCATIONAL BACKGROUND</legend>

                    <!-- School Level Dropdown -->
                    <div class="row">
                        <label for="schoolLevel">School Level</label>
                        <select class="" aria-label="schoolLevel" name="schoolLevel" id="schoolLevel" required
                            onchange="filterOptions()">
                            <option value="" disabled selected hidden>Select school level</option>
                            <option value="Elementary" {{ old('schoolLevel') == 'Elementary' ? 'selected' : '' }}>
                                Elementary</option>
                            <option value="Junior High" {{ old('schoolLevel') == 'Junior High' ? 'selected' : '' }}>
                                Junior High School</option>
                            <option value="Senior High" {{ old('schoolLevel') == 'Senior High' ? 'selected' : '' }}>
                                Senior High School</option>
                            <option value="College" {{ old('schoolLevel') == 'College' ? 'selected' : '' }}>College
                            </option>
                        </select>
                        @error('schoolLevel')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="row">
                        <label for="school">Name of School</label>
                        <input type="text" id="school" placeholder="" name="nameOfSchool" required>
                    </div>

                    <!-- Grade/Year Level Dropdown -->
                    <div class="row">
                        <label for="yrLevel">Grade/Year Level</label>
                        <select class="" aria-label="yrLevel" id="yrLevelSelect" name="yearLevel" required>
                            <option value="" disabled selected hidden>Select level</option>
                            <!-- Year level options will be dynamically populated using JavaScript -->
                        </select>
                        @error('yearLevel')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Course/Strand/Section Field (Input or Dropdown) -->
                    <div class="row">
                        <label for="course">Course/Strand/Section</label>
                        <select id="courseSectionSelect" name="courseSection" required
                            style="display: none;"></select>
                        <input type="text" id="courseSectionInput" placeholder="Enter course or section"
                            style="display: none;">
                        @error('courseSection')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="row">
                        <label for="acadyear">Academic Year</label>
                        <input type="text" id="acadyear" placeholder="ex: 2024-2025" name="acadyear"
                            value="{{ old('acadyear') }}" required>
                        @error('acadyear')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </fieldset>

                <script>
                    // Pass PHP data to JavaScript
                    const yearLevels = @json($yearLevels);
                    const courses = @json($courses);

                    function filterOptions() {
                        const schoolLevel = document.getElementById('schoolLevel').value;
                        const yrLevelSelect = document.getElementById('yrLevelSelect');
                        const courseSelect = document.getElementById('courseSectionSelect');
                        const courseInput = document.getElementById('courseSectionInput');

                        // Clear existing options for the year level dropdown
                        yrLevelSelect.innerHTML = '<option value="" disabled selected hidden>Select level</option>';
                        courseSelect.innerHTML = '<option value="" disabled selected hidden>Select Course/Strand/Section</option>';

                        // Populate Year Levels in the dropdown
                        if (yearLevels[schoolLevel]) {
                            yearLevels[schoolLevel].forEach(level => {
                                const option = document.createElement('option');
                                option.value = level;
                                option.textContent = level;
                                yrLevelSelect.appendChild(option);
                            });
                        }

                        // Toggle Course/Strand/Section based on School Level
                        if (schoolLevel === 'Elementary' || schoolLevel === 'Junior High') {
                            courseInput.style.display = 'block';
                            courseInput.setAttribute('required', 'true');
                            courseSelect.style.display = 'none';
                            courseSelect.removeAttribute('required');
                        } else {
                            courseInput.style.display = 'none';
                            courseInput.removeAttribute('required');
                            courseSelect.style.display = 'block';
                            courseSelect.setAttribute('required', 'true');

                            // Populate Course Options in the dropdown if not Elementary or Junior High
                            if (courses[schoolLevel]) {
                                courses[schoolLevel].forEach(course => {
                                    const option = document.createElement('option');
                                    option.value = course.coursename;
                                    option.textContent = course.coursename;
                                    courseSelect.appendChild(option);
                                });
                            }
                        }
                    }

                    // Initialize filters on page load (useful if validation fails and the page reloads)
                    document.addEventListener('DOMContentLoaded', filterOptions);
                </script>

                <fieldset class="custom-fieldset">
                    <legend>FAMILY INFORMATION</legend>

                    <div class="row">
                        <label for="guardianName">Guardian's Full Name</label>
                        <input type="text" id="guardianName" placeholder="" name="guardianName"
                            value="{{ old('guardianName') }}" required>
                        @error('guardianName')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="row">
                        <label for="relation">Relation to Guardian</label>
                        <input type="text" id="relation" placeholder="" name="relationToGuardian"
                            value="{{ old('relationToGuardian') }}" required>
                        @error('relationToGuardian')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="row">
                        <label for="guardianEmail">Guardian's Email Address</label>
                        <input type="email" id="guardianEmail" placeholder="name@example.com"
                            name="guardianEmailAddress" value="{{ old('guardianEmailAddress') }}" required>
                        @error('guardianEmailAddress')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="row">
                        <label for="guardianNum">Guardian's Phone Number</label>
                        <input type="tel" id="guardianNum" placeholder="" name="guardianPhoneNumber"
                            value="{{ old('guardianPhoneNumber') }}" required>
                        @error('guardianPhoneNumber')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </fieldset>


                <fieldset class="custom-fieldset">
                    <legend>SET PASSWORD</legend>
                    @error('password')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                    <div class="row">
                        <label for="password">Password</label>
                        <input type="password" id="password" placeholder="must be at least 8 characters"
                            name="password" required>
                    </div>
                    @error('password_confirmation')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror

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
