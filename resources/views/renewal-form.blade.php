<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Application Form</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: sans-serif !important;
        }

        .appform-view {
            font-size: 16px;
        }

        .page1,
        .page2 {
            padding: 50px;
        }

        .header,
        .app-title {
            text-align: center;
        }

        .app-title {
            margin: 20px 0;
        }

        .app-title p {
            margin-bottom: 5px;
        }

        .personal-info .psec1 {
            display: flex;
            gap: 100px;
        }

        .psec1-info {
            display: flex;
            gap: 50px;
        }

        .personal-info .psec2 {
            display: flex;
            gap: 10px;
        }

        .psec2 img {
            width: 100%;
            height: auto;
            border: 1px solid #000;
        }

        .fvalue,
        .elabel span {
            font-weight: 600;
        }

        .educbg .esec2 {
            padding: 5px;
        }

        .esec2 .elabel {
            padding-bottom: 7px;
        }

        .educbg-info p {
            font-size: 14px;
            font-weight: 600;
            margin: 5px 0;
            text-align: center;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
        }

        .table td,
        .table th {
            border: 1px solid black;
            vertical-align: top;
        }

        .table tr td {
            padding: 2px;
        }

        .table td .flabel,
        .fvalue,
        .elabel,
        .evalue {
            font-size: 14px;
        }

        .table thead tr th {
            font-size: 13px;
            padding: 5px;
        }

        .table thead tr th span {
            font-size: 11px;
        }

        .table tbody tr td {
            font-size: 13px;
        }

        #familyTableBody td {
            text-align: center;
        }

        .other-info p {
            font-size: 14px;
            font-weight: 600;
            margin-top: 10px;
            margin-bottom: 5px;
            border-bottom: 1px solid #000;
        }

        .other-info span {
            width: 100%;
            font-size: 14px;
            text-align: justify;
        }

        .signature {
            text-align: center;
            font-size: 12px;
        }

        .sign {
            text-decoration: overline;
            font-weight: 600;
            padding-top: 30px;
        }

        .page2 h3 {
            text-align: center;
            padding-top: 20px;
        }

        .sketchome .sec-title {
            padding: 10px 0;
        }

        .sketchimg {
            width: 100%;
            height: 250px;
            border: 1px solid;
            text-align: center;
        }

        ol {
            padding: 0 10px;
        }

        ol li {
            width: 100%;
            font-size: 14px;
            text-align: justify;
        }

        .fillstaff {
            border-top: 1px solid;
        }

        .fillstaff .heading {
            font-weight: 600;
            font-size: 12px;
        }

        .table3 {
            font-size: 13px;
        }

        .table3 tr td {
            padding: 0 10px;
        }

        .page2 .group {
            gap: 20px;
            padding-top: 10px;
        }

        .page-break {
            page-break-before: always;
        }

        #cdtable * {
            font-size: 14px;
        }

        #cdtable tr td {
            vertical-align: top;
        }
    </style>
</head>

