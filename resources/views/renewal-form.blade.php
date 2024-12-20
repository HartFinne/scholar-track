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
            font-family: sans-serif;
            font-size: 14px;
        }

        body {
            padding: 100px 25px 25px 25px;
        }

        .header {
            position: fixed;
            width: 100%;
            top: 15px;
            left: 50%;
            transform: translateX(-50%);
            text-align: center;
        }

        table {
            border-collapse: collapse;
            width: 100%;
            word-wrap: break-word;
        }

        .table tr td,
        .table tr th {
            border: 1px solid #000;
            padding: 2px;
        }

        .fvalue {
            font-weight: bold;
        }

        .page-break-before {
            page-break-before: always;
        }
    </style>
</head>

<body>
    <div class="header">
        <img src="{{ public_path('images/appform-header.png') }}" alt="header" style="width: 60%">
    </div>
    <p style="text-align: center; margin-bottom: 8px; font-size: 16px">Educational Assistance Program</p>
    <p style="text-align: center; margin-bottom: 8px; font-size: 16px"><strong>APPLICATION FORM</strong></p>
    <p style="text-align: center; margin-bottom: 8px; font-size: 16px" id="schoolYear">S.Y.
        {{ $user->education->scAcademicYear }}</p>
    <table width="100%" style="margin-bottom: 10px;">
        <tr>
            <td width="40%" style="vertical-align: top;"><strong><u>PERSONAL INFORMATION</u></strong></td>
            <td width="15%">Status:<br><strong>New</strong></td>
            <td width="25%">Case Code:<br><strong>{{ $applicant->caseCode }}</strong></td>
            <td width="20%" style="vertical-align: top; border-bottom: 1px solid #000;">Form No.:<br>
                <p></p>
            </td>
        </tr>
    </table>
    <table style="width: 100%">
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
                            <p class="flabel">Chinese Name</p>
                            <p class="fvalue">{{ $user->basicInfo->scChinesename }}</p>
                        </td>
                        <td>
                            <p class="flabel">Gender</p>
                            <p class="fvalue" id="gender">{{ $user->basicInfo->scSex }}</p>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <p class="flabel">Home Address (House #/Unit #/Floor/Bldg. Name/Street
                                Name)</p>
                            <p class="fvalue" id="resAddress">{{ $user->addressinfo->scResidential }}</p>
                        </td>
                        <td>
                            <p class="flabel">Barangay</p>
                            <p class="fvalue" id="brgy">{{ $barangay }}</p>
                        </td>
                        <td>
                            <p class="flabel">City</p>
                            <p class="fvalue" id="city">{{ $city }}</p>
                        </td>
                        <td>
                            <p class="flabel">Age</p>
                            <p class="fvalue" id="age">{{ $user->basicInfo->scAge }}</p>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <p class="flabel">Email address</p>
                            <p class="fvalue" id="email">{{ $user->scEmail }}</p>
                        </td>
                        <td>
                            <p class="flabel">Occupation and Income</p>
                            <p class="fvalue" id="occupation">{{ $user->basicInfo->scOccupation }},<br>
                                Php {{ $user->basicInfo->scIncome }}</Php>
                        </td>
                        <td colspan="2">
                            <p class="flabel">Cellphone No./Landline</p>
                            <p class="fvalue" id="phoneNum">+{{ $user->scPhoneNum }}</p>
                        </td>
                        <td>
                            <p class="flabel">Birthdate</p>
                            <p class="fvalue" id="birthDate">
                                {{ \Carbon\Carbon::parse($user->basicInfo->scDateOfBirth)->format('F j, Y') }}</p>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <p class="flabel">Facebook Link</p>
                            <a href="{{ $applicant->fblink }}" class=" link link-success" id="fbName"
                                target="_blank">{{ $user->basicInfo->scFblink }}</a>
                        </td>
                        <td colspan="4">
                            <p class="flabel">Are you a member of any indigenous group?</p>
                            <p class="fvalue" id="indigenous">{{ $user->basicInfo->scIsIndigenous }}.
                                {{ $user->basicInfo->scIsIndigenous == 'No' ? '' : $user->basicInfo->scIndigenousgroup }}
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
            <td width="10%" style="vertical-align: top;">
                <img src="{{ public_path('storage/' . $applicant->idpic) }}" alt="Applicant 1x1 Pic"
                    style="width: 95%; margin-left: 5%; height: auto; border: 1px solid #000;">
            </td>
        </tr>
    </table>

    <p style="margin-top: 10px;"><strong><u>EDUCATIONAL BACKGROUND</u></strong></p>
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

    <p style="margin: 10px 0;"><strong><u>FAMILY INFORMATION</u></strong></p>
    <table class="table">
        <thead>
            <tr>
                <th>Name<br>
                    <p>(Last Name, First Name)</p>
                </th>
                <th>Age</th>
                <th>Sex</th>
                <th>Birtdate <br>
                    <p></p>
                </th>
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
                <td style="text-align: center;">{{ $father->age }}</td>
                <td style="text-align: center;">{{ $father->sex }}</td>
                <td style="text-align: center;">
                    {{ \Carbon\Carbon::parse($father->birthdate)->format('m/d/Y') }}
                </td>
                <td style="text-align: center;">{{ $father->relationship }}</td>
                <td style="text-align: center;">{{ $father->religion }}</td>
                <td style="text-align: center;">{{ $father->educattainment }}</td>
                <td style="text-align: center;">{{ $father->occupation }}</td>
                <td style="text-align: center;">{{ $father->company }}</td>
                <td style="text-align: center;">{{ $father->income }}</td>
            </tr>
            {{-- MOTHER --}}
            <tr>
                <td>{{ $mother->name }}</td>
                <td style="text-align: center;">{{ $mother->age }}</td>
                <td style="text-align: center;">{{ $mother->sex }}</td>
                <td>
                    {{ \Carbon\Carbon::parse($mother->birthdate)->format('m/d/Y') }}
                </td>
                <td style="text-align: center;">{{ $mother->relationship }}</td>
                <td style="text-align: center;">{{ $mother->religion }}</td>
                <td style="text-align: center;">{{ $mother->educattainment }}</td>
                <td style="text-align: center;">{{ $mother->occupation }}</td>
                <td style="text-align: center;">{{ $mother->company }}</td>
                <td style="text-align: center;">{{ $mother->income }}</td>
            </tr>
            {{-- SIBLING/S --}}
            @foreach ($siblings as $sib)
                <tr>
                    <td>{{ $sib->name }}</td>
                    <td style="text-align: center;">{{ $sib->age }}</td>
                    <td style="text-align: center;">{{ $sib->sex }}</td>
                    <td>
                        {{ \Carbon\Carbon::parse($sib->birthdate)->format('m/d/Y') }}
                    </td>
                    <td style="text-align: center;">{{ $sib->relationship }}</td>
                    <td style="text-align: center;">{{ $sib->religion }}</td>
                    <td style="text-align: center;">{{ $sib->educattainment }}</td>
                    <td style="text-align: center;">{{ $sib->occupation }}</td>
                    <td style="text-align: center;">{{ $sib->company }}</td>
                    <td style="text-align: center;">{{ $sib->income }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <p style="margin-top: 10px; border-bottom: 1px solid #000; font-weight: bold;">Grant/Assistance
        from other
        Government and
        Non-Government
        scholarships, School Discount (How much
        per
        sem?)</p>
    <p style="margin-bottom: 5px; padding: 0 10px;">{{ $applicant->otherinfo->grant }}</p>
    <p style="border-bottom: 1px solid #000; font-weight: bold;">Talents & Skills/ Honor and Recognition/
        Extracurricular/Community Involvement/Employment</p>
    <p style="margin-bottom: 5px; padding: 0 10px;">{{ $applicant->otherinfo->talent }}</p>
    <p style="border-bottom: 1px solid #000; font-weight: bold;">What are your expectations from Tzu Chi
        Foundation?</p>
    <p style="margin-bottom: 5px; padding: 0 10px;">{{ $applicant->otherinfo->expectation }}</p>

    <p style="text-align: center; margin: 20px;">I hereby attest that the information I have provided is true and
        correct. I also consents Tzu Chi Foundation to obtain and retain my personal information
        for the purpose of this application.</p>
    <div style="text-align: center; width: 100%; margin-top: 50px;">
        <span style="border-top: 1px solid #000; font-weight: bold; display: inline-block; padding-top: 5px;">
            Applicant's Signature over Printed Name and Date
        </span>
    </div>
    <div class="page-break-before"></div>
    <p style="text-align: center; font-size: 24px; margin: 10px 0 25px 0; font-weight: bold;">Educational Assistance
        Application
        Form</p>
    <p style="margin-bottom: 10px;"><strong><u>SKETCH OF HOME ADDRESS</u></strong></p>
    <div style="width: 100%; border: 1px solid #000; height: 250px; text-align: center;">
        <img src="{{ public_path('storage/' . $applicant->sketchmap) }}" alt="Sketch Map of Home Address"
            style="height: 100%; width: auto;">
    </div>

    <ol style="padding: 5px 25px; margin-bottom: 10px; text-align: justify">
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
                    Income Tax Return of Both Parents (if working).</span><span></strong>
                Deadline of Submission is on <strong><u
                        style="color: red">{{ $form->deadline ? \Carbon\Carbon::parse($form->deadline)->format('F j, Y') : '--' }}</u>
                </strong>.</span>
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

    <div style="width: 100%; border-top: 1px solid #000; padding-top: 5px; font-weight: bold">
        <p style="font-size: 12px; margin-bottom: 10px;">To be filled up by TZU CHI FOUNDATION</p>
        <p style="font-size: 14px; margin-bottom: 5px;">Case Details</p>
    </div>
    <table class="table" style="margin-bottom: 5px;">
        <tr>
            <td width="20%" style="padding: 3px;">
                <p><strong>Nature of Needs</strong></p>
                @foreach ($needs as $need)
                    <div style="line-height: 24px;">
                        <span
                            style="display: inline-block; width: 12px; height: 12px; border-radius: 50%; margin-right: 5px; 
        background: {{ isset($applicant->casedetails) && $applicant->casedetails->natureofneeds == $need ? '#000' : 'transparent' }};
        border: 1px solid #000;"></span>
                        {{ $need }}
                    </div>
                @endforeach

                <div style="line-height: 24px;">
                    <span
                        style="display: inline-block; width: 12px; height: 12px; border-radius: 50%; margin-right: 5px; 
        background: {{ isset($applicant->casedetails) && $applicant->casedetails->natureofneeds == 'Others' ? '#000' : 'transparent' }};
        border: 1px solid #000;"></span>
                    Others
                </div>
                @if (isset($applicant->casedetails) && !in_array($applicant->casedetails->natureofneeds, $needs))
                    <div style="border-bottom: 1px solid #000; margin-left: 20px; margin-top: 5px;">
                        {{ $applicant->casedetails->natureofneeds }}
                    </div>
                @endif
            </td>
            <td width="80%" style="vertical-align: top; padding: 3px;">
                <p><strong>Problem Statement</strong></p>
                <p style="text-align: justify; margin-top: 5px;">
                    {{ $applicant->casedetails->problemstatement ?? '' }}
                </p>
            </td>
        </tr>
    </table>

    <table width="100%" id="cdtable" style="border-collapse: collapse; margin: 10px 0;">
        <tr>
            <!-- Left Column -->
            <td width="42%" style="padding-right: 10px; vertical-align: top;">
                <table style="width: 100%; border-spacing: 0; font-size: 14px;">
                    <tr>
                        <td width="48%" style="padding: 5px 0;">Received By:</td>
                        <td width="52%"
                            style="border-bottom: 1px solid #000; padding-left: 5px; text-align: center;">
                            {{ isset($applicant->casedetails->receivedby) ? $applicant->casedetails->receivedby : '' }}
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 5px 0;">Date Received:</td>
                        <td style="border-bottom: 1px solid #000; padding-left: 5px; text-align: center;">
                            {{ isset($applicant->casedetails->datereceived) ? \Carbon\Carbon::parse($applicant->casedetails->datereceived)->format('F j, Y') : '' }}
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 5px 0;">Assigned District:</td>
                        <td style="border-bottom: 1px solid #000; padding-left: 5px; text-align: center;">
                            {{ isset($applicant->casedetails->district) ? $applicant->casedetails->district : '' }}
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 5px 0;">Assigned Volunteer:</td>
                        <td style="border-bottom: 1px solid #000; padding-left: 5px; text-align: center;">
                            {{ isset($applicant->casedetails->volunteer) ? $applicant->casedetails->volunteer : '' }}
                        </td>
                    </tr>
                </table>
            </td>

            <!-- Right Column -->
            <td width="57%" style="padding-left: 10px;">
                <table style="width: 100%; border-spacing: 0; font-size: 14px;">
                    <tr>
                        <td colspan="2" style="text-align: justify; font-style: italic; padding: 5px 0;">
                            Note: Important information is essential to assess and evaluate the student's
                            situation. Please note significant details for home visitation purposes. Use the back page
                            if
                            necessary.
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 5px 0;">Case Referred By:</td>
                        <td style="border-bottom: 1px solid #000; padding-left: 5px; text-align: center;">
                            {{ isset($applicant->casedetails->referredby) ? $applicant->casedetails->referredby : '' }}
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 5px 0;">Referral Contact No.:</td>
                        <td style="border-bottom: 1px solid #000; padding-left: 5px; text-align: center;">
                            {{ isset($applicant->casedetails->referphonenum) ? $applicant->casedetails->referphonenum : '' }}
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 5px 0;">Relationship with Beneficiary:</td>
                        <td style="border-bottom: 1px solid #000; padding-left: 5px; text-align: center;">
                            {{ isset($applicant->casedetails->relationship) ? $applicant->casedetails->relationship : '' }}
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 5px 0;">Applicant's Signature:</td>
                        <td style="border-bottom: 1px solid #000; padding-left: 5px; text-align: center;"></td>
                    </tr>
                    <tr>
                        <td style="padding: 5px 0;">Date Reported:</td>
                        <td style="border-bottom: 1px solid #000; padding-left: 5px; text-align: center;">
                            {{ isset($applicant->casedetails->datereported) ? \Carbon\Carbon::parse($applicant->casedetails->datereported)->format('F j, Y') : '' }}
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

</body>

</html>
