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
    <x-alert />

    <!-- MAIN -->
    <div class="ctn-main">
        <div class="container">
            <a href="" class="goback">&lt Go back</a>
            <h1 class="title text-center">Scholarship Renewal Form</h1>
            <p class="mt-4 mb-5 description">Welcome to Tzu Chi Scholarship Renewal Form. Please fill out the required
                fields
                in each section with true and correct information to complete your registration. If a field does not
                apply,
                write <strong>Not Applicable</strong>.
            </p>

            <div class="renewal-form">
                <form autocomplete="on" method="POST" action="{{ route('saveapplicant') }}"
                    enctype="multipart/form-data">
                    @csrf
                    <fieldset class="custom-fieldset">
                        <legend>Personal Information</legend>
                        <div class="row">
                            <div class="column">
                                <label for="scholarname">Name</label>
                                <input type="text" id="scholarname" name="scholarname" maxlength="255"
                                    value="{{ $user->basicInfo->scLastname }}, {{ $user->basicInfo->scFirstname }} {{ $user->basicInfo->scMiddlename }}"
                                    placeholder="(Last Name, First Name, Middle Name)" readonly>
                            </div>
                            <div class="column">
                                <label for="chinesename">Chinese Name</label>
                                <input type="text" id="chinesename" name="chinesename" maxlength="255"
                                    value="{{ $user->basicInfo->scChinesename }}" readonly>
                            </div>
                        </div>
                        <div class="row">
                            <div class="column">
                                <label for="sex">Sex</label>
                                <input type="text" id="sex" name="sex" maxlength="10"
                                    value="{{ $user->basicInfo->scSex }}" readonly>
                            </div>
                            <div class="column">
                                <label for="age">Age</label>
                                <input type="number" name="age" value="{{ $user->basicInfo->scAge }}" readonly>
                            </div>
                            <div class="column">
                                <label for="birthdate">Birthdate</label>
                                <input type="date" name="birthdate"
                                    max="{{ \Carbon\Carbon::now()->subDay()->format('Y-m-d') }}"
                                    value="{{ $user->basicInfo->scDateOfBirth }}" readonly>
                            </div>
                        </div>
                        <div class="row">
                            <div class="column">
                                <label for="homeaddress">Home Address</label>
                                <input type="text" name="homeaddress"
                                    value="{{ $user->addressInfo->scResidential }}" max="255"
                                    placeholder="(House #/Unit #/Floor/Bldg. Name/Street Name)" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="column">
                                <label for="region">Region</label>
                                <select id="region" name="region" class="form-select" required>
                                </select>
                            </div>
                            <div class="column">
                                <label for="city">City/Municipality</label>
                                <select id="city" name="city" required class="form-select">
                                </select>
                            </div>
                            <div class="column">
                                <label for="barangay">Barangay</label>
                                <select id="barangay" name="barangay" class="form-select" required>
                                </select>
                            </div>
                        </div>

                        <script>
                            document.addEventListener('DOMContentLoaded', function() {
                                const regionSelect = document.getElementById('region');
                                const citySelect = document.getElementById('city');
                                const barangaySelect = document.getElementById('barangay');
                                const selectedRegion = "{{ $user->addressinfo->scRegion ?? '' }}";
                                const selectedCity = "{{ $user->addressinfo->scCity ?? '' }}";
                                const selectedBarangay = "{{ $user->addressinfo->scBarangay ?? '' }}";

                                // Populate regions on page load
                                loadRegions();

                                // Event listener for region change to load cities
                                regionSelect.addEventListener('change', function() {
                                    const regionCode = regionSelect.value; // Corrected `RegionSelect` to `regionSelect`
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
                                                option.value = region.code; // Use code as value
                                                option.textContent = region.name; // Display name

                                                // Check if this is the selected region
                                                if (region.code === selectedRegion) {
                                                    option.selected = true;
                                                }

                                                regionSelect.appendChild(option);
                                            });

                                            // Trigger the city load if a region is preselected
                                            if (selectedRegion) {
                                                loadCities(selectedRegion);
                                            }
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
                                                option.value = city.code; // Use code as value
                                                option.textContent = city.name; // Display name

                                                // Check if this is the selected city
                                                if (city.code === selectedCity) {
                                                    option.selected = true;
                                                }

                                                citySelect.appendChild(option);
                                            });

                                            // Trigger the barangay load if a city is preselected
                                            if (selectedCity) {
                                                loadBarangays(selectedCity);
                                            }
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
                                                option.value = barangay.code; // Use code as value
                                                option.textContent = barangay.name; // Display name

                                                // Check if this is the selected barangay
                                                if (barangay.code === selectedBarangay) {
                                                    option.selected = true;
                                                }

                                                barangaySelect.appendChild(option);
                                            });
                                        })
                                        .catch(error => console.error('Error loading barangays:', error));
                                }
                            });
                        </script>

                        <div class="row">
                            <div class="column">
                                <label for="email">Email Address</label>
                                <input type="email" name="email" maxlength="255" value="{{ $user->scEmail }}"
                                    required>
                            </div>
                            <div class="column">
                                <label for="phonenum">Cellphone No./Landline</label>
                                <input type="tel" minlength="11" maxlength="12" name="phonenum"
                                    pattern="^(09\d{9}|63\d{10})$" value="{{ $user->scPhoneNum }}" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="column">
                                <label for="occupation">Occupation</label>
                                <input type="text" name="occupation" maxlength="100"
                                    value="{{ $user->basicInfo->scOccupation }}" required>
                            </div>
                            <div class="column">
                                <label for="income">Income</label>
                                <input type="number" name="income" value="{{ $user->basicInfo->scIncome }}"
                                    placeholder="0 if none" min="0" required step="0.01">
                            </div>
                        </div>
                        <div class="row">
                            <div class="column">
                                <label for="fblink">Facebook Link</label>
                                <input type="url" name="fblink" value="{{ $user->basicInfo->scFblink }}"
                                    required>
                            </div>
                            <div class="column">
                                <p>Are you a member of any indigenous group?</p>
                                <div class="row-radio">
                                    <input type="radio" id="indigenousCheck" name="isIndigenous" value="Yes"
                                        onclick="toggleInput()"
                                        {{ $user->basicInfo->scIsIndigenous == 'Yes' ? 'checked' : '' }}
                                        style="cursor: pointer">
                                    <label for="indigenousCheck" style="cursor: pointer">Yes</label>
                                    <input type="radio" id="noCheck" name="isIndigenous" value="No"
                                        onclick="disableInput()"
                                        {{ $user->basicInfo->scIsIndigenous == 'No' ? 'checked' : '' }}
                                        style="cursor: pointer">
                                    <label for="noCheck" style="cursor: pointer">No</label>
                                </div>
                                <input type="text" name="indigenousgroup" id="indigenousInput"
                                    placeholder="Please specify the group you belong to" disabled
                                    value="{{ $user->basicInfo->scIndigenousgroup }}" maxlength="100">
                            </div>
                        </div>
                    </fieldset>

                    <fieldset class="custom-fieldset">
                        <legend>Educational Background</legend>
                        <div class="row">
                            <div class="column">
                                <label for="schoolname">Name of
                                    {{ $user->education->scSchoolLevel == 'College' ? 'University' : 'School' }}</label>
                                <select name="schoolname" id="schoolname" required>
                                    @foreach ($institutions['College'] as $insti)
                                        <option value="{{ $insti->schoolname }}"
                                            {{ $user->education->scSchoolName == $insti->schoolname ? 'selected' : '' }}>
                                            {{ $insti->schoolname }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        @if ($user->education->scSchoolLevel == 'College')
                            <div class="row">
                                <div class="column">
                                    <label for="collegedept">College Department</label>
                                    <input type="text" name="collegedept"
                                        value="{{ $user->education->scCollegedept }}" maxlength="255"
                                        {{ $user->education->scSchoolLevel == 'College' ? 'required' : '' }}>
                                </div>
                                <div class="column">
                                    <label for="incomingyear">Incoming Year Level</label>
                                    <select name="incomingyear"
                                        {{ $user->education->scSchoolLevel == 'College' ? 'required' : '' }}>
                                        <option value="Second Year"
                                            {{ $user->education->scYearGrade == 'First Year' ? 'selected' : '' }}>
                                            Second Year
                                        </option>
                                        <option value="Third Year"
                                            {{ $user->education->scYearGrade == 'Second Year' ? 'selected' : '' }}>
                                            Third Year
                                        </option>
                                        <option value="Fourth Year"
                                            {{ $user->education->scYearGrade == 'Third Year' ? 'selected' : '' }}>
                                            Fourth
                                            Year
                                        </option>
                                        <option value="Fifth Year"
                                            {{ $user->education->scYearGrade == 'Fourth Year' ? 'selected' : '' }}>
                                            Fifth Year
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="column">
                                    <label for="course">Course</label>
                                    <select name="course" id="course"
                                        {{ $user->education->scSchoolLevel == 'College' ? 'required' : '' }}>
                                        @foreach ($courses as $course)
                                            <option value="{{ $course->coursename }}"
                                                {{ $user->education->scCourseStrandSec == $course->coursename ? 'selected' : '' }}>
                                                {{ $course->coursename }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="column">
                                    <label for="gwa">General Average Last Sem</label>
                                    <input type="number" name="gwa" value="{{ $grade->GWA }}" min="1"
                                        max="5" step="0.01"
                                        {{ $user->education->scSchoolLevel == 'College' ? 'required' : '' }}>
                                </div>
                            </div>
                        @else
                            <div class="row">
                                <div class="column">
                                    <label for="incomingyear">Incoming Grade Level</label>
                                    <select name="incominggrade"
                                        {{ $user->education->scSchoolLevel != 'College' ? 'required' : '' }}>
                                        @if ($user->education->scSchoolLevel == 'Senior High')
                                            <option value="Grade 12"
                                                {{ $user->education->scYearGrade == 'Grade 11' ? 'selected' : '' }}>
                                                Grade 12
                                            </option>
                                            <option value="First Year"
                                                {{ $user->education->scYearGrade == 'Grade 12' ? 'selected' : '' }}>
                                                First Year
                                            </option>
                                        @elseif ($user->education->scSchoolLevel == 'Junior High')
                                            <option value="Grade 8"
                                                {{ $user->education->scYearGrade == 'Grade 7' ? 'selected' : '' }}>
                                                Grade 8
                                            </option>
                                            <option value="Grade 9"
                                                {{ $user->education->scYearGrade == 'Grade 8' ? 'selected' : '' }}>
                                                Grade 9
                                            </option>
                                            <option value="Grade 10"
                                                {{ $user->education->scYearGrade == 'Grade 9' ? 'selected' : '' }}>
                                                Grade 10
                                            </option>
                                            <option value="Grade 11"
                                                {{ $user->education->scYearGrade == 'Grade 10' ? 'selected' : '' }}>
                                                Grade 11
                                            </option>
                                        @elseif ($user->education->scSchoolLevel == 'Elementary')
                                            <option value="Grade 2"
                                                {{ $user->education->scYearGrade == 'Grade 1' ? 'selected' : '' }}>
                                                Grade 2
                                            </option>
                                            <option value="Grade 3"
                                                {{ $user->education->scYearGrade == 'Grade 2' ? 'selected' : '' }}>
                                                Grade 3
                                            </option>
                                            <option value="Grade 4"
                                                {{ $user->education->scYearGrade == 'Grade 3' ? 'selected' : '' }}>
                                                Grade 4
                                            </option>
                                            <option value="Grade 5"
                                                {{ $user->education->scYearGrade == 'Grade 4' ? 'selected' : '' }}>
                                                Grade 5
                                            </option>
                                            <option value="Grade 6"
                                                {{ $user->education->scYearGrade == 'Grade 5' ? 'selected' : '' }}>
                                                Grade 6
                                            </option>
                                            <option value="Grade 7"
                                                {{ $user->education->scYearGrade == 'Grade 6' ? 'selected' : '' }}>
                                                Grade 7
                                            </option>
                                        @endif
                                    </select>
                                </div>
                                @if ($user->education->scSchoolLevel == 'Senior High')
                                    <div class="column">
                                        <label for="strand">Strand</label>
                                        <select name="strand" id="strand"
                                            {{ $user->education->scSchoolLevel == 'Senior High' ? 'required' : '' }}>
                                            @foreach ($strands as $strand)
                                                <option value="{{ $strand->coursename }}"
                                                    {{ $user->education->scCourseStrandSec == $strand->coursename ? 'selected' : '' }}>
                                                    {{ $strand->coursename }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                @elseif ($user->education->scSchoolLevel == 'Elementary' || $user->education->scSchoolLevel == 'Junior High')
                                    <div class="column">
                                        <label for="course">Section</label>
                                        <input type="text" name="section"
                                            value="{{ $user->education->scCourseStrandSec }}" maxlength="255"
                                            {{ $user->education->scSchoolLevel == 'Elementary' || $user->education->scSchoolLevel == 'Junior High' ? 'required' : '' }}>
                                    </div>
                                @endif
                            </div>
                            <div class="row">
                                <div class="column">
                                    <label for="genave">General Average Last Sem</label>
                                    <input type="number" name="genave" value="{{ $grade->GWA ?? '' }}"
                                        min="1" max="5" step="0.01" id="genave"
                                        {{ $user->education->scSchoolLevel != 'College' ? 'required' : '' }}>
                                </div>
                                <div class="column">
                                    <label for="gwaconduct">Conduct Grade Last Sem</label>
                                    <input type="text" name="gwaconduct" value="{{ $grade->gwaconduct ?? '' }}"
                                        minlength="1" id="gwaconduct"
                                        {{ $user->education->scSchoolLevel != 'College' ? 'required' : '' }}>
                                </div>
                            </div>
                            <div class="row fw-bold text-center">
                                <span>For Chinese Subject</span>
                            </div>
                            <div class="row">
                                <div class="column">
                                    <label for="chinesegenave">General Average Last Sem</label>
                                    <input type="number" name="chinesegenave"
                                        value="{{ $grade->chineseGWA ?? '' }}" min="1" max="5"
                                        step="0.01" id="chinesegenave">
                                </div>
                                <div class="column">
                                    <label for="chinesegwaconduct">Conduct Grade Last Sem</label>
                                    <input type="text" name="chinesegwaconduct"
                                        value="{{ $grade->chinesegwaconduct ?? '' }}" minlength="1"
                                        id="chinesegwaconduct">
                                </div>
                            </div>
                        @endif
                    </fieldset>

                    <fieldset class="custom-fieldset">
                        <legend>Family Information</legend>
                        <div class="fatherinfo">
                            <p class="family">FATHER INFORMATION</p>
                            <div class="row">
                                <div class="column">
                                    <label for="fname">Name (Last Name, First Name)</label>
                                    <input type="text" id="fname" value="{{ old('fname') }}" name="fname"
                                        required maxlength="255">
                                </div>
                                <div class="column">
                                    <label for="fage">Age</label>
                                    <input type="number" id="fage" value="{{ old('fage') }}" name="fage"
                                        required>
                                </div>
                                <div class="column">
                                    <label for="fsex">Sex</label>
                                    <select name="fsex" id="fsex">
                                        <option value="F">F</option>
                                        <option value="M" selected>M</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="column">
                                    <label for="fbirthdate">Birthdate</label>
                                    <input type="date" id="fbirthdate" value="{{ old('fbirthdate') }}"
                                        max="{{ \Carbon\Carbon::now()->subDay()->format('Y-m-d') }}"
                                        name="fbirthdate" required>
                                </div>
                                <div class="column">
                                    <label for="frelationship">Relationship</label>
                                    <input type="text" id="frelationship" value="Father" name="frelationship"
                                        readonly>
                                </div>
                                <div class="column">
                                    <label for="freligion">Religion</label>
                                    <input type="text" id="freligion" value="{{ old('freligion') }}"
                                        name="freligion" required maxlength="100">
                                </div>
                            </div>
                            <div class="row">
                                <div class="column">
                                    <label for="fattainment">Educational Attainment</label>
                                    <input type="text" id="fattainment" value="{{ old('fattainment') }}"
                                        name="fattainment" required maxlength="100">
                                </div>
                                <div class="column">
                                    <label for="foccupation">School/Occupation</label>
                                    <input type="text" id="foccupation" value="{{ old('foccupation') }}"
                                        name="foccupation" required maxlength="100">
                                </div>
                            </div>
                            <div class="row">
                                <div class="column">
                                    <label for="fcompany">Company</label>
                                    <input type="text" id="fcompany" value="{{ old('fcompany') }}"
                                        name="fcompany" required maxlength="100">
                                </div>
                                <div class="column">
                                    <label for="fincome">Income</label>
                                    <input type="number" id="fincome" value="{{ old('fincome') }}"
                                        name="fincome" placeholder="0 if none" min="0" required
                                        step="0.01">
                                </div>
                            </div>
                        </div>
                        <div class="motherinfo">
                            <p class="family">MOTHER INFORMATION</p>
                            <div class="row">
                                <div class="column">
                                    <label for="mname">Name (Last Name, First Name)</label>
                                    <input type="text" id="mname" value="{{ old('mname') }}" name="mname"
                                        required maxlength="255">
                                </div>
                                <div class="column">
                                    <label for="mage">Age</label>
                                    <input type="number" id="mage" value="{{ old('mage') }}" name="mage"
                                        required>
                                </div>
                                <div class="column">
                                    <label for="msex">Sex</label>
                                    <select name="msex" id="msex">
                                        <option value="F" selected>F</option>
                                        <option value="M">M</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="column">
                                    <label for="mbirthdate">Birthdate</label>
                                    <input type="date" id="mbirthdate" value="{{ old('mbirthdate') }}"
                                        max="{{ \Carbon\Carbon::now()->subDay()->format('Y-m-d') }}"
                                        name="mbirthdate" required>
                                </div>
                                <div class="column">
                                    <label for="mrelationship">Relationship</label>
                                    <input type="text" id="mrelationship" value="Mother" name="mrelationship"
                                        readonly>
                                </div>
                                <div class="column">
                                    <label for="mreligion">Religion</label>
                                    <input type="text" id="mreligion" value="{{ old('mreligion') }}"
                                        name="mreligion" required maxlength="100">
                                </div>
                            </div>
                            <div class="row">
                                <div class="column">
                                    <label for="mattainment">Educational Attainment</label>
                                    <input type="text" id="mattainment" value="{{ old('mattainment') }}"
                                        name="mattainment" required maxlength="100">
                                </div>
                                <div class="column">
                                    <label for="moccupation">School/Occupation</label>
                                    <input type="text" id="moccupation" value="{{ old('moccupation') }}"
                                        name="moccupation" required maxlength="100">
                                </div>
                            </div>
                            <div class="row">
                                <div class="column">
                                    <label for="mcompany">Company</label>
                                    <input type="text" id="mcompany" value="{{ old('mcompany') }}"
                                        name="mcompany" required maxlength="100">
                                </div>
                                <div the="column">
                                    <label for="mincome">Income</label>
                                    <input type="number" id="mincome" value="{{ old('mincome') }}"
                                        name="mincome" placeholder="0 if none" min="0" required
                                        step="0.01">
                                </div>
                            </div>
                        </div>
                        <div id="siblings-container">
                            <div class="siblingsinfo" style="display: none;">
                                <p class="family">SIBLING INFORMATION</p>
                                <div class="row">
                                    <div class="column">
                                        <label for="sname[]">Name (Last Name, First Name)</label>
                                        <input type="text" name="sname[]" value="" maxlength="255">
                                    </div>
                                    <div class="column">
                                        <label for="sage[]">Age</label>
                                        <input type="number" name="sage[]" min="1" value="">
                                    </div>
                                    <div class="column">
                                        <label for="ssex[]">Sex</label>
                                        <select name="ssex[]" id="ssex[]">
                                            <option value="" selected hidden></option>
                                            <option value="F">F</option>
                                            <option value="M">M</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="column">
                                        <label for="sbirthdate[]">Birthdate</label>
                                        <input type="date" name="sbirthdate[]" value=""
                                            max="{{ \Carbon\Carbon::now()->subDay()->format('Y-m-d') }}">
                                    </div>
                                    <div class="column">
                                        <label for="srelationship">Relationship</label>
                                        <input type="text" name="srelationship" maxlength="100" value="Sibling"
                                            readonly>
                                    </div>
                                    <div class="column">
                                        <label for="sreligion[]">Religion</label>
                                        <input type="text" name="sreligion[]" maxlength="100" value=""
                                            maxlength="100">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="column">
                                        <label for="sattainment[]">Educational Attainment</label>
                                        <input type="text" name="sattainment[]" maxlength="100" value=""
                                            maxlength="100">
                                    </div>
                                    <div class="column">
                                        <label for="soccupation[]">School/Occupation</label>
                                        <input type="text" name="soccupation[]" maxlength="100" value=""
                                            maxlength="100">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="column">
                                        <label for="scompany[]">Company</label>
                                        <input type="text" name="scompany[]" maxlength="100" value=""
                                            maxlength="100">
                                    </div>
                                    <div class="column">
                                        <label for="sincome[]">Income</label>
                                        <input type="number" name="sincome[]" maxlength="100" value=""
                                            placeholder="0 if none" min="0" step="0.01">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <input id="siblingCount" name="siblingcount" value="0" hidden>
                        <div class="row mx-auto">
                            <button id="addSibling">Add Sibling</button>
                        </div>
                    </fieldset>

                    <fieldset class="custom-fieldset">
                        <legend>Other Information</legend>
                        <span>Each answer <strong>must not exceed 255</strong> characters.</span>
                        <div class="row">
                            <div class="column">
                                <label for="grant">Grant/Assistance from other Government and Non-Government
                                    scholarships, School Discount (How much per sem?)</label>
                                <textarea id="grant" name="grant" rows="2" cols="50" placeholder="Input your answer here..."
                                    maxlength="255" required>{{ old('grant') }}</textarea>
                            </div>
                        </div>
                        <div class="row">
                            <div class="column">
                                <label for="talent">Talents & Skills/ Honor and Recognition/
                                    Extracurricular/Community
                                    Involvement/Employment</label>
                                <textarea id="talent" name="talent" rows="2" cols="50" placeholder="Input your answer here..."
                                    maxlength="255" required>{{ old('talent') }}</textarea>
                            </div>
                        </div>
                        <div class="row">
                            <div class="column">
                                <label for="expectation">What are your expectations from Tzu Chi
                                    Foundation?</label>
                                <textarea id="expectation" name="expectation" rows="2" cols="50" placeholder="Input your answer here..."
                                    maxlength="255" required>{{ old('expectation') }}</textarea>
                            </div>
                        </div>
                    </fieldset>

                    <fieldset class="custom-fieldset">
                        <legend>Requirements Submission</legend>
                        <span>Each document <strong>must not exceed 2MB</strong> or the system will not accept your
                            application.</span>

                        <div class="row">
                            <div class="column">
                                <label for="idpic">1x1 ID Picture</label>
                                <input type="file" name="idpic" accept="image/jpeg, image/png" required>
                                <small class="fst-italic text-muted">Accepted file types: JPG, JPEG, or
                                    PNG.</small>
                            </div>
                            <div class="column">
                                <label for="reportcard">Scanned copy of latest Report Card</label>
                                <input type="file" name="reportcard"
                                    accept="image/jpeg, image/png, application/pdf" required>
                                <small class="fst-italic text-muted">Accepted file types: JPG, JPEG, PNG, or
                                    PDF.</small>
                            </div>
                        </div>

                        <div class="row">
                            <div class="column">
                                <label for="regiform">Scanned copy of latest Registration Form</label>
                                <input type="file" name="regiform" accept="image/jpeg, image/png, application/pdf"
                                    required>
                                <small class="fst-italic text-muted">Accepted file types: JPG, JPEG, PNG, or
                                    PDF.</small>
                            </div>
                            <div class="column">
                                <label for="autobiography">Autobiography</label>
                                <input type="file" name="autobiography" accept="application/pdf" required>
                                <small class="fst-italic text-muted">Accepted file type: PDF only.</small>
                            </div>
                        </div>

                        <div class="row">
                            <div class="column">
                                <label for="familypic">Family Picture</label>
                                <input type="file" name="familypic" accept="image/jpeg, image/png" required>
                                <small class="fst-italic text-muted">Accepted file types: JPG, JPEG, or
                                    PNG.</small>
                            </div>
                            <div class="column">
                                <label for="insidehouse">Picture of the inside of the house</label>
                                <input type="file" name="insidehouse" accept="image/jpeg, image/png" required>
                                <small class="fst-italic text-muted">Accepted file types: JPG, JPEG, or
                                    PNG.</small>
                            </div>
                        </div>

                        <div class="row">
                            <div class="column">
                                <label for="outsidehouse">Picture of the outside of the house</label>
                                <input type="file" name="outsidehouse" accept="image/jpeg, image/png" required>
                                <small class="fst-italic text-muted">Accepted file types: JPG, JPEG, or
                                    PNG.</small>
                            </div>
                            <div class="column">
                                <label for="utility">Scanned copy of latest Utility Bills</label>
                                <input type="file" name="utility" accept="image/jpeg, image/png, application/pdf"
                                    required>
                                <small class="fst-italic text-muted">Accepted file types: JPG, JPEG, PNG, or
                                    PDF.</small>
                            </div>
                        </div>

                        <div class="row">
                            <div class="column">
                                <label for="sketchmap">Detailed Sketch Map of Home Address</label>
                                <input type="file" name="sketchmap" accept="image/jpeg, image/png" required>
                                <small class="fst-italic text-muted">Accepted file types: JPG, JPEG, or
                                    PNG.</small>
                            </div>
                            <div class="column">
                                <label for="payslip">Scanned copy of latest ITR/ Official Pay Slip of parent/s (if
                                    applicable)</label>
                                <input type="file" name="payslip" accept="image/jpeg, image/png, application/pdf">
                                <small class="fst-italic text-muted">Accepted file types: JPG, JPEG, PNG, or PDF.
                                    (Optional)</small>
                            </div>
                        </div>

                        <div class="row">
                            <div class="column">
                                <label for="indigencycert">Barangay Certificate of Indigency</label>
                                <input type="file" name="indigencycert"
                                    accept="image/jpeg, image/png, application/pdf" required>
                                <small class="fst-italic text-muted">Accepted file types: JPG, JPEG, PNG, or
                                    PDF.</small>
                            </div>
                        </div>
                    </fieldset>

                    <div class="agreement">
                        <input type="checkbox" value="" name="agreement" id="agreement" required>
                        <label for="agreement">
                            <i>I hereby attest that the information I have provided is true and correct. I also
                                consents Tzu Chi Foundation to obtain and retain my personal information for the
                                purpose
                                of
                                this application.</i>
                        </label>
                    </div>
                    <div class="submit text-center">
                        <button type="submit" class="btn-submit fw-bold">Submit</button>
                    </div>
                </form>
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

        document.addEventListener('DOMContentLoaded', () => {
            // Load saved data from sessionStorage for all input types
            document.querySelectorAll('input, textarea, select').forEach(element => {
                if (element.type === 'radio' || element.type === 'checkbox') {
                    // Restore checked state for radio buttons and checkboxes
                    const savedValue = sessionStorage.getItem(element.name);
                    if (savedValue) {
                        element.checked = savedValue === 'true';
                    }
                } else if (element.type === 'file') {
                    // Skip file inputs (cannot persist due to security reasons)
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
                    if (element.type === 'radio' || element.type === 'checkbox') {
                        // Save checked state as true/false for radio buttons and checkboxes
                        sessionStorage.setItem(element.name, element.checked);
                    } else if (element.type === 'file') {
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

        // Track sibling count
        let siblingCount = 0;

        document.getElementById('addSibling').addEventListener('click', function(event) {
            event.preventDefault();

            var originalDiv = document.querySelector('.siblingsinfo');

            var clone = originalDiv.cloneNode(true);
            clone.style.display = 'block';

            // Clear values and set required attributes for the new clone
            clone.querySelectorAll('input, select').forEach(input => {
                input.value = '';
                input.required = true;
            });

            clone.querySelector('[name="srelationship"]').value = 'Sibling';

            // Ensure no duplicate "Remove" button in the clone
            if (!clone.querySelector('.removeSibling')) {
                var removeButton = document.createElement('button');
                removeButton.textContent = 'Remove';
                removeButton.type = 'button';
                removeButton.classList.add('removeSibling');
                removeButton.onclick = function() {
                    clone.remove();
                    updateSiblingCount(-1); // Decrease the sibling count when removed
                };
                clone.appendChild(removeButton);
            }

            // Append the clone to the container
            document.getElementById('siblings-container').appendChild(clone);

            // Update sibling count
            updateSiblingCount(1);
        });

        // Update the sibling count and hidden field
        function updateSiblingCount(change) {
            siblingCount += change;
            document.getElementById('siblingCount').value = siblingCount;

            // If no siblings, remove required attributes from all inputs in the hidden template
            if (siblingCount === 0) {
                document.querySelector('.siblingsinfo').querySelectorAll('input, select').forEach(input => {
                    input.required = false;
                });
            }
        }
    </script>
</body>

</html>