<body>
    <div class="appform-view">
        <div class="page1">
            <div class="header">
                <img src="{{ public_path('images/appform-header.png') }}" alt="header" style="width: 60%">
            </div>
            <div class="app-title">
                <p>Educational Assistance Program</p>
                <p><strong>APPLICATION FORM</strong></p>
                <p id="schoolYear">S.Y. {{ $applicant->schoolyear }}</p>
            </div>
            <div class="personal-info" style="margin-bottom: 10px;">
                <table width="100%" style="margin-bottom: 10px;">
                    <tr>
                        <td width="40%"><strong><u>PERSONAL INFORMATION</u></strong></td>
                        <td width="15%">Status:</td>
                        <td width="25%">Case Code:</td>
                        <td width="20%">Form No.:</td>
                    </tr>
                    <tr>
                        <td width="40%"></td>
                        <td width="15%"><strong>New</strong></td>
                        <td width="25%"><strong>{{ $applicant->caseCode }}</strong></td>
                        <td width="20%" style="border-bottom: 1px solid #000;"></td>
                    </tr>
                </table>
                <div class="psec2">
                    <table class="table1" style="width: 100%">
                        <tr>
                            <td width="90%">
                                <table class="table">
                                    <tr>
                                        <td colspan="2">
                                            <p class="flabel">Name: (Last Name, First Name, Middle Name)</p>
                                            <p class="fvalue">{{ $user->basicInfo->scLastname }},
                                                {{ $user->basicInfo->scFirstname }} {{ $user->basicInfo->scMiddlename }}
                                            </p>
                                        </td>
                                        <td colspan="2">
                                            <span class="flabel">Chinese Name</span><br>
                                            <span class="fvalue">{{ $user->basicInfo->scChinesename }}</span>
                                        </td>
                                        <td>
                                            <span class="flabel">Gender</span><br>
                                            <span class="fvalue" id="gender">{{ $user->basicInfo->scSex }}</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2">
                                            <span class="flabel">Home Address (House #/Unit #/Floor/Bldg. Name/Street
                                                Name)</span><br>
                                            <span class="fvalue"
                                                id="resAddress">{{ $user->addressinfo->scResidential }}</span>
                                        </td>
                                        <td>
                                            <span class="flabel">Barangay</span><br>
                                            <span class="fvalue" id="brgy">{{ $barangay }}</span>
                                        </td>
                                        <td>
                                            <span class="flabel">City</span><br>
                                            <span class="fvalue" id="city">{{ $city }}</span>
                                        </td>
                                        <td>
                                            <span class="flabel">Age</span><br>
                                            <span class="fvalue" id="age">{{ $user->basicInfo->scAge }}</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <span class="flabel">Email address</span><br>
                                            <span class="fvalue" id="email">{{ $user->scEmail }}</span>
                                        </td>
                                        <td>
                                            <span class="flabel">Occupation and Income</span><br>
                                            <span class="fvalue"
                                                id="occupation">{{ $user->basicInfo->scOccupation }}</span>,<br>
                                            <span class="fvalue" id="income">Php
                                                {{ $user->basicInfo->scIncome }}</span>
                                        </td>
                                        <td colspan="2">
                                            <span class="flabel">Cellphone No./Landline</span><br>
                                            <span class="fvalue" id="phoneNum">+{{ $user->scPhoneNum }}</span>
                                        </td>
                                        <td>
                                            <span class="flabel">Birthdate</span><br>
                                            <span class="fvalue"
                                                id="birthDate">{{ \Carbon\Carbon::parse($user->basicInfo->scDateOfBirth)->format('F j, Y') }}</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <span class="flabel">Facebook Link</span><br>
                                            <a href="{{ $applicant->fblink }}" class=" link link-success"
                                                id="fbName" target="_blank">{{ $user->basicInfo->scFblink }}</a>
                                        </td>
                                        <td colspan="4">
                                            <span class="flabel">Are you a member of any indigenous group?</span><br>
                                            <span class="fvalue"
                                                id="indigenous">{{ $user->basicInfo->scIsIndigenous }}.
                                                {{ $user->basicInfo->scIndigenousgroup ?? '' }}</span>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                            <td width="10%" style="vertical-align: top;">
                                <img src="{{ public_path('storage/' . $applicant->idpic) }}" alt="Applicant 1x1 Pic">
                            </td>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="educbg" style="margin-bottom: 10px;">
                <div class="esec1">
                    <span class="my-2"><strong><u>EDUCATIONAL BACKGROUND</u></strong></span>
                </div>
                <div class="educbg-info">
                    @if ($iscollege)
                        <p style="text-align: center;"><strong>COLLEGE</strong></p>
                        <table width="100%" style="font-size: 14px">
                            <tr>
                                <td width="40%">Name of University:</td>
                                <td width="60%" style="border-bottom: 1px solid #000">
                                    {{ $applicant->education->scSchoolName ?? '' }}</td>
                            </tr>
                            <tr>
                                <td width="40%">College Department:</td>
                                <td width="60%" style="border-bottom: 1px solid #000">
                                    {{ $applicant->education->scCollegedept ?? '' }}</td>
                            </tr>
                            <tr>
                                <td width="40%">Incoming Year Level:</td>
                                <td width="60%" style="border-bottom: 1px solid #000">
                                    {{ $applicant->education->scYearGrade ?? '' }}</td>
                            </tr>
                            <tr>
                                <td width="40%">Course:</td>
                                <td width="60%" style="border-bottom: 1px solid #000">
                                    {{ $applicant->education->scCourseStrandSec ?? '' }}</td>
                            </tr>
                            <tr>
                                <td width="40%">General Average Last Sem:</td>
                                <td width="60%" style="border-bottom: 1px solid #000">
                                    {{ $applicant->grade->gwa ?? '' }}</td>
                            </tr>
                        </table>
                    @else
                        <p style="text-align: center;"><strong>ELEMENTARY/HIGH SCHOOL</strong></p>
                        <table width="100%" style="font-size: 14px">
                            <tr>
                                <td width="40%">Name of Elementary/High School:</td>
                                <td width="60%" style="border-bottom: 1px solid #000">
                                    {{ $applicant->education->scSchoolName ?? '' }}
                                </td>
                            </tr>
                            <tr>
                                <td width="40%">Incoming Year Level:</td>
                                <td width="60%" style="border-bottom: 1px solid #000">
                                    {{ $applicant->education->scYearGrade ?? '' }}
                                </td>
                            </tr>
                            <tr>
                                <td width="40%">
                                    {{ $applicant->education->scYearGrade == 'Senior High' ? 'Strand' : 'Section' }}:
                                </td>
                                <td width="60%" style="border-bottom: 1px solid #000">
                                    {{ $applicant->education->scCourseStrandSec ?? '' }}
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" style="text-align: center; font-weight: bold; padding-top: 10px;">
                                    Chinese Subject
                                </td>
                            </tr>
                            <tr>
                                <td width="40%">General Average:</td>
                                <td width="60%" style="border-bottom: 1px solid #000">
                                    {{ $applicant->grade->gwa ?? '' }}
                                </td>
                            </tr>
                            <tr>
                                <td width="40%">Chinese General Average:</td>
                                <td width="60%" style="border-bottom: 1px solid #000">
                                    {{ $applicant->grade->gwa ?? '' }}
                                </td>
                            </tr>
                        </table>
                    @endif
                </div>
            </div>

            <div class="fam-info">
                <p class="sec-title fam" style="margin-bottom: 5px;"><strong><u>FAMILY INFORMATION</u></strong></p>
                <div class="table1">
                    <table class="table" style="width: 100%;">
                        <thead>
                            <tr>
                                <th>Name<br><span>(Last Name, First Name)</span></th>
                                <th>Age</th>
                                <th>Sex</th>
                                <th>Birtdate <br><span></span></th>
                                <th>Relationship</th>
                                <th>Religion</th>
                                <th>Educational Attainment</th>
                                <th>School/ Occupation</th>
                                <th>Company</th>
                                <th>Income</th>
                            </tr>
                        </thead>
                        <tbody id="familyTableBody">
                            {{-- FATHER --}}
                            <tr>
                                <td>{{ $father->name }}</td>
                                <td>{{ $father->age }}</td>
                                <td>{{ $father->sex }}</td>
                                <td>
                                    {{ \Carbon\Carbon::parse($father->birthdate)->format('m/d/Y') }}
                                </td>
                                <td>{{ $father->relationship }}</td>
                                <td>{{ $father->religion }}</td>
                                <td>{{ $father->educattainment }}</td>
                                <td>{{ $father->occupation }}</td>
                                <td>{{ $father->company }}</td>
                                <td>{{ $father->income }}</td>
                            </tr>
                            {{-- MOTHER --}}
                            <tr>
                                <td>{{ $mother->name }}</td>
                                <td>{{ $mother->age }}</td>
                                <td>{{ $mother->sex }}</td>
                                <td>
                                    {{ \Carbon\Carbon::parse($mother->birthdate)->format('m/d/Y') }}
                                </td>
                                <td>{{ $mother->relationship }}</td>
                                <td>{{ $mother->religion }}</td>
                                <td>{{ $mother->educattainment }}</td>
                                <td>{{ $mother->occupation }}</td>
                                <td>{{ $mother->company }}</td>
                                <td>{{ $mother->income }}</td>
                            </tr>
                            {{-- SIBLING/S --}}
                            @foreach ($siblings as $sib)
                                <tr>
                                    <td>{{ $sib->name }}</td>
                                    <td>{{ $sib->age }}</td>
                                    <td>{{ $sib->sex }}</td>
                                    <td>
                                        {{ \Carbon\Carbon::parse($sib->birthdate)->format('m/d/Y') }}
                                    </td>
                                    <td>{{ $sib->relationship }}</td>
                                    <td>{{ $sib->religion }}</td>
                                    <td>{{ $sib->educattainment }}</td>
                                    <td>{{ $sib->occupation }}</td>
                                    <td>{{ $sib->company }}</td>
                                    <td>{{ $sib->income }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="other-info" width="100%">
                <p>Grant/Assistance from other Government and Non-Government scholarships, School Discount (How much per
                    sem?)</p>
                <span id="grant">{{ $applicant->otherinfo->grant }}</span>
                <p>Talents & Skills/ Honor and Recognition/ Extracurricular/Community Involvement/Employment</p>
                <span id="talents">{{ $applicant->otherinfo->talent }}</span>
                <p>What are your expectations from Tzu Chi Foundation?</p>
                <span id="expectations">{{ $applicant->otherinfo->expectation }}</span>
            </div>

            <div class="signature">
                <p style="margin: 30px 0 20px 0;">I hereby attest that the information I have provided is true and
                    correct. I also consents Tzu Chi Foundation to obtain and retain my personal information
                    for the purpose of this application.</p>
                <p class="sign">Applicant's Signature over Printed Name and Date</p>
            </div>
        </div>

        <div class="page2 page-break">
            <div class="header">
                <img src="{{ public_path('images/appform-header.png') }}" alt="header" style="width: 60%">
            </div>
            <h3>Educational Assistance Application Form</h3>
            <div class="sketchome">
                <p class="sec-title"><strong><u>SKETCH OF HOME ADDRESS</u></strong></p>
                <div class="sketchimg">
                    <img src="{{ public_path('storage/' . $applicant->sketchmap) }}" alt="Sketch Map of Home Address"
                        style="height: 250px;">
                </div>
            </div>

            <div style="width: 100%; padding: 5px 10px; margin-bottom: 10px">
                <ol>
                    <li>
                        The objective of Tzu Chi scholarship program is to provide financial assistance to
                        deserving
                        students through tuition fee support,
                        monthly living allowance, as well as additional assistance for other school needs,
                        should it
                        be deemed necessary. College students
                        are only authorized to enroll in partner schools and authorized courses.
                    </li>
                    <li>
                        Students with a failing grade on any subject, with <strong>general weighted average 82%
                            both
                            English and Chinese</strong> or with a grade on
                        <strong>Conduct below B</strong> or with scholarship grant from other foundations or
                        organizations <strong>will not be accepted</strong>.
                    </li>
                    <li>Please fill up the Scholarship Application form completely and Draw Sketch of home
                        address
                        use the back page if necessary. Any
                        misleading information may lead to disqualification.
                    </li>
                    <li>
                        Submit the following: <span style="font-style: italic;"><strong>Photocopy of Report Card or
                                copy
                                of
                                grade
                                form
                                last school year, Registration form, Accomplished Application
                                Form, Two (2) 1x1 ID Pictures, Autobiography, Family Picture, Copies of Utility
                                Bills,
                                Detailed Sketch of Home Address, Certificate
                                of indigence from the Barangay, Pictures of House (Inside and outside), Payslip or
                                Income Tax Return of Both Parents (if working).</strong></span>
                        <strong>Deadline of Submission is on <span><u>
                                    {{ $form->deadline ? \Carbon\Carbon::parse($form->deadline)->format('F j, Y') : '--' }}</u>
                            </span></strong>
                        .
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

            <div style="width: 100%; border-top: 1px solid #000; padding-top: 5px; font-weight: bold">
                <p style="font-size: 12px; margin-bottom: 10px;">To be filled up by TZU CHI FOUNDATION</p>
                <p style="font-size: 14px; margin-bottom: 5px;">Case Details</p>
            </div>
            <table class="table" style="margin-bottom: 5px;">
                <tr>
                    <td width="20%">
                        <span><strong>Nature of Needs</strong></span><br>
                        @foreach ($needs as $need)
                            <div>
                                <!-- Use solid circle for selected, outline circle for unselected -->
                                {!! isset($applicant->casedetails) && $applicant->casedetails->natureofneeds == $need ? '&#x25CF;' : '&#x25CB;' !!}
                                {{ $need }}
                            </div>
                        @endforeach
                        <div>
                            {!! isset($applicant->casedetails) && $applicant->casedetails->natureofneeds == 'Others'
                                ? '&#x25CF;'
                                : '&#x25CB;' !!}
                            Others
                        </div>
                        @if (isset($applicant->casedetails) && !in_array($applicant->casedetails->natureofneeds, $needs))
                            <div style="border-bottom: 1px solid #000; margin-left: 20px; margin-top: 5px;">
                                {{ $applicant->casedetails->natureofneeds }}
                            </div>
                        @endif
                    </td>
                    <td width="80%">
                        <div><strong>Problem Statement</strong></div>
                        <div style="text-align: justify; margin-top: 5px;">
                            {{ $applicant->casedetails->problemstatement ?? '' }}
                        </div>
                    </td>
                </tr>
            </table>

            <table width="100%" id="cdtable" style="border-collapse: collapse; margin: 10px 0;">
                <tr>
                    <!-- Left Column -->
                    <td width="42%" style="vertical-align: top; padding-right: 10px;">
                        <table style="width: 100%; border-spacing: 0; font-size: 14px;">
                            <tr>
                                <td width="48%" style="padding: 5px 0;">Received By:</td>
                                <td width="52%" style="border-bottom: 1px solid #000; padding-left: 5px;">
                                    {{ isset($applicant->casedetails->receivedby) ? $applicant->casedetails->receivedby : '' }}
                                </td>
                            </tr>
                            <tr>
                                <td width="48%" style="padding: 5px 0;">Date Received:</td>
                                <td width="52%" style="border-bottom: 1px solid #000; padding-left: 5px;">
                                    {{ isset($applicant->casedetails->datereceived) ? $applicant->casedetails->datereceived : '' }}
                                </td>
                            </tr>
                            <tr>
                                <td width="48%" style="padding: 5px 0;">Assigned District:</td>
                                <td width="52%" style="border-bottom: 1px solid #000; padding-left: 5px;">
                                    {{ isset($applicant->casedetails->district) ? $applicant->casedetails->district : '' }}
                                </td>
                            </tr>
                            <tr>
                                <td width="48%" style="padding: 5px 0;">Assigned Volunteer:</td>
                                <td width="52%" style="border-bottom: 1px solid #000; padding-left: 5px;">
                                    {{ isset($applicant->casedetails->volunteer) ? $applicant->casedetails->volunteer : '' }}
                                </td>
                            </tr>
                        </table>
                    </td>

                    <!-- Right Column -->
                    <td width="57%" style="vertical-align: top; padding-left: 10px;">
                        <table style="width: 100%; border-spacing: 0; font-size: 14px;">
                            <tr>
                                <td colspan="2" style="text-align: justify; font-style: italic; padding: 5px 0;">
                                    Note: Important information is essential to assess and evaluate the student's
                                    situation. Please
                                    note significant details for home visitation purposes. Use the back page if
                                    necessary.
                                </td>
                            </tr>
                            <tr>
                                <td width="48%" style="padding: 5px 0;">Case Referred By:</td>
                                <td width="52%" style="border-bottom: 1px solid #000; padding-left: 5px;">
                                    {{ isset($applicant->casedetails->referredby) ? $applicant->casedetails->referredby : '' }}
                                </td>
                            </tr>
                            <tr>
                                <td width="48%" style="padding: 5px 0;">Referral Contact No.:</td>
                                <td width="52%" style="border-bottom: 1px solid #000; padding-left: 5px;">
                                    {{ isset($applicant->casedetails->referphonenum) ? $applicant->casedetails->referphonenum : '' }}
                                </td>
                            </tr>
                            <tr>
                                <td width="48%" style="padding: 5px 0;">Relationship with Beneficiary:</td>
                                <td width="52%" style="border-bottom: 1px solid #000; padding-left: 5px;">
                                    {{ isset($applicant->casedetails->relationship) ? $applicant->casedetails->relationship : '' }}
                                </td>
                            </tr>
                            <tr>
                                <td width="48%" style="padding: 5px 0;">Applicant's Signature:</td>
                                <td width="52%" style="border-bottom: 1px solid #000; padding-left: 5px;"></td>
                            </tr>
                            <tr>
                                <td width="48%" style="padding: 5px 0;">Date Reported:</td>
                                <td width="52%" style="border-bottom: 1px solid #000; padding-left: 5px;">
                                    {{ isset($applicant->casedetails->datereported) ? $applicant->casedetails->datereported : '' }}
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>

        </div>
    </div>
</body>

</html>
