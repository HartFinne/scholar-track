<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    {{-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> --}}
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
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
        <x-alert />


        <div class="form">
            <form action="{{ route('registerScholar') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <fieldset class="custom-fieldset">
                    <legend>SCHOLARSHIP INFORMATION</legend>

                    <div class="row">
                        <label for="area">Assigned Area</label>
                        <select class="" aria-label="area" name="assignedArea" required>
                            <option value="" hidden selected {{ old('assignedArea') ? '' : 'selected' }}>Select
                                area</option>
                            @forelse ($areas as $area)
                                <option value="{{ $area->areaname }}"
                                    {{ old('assignedArea') == $area->areaname ? 'selected' : '' }}>
                                    {{ $area->areaname }}
                                </option>
                            @empty
                                <option value="" disabled {{ old('assignedArea') ? '' : 'selected' }}>Failed to
                                    load options</option>
                            @endforelse
                        </select>
                        @error('assignedArea')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="row">
                        <label for="startdate">Start of Scholarship</label>
                        <input type="date" class="reg-input" name="startdate"
                            max="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" value="{{ old('startdate') }}"
                            required>
                        @error('startdate')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </fieldset>

                <fieldset class="custom-fieldset">
                    <legend>PERSONAL INFORMATION</legend>

                    <div class="row">
                        <label for="fname">First Name</label>
                        <input type="text" id="fname" maxlength="50" class="reg-input" name="firstName"
                            value="{{ old('firstName') }}" required>
                        {{-- @error('firstName')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror --}}
                    </div>

                    <div class="row">
                        <label for="mname">Middle Name</label>
                        <input type="text" id="mname" maxlength="50" class="reg-input" name="middleName"
                            value="{{ old('middleName') }}" required>
                        @error('middleName')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="row">
                        <label for="lname">Last Name</label>
                        <input type="text" id="lname" maxlength="50" class="reg-input" name="lastName"
                            value="{{ old('lastName') }}" required>
                        @error('lastName')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="row">
                        <label for="cname">Chinese Name</label>
                        <input type="text" id="cname" maxlength="255" class="reg-input"
                            placeholder="if none, input 'Not Applicable'" name="chineseName"
                            value="{{ old('chineseName') }}" required>
                        @error('chineseName')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="row">
                        <label for="date">Date of Birth</label>
                        <input type="date" id="date" class="reg-input" name="birthdate"
                            value="{{ old('birthdate') }}"
                            max="{{ \Carbon\Carbon::now()->subDay()->format('Y-m-d') }}" required>
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
                        <label for="occupation">Occupation</label>
                        <input type="text" id="occupation" maxlength="100" class="reg-input"
                            placeholder="if none, input 'Not Applicable'" name="occupation"
                            value="{{ old('occupation') }}" required>
                        @error('occupation')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="row">
                        <label for="income">Income</label>
                        <input type="number" id="income" min="0" step="0.01" class="reg-input"
                            placeholder="if none, input '0'" name="income" value="{{ old('income') }}" required>
                        @error('income')
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
                        <input type="radio" name="isIndigenous" id="indigenousCheck" value="Yes"
                            onclick="toggleInput(true)" style="cursor: pointer"
                            {{ old('isIndigenous') == 'Yes' ? 'checked' : '' }} required>
                        <label for="indigenousCheck" style="cursor: pointer"> Yes</label>

                        <input type="text" name="indigenousGroup" id="indigenousInput"
                            placeholder="If Yes, please specify" value="{{ old('indigenousGroup') }}"
                            {{ old('isIndigenous') == 'Yes' ? '' : 'disabled' }}>

                        <input type="radio" name="isIndigenous" id="noCheck" value="No"
                            onclick="toggleInput(false)" style="cursor: pointer"
                            {{ old('isIndigenous') == 'No' ? 'checked' : '' }} required>
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
                            value="{{ old('phoneNumber') }}" maxlength="12" minlength="11"
                            pattern="^(09\d{9}|63\d{10})$" placeholder="e.g. 09xxxxxxxxx or 63xxxxxxxxx" required>
                        @error('phoneNumber')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="row">
                        <label for="fblink">Facebook Profile Link</label>
                        <input type="text" id="fblink" class="reg-input" name="fblink"
                            value="{{ old('fblink') }}" required>
                        @error('phoneNumber')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </fieldset>



                <fieldset class="custom-fieldset">
                    <legend>ADDRESS INFORMATION</legend>
                    <div class="row">
                        <label for="resAddress">Home Address</label>
                        <input type="text" maxlength="255" id="resAddress"
                            placeholder="please provide complete address" name="homeAddress"
                            value="{{ old('homeAddress') }}" required>
                        @error('homeAddress')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="row">
                        <label for="region">Province</label>
                        <select id="region" name="region" required>
                            <option value="" disabled selected>Select Region</option>
                            <!-- regions will be populated here by JavaScript -->
                        </select>
                        @error('region')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="row">
                        <label for="city">City/Municipality</label>
                        <select id="city" name="city" required>
                            <option value="" disabled selected>Select City/Municipality</option>
                            <!-- Cities will be populated here by JavaScript -->
                        </select>
                        @error('city')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="row">
                        <label for="barangay">Barangay</label>
                        <select id="barangay" name="barangay" required>
                            <option value="" disabled selected>Select Barangay</option>
                            <!-- Barangays will be populated here by JavaScript after city selection -->
                        </select>
                        @error('barangay')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </fieldset>

                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        const regionSelect = document.getElementById('region');
                        const citySelect = document.getElementById('city');
                        const barangaySelect = document.getElementById('barangay');

                        // Populate regions on page load
                        loadRegions();

                        // Event listener for region change to load cities
                        regionSelect.addEventListener('change', function() {
                            const regionCode = regionSelect.value;
                            loadCities(regionCode);
                        });

                        // Event listener for city change to load barangays
                        citySelect.addEventListener('change', function() {
                            const cityCode = citySelect.value;
                            loadBarangays(cityCode);
                        });

                        // Function to load regions from the PSGC API
                        function loadRegions() {
                            fetch('https://psgc.gitlab.io/api/regions/')
                                .then(response => response.json())
                                .then(data => {
                                    // Sort regions alphabetically by name
                                    data.sort((a, b) => a.name.localeCompare(b.name));

                                    // Populate the region dropdown
                                    data.forEach(region => {
                                        const option = document.createElement('option');
                                        option.value = region.code;
                                        option.textContent = region.name;
                                        regionSelect.appendChild(option);
                                    });
                                })
                                .catch(error => console.error('Error loading regions:', error));
                        }

                        // Function to load cities based on selected region code from the PSGC API
                        function loadCities(regionCode) {
                            // Clear existing city options
                            citySelect.innerHTML = '<option value="" disabled selected>Select City/Municipality</option>';
                            barangaySelect.innerHTML =
                                '<option value="" disabled selected>Select Barangay</option>'; // Clear barangays as well

                            fetch(`https://psgc.gitlab.io/api/regions/${regionCode}/cities-municipalities/`)
                                .then(response => response.json())
                                .then(data => {
                                    // Sort cities alphabetically by name
                                    data.sort((a, b) => a.name.localeCompare(b.name));

                                    // Populate the city dropdown
                                    data.forEach(city => {
                                        const option = document.createElement('option');
                                        option.value = city.code;
                                        option.textContent = city.name;
                                        citySelect.appendChild(option);
                                    });
                                })
                                .catch(error => console.error('Error loading cities:', error));
                        }

                        // Function to load barangays based on selected city code from the PSGC API
                        function loadBarangays(cityCode) {
                            // Clear existing barangay options
                            barangaySelect.innerHTML = '<option value="" disabled selected>Select Barangay</option>';

                            fetch(`https://psgc.gitlab.io/api/cities-municipalities/${cityCode}/barangays/`)
                                .then(response => response.json())
                                .then(data => {
                                    // Sort barangays alphabetically by name
                                    data.sort((a, b) => a.name.localeCompare(b.name));

                                    // Populate the barangay dropdown
                                    data.forEach(barangay => {
                                        const option = document.createElement('option');
                                        option.value = barangay.code;
                                        option.textContent = barangay.name;
                                        barangaySelect.appendChild(option);
                                    });
                                })
                                .catch(error => console.error('Error loading barangays:', error));
                        }
                    });
                </script>

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
                        <select class="" aria-label="school" id="school" name="nameOfSchool" required>
                            <option value="" disabled selected hidden>Select School</option>
                            <!-- School options will be dynamically populated using JavaScript -->
                        </select>
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
                        <select id="courseSectionSelect" name="courseStrand" style="display: none;"></select>
                        <input type="text" maxlength="255" id="courseSectionInput" name="section"
                            placeholder="Enter course or section" style="display: none;">
                        @error('courseSection')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div id="collegedept">
                        <div class="row">
                            <label for="collegedept">College Department</label>
                            <input type="text" maxlength="255" id="collegedept" name="collegedept"
                                placeholder="Enter course or section">
                        </div>
                        @error('collegedept')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </fieldset>

                <script>
                    const yearLevels = @json($yearLevels);
                    const courses = @json($courses);
                    const institutions = @json($institutions);

                    function filterOptions() {
                        const schoolLevel = document.getElementById('schoolLevel').value;
                        const yrLevelSelect = document.getElementById('yrLevelSelect');
                        const schoolnames = document.getElementById('school');
                        const courseSelect = document.getElementById('courseSectionSelect');
                        const courseInput = document.getElementById('courseSectionInput');
                        const collegeDept = document.getElementById('collegedept');

                        // Clear existing options for the year level dropdown
                        yrLevelSelect.innerHTML = '<option value="" disabled selected hidden>Select level</option>';
                        courseSelect.innerHTML = '<option value="" disabled selected hidden>Select Course/Strand/Section</option>';
                        schoolnames.innerHTML = '<option value="" disabled selected hidden>Select School</option>';

                        if (schoolLevel === 'College') {
                            collegeDept.style.display = 'block';
                            collegeDept.setAttribute('required', 'required');
                        } else {
                            collegeDept.style.display = 'none';
                            collegeDept.removeAttribute('required');
                        }

                        // Populate Year Levels in the dropdown
                        if (yearLevels[schoolLevel]) {
                            yearLevels[schoolLevel].forEach(level => {
                                const option = document.createElement('option');
                                option.value = level;
                                option.textContent = level;
                                yrLevelSelect.appendChild(option);
                            });
                        }

                        if (institutions[schoolLevel]) {
                            if (institutions[schoolLevel] === null || institutions[schoolLevel].length === 0) {
                                const option = document.createElement('option');
                                option.value = "";
                                option.textContent = "No School Available";
                                schoolnames.appendChild(option);
                            } else {
                                institutions[schoolLevel].forEach(institution => {
                                    const option = document.createElement('option');
                                    option.value = institution.schoolname;
                                    option.textContent = institution.schoolname;
                                    schoolnames.appendChild(option);
                                });
                            }
                        }


                        // Toggle Course/Strand/Section based on School Level
                        if (schoolLevel === 'Elementary' || schoolLevel === 'Junior High') {
                            courseInput.style.display = 'block';
                            courseInput.setAttribute('required', 'required');
                            courseSelect.style.display = 'none';
                            courseSelect.removeAttribute('required');
                        } else {
                            courseInput.style.display = 'none';
                            courseInput.removeAttribute('required');
                            courseSelect.style.display = 'block';
                            courseSelect.setAttribute('required', 'required');

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
                        <input type="text" maxlength="255" id="guardianName" placeholder="" name="guardianName"
                            value="{{ old('guardianName') }}" required>
                        @error('guardianName')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="row">
                        <label for="relation">Relation to Guardian</label>
                        <input type="text" maxlength="50" id="relation" placeholder=""
                            name="relationToGuardian" value="{{ old('relationToGuardian') }}" required>
                        @error('relationToGuardian')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="row">
                        <label for="guardianEmail">Guardian's Email Address</label>
                        <input type="email" id="guardianEmail" maxlength="255" placeholder="name@example.com"
                            name="guardianEmailAddress" value="{{ old('guardianEmailAddress') }}" required>
                        @error('guardianEmailAddress')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="row">
                        <label for="guardianNum">Guardian's Phone Number</label>
                        <input type="tel" maxlength="12" minlength="11" id="guardianNum"
                            pattern="^(09\d{9}|63\d{10})$" placeholder="e.g. 09xxxxxxxxx or 63xxxxxxxxx"
                            name="guardianPhoneNumber" value="{{ old('guardianPhoneNumber') }}" required>
                        @error('guardianPhoneNumber')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </fieldset>


                <fieldset class="custom-fieldset">
                    <legend>SET PASSWORD</legend>
                    <div class="">
                        <span class="text-success">Your password must:</span>
                        <ul>
                            <li id="lengthRequirement" class="text-danger">Be at least <strong>8 characters
                                    long</strong>.</li>
                            <li id="uppercaseRequirement" class="text-danger">Include at least <strong>one uppercase
                                    letter (A-Z)</strong>.</li>
                            <li id="lowercaseRequirement" class="text-danger">Include at least <strong>one lowercase
                                    letter (a-z)</strong>.</li>
                            <li id="numberRequirement" class="text-danger">Include at least <strong>one numeric digit
                                    (0-9)</strong>.</li>
                            <li id="specialCharRequirement" class="text-danger">Include at least <strong>one special
                                    character (such as ! @ # $ % ^ & *)</strong>.</li>
                        </ul>
                    </div>
                    @error('password')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                    <div class="row">
                        <label for="password">Password</label>
                        <input type="password" minlength="8" id="password"
                            placeholder="must be at least 8 characters" name="password" required>
                    </div>
                    <div class="row">
                        <span></span>
                        <small id="passwordLengthWarning" class="text-danger d-none">Password must be at least 8
                            characters long.</small>
                    </div>
                    @error('password_confirmation')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                    <div class="row">
                        <label for="password_confirmation">Confirm Password</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" required>
                    </div>
                    <div class="row">
                        <span></span>
                        <small id="passwordMatchWarning" class="text-danger d-none">Passwords do not match.</small>
                    </div>
                </fieldset>

                <div class="agreement">
                    <input type="checkbox" value="on" id="agreement" name="agreement" required>
                    <label for="agreement">
                        <i>I hereby attest that the information I have provided is true and correct.
                            I also give my consent to Tzu Chi Foundation to obtain, retain and verify
                            my personal information for the purpose of this registration.</i>
                    </label>
                </div>
                <div class="register">
                    <button type="submit" class="btn-register fw-bold">Register</button>
                </div>

                <script>
                    const passwordInput = document.getElementById('password');
                    const confirmPasswordInput = document.getElementById('password_confirmation');
                    const passwordMatchWarning = document.getElementById('passwordMatchWarning');
                    const registerButton = document.querySelector('.btn-register');

                    // Requirement elements
                    const lengthRequirement = document.getElementById('lengthRequirement');
                    const uppercaseRequirement = document.getElementById('uppercaseRequirement');
                    const lowercaseRequirement = document.getElementById('lowercaseRequirement');
                    const numberRequirement = document.getElementById('numberRequirement');
                    const specialCharRequirement = document.getElementById('specialCharRequirement');

                    // Disable register button initially
                    registerButton.disabled = true;

                    passwordInput.addEventListener('input', function() {
                        checkPasswordRequirements();
                        checkPasswordMatch();
                        toggleRegisterButton();
                    });

                    confirmPasswordInput.addEventListener('input', function() {
                        checkPasswordMatch();
                        toggleRegisterButton();
                    });

                    function checkPasswordRequirements() {
                        const passwordValue = passwordInput.value;

                        // Check length requirement
                        if (passwordValue.length >= 8) {
                            lengthRequirement.classList.remove('text-danger');
                            lengthRequirement.classList.add('text-success');
                        } else {
                            lengthRequirement.classList.remove('text-success');
                            lengthRequirement.classList.add('text-danger');
                        }

                        // Check uppercase requirement
                        if (/[A-Z]/.test(passwordValue)) {
                            uppercaseRequirement.classList.remove('text-danger');
                            uppercaseRequirement.classList.add('text-success');
                        } else {
                            uppercaseRequirement.classList.remove('text-success');
                            uppercaseRequirement.classList.add('text-danger');
                        }

                        // Check lowercase requirement
                        if (/[a-z]/.test(passwordValue)) {
                            lowercaseRequirement.classList.remove('text-danger');
                            lowercaseRequirement.classList.add('text-success');
                        } else {
                            lowercaseRequirement.classList.remove('text-success');
                            lowercaseRequirement.classList.add('text-danger');
                        }

                        // Check number requirement
                        if (/[0-9]/.test(passwordValue)) {
                            numberRequirement.classList.remove('text-danger');
                            numberRequirement.classList.add('text-success');
                        } else {
                            numberRequirement.classList.remove('text-success');
                            numberRequirement.classList.add('text-danger');
                        }

                        // Check special character requirement (accept any non-alphanumeric character)
                        if (/[^a-zA-Z0-9]/.test(passwordValue)) {
                            specialCharRequirement.classList.remove('text-danger');
                            specialCharRequirement.classList.add('text-success');
                        } else {
                            specialCharRequirement.classList.remove('text-success');
                            specialCharRequirement.classList.add('text-danger');
                        }
                    }

                    function checkPasswordMatch() {
                        if (passwordInput.value !== confirmPasswordInput.value) {
                            passwordMatchWarning.classList.remove('d-none');
                        } else {
                            passwordMatchWarning.classList.add('d-none');
                        }
                    }

                    function toggleRegisterButton() {
                        // Enable the register button only if all requirements are met and passwords match
                        const allRequirementsMet =
                            passwordInput.value.length >= 8 &&
                            /[A-Z]/.test(passwordInput.value) &&
                            /[a-z]/.test(passwordInput.value) &&
                            /[0-9]/.test(passwordInput.value) &&
                            /[^a-zA-Z0-9]/.test(passwordInput.value) &&
                            passwordInput.value === confirmPasswordInput.value;

                        registerButton.disabled = !allRequirementsMet;
                    }
                </script>
            </form>
        </div>
    </div>

    <script>
        function toggleInput(isYesChecked) {
            const indigenousInput = document.getElementById('indigenousInput');
            const yesCheck = document.getElementById('indigenousCheck');
            const noCheck = document.getElementById('noCheck');

            if (isYesChecked) {
                // Enable the input if "Yes" is selected, and require it
                indigenousInput.disabled = false;
                indigenousInput.required = true;
                noCheck.checked = false; // Uncheck "No"
            } else {
                // Disable the input if "No" is selected, and remove requirement
                indigenousInput.disabled = true;
                indigenousInput.required = false;
                indigenousInput.value = ''; // Clear input if not needed
                yesCheck.checked = false; // Uncheck "Yes"
            }
        }

        // Initial call in case of pre-existing selection (e.g., from old() in Laravel)
        document.addEventListener("DOMContentLoaded", function() {
            if (document.getElementById('indigenousCheck').checked) {
                toggleInput(true);
            } else if (document.getElementById('noCheck').checked) {
                toggleInput(false);
            }
        });

        document.addEventListener('DOMContentLoaded', () => {
            // Load saved data from sessionStorage for all input types
            document.querySelectorAll('input, textarea, select').forEach(element => {
                if (element.type === 'file') {
                    // Skip file inputs (cannot persist due to security reasons)
                    return;
                } else if (element.id === 'indigenousInput') {
                    return;
                } else {
                    // Restore value for text, textarea, number, date, and select inputs
                    const savedValue = sessionStorage.getItem(element.name);
                    if (savedValue) {
                        element.value = savedValue;
                    }
                }
            });

            // Save data to sessionStorage on input change
            document.querySelectorAll('input, textarea, select').forEach(element => {
                element.addEventListener('input', () => {
                    if (element.type === 'file') {
                        // Skip file inputs (cannot be saved for security reasons)
                        return;
                    } else {
                        // Save value for text, textarea, number, date, and select inputs
                        sessionStorage.setItem(element.name, element.value);
                    }
                });
            });

            // Clear sessionStorage on form submit
            document.querySelector('form').addEventListener('submit', () => {
                sessionStorage.clear();
            });
        });
    </script>
</body>

</html>
