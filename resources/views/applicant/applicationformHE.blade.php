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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body>
    <!-- MAIN -->
    <div class="ctn-main">
        <a href="{{ route('appinstructions') }}" class="btn-back fw-bold" style="text-decoration: none">&lt Go
            back</a>
        @if ($form->status == 'Closed' && ($form->formname == 'High School' || $form->formname == 'Elementary'))
            <h1 class="title text-center fw-bold app-close">APPLICATION IS NOT YET OPEN.</h1>
        @else
            <div class="hide">
                <h1 class="title text-center fw-bold app-open">TZU CHI PHILIPPINES<br>SCHOLARSHIP APPLICATION FORM</h1>
                <p class="mt-4 mb-5 description">Welcome to Tzu Chi Scholarship Application Form
                    <strong>(Elementary/High
                        School)</strong>. Please fill out the required fields
                    in each section with true and correct information to complete your application. If a field does not
                    apply, write <strong>N/A</strong>.
                </p>
            </div>
            <div class="app-form hide">
                <form action="">
                    <fieldset class="custom-fieldset">
                        <legend>Personal Information</legend>
                        <div class="row">
                            <div class="column">
                                <label for="fullName">Name</label>
                                <input type="text" id="fullName" value=""
                                    placeholder="(Last Name, First Name, Middle Name)" required>
                            </div>
                            <div class="column">
                                <label for="cName">Chinese Name</label>
                                <input type="text" id="cName" value="" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="column">
                                <label for="gender">Gender</label>
                                <input type="text" id="gender" value="" required>
                            </div>
                            <div class="column">
                                <label for="age">Age</label>
                                <input type="text" id="age" value="" required>
                            </div>
                            <div class="column">
                                <label for="birthDate">Birthdate</label>
                                <input type="date" id="birthDate" value="" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="column">
                                <label for="resAddress">Home Address</label>
                                <input type="text" id="resAddress" value=""
                                    placeholder="(House #/Unit #/Floor/Bldg. Name/Street Name)" name="residential"
                                    required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="column">
                                <label for="brgy">Barangay</label>
                                <input type="text" id="brgy" value="" name="brgy" required>
                            </div>
                            <div class="column">
                                <label for="city">City</label>
                                <input type="text" id="city" value="" name="city" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="column">
                                <label for="email">Email Address</label>
                                <input type="text" id="email" value="" required>
                            </div>
                            <div class="column">
                                <label for="phoneNum">Cellphone No./Landline</label>
                                <input type="tel" id="phoneNum" value="" required>
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
                                <label for="fbName">Facebook Link</label>
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
                                <input type="text" id="indigenousInput" placeholder="If Yes, please specify"
                                    disabled>
                            </div>
                        </div>

                    </fieldset>

                    <fieldset class="custom-fieldset">
                        <legend>Educational Background</legend>
                        <div class="row">
                            <div class="column">
                                <label for="school">Name of Elementary/High School</label>
                                <select name="school" id="school">
                                    <option value="" selected hidden>Select school</option>
                                    <option value="">Pamantasan ng Lungsad ng Maynila</option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="column">
                                <label for="yrLevel">Incoming Year Level</label>
                                <input type="text" id="yrLevel" value="" required>
                            </div>
                            <div class="column">
                                <label for="collegeDept">Section</label>
                                <input type="text" id="section" value="" name="section" required>
                            </div>
                            <div class="column">
                                <label for="collegeDept">Strand</label>
                                <select name="yrLevel" id="yrLevel">
                                    <option value="" selected hidden>Select strand</option>
                                    <option value="N/A">N/A</option>
                                    <option value="Accountancy, Business and Management (ABM) Strand">Accountancy,
                                        Business
                                        and Management (ABM) Strand</option>
                                    <option value="Science, Technology, Engineering, and Mathematics (STEM) Strand">
                                        Science, Technology, Engineering, and Mathematics (STEM) Strand</option>
                                    <option value="Humanities and Social Science (HUMSS) Strand">Humanities and Social
                                        Science (HUMSS) Strand</option>
                                    <option value="General Academic Strand (GAS)">General Academic Strand (GAS)
                                    </option>
                                    <option value="Arts and Design Track">Arts and Design Track</option>
                                    <option value="Technical-Vocational-Livelihood (TVL) Track">
                                        Technical-Vocational-Livelihood (TVL) Track</option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="column">
                                <label for="course">General Average</label>
                                <input type="text" id="gwa" value="" required>
                            </div>
                            <div class="column">
                                <label for="gwa">Conduct</label>
                                <input type="text" id="gwaConduct" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="column">
                                <label for="course">Chinese Subject General Average</label>
                                <input type="text" id="chinesegwa" value="" required>
                            </div>
                            <div class="column">
                                <label for="gwa">Conduct</label>
                                <input type="text" id="chinesegwaConduct" required>
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
                                    <input type="text" id="fName" value="" name="fName" required>
                                </div>
                                <div class="column">
                                    <label for="fAge">Age</label>
                                    <input type="text" id="fAge" value="" name="fAge" required>
                                </div>
                                <div class="column">
                                    <label for="fSex">Sex</label>
                                    <select name="" id="fSex">
                                        <option value="" disabled selected hidden></option>
                                        <option value="F">F</option>
                                        <option value="M">M</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="column">
                                    <label for="fBirthdate">Birthdate</label>
                                    <input type="date" id="fBirthdate" value="" name="fBirthdate" required>
                                </div>
                                <div class="column">
                                    <label for="fRelationship">Relationship</label>
                                    <input type="text" id="fRelationship" value="Father" name="fRelationship"
                                        readonly>
                                </div>
                                <div class="column">
                                    <label for="fReligion">Religion</label>
                                    <input type="text" id="fReligion" value="" name="fReligion" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="column">
                                    <label for="fAttainment">Educational Attainment</label>
                                    <input type="text" id="fAttainment" value="" name="fAttainment"
                                        required>
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
                        </div>
                        <div class="motherinfo">
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
                                <div class="column">
                                    <label for="fSex">Sex</label>
                                    <select name="" id="fSex">
                                        <option value="" disabled selected hidden></option>
                                        <option value="F">F</option>
                                        <option value="M">M</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="column">
                                    <label for="fBirthdate">Birthdate</label>
                                    <input type="date" id="fBirthdate" value="" name="fBirthdate" required>
                                </div>
                                <div class="column">
                                    <label for="fRelationship">Relationship</label>
                                    <input type="text" id="fRelationship" value="Mother" name="fRelationship"
                                        readonly>
                                </div>
                                <div class="column">
                                    <label for="fReligion">Religion</label>
                                    <input type="text" id="fReligion" value="" name="fReligion" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="column">
                                    <label for="fAttainment">Educational Attainment</label>
                                    <input type="text" id="fAttainment" value="" name="fAttainment"
                                        required>
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
                        </div>
                        <div id="siblings-container">
                            <div class="siblingsinfo">
                                <p class="family">SIBLING INFORMATION</p>
                                <div class="row">
                                    <div class="column">
                                        <label for="fName">Name (Last Name, First Name)</label>
                                        <input type="text" id="fName" value="" name="fName" required>
                                    </div>
                                    <div class="column">
                                        <label for="fAge">Age</label>
                                        <input type="text" id="fAge" value="" name="fAge" required>
                                    </div>
                                    <div class="column">
                                        <label for="fSex">Sex</label>
                                        <select name="" id="fSex">
                                            <option value="" disabled selected hidden></option>
                                            <option value="F">F</option>
                                            <option value="M">M</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="column">
                                        <label for="fBirthdate">Birthdate</label>
                                        <input type="date" id="fBirthdate" value="" name="fBirthdate"
                                            required>
                                    </div>
                                    <div class="column">
                                        <label for="fRelationship">Relationship</label>
                                        <input type="text" id="fRelationship" value="Sibling"
                                            name="fRelationship" readonly>
                                    </div>
                                    <div class="column">
                                        <label for="fReligion">Religion</label>
                                        <input type="text" id="fReligion" value="" name="fReligion"
                                            required>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="column">
                                        <label for="fAttainment">Educational Attainment</label>
                                        <input type="text" id="fAttainment" value="" name="fAttainment"
                                            required>
                                    </div>
                                    <div class="column">
                                        <label for="fSchoolOcc">School/Occupation</label>
                                        <input type="text" id="fSchoolOcc" value="" name="fSchoolOcc"
                                            required>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="column">
                                        <label for="fCompany">Company</label>
                                        <input type="text" id="fCompany" value="" name="fCompany"
                                            required>
                                    </div>
                                    <div class="column">
                                        <label for="fIncome">Income</label>
                                        <input type="text" id="fIncome" value="" name="fIncome" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button id="addSibling">Add Sibling</button>

                    </fieldset>

                    <p class="familyinfo">Family Information</p>
                    <div class="ctn-table table-responsive">
                        <table class="table table-bordered" id="table">
                            <thead>
                                <tr>
                                    <th class="text-center align-middle col1">Name <br><span>(Last Name, First
                                            Name)</span>
                                    </th>
                                    <th class="text-center align-middle col2">Age</th>
                                    <th class="text-center align-middle col3">Sex</th>
                                    <th class="text-center align-middle col4">Birthdate <br><span></span></th>
                                    <th class="text-center align-middle col5">Relationship</th>
                                    <th class="text-center align-middle col6">Religion</th>
                                    <th class="text-center align-middle col7">Educational Attainment</th>
                                    <th class="text-center align-middle col8">School/ Occupation</th>
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
                                            <option value="F">F</option>
                                            <option value="M">M</option>
                                        </select>
                                    </td>
                                    <td><input type="date" id="fBirthdate" required></td>
                                    <td><input type="text" id="fRelationship" required></td>
                                    <td><input type="text" id="fReligion" required></td>
                                    <td><input type="text" id="fAttainment" required></td>
                                    <td><input type="text" id="fSchoolOcc" required></td>
                                    <td><input type="text" id="fCompany" required></td>
                                    <td><input type="text" id="fIncome" required></td>
                                    <td><button class="removeRowBtn">x</button></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <button id="addRowBtn">Add Row</button>


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
                                <label for="talents">Talents & Skills/ Honor and Recognition/
                                    Extracurricular/Community
                                    Involvement/Employment</label>
                                <textarea id="talents" name="talents" rows="2" cols="50" placeholder="Input your answer here..."></textarea>
                            </div>
                        </div>
                        <div class="row">
                            <div class="column">
                                <label for="expectations">What are your expectations from Tzu Chi Foundation?</label>
                                <textarea id="expectations" name="expectations" rows="2" cols="50"
                                    placeholder="Input your answer here..."></textarea>
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

                    <div class="agreement">
                        <input type="checkbox" value="" id="agreement">
                        <label for="agreement">
                            <i>I hereby attest that the information I have provided is true and correct. I also
                                consents Tzu Chi Foundation to obtain and retain my personal information for the purpose
                                of
                                this application.</i>
                        </label>
                    </div>
                    <div class="submit text-center">
                        <button type="submit" class="btn-submit fw-bold"
                            onclick="window.location.href='appconfirmdialog.html'">Submit</button>
                    </div>
                </form>
            </div>
        @endif
    </div>

    <script src="{{ asset('js/applicant.js') }}"></script>
</body>

</html>
