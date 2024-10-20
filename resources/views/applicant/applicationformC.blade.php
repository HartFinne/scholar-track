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
    <script>
        window.onerror = function(message, source, lineno, colno, error) {
            alert('Error: ' + message + '\nSource: ' + source + '\nLine: ' + lineno);
        };
    </script>
</head>

<body>
    <!-- MAIN -->
    <div class="ctn-main">
        <a href="">
            <button class="btn-back fw-bold">&lt Go back</button>
        </a>
        <h1 class="title text-center fw-bold app-close hide">APPLICATION IS NOT YET OPEN.</h1>
        <div class="">
            <h1 class="title text-center fw-bold app-open">TZU CHI PHILIPPINES<br>SCHOLARSHIP APPLICATION FORM</h1>
            <div class="row">
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
            </div>
            <p class="mt-4 mb-5 description">Welcome to Tzu Chi Scholarship Application Form <strong>(College)</strong>.
                Before you proceed, kindly read and understand the following statements:
            <ol>
                <li>
                    The objective of Tzu Chi scholarship program is to provide financial assistance to deserving
                    students through tuition fee support, monthly living allowance, as well as additional
                    assistance for other school needs, should it be deemed necessary. College students are only
                    authorized to enroll in partner schools and authorized courses.
                </li>
                <li>
                    Students with a failing grade on any subject, with <strong>general weighted average 82% both English
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
            </ol>
            </p>
        </div>


        <div class="app-form">
            <form method="POST" action="{{ route('saveapplicant') }}">
                <fieldset class="custom-fieldset">
                    <legend>Personal Information</legend>
                    <div class="row">
                        <div class="column">
                            <label for="fullName">Name</label>
                            <input type="text" name="scholarname" value=""
                                placeholder="(Last Name, First Name, Middle Name)" required>
                        </div>
                        <div class="column">
                            <label for="cName">Chinese Name</label>
                            <input type="text" name="chinesename" value="" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="column">
                            <label for="gender">Sex</label>
                            <select name="sex">
                                <option value="" selected hidden>Select gender</option>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                            </select>
                        </div>
                        <div class="column">
                            <label for="age">Age</label>
                            <input type="number" name="age" value="" required>
                        </div>
                        <div class="column">
                            <label for="birthDate">Birthdate</label>
                            <input type="date" name="birthdate" value="" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="column">
                            <label for="resAddress">Home Address</label>
                            <input type="text" name="homeaddress" value=""
                                placeholder="(House #/Unit #/Floor/Bldg. Name/Street Name)" name="residential" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="column">
                            <label for="brgy">Barangay</label>
                            <input type="text" name="barangay" value="" required>
                        </div>
                        <div class="column">
                            <label for="city">City</label>
                            <input type="text" name="city" value=""required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="column">
                            <label for="email">Email Address</label>
                            <input type="email" name="email" value="" required>
                        </div>
                        <div class="column">
                            <label for="phoneNum">Cellphone No./Landline</label>
                            <input type="tel" name="phonenum" value="" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="column">
                            <label for="occupation">Occupation</label>
                            <input type="text" name="occupation" value="" required>
                        </div>
                        <div class="column">
                            <label for="income">Income</label>
                            <input type="number" name="income" value="" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="column">
                            <label for="fblink">Facebook Link</label>
                            <input type="text" name="fblink" value="" required>
                        </div>
                        <div class="column">
                            <p>Are you a member of any indigenous group?</p>
                            <div class="row-radio">
                                <input type="radio" id="indigenousCheck" name="isIndigenous" value="Yes"
                                    onclick="toggleInput()">
                                <label for="indigenousCheck">Yes</label>
                                <input type="radio" id="noCheck" name="isIndigenous" value="No"
                                    onclick="disableInput()">
                                <label for="noCheck">No</label>
                            </div>
                            <input type="text" name="indigenousgroup" id="indigenousInput"
                                placeholder="Please specify the group you belong to" disabled>
                        </div>
                    </div>

                </fieldset>

                <fieldset class="custom-fieldset">
                    <legend>Educational Background</legend>
                    <div class="row">
                        <div class="column">
                            <label for="school">Name of University</label>
                            <select name="schoolname" id="school">
                                <option value="" selected hidden>Select school</option>
                                @foreach ($institutions as $insti)
                                    <option value="{{ $insti->schoolname }}">{{ $insti->schoolname }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="column">
                            <label for="collegeDept">College Department</label>
                            <input type="text" name="collegedept" value="" required>
                        </div>
                        <div class="column">
                            <label for="yrLevel">Incoming Year Level</label>
                            <select name="incomingyear">
                                <option value="" selected hidden>Select year level</option>
                                <option value="First Year">First Year</option>
                                <option value="Second Year">Second Year</option>
                                <option value="Third Year">Third Year</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="column">
                            <label for="course">Course</label>
                            <select name="course" id="course">
                                <option value="" selected hidden>Select course</option>
                                @foreach ($courses as $course)
                                    <option value="{{ $course->coursename }}">{{ $course->coursename }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="column">
                            <label for="gwa">General Average Last Sem</label>
                            <input type="text" name="gwa" required>
                        </div>
                    </div>
                </fieldset>

                <fieldset class="custom-fieldset">
                    <legend>Family Information</legend>
                    <div class="fatherinfo">
                        <p class="family">FATHER INFORMATION</p>
                        <div class="row">
                            <div class="column">
                                <label for="fName">Name (Last Name, First Name)</label>
                                <input type="text" id="fName" value="" name="fname" required>
                            </div>
                            <div class="column">
                                <label for="fAge">Age</label>
                                <input type="text" id="fAge" value="" name="fage" required>
                            </div>
                            <div class="column">
                                <label for="fSex">Sex</label>
                                <select name="" id="fsex">
                                    <option value="" disabled selected hidden></option>
                                    <option value="F">F</option>
                                    <option value="M">M</option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="column">
                                <label for="fBirthdate">Birthdate</label>
                                <input type="date" id="fBirthdate" value="" name="fbirthdate" required>
                            </div>
                            <div class="column">
                                <label for="fRelationship">Relationship</label>
                                <input type="text" id="fRelationship" value="Father" name="frelationship"
                                    readonly>
                            </div>
                            <div class="column">
                                <label for="fReligion">Religion</label>
                                <input type="text" id="fReligion" value="" name="freligion" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="column">
                                <label for="fAttainment">Educational Attainment</label>
                                <input type="text" id="fAttainment" value="" name="fattainment" required>
                            </div>
                            <div class="column">
                                <label for="fSchoolOcc">School/Occupation</label>
                                <input type="text" id="fSchoolOcc" value="" name="foccupation" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="column">
                                <label for="fCompany">Company</label>
                                <input type="text" id="fCompany" value="" name="fcompany" required>
                            </div>
                            <div class="column">
                                <label for="fIncome">Income</label>
                                <input type="text" id="fIncome" value="" name="fincome" required>
                            </div>
                        </div>
                    </div>
                    <div class="motherinfo">
                        <p class="family">MOTHER INFORMATION</p>
                        <div class="row">
                            <div class="column">
                                <label for="mname">Name (Last Name, First Name)</label>
                                <input type="text" id="fName" value="" name="mname" required>
                            </div>
                            <div class="column">
                                <label for="mage">Age</label>
                                <input type="text" id="fAge" value="" name="mage" required>
                            </div>
                            <div class="column">
                                <label for="msex">Sex</label>
                                <select name="msex" id="fSex">
                                    <option value="" disabled selected hidden></option>
                                    <option value="F">F</option>
                                    <option value="M">M</option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="column">
                                <label for="fBirthdate">Birthdate</label>
                                <input type="date" id="fBirthdate" value="" name="mbirthdate" required>
                            </div>
                            <div class="column">
                                <label for="fRelationship">Relationship</label>
                                <input type="text" id="fRelationship" value="Mother" name="mrelationship"
                                    readonly>
                            </div>
                            <div class="column">
                                <label for="fReligion">Religion</label>
                                <input type="text" id="fReligion" value="" name="mreligion" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="column">
                                <label for="fAttainment">Educational Attainment</label>
                                <input type="text" id="fAttainment" value="" name="mattainment" required>
                            </div>
                            <div class="column">
                                <label for="fSchoolOcc">School/Occupation</label>
                                <input type="text" id="fSchoolOcc" value="" name="moccupation" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="column">
                                <label for="fCompany">Company</label>
                                <input type="text" id="fCompany" value="" name="mcompany" required>
                            </div>
                            <div class="column">
                                <label for="fIncome">Income</label>
                                <input type="text" id="fIncome" value="" name="mincome" required>
                            </div>
                        </div>
                    </div>
                    <div id="siblings-container">
                        <div class="siblingsinfo">
                            <p class="family">SIBLING INFORMATION</p>
                            <div class="row">
                                <div class="column">
                                    <label for="sname[]">Name (Last Name, First Name)</label>
                                    <input type="text" id="fName" value="" name="sname[]" required>
                                </div>
                                <div class="column">
                                    <label for="sage[]">Age</label>
                                    <input type="text" id="fAge" value="" name="sage[]" required>
                                </div>
                                <div class="column">
                                    <label for="ssex[]">Sex</label>
                                    <select name="ssex[]" id="ssex">
                                        <option value="" disabled selected hidden></option>
                                        <option value="F">F</option>
                                        <option value="M">M</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="column">
                                    <label for="sbirthdate[]">Birthdate</label>
                                    <input type="date" id="fBirthdate" value="" name="sbirthdate[]"
                                        required>
                                </div>
                                <div class="column">
                                    <label for="srelationship[]">Relationship</label>
                                    <input type="text" id="fRelationship" value="Sibling" name="srelationship[]"
                                        readonly>
                                </div>
                                <div class="column">
                                    <label for="sreligion[]">Religion</label>
                                    <input type="text" id="fReligion" value="" name="sreligion[]" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="column">
                                    <label for="sattainment[]">Educational Attainment</label>
                                    <input type="text" id="fAttainment" value="" name="sattainment[]"
                                        required>
                                </div>
                                <div class="column">
                                    <label for="soccupation[]">School/Occupation</label>
                                    <input type="text" id="fSchoolOcc" value="" name="soccupation[]"
                                        required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="column">
                                    <label for="fCompany[]">Company</label>
                                    <input type="text" id="fCompany" value="" name="scompany[]" required>
                                </div>
                                <div class="column">
                                    <label for="fIncome[]">Income</label>
                                    <input type="text" id="fIncome" value="" name="sincome[]" required>
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
                            <textarea id="grant" name="grant" rows="2" cols="50" placeholder="Input your answer here..."></textarea>
                        </div>
                    </div>
                    <div class="row">
                        <div class="column">
                            <label for="talents">Talents & Skills/ Honor and Recognition/ Extracurricular/Community
                                Involvement/Employment</label>
                            <textarea id="talents" name="talent" rows="2" cols="50" placeholder="Input your answer here..."></textarea>
                        </div>
                    </div>
                    <div class="row">
                        <div class="column">
                            <label for="expectations">What are your expectations from Tzu Chi Foundation?</label>
                            <textarea id="expectations" name="expectation" rows="2" cols="50"
                                placeholder="Input your answer here..."></textarea>
                        </div>
                    </div>
                </fieldset>

                <fieldset class="custom-fieldset">
                    <legend>Requirements Submission</legend>
                    <div class="row">
                        <div class="column">
                            <label for="idPic">1x1 ID Picture</label>
                            <input type="file" name="idpic" required>
                        </div>
                        <div class="column">
                            <label for="grades">Scanned copy of latest Report Card</label>
                            <input type="file" name="reportcard" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="column">
                            <label for="regForm">Scanned copy of latest Registration Form</label>
                            <input type="file" name="regiform" required>
                        </div>
                        <div class="column">
                            <label for="autobiography">Autobiography</label>
                            <input type="file" name="autobiography" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="column">
                            <label for="famPic">Family Picture</label>
                            <input type="file" name="familypic" required>
                        </div>
                        <div class="column">
                            <label for="housePic">Picture of the inside of the house</label>
                            <input type="file" name="insidehouse" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="column">
                            <label for="housePic">Picture of the outside of the house</label>
                            <input type="file" name="outsidehouse" required>
                        </div>
                        <div class="column">
                            <label for="utility">Scanned copy of latest Utility Bills</label>
                            <input type="file" name="utility" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="column">
                            <label for="sketchMap">Detailed Sketch Map of Home Address</label>
                            <input type="file" name="sketchmap" required>
                        </div>
                        <div class="column">
                            <label for="paySlip">Scanned copy latest ITR/ Official Pay Slip of parent/s (if
                                applicable)</label>
                            <input type="file" name="payslip">
                        </div>
                    </div>
                    <div class="row">
                        <div class="column">
                            <label for="brgyCert">Barangay Certificate of Indigency</label>
                            <input type="file" name="indigencycert" required>
                        </div>
                    </div>
                </fieldset>

                <div class="agreement">
                    <input type="checkbox" value="" name="agreement" id="agreement">
                    <label for="agreement">
                        <i>I hereby attest that the information I have provided is true and correct. I also
                            consents Tzu Chi Foundation to obtain and retain my personal information for the purpose of
                            this application.</i>
                    </label>
                </div>
                <div class="submit text-center">
                    <button type="submit" class="btn-submit fw-bold"
                        onclick="window.location.href='appconfirmdialog.html'">Submit</button>
                </div>
            </form>

        </div>
    </div>

    <script src="{{ asset('js/applicant.js') }}"></script>
</body>

</html>
