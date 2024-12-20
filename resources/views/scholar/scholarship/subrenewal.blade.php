<!DOCTYPE html>
<html lang="en">

<head>
    <title>View Scholarship Renewal</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="{{ asset('css/partial.css') }}">
    <link rel="stylesheet" href="{{ asset('css/subrenewal.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet" />
</head>

<body>
    <!-- Include Sidebar -->
    @include('partials._sidebar')

    <!-- Include Navbar -->
    @include('partials._navbar')

    <!-- MAIN -->
    <div class="ctn-main">
        <a href="{{ route('overview') }}" class="goback">&lt Go back</a>
        <h1 class="title text-center">Scholarship Renewal</h1>

        <div class="appinfo row mx-auto">
            <div class="col-md-9">
                <div class="row my-1">
                    <span class="col-md-4 label" style="font-size: 15px;">Applicant Name</span>
                    <span class="col-md-1 label">: </span>
                    <input class="col-md-7" style="max-width: 70%; padding: 2px 5px;"
                        value="{{ $user->basicInfo->scLastname }}, {{ $user->basicInfo->scFirstname }} {{ $user->basicInfo->scMiddlename }}"
                        readonly>
                </div>
                <div class="row my-1">
                    <span class="col-md-4 label" style="font-size: 15px;">Applicant Case Code</span>
                    <span class="col-md-1 label">: </span>
                    <input class="col-md-7" style="max-width: 70%; padding: 2px 5px;" value="{{ $user->caseCode }}"
                        readonly>
                </div>
                <div class="row my-1">
                    <span class="col-md-4 label" style="font-size: 15px;">Application Status</span>
                    <span class="col-md-1 label">: </span>
                    <input class="col-md-7" style="max-width: 70%; padding: 2px 5px;" value="{{ $renewal->status }}"
                        readonly>
                </div>
            </div>
            <div class="col-md-3">
                <div class="row my-1 mx-1">
                    <a href="{{ route('generateRenewalForm', ['rid' => $renewal->rid]) }}"
                        class="btn btn-outline-success text-success bg-light" target="_blank">Download Form</a>
                </div>
                <div class="row mx-1">
                    <button class="btn btn-danger" id="btnwithdraw" data-bs-toggle="modal"
                        data-bs-target="#withdrawModal">
                        Withdraw
                    </button>
                </div>
            </div>
        </div>
        <div class="modal fade" id="withdrawModal" tabindex="-1" aria-labelledby="withdrawModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="withdrawModalLabel">Confirm Withdrawal</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        Are you sure you want to withdraw your application?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-danger" id="confirmWithdraw">Confirm</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="appform-view">
            <div class="page1 shadow">
                <div class="header">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo">
                    <p><strong>佛教慈濟慈善事業基金會菲律濱分會<br>Buddhist Compassion Relief Tzu Chi Foundation, Philippines</strong>
                    </p>
                </div>
                <div class="app-title text-center">
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
                        <span class="col-md-3"><strong>{{ $user->caseCode }}</strong></span>
                        <span class="col-md-3"></span>
                    </div>
                    <div class="psec2">
                        <div class="table1">
                            <table class="table table-bordered" id="table">
                                <tr>
                                    <td colspan="2">
                                        <span class="flabel">Name: (Last Name, First Name, Middle Name)</span><br>
                                        <span class="fvalue" id="fullName">{{ $user->basicInfo->scLastname }},
                                            {{ $user->basicInfo->scFirstname }}
                                            {{ $user->basicInfo->scMiddlename }}</span>
                                    </td>
                                    <td colspan="2">
                                        <span class="flabel">Chinese Name</span><br>
                                        <span class="fvalue"
                                            id="cName">{{ $user->basicInfo->scChinesename }}</span>
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
                                        <span class="fvalue" id="brgy"></span>
                                    </td>
                                    <td>
                                        <span class="flabel">City</span><br>
                                        <span class="fvalue" id="city"></span>
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
                                        <span class="flabel">Facebook name</span><br>
                                        <a href="{{ $user->basicInfo->scFblink }}" class="fvalue" id="fbName"
                                            target="_blank">{{ $user->basicInfo->scFblink }}</a>
                                    </td>
                                    <td colspan="4">
                                        <span class="flabel">Are you a member of any indigenous group?</span><br>
                                        <span class="fvalue" id="indigenous">{{ $user->basicInfo->scIsIndigenous }}.
                                            {{ $user->basicInfo->scIndigenousgroup == 'Not Applicable' ? '' : $user->basicInfo->scIndigenousgroup }}</span>
                                    </td>
                                </tr>
                            </table>

                        </div>
                        <img src="{{ asset('storage/' . $renewal->idpic) }}" alt="Applicant 1x1 Pic">
                    </div>
                </div>

                <div class="educbg">
                    <div class="esec1">
                        <span class="my-2"><strong><u>EDUCATIONAL BACKGROUND</u></strong></span>
                    </div>
                    <div class="d-flex flex-column mb-2">
                        @if ($iscollege)
                            <span class="text-center my-2"><strong>COLLEGE</strong></span>
                            <div class="column">
                                <div class="mb-1 small">
                                    Name of University:
                                    <span id="university">
                                        <strong>{{ $user->education->scSchoolName ?? '' }}</strong>
                                    </span>
                                </div>
                                <div class="mb-1 small">
                                    College Department:
                                    <span id="collegeDept">
                                        <strong>{{ $user->education->scCollegedept ?? '' }}</strong>
                                    </span>
                                </div>
                                <div class="mb-1 small">
                                    Incoming Year Level:
                                    <span id="yrLevel">
                                        <strong>{{ $user->education->scYearGrade ?? '' }}</strong>
                                    </span>
                                </div>
                                <div class="mb-1 small">
                                    Course:
                                    <span id="course">
                                        <strong>{{ $user->education->scCourseStrandSec ?? '' }}</strong>
                                    </span>
                                </div>
                                <div class="mb-1 small">General Average Last Sem:
                                    <span id="gwa">
                                        <strong>{{ $renewal->grade->gwa ?? '' }}</strong>
                                    </span>
                                </div>
                            @else
                                <span class="text-center my-2"><strong>ELEMENTARY/HIGH SCHOOL</strong></span>
                                <div class="column">
                                    <div class="mb-1 small">
                                        Name of Elementary/High School:
                                        <span id="university">
                                            <strong>{{ $user->education->scSchoolName ?? '' }}</strong>
                                        </span>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-1 small">
                                                Incoming Year Level:
                                                <span id="university">
                                                    <strong>{{ $user->education->scYearGrade ?? '' }}</strong>
                                                </span>
                                            </div>
                                            <div class="mb-1 small">
                                                Section:
                                                <span id="university">
                                                    <strong>{{ $user->education->scCourseStrandSec ?? 'Not Applicable' }}</strong>
                                                </span>
                                            </div>
                                            <div class="mb-1 small">
                                                General Average:
                                                <span id="university">
                                                    <strong>{{ $renewal->grade->gwa ?? '' }}</strong>
                                                </span>
                                            </div>
                                            <div class="mb-1 small">
                                                Conduct:
                                                <span id="university">
                                                    <strong>{{ $renewal->grade->gwaconduct ?? 'Not Applicable' }}</strong>
                                                </span>
                                            </div>
                                            <div class="mb-1 small">
                                                Strand:
                                                <span id="university">
                                                    <strong>{{ $user->education->scCourseStrandSec ?? 'Not Applicable' }}</strong>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <span class="small">Chinese Subject</span>
                                            <div class="mb-1 small">
                                                General Average:
                                                <span id="university">
                                                    <strong>{{ $renewal->grade->chinesegwa ?? 'Not Applicable' }}</strong>
                                                </span>
                                            </div>
                                            <div class="mb-1 small">
                                                Conduct:
                                                <span id="university">
                                                    <strong>{{ $renewal->grade->chinesegwaconduct ?? 'Not Applicable' }}</strong>
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
                                    <th class="text-center align-center" style="width: 150px;">Name <br><span>(Last
                                            Name, First Name)</span></th>
                                    <th class="text-center align-center">Age</th>
                                    <th class="text-center align-center">Sex</th>
                                    <th class="text-center align-center">Birtdate <br><span></span></th>
                                    <th class="text-center align-center">Relationship</th>
                                    <th class="text-center align-center">Religion</th>
                                    <th class="text-center align-center">Educational Attainment</th>
                                    <th class="text-center align-center">School/ Occupation</th>
                                    <th class="text-center align-center">Company</th>
                                    <th class="text-center align-center">Income</th>
                                </tr>
                            </thead>
                            <tbody id="familyTableBody">
                                {{-- FATHER --}}
                                <tr>
                                    <td class="text-left align-center">{{ $father->name ?? '' }}</td>
                                    <td class="text-center align-center">{{ $father->age ?? '' }}</td>
                                    <td class="text-center align-center">{{ $father->sex ?? '' }}</td>
                                    <td class="text-center align-center">
                                        {{ \Carbon\Carbon::parse($father->birthdate)->format('F j, Y') ?? '' }}
                                    </td>
                                    <td class="text-center align-center">{{ $father->relationship ?? '' }}</td>
                                    <td class="text-center align-center">{{ $father->religion ?? '' }}</td>
                                    <td class="text-center align-center">{{ $father->educattainment ?? '' }}</td>
                                    <td class="text-center align-center">{{ $father->occupation ?? '' }}</td>
                                    <td class="text-center align-center">{{ $father->company ?? '' }}
                                    </td>
                                    <td class="text-center align-center">{{ $father->income ?? '' }}</td>
                                </tr>
                                {{-- MOTHER --}}
                                <tr>
                                    <td class="text-left align-center">{{ $mother->name ?? '' }}</td>
                                    <td class="text-center align-center">{{ $mother->age ?? '' }}</td>
                                    <td class="text-center align-center">{{ $mother->sex ?? '' }}</td>
                                    <td class="text-center align-center">
                                        {{ \Carbon\Carbon::parse($mother->birthdate)->format('F j, Y') ?? '' }}
                                    </td>
                                    <td class="text-center align-center">{{ $mother->relationship ?? '' }}</td>
                                    <td class="text-center align-center">{{ $mother->religion ?? '' }}</td>
                                    <td class="text-center align-center">{{ $mother->educattainment ?? '' }}</td>
                                    <td class="text-center align-center">{{ $mother->occupation ?? '' }}</td>
                                    <td class="text-center align-center">{{ $mother->company ?? '' }}</td>
                                    <td class="text-center align-center">{{ $mother->income ?? '' }}</td>
                                </tr>
                                {{-- SIBLING/S --}}
                                @foreach ($siblings as $sib)
                                    <tr>
                                        <td class="text-left align-center">{{ $sib->name ?? '' }}</td>
                                        <td class="text-center align-center">{{ $sib->age ?? '' }}</td>
                                        <td class="text-center align-center">{{ $sib->sex ?? '' }}</td>
                                        <td class="text-center align-center">
                                            {{ \Carbon\Carbon::parse($sib->birthdate)->format('F j, Y') ?? '' }}
                                        </td>
                                        <td class="text-center align-center">{{ $sib->relationship ?? '' }}</td>
                                        <td class="text-center align-center">{{ $sib->religion ?? '' }}</td>
                                        <td class="text-center align-center">{{ $sib->educattainment ?? '' }}</td>
                                        <td class="text-center align-center">{{ $sib->occupation ?? '' }}</td>
                                        <td class="text-center align-center">{{ $sib->company ?? '' }}</td>
                                        <td class="text-center align-center">{{ $sib->income ?? '' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="other-info column small">
                    <div class="row mt-2">
                        <strong>
                            Grant/Assistance from other Government and Non-Government scholarships, School Discount
                            (How
                            much per sem?)
                        </strong>
                    </div>
                    <div class="row px-3">
                        <span style="white-space: pre-wrap" id="grant">{{ $renewal->otherinfo->grant }}</span>
                    </div>
                    <div class="row mt-2">
                        <strong>
                            Talents & Skills/ Honor and Recognition/ Extracurricular/Community
                            Involvement/Employment
                        </strong>
                    </div>
                    <div class="row px-3">
                        <span style="white-space: pre-wrap" id="talents">{{ $renewal->otherinfo->talent }}</span>
                    </div>
                    <div class="row mt-2">
                        <strong>
                            What are your expectations from Tzu Chi Foundation?
                        </strong>
                    </div>
                    <div class="row px-3">
                        <span style="white-space: pre-wrap"
                            id="expectations">{{ $renewal->otherinfo->expectation }}</span>
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
        <div class="page2 shadow">
            <div class="header">
                <img src="{{ asset('images/logo.png') }}" alt="Logo">
                <p><strong>佛教慈濟慈善事業基金會菲律濱分會<br>Buddhist Compassion Relief Tzu Chi Foundation, Philippines</strong>
                </p>
            </div>
            <h3 class="text-center my-3">Educational Assistance Application Form</h3>
            <span class="row"><strong><u>Sketch of Home Address</u></strong></span>
            <div class="sketchimg">
                <img class="sketchimg" src="{{ asset('storage/' . $renewal->sketchmap) }}"
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
                        <strong>Deadline of Submission is on</strong> <span id="deadline">
                            {{ $form->deadline ? \Carbon\Carbon::parse($form->deadline)->format('F j, Y') : '--' }}</span>.
                    </li>
                    <li>
                        Late renewals will not be entertained.
                    </li>
                    <li>
                        All applications are subject for home visit and approval.
                    </li>
                    <li>
                        The renewals will be notified on the acceptance or rejection of the application.
                    </li>
                    <li>
                        For inquiries, you may contact the Educational Assistance Program Staff with number
                        09953066694.
                    </li>
                </ol>
            </div>
            {{-- CASE DETAILS --}}
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
                                @foreach ($needs as $need)
                                    <div class="col-12 align-items-center">
                                        <input type="radio" value="{{ $need }}"
                                            {{ isset($renewal->casedetails) && $renewal->casedetails->natureofneeds === $need ? 'checked' : '' }}
                                            disabled>
                                        <label>{{ $need }}</label>
                                    </div>
                                @endforeach
                                <div class="col-12 align-items-center">
                                    <input type="radio" value="Others"
                                        {{ isset($renewal->casedetails) && $renewal->casedetails->natureofneeds === 'Others' ? 'checked' : '' }}
                                        disabled>
                                    <label>Others</label>
                                </div>
                                @if (isset($renewal->casedetails) && !in_array($renewal->casedetails->natureofneeds, $needs))
                                    <input type="text" class="form-control border-bottom border-dark"
                                        value="{{ $renewal->casedetails->natureofneeds }}">
                                @endif
                            </span>
                        </td>
                        <td style="width: 600px;">
                            <span class="slabel"><strong>Problem Statement</strong></span><br>
                            <textarea id="" rows="5" cols="6"
                                placeholder="{{ $renewal->casedetails->problemstatement ?? '' }}" readonly></textarea>
                        </td>
                    </tr>
                </table>
            </div>
            <div class="row">
                <div class="column col-md-5">
                    <div class="row my-2 d-flex justify-content-between casedeets">
                        Received By:
                        <input class="casedeets-input text-center" style="width: 65% !important" type="text"
                            value="{{ $renewal->casedetails->receiveby ?? '' }}" readonly>
                    </div>
                    <div class="row my-2 d-flex justify-content-between casedeets">
                        Date Receive:
                        <input class="casedeets-input text-center" style="width: 50% !important" type="text"
                            value="{{ $renewal->casedetails->datereceived ?? '' }}" readonly>
                    </div>
                    <div class="row my-2 d-flex justify-content-between casedeets">
                        Assigned District:
                        <input class="casedeets-input text-center" style="width: 50% !important" type="text"
                            value="{{ $renewal->casedetails->district ?? '' }}" readonly>
                    </div>
                    <div class="row my-2 d-flex justify-content-between casedeets">
                        Assigned Volunteer:
                        <input class="casedeets-input text-center" style="width: 50% !important" type="text"
                            value="{{ $renewal->casedetails->volunteer ?? '' }}" readonly>
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
                                value="{{ $renewal->casedetails->referredby ?? '' }}" readonly>
                        </div>
                        <div class="row my-2 d-flex justify-content-between casedeets">
                            Referral Contact no.:
                            <input class="casedeets-input text-center" style="width: 45% !important" type="tel"
                                value="{{ $renewal->casedetails->referphonenum ?? '' }}" readonly>
                        </div>
                        <div class="row my-2 d-flex justify-content-between casedeets">Relationship with
                            Beneficiary:
                            <input class="casedeets-input text-center" style="width: 45% !important" type="text"
                                value="{{ $renewal->casedetails->relationship ?? '' }}" readonly>
                        </div>
                        <div class="row my-2 d-flex justify-content-between casedeets">
                            Applicant's Signature:
                            <span class="casedeets-input text-center" style="width: 45% !important"></span>
                        </div>
                        <div class="row my-2 d-flex justify-content-between casedeets">
                            Date Reported:
                            <input class="casedeets-input text-center" style="width: 45% !important" type="text"
                                value="{{ $renewal->casedetails->datereported ?? '' }}" readonly>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- Documents --}}
    <div class="card mx-auto mt-3 mb-5 shadow-sm" style="width: 8.5in;">
        <div class="card-header py-3 bg-success text-white">
            <span class="h5 fw-bold">Submitted Documents</span>
        </div>
        <div class="card-body">
            <div class="row mb-2 pb-2 border-bottom">
                <div class="col-md-7 file">Latest Report Card</div>
                <div class="col-md-5 text-center">
                    <a href="{{ asset('storage/' . $renewal->reportcard) }}">
                        <i class="fa-solid fa-file-lines"></i> <span>View document</span>
                    </a>
                </div>
            </div>
            <div class="row mb-2 py-2 border-bottom">
                <div class="col-md-7 file">Latest Registration Card</div>
                <div class="col-md-5 text-center">
                    <a href="{{ asset('storage/' . $renewal->regiform) }}">
                        <i class="fa-solid fa-file-lines"></i> <span>View document</span>
                    </a>
                </div>
            </div>
            <div class="row mb-2 py-2 border-bottom">
                <div class="col-md-7 file">Autobiography</div>
                <div class="col-md-5 text-center">
                    <a href="{{ asset('storage/' . $renewal->autobio) }}">
                        <i class="fa-solid fa-file-lines"></i> <span>View document</span>
                    </a>
                </div>
            </div>
            <div class="row mb-2 py-2 border-bottom">
                <div class="col-md-7 file">Family Picture</div>
                <div class="col-md-5 text-center">
                    <a href="{{ asset('storage/' . $renewal->familypic) }}">
                        <i class="fa-solid fa-file-lines"></i> <span>View document</span>
                    </a>
                </div>
            </div>
            <div class="row mb-2 py-2 border-bottom">
                <div class="col-md-7 file">Picture of House (Inside)</div>
                <div class="col-md-5 text-center">
                    <a href="{{ asset('storage/' . $renewal->houseinside) }}">
                        <i class="fa-solid fa-file-lines"></i> <span>View document</span>
                    </a>
                </div>
            </div>
            <div class="row mb-2 py-2 border-bottom">
                <div class="col-md-7 file">Picture of House (Outside)</div>
                <div class="col-md-5 text-center">
                    <a href="{{ asset('storage/' . $renewal->houseoutside) }}">
                        <i class="fa-solid fa-file-lines"></i> <span>View document</span>
                    </a>
                </div>
            </div>
            <div class="row mb-2 py-2 border-bottom">
                <div class="col-md-7 file">Latest Utility Bill</div>
                <div class="col-md-5 text-center">
                    <a href="{{ asset('storage/' . $renewal->utilitybill) }}">
                        <i class="fa-solid fa-file-lines"></i> <span>View document</span>
                    </a>
                </div>
            </div>
            <div class="row mb-2 py-2 border-bottom">
                <div class="col-md-7 file">Sketch Map of Home Address</div>
                <div class="col-md-5 text-center">
                    <a href="{{ asset('storage/' . $renewal->sketchmap) }}">
                        <i class="fa-solid fa-file-lines"></i> <span>View document</span>
                    </a>
                </div>
            </div>
            <div class="row mb-2 py-2 border-bottom">
                <div class="col-md-7 file">Latest Pay Slip of Parent/s</div>
                <div class="col-md-5 text-center">
                    @if (!empty($renewal) && !empty($renewal->payslip))
                        <a href="{{ asset('storage/' . $renewal->payslip) }}">
                            <i class="fa-solid fa-file-lines"></i> <span>View document</span>
                        </a>
                    @else
                        <span class="text-muted">No document available</span>
                    @endif
                </div>
            </div>
            <div class="row mb-2 py-2 border-bottom">
                <div class="col-md-7 file">Certificate of Indigency</div>
                <div class="col-md-5 text-center">
                    <a href="{{ asset('storage/' . $renewal->indigencycert) }}">
                        <i class="fa-solid fa-file-lines"></i> <span>View document</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('confirmWithdraw').addEventListener('click', function() {
            window.location.href = "#";
        });

        const regionCode = '{{ $user->addressinfo->scRegion }}';
        const cityCode = '{{ $user->addressinfo->scCity }}';
        const barangayCode = '{{ $user->addressinfo->scBarangay }}';

        // Base API URLs
        const cityApi = `https://psgc.gitlab.io/api/regions/${regionCode}/cities-municipalities/`;
        const barangayApi = `https://psgc.gitlab.io/api/cities-municipalities/${cityCode}/barangays/`;

        // Populate city/municipality
        fetch(cityApi)
            .then(response => response.json())
            .then(data => {
                const city = data.find(item => item.code === cityCode);
                document.getElementById('city').textContent = city ? city.name : 'Unknown City/Municipality';
            })
            .catch(error => {
                console.error('Error fetching city/municipality:', error);
                document.getElementById('city').textContent = 'Error loading city/municipality';
            });

        // Populate barangay
        fetch(barangayApi)
            .then(response => response.json())
            .then(data => {
                const barangay = data.find(item => item.code === barangayCode);
                document.getElementById('brgy').textContent = barangay ? barangay.name : 'Unknown Barangay';
            })
            .catch(error => {
                console.error('Error fetching barangay:', error);
                document.getElementById('brgy').textContent = 'Error loading barangay';
            });
    </script>


    <script src="{{ asset('js/scholar.js') }}"></script>
</body>

</html>
