<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Application Form</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/appformview.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body>
    <!-- PAGE HEADER -->
    @include('partials._pageheader')
    <x-alert />
    <div class="ctnmain">
        <div class="appformview">
            <div class="mx-auto mb-2" style="width: 8.5in">
                <div class="col-md-2" style="margin-left:auto;">
                    <a href="{{ route('applicants') }}" class="btn btn-success w-100">Go back</a>
                </div>
            </div>
            <div class="appinfo p-4">
                <div class="row mb-3">
                    <label class="col-md-3 col-form-label">Applicant Name</label>
                    <div class="col-md-9">
                        <input type="text" class="form-control" value="{{ $applicant->name }}" readonly>
                    </div>
                </div>

                <div class="row mb-3">
                    <label class="col-md-3 col-form-label">Applicant Case Code</label>
                    <div class="col-md-9">
                        <input type="text" class="form-control" value="{{ $applicant->casecode }}" readonly>
                    </div>
                </div>

                <form method="POST" action="{{ route('updateapplicantstatus', $applicant->casecode) }}">
                    @csrf

                    <div class="row mb-3">
                        <label class="col-md-3 col-form-label">Application Status</label>
                        <div class="col-md-9">
                            <select name="applicationstatus" class="form-select"
                                {{ $applicant->applicationstatus == 'Withdrawn' ? 'disabled' : '' }}>
                                <option value="Under Review"
                                    {{ $applicant->applicationstatus == 'Under Review' ? 'selected' : '' }}>Under
                                    Review</option>
                                <option value="For Initial Interview"
                                    {{ $applicant->applicationstatus == 'For Initial Interview' ? 'selected' : '' }}>
                                    For Initial Interview</option>
                                <option value="For Panel Interview"
                                    {{ $applicant->applicationstatus == 'For Panel Interview' ? 'selected' : '' }}>For
                                    Panel Interview</option>
                                <option value="For Virtual Home Visit"
                                    {{ $applicant->applicationstatus == 'For Virtual Home Visit' ? 'selected' : '' }}>
                                    For Virtual Home Visit</option>
                                <option value="Accepted"
                                    {{ $applicant->applicationstatus == 'Accepted' ? 'selected' : '' }}>Accepted
                                </option>
                                <option value="Rejected"
                                    {{ $applicant->applicationstatus == 'Rejected' ? 'selected' : '' }}>Rejected
                                </option>
                                <option value="Withdrawn"
                                    {{ $applicant->applicationstatus == 'Withdrawn' ? 'selected' : '' }}>Withdrawn
                                </option>
                            </select>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-md-3 col-form-label">Comment</label>
                        <div class="col-md-9">
                            <textarea rows="8" name="comment" class="form-control" placeholder="Type here..." style="resize: none;">{{ $applicant->comment }}
                            </textarea>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-2" style="margin-left: auto">
                            <button type="submit" class="btn btn-light fw-bold w-100"
                                {{ $applicant->applicationstatus == 'Withdrawn' ? 'disabled' : '' }}>Save</button>
                        </div>
                    </div>
                </form>
            </div>

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
                        <span class="col-md-3"><strong>{{ $applicant->casecode }}</strong></span>
                        <span class="col-md-3"></span>
                    </div>
                    <div class="psec2">
                        <div class="table1">
                            <table class="table table-bordered" id="table">
                                <tr>
                                    <td colspan="2">
                                        <span class="flabel">Name: (Last Name, First Name, Middle Name)</span><br>
                                        <span class="fvalue" id="fullName">{{ $applicant->name }}</span>
                                    </td>
                                    <td colspan="2">
                                        <span class="flabel">Chinese Name</span><br>
                                        <span class="fvalue" id="cName">{{ $applicant->chinesename }}</span>
                                    </td>
                                    <td>
                                        <span class="flabel">Gender</span><br>
                                        <span class="fvalue" id="gender">{{ $applicant->sex }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2">
                                        <span class="flabel">Home Address (House #/Unit #/Floor/Bldg. Name/Street
                                            Name)</span><br>
                                        <span class="fvalue" id="resAddress">{{ $applicant->homeaddress }}</span>
                                    </td>
                                    <td>
                                        <span class="flabel">Barangay</span><br>
                                        <span class="fvalue" id="brgy">{{ $applicant->barangay }}</span>
                                    </td>
                                    <td>
                                        <span class="flabel">City</span><br>
                                        <span class="fvalue" id="city">{{ $applicant->city }}</span>
                                    </td>
                                    <td>
                                        <span class="flabel">Age</span><br>
                                        <span class="fvalue" id="age">{{ $applicant->age }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <span class="flabel">Email address</span><br>
                                        <span class="fvalue" id="email">{{ $applicant->email }}</span>
                                    </td>
                                    <td>
                                        <span class="flabel">Occupation and Income</span><br>
                                        <span class="fvalue" id="occupation">{{ $applicant->occupation }}</span>,<br>
                                        <span class="fvalue" id="income">Php {{ $applicant->income }}</span>
                                    </td>
                                    <td colspan="2">
                                        <span class="flabel">Cellphone No./Landline</span><br>
                                        <span class="fvalue" id="phoneNum">{{ $applicant->phonenum }}</span>
                                    </td>
                                    <td>
                                        <span class="flabel">Birthdate</span><br>
                                        <span class="fvalue"
                                            id="birthDate">{{ \Carbon\Carbon::parse($applicant->birthdate)->format('F j, Y') }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <span class="flabel">Facebook name</span><br>
                                        <a href="{{ $applicant->fblink }}" class="fvalue" id="fbName"
                                            target="_blank">{{ $applicant->fblink }}</a>
                                    </td>
                                    <td colspan="4">
                                        <span class="flabel">Are you a member of any indigenous group?</span><br>
                                        <span class="fvalue" id="indigenous">{{ $applicant->isIndigenous }}.
                                            {{ $applicant->indigenousgroup ?? '' }}</span>
                                    </td>
                                </tr>
                            </table>

                        </div>
                        <img src="{{ asset('storage/' . $applicant->requirements->idpic) }}" alt="Applicant 1x1 Pic">
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
                                        <strong>{{ $applicant->educcollege->univname ?? '' }}</strong>
                                    </span>
                                </div>
                                <div class="mb-1 small">
                                    College Department:
                                    <span id="collegeDept">
                                        <strong>{{ $applicant->educcollege->collegedept ?? '' }}</strong>
                                    </span>
                                </div>
                                <div class="mb-1 small">
                                    Incoming Year Level:
                                    <span id="yrLevel">
                                        <strong>{{ $applicant->educcollege->inyear ?? '' }}</strong>
                                    </span>
                                </div>
                                <div class="mb-1 small">
                                    Course:
                                    <span id="course">
                                        <strong>{{ $applicant->educcollege->course ?? '' }}</strong>
                                    </span>
                                </div>
                                <div class="mb-1 small">General Average Last Sem:
                                    <span id="gwa">
                                        <strong>{{ $applicant->educcollege->gwa ?? '' }}</strong>
                                    </span>
                                </div>
                            @else
                                <span class="text-center my-2"><strong>ELEMENTARY/HIGH SCHOOL</strong></span>
                                <div class="column">
                                    <div class="mb-1 small">
                                        Name of Elementary/High School:
                                        <span id="university">
                                            <strong>{{ $applicant->educelemhs->schoolname ?? '' }}</strong>
                                        </span>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-1 small">
                                                Incoming Year Level:
                                                <span id="university">
                                                    <strong>{{ $applicant->educelemhs->ingrade ?? '' }}</strong>
                                                </span>
                                            </div>
                                            <div class="mb-1 small">
                                                Section:
                                                <span id="university">
                                                    <strong>{{ $applicant->educelemhs->section ?? '' }}</strong>
                                                </span>
                                            </div>
                                            <div class="mb-1 small">
                                                General Average:
                                                <span id="university">
                                                    <strong>{{ $applicant->educelemhs->gwa ?? '' }}</strong>
                                                </span>
                                            </div>
                                            <div class="mb-1 small">
                                                Conduct:
                                                <span id="university">
                                                    <strong>{{ $applicant->educelemhs->gwaconduct ?? '' }}</strong>
                                                </span>
                                            </div>
                                            <div class="mb-1 small">
                                                Strand:
                                                <span id="university">
                                                    <strong>{{ $applicant->educelemhs->strand ?? '' }}</strong>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <span class="small">Chinese Subject</span>
                                            <div class="mb-1 small">
                                                General Average:
                                                <span id="university">
                                                    <strong>{{ $applicant->educelemhs->chinesegwa ?? '' }}</strong>
                                                </span>
                                            </div>
                                            <div class="mb-1 small">
                                                Conduct:
                                                <span id="university">
                                                    <strong>{{ $applicant->educelemhs->chinesegwaconduct ?? '' }}</strong>
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
                                    <td class="text-left align-center">{{ $father->name }}</td>
                                    <td class="text-center align-center">{{ $father->age }}</td>
                                    <td class="text-center align-center">{{ $father->sex }}</td>
                                    <td class="text-center align-center">
                                        {{ \Carbon\Carbon::parse($father->birthdate)->format('F j, Y') }}
                                    </td>
                                    <td class="text-center align-center">{{ $father->relationship }}</td>
                                    <td class="text-center align-center">{{ $father->religion }}</td>
                                    <td class="text-center align-center">{{ $father->educattainment }}</td>
                                    <td class="text-center align-center">{{ $father->occupation }}</td>
                                    <td class="text-center align-center">{{ $father->company }}</td>
                                    <td class="text-center align-center">{{ $father->income }}</td>
                                </tr>
                                {{-- MOTHER --}}
                                <tr>
                                    <td class="text-left align-center">{{ $mother->name }}</td>
                                    <td class="text-center align-center">{{ $mother->age }}</td>
                                    <td class="text-center align-center">{{ $mother->sex }}</td>
                                    <td class="text-center align-center">
                                        {{ \Carbon\Carbon::parse($mother->birthdate)->format('F j, Y') }}
                                    </td>
                                    <td class="text-center align-center">{{ $mother->relationship }}</td>
                                    <td class="text-center align-center">{{ $mother->religion }}</td>
                                    <td class="text-center align-center">{{ $mother->educattainment }}</td>
                                    <td class="text-center align-center">{{ $mother->occupation }}</td>
                                    <td class="text-center align-center">{{ $mother->company }}</td>
                                    <td class="text-center align-center">{{ $mother->income }}</td>
                                </tr>
                                {{-- SIBLING/S --}}
                                @foreach ($siblings as $sib)
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
                        <span id="grant">{{ $applicant->otherinfo->grant }}</span>
                    </div>
                    <div class="row mt-2">
                        <strong>
                            Talents & Skills/ Honor and Recognition/ Extracurricular/Community
                            Involvement/Employment
                        </strong>
                    </div>
                    <div class="row px-3">
                        <span id="talents">{{ $applicant->otherinfo->talent }}</span>
                    </div>
                    <div class="row mt-2">
                        <strong>
                            What are your expectations from Tzu Chi Foundation?
                        </strong>
                    </div>
                    <div class="row px-3">
                        <span id="expectations">{{ $applicant->otherinfo->expectations }}</span>
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
        <form action="{{ route('updateapplicantcd', ['casecode' => $applicant->casecode]) }}" method="post">
            @csrf
            <div class="page2">
                <div class="header">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo">
                    <p><strong>佛教慈濟慈善事業基金會菲律濱分會<br>Buddhist Compassion Relief Tzu Chi Foundation, Philippines</strong>
                    </p>
                </div>
                <h3 class="text-center my-3">Educational Assistance Application Form</h3>
                <span class="row"><strong><u>Sketch of Home Address</u></strong></span>
                <div class="sketchimg">
                    <img src="{{ asset('storage/' . $applicant->requirements->sketchmap) }}"
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
                                <span class="svalue" id="natureofneeds">
                                    <input type="radio" id="financial" name="needs" value="Financial"
                                        {{ isset($applicant->casedetails) && $applicant->casedetails->natureofneeds === 'Financial' ? 'checked' : '' }}>
                                    <label for="financial">Financial</label><br>

                                    <input type="radio" id="medical" name="needs" value="Medical"
                                        {{ isset($applicant->casedetails) && $applicant->casedetails->natureofneeds === 'Medical' ? 'checked' : '' }}>
                                    <label for="medical">Medical</label><br>

                                    <input type="radio" id="food" name="needs" value="Food"
                                        {{ isset($applicant->casedetails) && $applicant->casedetails->natureofneeds === 'Food' ? 'checked' : '' }}>
                                    <label for="food">Food</label><br>

                                    <input type="radio" id="material" name="needs" value="Material"
                                        {{ isset($applicant->casedetails) && $applicant->casedetails->natureofneeds === 'Material' ? 'checked' : '' }}>
                                    <label for="material">Material</label><br>

                                    <input type="radio" id="educ" name="needs" value="Education"
                                        {{ isset($applicant->casedetails) && $applicant->casedetails->natureofneeds === 'Education' ? 'checked' : '' }}>
                                    <label for="educ">Education</label><br>

                                    <input type="radio" id="others" name="needs" value="Others"
                                        {{ isset($applicant->casedetails) && $applicant->casedetails->natureofneeds === 'Others' ? 'checked' : '' }}>
                                    <label for="others">Others</label><br>
                                </span>
                            </td>
                            <td style="width: 600px;">
                                <span class="slabel"><strong>Problem Statement</strong></span><br>
                                <textarea name="problemstatement" id="" rows="5" cols="6" maxlength="255"
                                    placeholder="Type here..." required>{{ isset($applicant->casedetails) && $applicant->casedetails->problemstatement }}</textarea>
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="row">
                    <div class="column col-md-5">
                        <div class="row my-2 d-flex justify-content-between casedeets">
                            Received By:
                            <input class="casedeets-input text-center" style="width: 65% !important" type="text"
                                name="receivedby" required maxlength="255"
                                value="{{ isset($applicant->casedetails->receivedby) ? $applicant->casedetails->receivedby : $worker->name }}">
                        </div>
                        <div class="row my-2 d-flex justify-content-between casedeets">
                            Date Receive:
                            <input class="casedeets-input text-center" style="width: 50% !important" type="date"
                                name="datereceived" required min="{{ date('Y-m-d') }}"
                                value="{{ isset($applicant->casedetails->datereceived) ? $applicant->casedetails->datereceived : date('Y-m-d') }}">
                        </div>
                        <div class="row my-2 d-flex justify-content-between casedeets">
                            Assigned District:
                            <input class="casedeets-input text-center" style="width: 50% !important" type="text"
                                name="district" required maxlength="50"
                                value="{{ isset($applicant->casedetails) && $applicant->casedetails->district ?? null }}">
                        </div>
                        <div class="row my-2 d-flex justify-content-between casedeets">
                            Assigned Volunteer:
                            <input class="casedeets-input text-center" style="width: 50% !important" type="text"
                                name="volunteer" required maxlength="255"
                                value="{{ isset($applicant->casedetails) && $applicant->casedetails->volunteer ?? null }}">
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
                                <input class="casedeets-input text-center" style="width: 45% !important"
                                    type="text" name="referredby" required maxlength="255"
                                    value="{{ isset($applicant->casedetails) && $applicant->casedetails->referredby ?? null }}">
                            </div>
                            <div class="row my-2 d-flex justify-content-between casedeets">
                                Referral Contact no.:
                                <input class="casedeets-input text-center" style="width: 45% !important"
                                    type="tel" name="referphonenum" required maxlength="12" minlength="11"
                                    value="{{ isset($applicant->casedetails) && $applicant->casedetails->referphonenum ?? null }}">
                            </div>
                            <div class="row my-2 d-flex justify-content-between casedeets">Relationship with
                                Beneficiary:
                                <input class="casedeets-input text-center" style="width: 45% !important"
                                    type="text" name="relationship" required maxlength="50"
                                    value="{{ isset($applicant->casedetails) && $applicant->casedetails->relationship ?? null }}">
                            </div>
                            <div class="row my-2 d-flex justify-content-between casedeets">
                                Applicant's Signature:
                                <span class="casedeets-input text-center" style="width: 45% !important"></span>
                            </div>
                            <div class="row my-2 d-flex justify-content-between casedeets">
                                Date Reported:
                                <input class="casedeets-input text-center" style="width: 45% !important"
                                    type="date" name="datereported" required min="{{ date('Y-m-d') }}"
                                    value="{{ isset($applicant->casedetails) && $applicant->casedetails->datereported ?? date('Y-m-d') }}">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <button type="submit" class="col-md-1 btn btn-success mx-auto">Save</button>
            </div>
        </form>
        <div class="card mx-auto mt-3 mb-5 shadow-sm" style="width: 8.5in;">
            <div class="card-header py-3 bg-success text-white">
                <span class="h5 fw-bold">Submitted Documents</span>
            </div>
            <div class="card-body">
                <div class="row mb-2 pb-2 border-bottom">
                    <div class="col-md-7 file">Latest Report Card</div>
                    <div class="col-md-5 text-center">
                        <a href="{{ asset('storage/' . $applicant->requirements->reportcard) }}" target="_blank">
                            <i class="fa-solid fa-file-lines"></i> <span>View document</span>
                        </a>
                    </div>
                </div>
                <div class="row mb-2 py-2 border-bottom">
                    <div class="col-md-7 file">Latest Registration Card</div>
                    <div class="col-md-5 text-center">
                        <a href="{{ asset('storage/' . $applicant->requirements->regiform) }}" target="_blank">
                            <i class="fa-solid fa-file-lines"></i> <span>View document</span>
                        </a>
                    </div>
                </div>
                <div class="row mb-2 py-2 border-bottom">
                    <div class="col-md-7 file">Autobiography</div>
                    <div class="col-md-5 text-center">
                        <a href="{{ asset('storage/' . $applicant->requirements->autobio) }}" target="_blank">
                            <i class="fa-solid fa-file-lines"></i> <span>View document</span>
                        </a>
                    </div>
                </div>
                <div class="row mb-2 py-2 border-bottom">
                    <div class="col-md-7 file">Family Picture</div>
                    <div class="col-md-5 text-center">
                        <a href="{{ asset('storage/' . $applicant->requirements->familypic) }}" target="_blank">
                            <i class="fa-solid fa-file-lines"></i> <span>View document</span>
                        </a>
                    </div>
                </div>
                <div class="row mb-2 py-2 border-bottom">
                    <div class="col-md-7 file">Picture of House (Inside)</div>
                    <div class="col-md-5 text-center">
                        <a href="{{ asset('storage/' . $applicant->requirements->houseinside) }}" target="_blank">
                            <i class="fa-solid fa-file-lines"></i> <span>View document</span>
                        </a>
                    </div>
                </div>
                <div class="row mb-2 py-2 border-bottom">
                    <div class="col-md-7 file">Picture of House (Outside)</div>
                    <div class="col-md-5 text-center">
                        <a href="{{ asset('storage/' . $applicant->requirements->houseoutside) }}" target="_blank">
                            <i class="fa-solid fa-file-lines"></i> <span>View document</span>
                        </a>
                    </div>
                </div>
                <div class="row mb-2 py-2 border-bottom">
                    <div class="col-md-7 file">Latest Utility Bill</div>
                    <div class="col-md-5 text-center">
                        <a href="{{ asset('storage/' . $applicant->requirements->utilitybill) }}" target="_blank">
                            <i class="fa-solid fa-file-lines"></i> <span>View document</span>
                        </a>
                    </div>
                </div>
                <div class="row mb-2 py-2 border-bottom">
                    <div class="col-md-7 file">Sketch Map of Home Address</div>
                    <div class="col-md-5 text-center">
                        <a href="{{ asset('storage/' . $applicant->requirements->sketchmap) }}" target="_blank">
                            <i class="fa-solid fa-file-lines"></i> <span>View document</span>
                        </a>
                    </div>
                </div>
                <div class="row mb-2 py-2 border-bottom">
                    <div class="col-md-7 file">Latest Pay Slip of Parent/s</div>
                    <div class="col-md-5 text-center">
                        @if (!empty($applicant->requirements) && !empty($applicant->requirements->payslip))
                            <a href="{{ asset('storage/' . $applicant->requirements->payslip) }}" target="_blank">
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
                        <a href="{{ asset('storage/' . $applicant->requirements->indigencycert) }}" target="_blank">
                            <i class="fa-solid fa-file-lines"></i> <span>View document</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="{{ asset('js/headercontrol.js') }}"></script>
    <script>
        const regionCode = '{{ $applicant->region }}';
        const cityCode = '{{ $applicant->city }}';
        const barangayCode = '{{ $applicant->barangay }}';

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
</body>

</html>
