<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $data['title'] }}</title>
    </title>
    <link rel="stylesheet" href="{{ asset('css/appformviewpdf.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet" />
</head>

<body>
    <!-- MAIN -->
    <div class="appform-view">
        <div class="page1">
            <div class="header">
                <img src="{{ asset('images/logo.png') }}" alt="Logo">
                <p><strong>佛教慈濟慈善事業基金會菲律濱分會<br>Buddhist Compassion Relief Tzu Chi Foundation, Philippines</strong>
                </p>
            </div>
            <div class="app-title">
                <p>Educational Assistance Program</p>
                <p><strong>APPLICATION FORM</strong></p>
                <p id="schoolYear">S.Y. {{ date('Y') }}-{{ date('Y') + 1 }}</p>
            </div>
            <div class="personal-info">
                <div class="row">
                    <span class="col-md-4"><strong><u>PERSONAL INFORMATION</u></strong></span>
                    <span class="col-md-2">Status:</span>
                    <span class="col-md-3">Case Code:</span>
                    <span class="col-md-3">Form No.:</span>
                </div>
                <div class="row mb-2">
                    <span class="col-md-4"></span>
                    <span class="col-md-2"><strong>New</strong></span>
                    <span class="col-md-3"><strong>{{ $data['applicant']->casecode }}</strong></span>
                    <span class="col-md-3"></span>
                </div>
                <div class="psec2">
                    <div class="table1">
                        <table class="table table-bordered" id="table">
                            <tr>
                                <td colspan="2">
                                    <span class="flabel">Name: (Last Name, First Name, Middle Name)</span><br>
                                    <span class="fvalue" id="fullName">{{ $data['applicant']->name }}</span>
                                </td>
                                <td colspan="2">
                                    <span class="flabel">Chinese Name</span><br>
                                    <span class="fvalue" id="cName">{{ $data['applicant']->chinesename }}</span>
                                </td>
                                <td>
                                    <span class="flabel">Gender</span><br>
                                    <span class="fvalue" id="gender">{{ $data['applicant']->sex }}</span>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <span class="flabel">Home Address (House #/Unit #/Floor/Bldg. Name/Street
                                        Name)</span><br>
                                    <span class="fvalue" id="resAddress">{{ $data['applicant']->homeaddress }}</span>
                                </td>
                                <td>
                                    <span class="flabel">Barangay</span><br>
                                    <span class="fvalue" id="brgy">{{ $data['applicant']->barangay }}</span>
                                </td>
                                <td>
                                    <span class="flabel">City</span><br>
                                    <span class="fvalue" id="city">{{ $data['applicant']->city }}</span>
                                </td>
                                <td>
                                    <span class="flabel">Age</span><br>
                                    <span class="fvalue" id="age">{{ $data['applicant']->age }}</span>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <span class="flabel">Email address</span><br>
                                    <span class="fvalue" id="email">{{ $data['applicant']->email }}</span>
                                </td>
                                <td>
                                    <span class="flabel">Occupation and Income</span><br>
                                    <span class="fvalue"
                                        id="occupation">{{ $data['applicant']->occupation }}</span>,<br>
                                    <span class="fvalue" id="income">Php {{ $data['applicant']->income }}</span>
                                </td>
                                <td colspan="2">
                                    <span class="flabel">Cellphone No./Landline</span><br>
                                    <span class="fvalue" id="phoneNum">{{ $data['applicant']->phonenum }}</span>
                                </td>
                                <td>
                                    <span class="flabel">Birthdate</span><br>
                                    <span class="fvalue"
                                        id="birthDate">{{ \Carbon\Carbon::parse($data['applicant']->birthdate)->format('F j, Y') }}</span>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <span class="flabel">Facebook name</span><br>
                                    <a href="{{ $data['applicant']->fblink }}" class="fvalue" id="fbName"
                                        target="_blank">{{ $data['applicant']->fblink }}</a>
                                </td>
                                <td colspan="4">
                                    <span class="flabel">Are you a member of any indigenous group?</span><br>
                                    <span class="fvalue" id="indigenous">{{ $data['applicant']->isIndigenous }}.
                                        {{ $data['applicant']->indigenousgroup ?? '' }}</span>
                                </td>
                            </tr>
                        </table>

                    </div>
                    <img src="{{ asset('storage/' . $data['applicant']->requirements->idpic) }}"
                        alt="Applicant 1x1 Pic">
                </div>
            </div>

            <div class="educbg">
                <div class="esec1">
                    <span class="my-2"><strong><u>EDUCATIONAL BACKGROUND</u></strong></span>
                </div>
                <div class="d-flex flex-column mb-2">
                    @if ($data['iscollege'])
                        <span class="text-center my-2"><strong>COLLEGE</strong></span>
                        <div class="column">
                            <div class="mb-1 small">
                                Name of University:
                                <span id="university">
                                    <strong>{{ $data['applicant']->educcollege->univname ?? '' }}</strong>
                                </span>
                            </div>
                            <div class="mb-1 small">
                                College Department:
                                <span id="collegeDept">
                                    <strong>{{ $data['applicant']->educcollege->collegedept ?? '' }}</strong>
                                </span>
                            </div>
                            <div class="mb-1 small">
                                Incoming Year Level:
                                <span id="yrLevel">
                                    <strong>{{ $data['applicant']->educcollege->inyear ?? '' }}</strong>
                                </span>
                            </div>
                            <div class="mb-1 small">
                                Course:
                                <span id="course">
                                    <strong>{{ $data['applicant']->educcollege->course ?? '' }}</strong>
                                </span>
                            </div>
                            <div class="mb-1 small">General Average Last Sem:
                                <span id="gwa">
                                    <strong>{{ $data['applicant']->educcollege->gwa ?? '' }}</strong>
                                </span>
                            </div>
                        @else
                            <span class="text-center my-2"><strong>ELEMENTARY/HIGH SCHOOL</strong></span>
                            <div class="column">
                                <div class="mb-1 small">
                                    Name of Elementary/High School:
                                    <span id="university">
                                        <strong>{{ $data['applicant']->educelemhs->schoolname ?? '' }}</strong>
                                    </span>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-1 small">
                                            Incoming Year Level:
                                            <span id="university">
                                                <strong>{{ $data['applicant']->educelemhs->ingrade ?? '' }}</strong>
                                            </span>
                                        </div>
                                        <div class="mb-1 small">
                                            Section:
                                            <span id="university">
                                                <strong>{{ $data['applicant']->educelemhs->section ?? '' }}</strong>
                                            </span>
                                        </div>
                                        <div class="mb-1 small">
                                            General Average:
                                            <span id="university">
                                                <strong>{{ $data['applicant']->educelemhs->gwa ?? '' }}</strong>
                                            </span>
                                        </div>
                                        <div class="mb-1 small">
                                            Conduct:
                                            <span id="university">
                                                <strong>{{ $data['applicant']->educelemhs->gwaconduct ?? '' }}</strong>
                                            </span>
                                        </div>
                                        <div class="mb-1 small">
                                            Strand:
                                            <span id="university">
                                                <strong>{{ $data['applicant']->educelemhs->strand ?? '' }}</strong>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <span class="small">Chinese Subject</span>
                                        <div class="mb-1 small">
                                            General Average:
                                            <span id="university">
                                                <strong>{{ $data['applicant']->educelemhs->chinesegwa ?? '' }}</strong>
                                            </span>
                                        </div>
                                        <div class="mb-1 small">
                                            Conduct:
                                            <span id="university">
                                                <strong>{{ $data['applicant']->educelemhs->chinesegwaconduct ?? '' }}</strong>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                    @endif
                </div>
            </div>

            <div class="fam-info">
                <span class="my-2"><strong><u>FAMILY INFORMATION</u></strong></span>
                <div class="table1">
                    <table class="table table-bordered" id="table">
                        <thead>
                            <tr>
                                <th class="text-center align-center" style="width: 150px;">Name <br><span>(Last Name,
                                        First Name)</span></th>
                                <th class="text-center align-center">Age</th>
                                <th class="text-center align-center">Sex</th>
                                <th class="text-center align-center">Birthdate <br><span></span></th>
                                <th class="text-center align-center">Relationship</th>
                                <th class="text-center align-center">Religion</th>
                                <th class="text-center align-center">Educational Attainment</th>
                                <th class="text-center align-center">School/Occupation</th>
                                <th class="text-center align-center">Company</th>
                                <th class="text-center align-center">Income</th>
                            </tr>
                        </thead>
                        <tbody id="familyTableBody">
                            {{-- FATHER --}}
                            <tr>
                                <td class="text-left align-center">{{ $data['father']->name }}</td>
                                <td class="text-center align-center">{{ $data['father']->age }}</td>
                                <td class="text-center align-center">{{ $data['father']->sex }}</td>
                                <td class="text-center align-center">
                                    {{ \Carbon\Carbon::parse($data['father']->birthdate)->format('F j, Y') }}
                                </td>
                                <td class="text-center align-center">{{ $data['father']->relationship }}</td>
                                <td class="text-center align-center">{{ $data['father']->religion }}</td>
                                <td class="text-center align-center">{{ $data['father']->educattainment }}</td>
                                <td class="text-center align-center">{{ $data['father']->occupation }}</td>
                                <td class="text-center align-center">{{ $data['father']->company }}</td>
                                <td class="text-center align-center">{{ $data['father']->income }}</td>
                            </tr>
                            {{-- MOTHER --}}
                            <tr>
                                <td class="text-left align-center">{{ $data['mother']->name }}</td>
                                <td class="text-center align-center">{{ $data['mother']->age }}</td>
                                <td class="text-center align-center">{{ $data['mother']->sex }}</td>
                                <td class="text-center align-center">
                                    {{ \Carbon\Carbon::parse($data['mother']->birthdate)->format('F j, Y') }}
                                </td>
                                <td class="text-center align-center">{{ $data['mother']->relationship }}</td>
                                <td class="text-center align-center">{{ $data['mother']->religion }}</td>
                                <td class="text-center align-center">{{ $data['mother']->educattainment }}</td>
                                <td class="text-center align-center">{{ $data['mother']->occupation }}</td>
                                <td class="text-center align-center">{{ $data['mother']->company }}</td>
                                <td class="text-center align-center">{{ $data['mother']->income }}</td>
                            </tr>
                            {{-- SIBLINGS --}}
                            @foreach ($data['siblings'] as $sib)
                                <tr>
                                    <td class="text-left align-center">{{ $sib->name }}</td>
                                    <td class="text-center align-center">{{ $sib->age }}</td>
                                    <td class="text-center align-center">{{ $sib->sex }}</td>
                                    <td class="text-center align-center">
                                        {{ \Carbon\Carbon::parse($sib->birthdate)->format('F j, Y') }}
                                    </td>
                                    <td class="text-center align-center">{{ $sib->relationship }}</td>
                                    <td class="text-center align-center">{{ $sib->religion }}</td>
                                    <td class="text-center align-center">{{ $sib->educattainment }}</td>
                                    <td class="text-center align-center">{{ $sib->occupation }}</td>
                                    <td class="text-center align-center">{{ $sib->company }}</td>
                                    <td class="text-center align-center">{{ $sib->income }}</td>
                                </tr>
                            @endforeach
                            {{-- Add Empty Rows to Maintain Minimum Row Count --}}

                        </tbody>
                    </table>
                </div>
            </div>


            <div class="other-info column small">
                <div class="row mt-2">
                    <strong>
                        Grant/Assistance from other Government and Non-Government scholarships, School Discount (How
                        much per sem?)
                    </strong>
                </div>
                <div class="row px-3">
                    <span style="white-space: pre-wrap;">{{ $data['applicant']->otherinfo->grant }}</span>
                </div>

                <div class="row mt-2">
                    <strong>
                        Talents & Skills/ Honors and Recognition/ Extracurricular/Community Involvement/Employment
                    </strong>
                </div>
                <div class="row px-3">
                    <span style="white-space: pre-wrap;">{{ $data['applicant']->otherinfo->talent }}</span>
                </div>

                <div class="row mt-2 page-break">
                    <strong>What are your expectations from Tzu Chi Foundation?</strong>
                </div>
                <div class="row px-3">
                    <span style="white-space: pre-wrap;">{{ $data['applicant']->otherinfo->expectations }}</span>
                </div>
            </div>

            <div class="signature">
                <p>I hereby attest that the information I have provided is true and correct. I also consents Tzu Chi
                    Foundation to obtain and retain my personal information for the purpose of this application.</p>
                <div>
                    <p id="nameDate"></p>
                    <p class="sign">Applicant's Signature over Printed Name and Date</p>
                </div>
            </div>
        </div>
    </div>


    <div class="page2">
        <div class="header">
            <img src="{{ asset('images/logo.png') }}" class="logo" alt="Logo">
            <p><strong><br>Buddhist Compassion Relief Tzu Chi Foundation, Philippines</strong>
            </p>
        </div>
        <h3 class="text-center my-3">Educational Assistance Application Form</h3>
        <span class="row"><strong><u>Sketch of Home Address</u></strong></span>
        <div class="sketchimg">
            <img src="{{ asset('storage/' . $data['applicant']->requirements->sketchmap) }}"
                alt="Sketch Map of Home Address" id="sketchmap">
        </div>
        <div class="instructions">
            <ol>
                <li>
                    The objective of Tzu Chi scholarship program is to provide financial assistance to deserving
                    students through tuition fee support,
                    monthly living allowance, as well as additional assistance for other school needs, should it
                    be deemed necessary. College students
                    are only authorized to enroll in partner schools and authorized courses.
                </li>
                <li>
                    Students with a failing grade on any subject, with <strong>general weighted average 82% both
                        English and Chinese</strong> or with a grade on
                    <strong>Conduct below B</strong> or with scholarship grant from other foundations or
                    organizations will not be accepted.
                </li>
                <li>Please fill up the Scholarship Application form completely and Draw Sketch of home address
                    use the back page if necessary. Any
                    misleading information may lead to disqualification.
                </li>
                <li>
                    Submit the following: <span id="reqs">Photocopy of Report Card or copy of grade form
                        last school year, Registration form, Accomplished Application
                        Form, Two (2) 1x1 ID Pictures, Autobiography, Family Picture, Copies of Utility Bills,
                        Detailed Sketch of Home Address, Certificate
                        of indigence from the Barangay, Pictures of House (Inside and outside), Payslip or
                        Income Tax Return of Both Parents (if working).</span>
                    <strong>Deadline of Submission is on</strong> <span id="deadline">DEADLINE</span>.
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
                    09953066694.
                </li>
            </ol>
        </div>
        <div class="fillstaff">
            <div class="heading">
                <p>To be filled up by TZU CHI FOUNDATION</p>
                <p>Case Details</p>
            </div>
        </div>
        <div class="table3">
            <table class="table" id="table">
                <tr>
                    <td>
                        <span class="slabel">Nature of Needs</span><br>
                        <span class="svalue" id="natureOfneeds">
                            <input type="radio" id="financial" name="needs" value="Financial" readonly>
                            <label for="financial">Financial</label><br>
                            <input type="radio" id="medical" name="needs" value="Medical" readonly>
                            <label for="medical">Medical</label><br>
                            <input type="radio" id="food" name="needs" value="Food" readonly>
                            <label for="food">Food</label><br>
                            <input type="radio" id="material" name="needs" value="Material" readonly>
                            <label for="material">Material</label><br>
                            <input type="radio" id="educ" name="needs" value="Education" readonly checked>
                            <label for="educ"> Education</label><br>
                            <input type="radio" id="others" name="needs" value="Others" readonly>
                            <label for="others"> Others</label><br>
                        </span>
                    </td>
                    <td style="width: 600px;">
                        <span class="slabel"><strong>Problem Statement</strong></span><br>
                        <textarea name="problemstatement" id="" rows="5" cols="6"
                            placeholder="{{ $data['applicant']->casedetails->problemstatement ?? '' }}" readonly></textarea>
                    </td>
                </tr>
            </table>
        </div>
        <div class="row">
            <div class="column col-md-5">
                <div class="row my-2 d-flex justify-content-between casedeets">
                    Received By:
                    <input class="casedeets-input text-center" style="width: 65% !important" type="text"
                        name="receiveby" value="{{ $data['applicant']->casedetails->receiveby ?? '' }}" readonly>
                </div>
                <div class="row my-2 d-flex justify-content-between casedeets">
                    Date Receive:
                    <input class="casedeets-input text-center" style="width: 50% !important" type="text"
                        name="datereceived" value="{{ $data['applicant']->casedetails->datereceived ?? '' }}"
                        readonly>
                </div>
                <div class="row my-2 d-flex justify-content-between casedeets">
                    Assigned District:
                    <input class="casedeets-input text-center" style="width: 50% !important" type="text"
                        name="district" value="{{ $data['applicant']->casedetails->district ?? '' }}" readonly>
                </div>
                <div class="row my-2 d-flex justify-content-between casedeets">
                    Assigned Volunteer:
                    <input class="casedeets-input text-center" style="width: 50% !important" type="text"
                        name="volunteer" value="{{ $data['applicant']->casedetails->volunteer ?? '' }}" readonly>
                </div>
            </div>
            <div class="column col-md-7">
                <div class="row">
                    <span class="note">
                        Note: Important information are essential to be able to assess and evaluate students'
                        situation. Also please note significant details for home visitation purposes. You may
                        use the back page if necessary.
                    </span>
                </div>
                <div class="column" style="padding: 0 10px">
                    <div class="row my-2 d-flex justify-content-between casedeets">
                        Case Referred By:
                        <input class="casedeets-input text-center" style="width: 45% !important" type="text"
                            name="referredby" value="{{ $data['applicant']->casedetails->referredby ?? '' }}"
                            readonly>
                    </div>
                    <div class="row my-2 d-flex justify-content-between casedeets">
                        Referral Contact no.:
                        <input class="casedeets-input text-center" style="width: 45% !important" type="tel"
                            name="referphonenum" value="{{ $data['applicant']->casedetails->referphonenum ?? '' }}"
                            readonly>
                    </div>
                    <div class="row my-2 d-flex justify-content-between casedeets">Relationship with
                        Beneficiary:
                        <input class="casedeets-input text-center" style="width: 45% !important" type="text"
                            name="relationship" value="{{ $data['applicant']->casedetails->relationship ?? '' }}"
                            readonly>
                    </div>
                    <div class="row my-2 d-flex justify-content-between casedeets">
                        Applicant's Signature:
                        <span class="casedeets-input text-center" style="width: 45% !important"></span>
                    </div>
                    <div class="row my-2 d-flex justify-content-between casedeets">
                        Date Reported:
                        <input class="casedeets-input text-center" style="width: 45% !important" type="text"
                            name="datereported" value="{{ $data['applicant']->casedetails->datereported ?? '' }}"
                            readonly>
                    </div>
                </div>
            </div>
        </div>
    </div>


</body>

</html>
