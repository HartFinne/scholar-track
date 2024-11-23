<!DOCTYPE html>
<html lang="en">

<head>
    <title>Application Form</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="{{ asset('css/applicationform.css') }}" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body>
    <!-- MAIN -->
    <div class="ctn-main">
        <a href="{{ route('appinstructions') }}" class="btn-back fw-bold" style="text-decoration: none">&lt Go
            back</a>
        @if ($form->status == 'Closed')
            <h1 class="title text-center fw-bold app-close">APPLICATION IS NOT YET OPEN.</h1>
        @else
            {{-- header instruction --}}
            <div class="">
                <h1 class="title text-center fw-bold app-open">TZU CHI PHILIPPINES<br>SCHOLARSHIP APPLICATION FORM</h1>
                {{-- <div class="row mt-4">
                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert" id="error-alert">
                            {!! session('error') !!}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"
                                aria-label="Close"></button>
                        </div>
                    @endif
                </div> --}}
                <p class="mt-2 mb-1">Welcome to Tzu Chi Scholarship Application Form <strong>(College)</strong>.
                    Before you proceed, kindly read and understand the following statements:
                <ol>
                    <li>
                        The objective of Tzu Chi scholarship program is to provide financial assistance to deserving
                        students through tuition fee support, monthly living allowance, as well as additional
                        assistance for other school needs, should it be deemed necessary. College students are only
                        authorized to enroll in partner schools and authorized courses.
                    </li>
                    <li>
                        Students with a failing grade on any subject, with <strong>general weighted average 82% both
                            English
                            and Chinese or</strong> with a grade on <strong>Conduct below B</strong> or with scholarship
                        grant from other foundations or organizations will <strong>not be accepted</strong>.
                    </li>
                    <li>
                        Please fill up the <strong>Scholarship Application Form</strong> completely, If a field does not
                        apply, write <strong>Not Applicable</strong>. Any misleading information may lead to
                        disqualification.
                    </li>
                    <li>
                        Please upload the necessary documents below and submit the hard copy <strong> on or before
                            DUE DATE</strong>.
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
                        <strong>09953066694</strong>.
                    </li>
                </ol>
                </p>
            </div>

            <div class="app-form">
                <form autocomplete="on" method="POST" action="{{ route('saveapplicant') }}"
                    enctype="multipart/form-data">
                    @csrf

                    {{-- personal info --}}
                    <fieldset class="custom-fieldset">
                        <legend>Personal Information</legend>
                        <div class="row">
                            <div class="column">
                                <label for="scholarname">Name</label>
                                <input type="text" id="scholarname" name="scholarname" maxlength="255"
                                    value="{{ old('scholarname') }}" placeholder="(Last Name, First Name, Middle Name)"
                                    required>
                            </div>
                            <div class="column">
                                <label for="chinesename">Chinese Name</label>
                                <input type="text" id="chinesename" name="chinesename" maxlength="255"
                                    value="{{ old('chinesename') }}" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="column">
                                <label for="sex">Sex</label>
                                <select name="sex" value="{{ old('sex') }}" required>
                                    <option value="" disabled>Select gender</option>
                                    <option value="Male" {{ old('sex') == 'Male' ? 'selected' : '' }}>Male</option>
                                    <option value="Female" {{ old('sex') == 'Female' ? 'selected' : '' }}>Female
                                    </option>
                                </select>
                            </div>
                            <div class="column">
                                <label for="age">Age</label>
                                <input type="number" name="age" value="{{ old('age') }}" required>
                            </div>
                            <div class="column">
                                <label for="birthdate">Birthdate</label>
                                <input type="date" name="birthdate"
                                    max="{{ \Carbon\Carbon::now()->subDay()->format('Y-m-d') }}"
                                    value="{{ old('birthdate') }}" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="column">
                                <label for="homeaddress">Home Address</label>
                                <input type="text" name="homeaddress" value="{{ old('homeaddress') }}"
                                    max="255" placeholder="(House #/Unit #/Floor/Bldg. Name/Street Name)" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="column">
                                <label for="city">Region</label>
                                <select name="region" id="region" required>
                                </select>
                            </div>
                            <div class="column">
                                <label for="city">City/Municipality</label>
                                <select name="city" id="city" required>
                                </select>
                            </div>
                            <div class="column">
                                <label for="barangay">Barangay</label>
                                <select name="barangay" id="barangay" required>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="column">
                                <label for="email">Email Address</label>
                                <input type="email" name="email" maxlength="255" value="{{ old('email') }}"
                                    required>
                            </div>
                            <div class="column">
                                <label for="phonenum">Cellphone No./Landline</label>
                                <input type="tel" minlength="11" maxlength="12" name="phonenum"
                                    pattern="^(09\d{9}|63\d{10})$" value="{{ old('phonenum') }}" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="column">
                                <label for="occupation">Occupation</label>
                                <input type="text" name="occupation" maxlength="100"
                                    value="{{ old('occupation') }}" required>
                            </div>
                            <div class="column">
                                <label for="income">Income</label>
                                <input type="number" name="income" value="{{ old('income') }}"
                                    placeholder="0 if none" min="0" required step="0.01">
                            </div>
                        </div>
                        <div class="row">
                            <div class="column">
                                <label for="fblink">Facebook Link</label>
                                <input type="url" name="fblink" value="{{ old('fblink') }}" required>
                            </div>
                            <div class="column">
                                <p>Are you a member of any indigenous group?</p>
                                <div class="row-radio">
                                    <input type="radio" id="indigenousCheck" name="isIndigenous" value="Yes"
                                        onclick="toggleInput()" {{ old('isIndigenous') == 'Yes' ? 'checked' : '' }}
                                        style="cursor: pointer" required>
                                    <label for="indigenousCheck" style="cursor: pointer">Yes</label>
                                    <input type="radio" id="noCheck" name="isIndigenous" value="No"
                                        onclick="disableInput()" {{ old('isIndigenous') == 'No' ? 'checked' : '' }}
                                        style="cursor: pointer" required>
                                    <label for="noCheck" style="cursor: pointer">No</label>
                                </div>
                                <input type="text" name="indigenousgroup" id="indigenousInput"
                                    placeholder="Please specify the group you belong to" disabled
                                    value="{{ old('indigenousgroup') }}" maxlength="100">
                            </div>
                        </div>
                    </fieldset>

                    {{-- education info --}}
                    <fieldset class="custom-fieldset">
                        <legend>Educational Background</legend>
                        <div class="row">
                            <div class="column">
                                <label for="schoollevel">School Level</label>
                                <input type="text" name="schoollevel" id="schoollevel"
                                    value="{{ $level }}" readonly>
                                {{-- <select name="schoollevel" id="schoollevel" required>
                                    @if ($level == 'elementary')
                                        <option value="Elementary" selected>Elementary</option>
                                    @else
                                        <option value="" selected hidden>Select school level</option>
                                        <option value="Junior High"
                                            {{ old('schoollevel') == 'Junior High' ? 'selected' : '' }}>Junior High
                                        </option>
                                        <option value="Senior High"
                                            {{ old('schoollevel') == 'Senior High' ? 'selected' : '' }}>Senior High
                                        </option>
                                    @endif
                                </select> --}}
                            </div>
                            <div class="column">
                                <label for="incomingyear">Incoming Grade Level</label>
                                <select name="incomingyear" required>
                                    <option value="" selected>-- Please select school level first --</option>
                                    {{-- to be populated based on selected school level --}}
                                </select>
                            </div>
                            {{-- display this by default, on change of school level if the selected school level is not senior high display this if senior high, hide this --}}
                            <div class="column" id="ctnsection">
                                <label for="section">Section</label>
                                <input type="text" maxlength="50" name="section" id="section">
                            </div>
                            {{-- hide this by default, on change of school level, if the selected is senior high display this if not hide this --}}
                            <div class="column" id="ctnstrand" hidden>
                                <label for="strand">Strand</label>
                                <select name="strand" id="strand">
                                    <option value="" selected hidden>Select strand</option>
                                    @foreach ($strands as $strand)
                                        <option value="{{ $strand->coursename }}"
                                            {{ old('strand') == $strand->coursename ? 'selected' : '' }}>
                                            {{ $strand->coursename }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="column">
                                <label for="schoolname">Name of School</label>
                                <select name="schoolname" id="schoolname" required>
                                    <option value="" selected>-- Please select school level first --</option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="column">
                                <label for="gwa">General Average Last Semester/Quarter</label>
                                <input type="number" name="gwa" value="{{ old('gwa') }}" min="1"
                                    max="100" step="0.01" required>
                            </div>
                            <div class="column">
                                <label for="gwa">Conduct Last Semester/Quarter</label>
                                <input type="string" name="gwaconduct" value="{{ old('gwaconduct') }}" required
                                    maxlength="50">
                            </div>
                        </div>
                        <div class="row">
                            <div class="column">
                                <label for="gwa">(Chinese Subject) General Average Last Semester/Quarter</label>
                                <input type="number" name="chinesegwa" value="{{ old('gwa') }}" min="1"
                                    max="100" step="0.01">
                            </div>
                            <div class="column">
                                <label for="gwa">(Chinese Subject) Conduct Last Semester/Quarter</label>
                                <input type="string" name="chinesegwaconduct" value="{{ old('gwaconduct') }}"
                                    maxlength="50">
                            </div>
                        </div>
                    </fieldset>

                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            const gradeLevels = @json($gradelevels);
                            const schoolnames = @json($schools);
                            const level = @json($level);
                            const schoolLevelSelect = document.getElementById('schoollevel');
                            const schoolNameSelect = document.getElementById('schoolname');
                            const incomingYearSelect = document.querySelector('select[name="incomingyear"]');
                            const sectionDiv = document.getElementById('ctnsection');
                            const strandDiv = document.getElementById('ctnstrand');
                            const sectionInput = document.getElementById('section');
                            const strandSelect = document.getElementById('strand');

                            // Function to populate grade levels based on school level
                            function populateGradeLevels(schoolLevel) {
                                incomingYearSelect.innerHTML =
                                    '<option value="" selected hidden>Select grade level</option>'; // Reset options
                                if (gradeLevels[schoolLevel]) {
                                    gradeLevels[schoolLevel].forEach(level => {
                                        const option = document.createElement('option');
                                        option.value = level;
                                        option.textContent = level;
                                        option.selected = "{{ old('incomingyear') }}" ===
                                            level; // Set selected if matches old value
                                        incomingYearSelect.appendChild(option);
                                    });
                                }
                            }

                            // Function to populate school names based on school level
                            function populateSchoolnames(schoolLevel) {
                                schoolNameSelect.innerHTML =
                                    '<option value="" selected hidden>Select school name</option>'; // Reset options

                                if (schoolnames[schoolLevel]) {
                                    schoolnames[schoolLevel].forEach(school => {
                                        const option = document.createElement('option');
                                        option.value = school;
                                        option.textContent = school;
                                        // Check if the school matches the old value for pre-selection
                                        if ("{{ old('schoolname') }}" === school) {
                                            option.selected = true;
                                        }
                                        schoolNameSelect.appendChild(option);
                                    });
                                }
                            }

                            // Function to handle visibility of section and strand fields
                            function toggleFields(schoolLevel) {
                                if (schoolLevel === 'Senior High') {
                                    strandDiv.hidden = false;
                                    sectionDiv.hidden = true;
                                    strand.required = true;
                                    section.required = false;
                                } else if (schoolLevel === 'Elementary' || schoolLevel === 'Junior High') {
                                    strandDiv.hidden = true;
                                    sectionDiv.hidden = false;
                                    section.required = true;
                                    strand.required = false;
                                } else {
                                    strandDiv.hidden = true;
                                    sectionDiv.hidden = false;
                                    section.required = true;
                                    strand.required = false;
                                }
                            }

                            // Event listener for school level changes
                            schoolLevelSelect.addEventListener('change', function() {
                                const selectedSchoolLevel = schoolLevelSelect.value;
                                populateGradeLevels(selectedSchoolLevel);
                                populateSchoolnames(selectedSchoolLevel);
                                toggleFields(selectedSchoolLevel);
                            });

                            // Initial population based on old values (if any)
                            const initialSchoolLevel = schoolLevelSelect.value;
                            if (initialSchoolLevel) {
                                populateGradeLevels(initialSchoolLevel);
                                populateSchoolnames(initialSchoolLevel);
                                toggleFields(initialSchoolLevel);
                            }
                        });
                    </script>

                    {{-- family info --}}
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
                                    <select name="fsex" id="fsex" required>
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
                                    <select name="msex" id="msex" required>
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

                    {{-- other info (qna) --}}
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

                    {{-- required documents --}}
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
        @endif
    </div>

    <div class="modal fade" id="errorModal" tabindex="-1" aria-labelledby="errorModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="errorModalLabel">Error</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @if (session('error'))
                        {!! session('error') !!}
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    @if (session('error'))
        var errorModal = new bootstrap.Modal(document.getElementById('errorModal'));
        errorModal.show();
    @endif

    <script src="{{ asset('js/applicant.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Load saved data from sessionStorage for all input types
            document.querySelectorAll('input, textarea, select').forEach(element => {
                if (element.type === 'file') {
                    // Skip file inputs (cannot persist due to security reasons)
                    return;
                } else if (element.id === 'indigenousInput') {
                    return;
                } else if (element.id === 'schoollevel') {
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

        // ADDRESS API
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
                regionSelect.innerHTML = '<option value="" disabled selected>Select Region</option>';
                citySelect.innerHTML = '<option value="" disabled selected>Select City/Municipality</option>';
                barangaySelect.innerHTML =
                    '<option value="" disabled selected>Select Barangay</option>';
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
                    '<option value="" disabled selected>Select Barangay</option>';

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

        @if (session('error'))
            var errorModal = new bootstrap.Modal(document.getElementById('errorModal'));
            errorModal.show();
        @endif
    </script>
</body>

</html>
