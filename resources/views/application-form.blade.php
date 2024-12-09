<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Application Form</title>
    <link rel="stylesheet" href="{{ asset('css/appform-pdf.css') }}">
</head>

<body>
    <div class="container border border-dark shadow p-5 mb-5">
        <div class="row justify-content-center">
            <img src="{{ public_path('images/appform-header.png') }}" alt="header" style="width: 60%">
        </div>
        <div class="row text-center my-3">
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
            <div class="row">
                <div class="col-md-10 table-responsive">
                    <table class="table table-bordered border-dark">
                        <tr>
                            <td colspan="2">
                                <span class="flabel">Name: (Last Name, First Name, Middle Name)</span><br>
                                <span class="fw-bold" id="fullName">{{ $applicant->name }}</span>
                            </td>
                            <td colspan="2">
                                <span class="flabel">Chinese Name</span><br>
                                <span class="fw-bold" id="cName">{{ $applicant->chinesename }}</span>
                            </td>
                            <td>
                                <span class="flabel">Gender</span><br>
                                <span class="fw-bold" id="gender">{{ $applicant->sex }}</span>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <span class="flabel">Home Address (House #/Unit #/Floor/Bldg. Name/Street
                                    Name)</span><br>
                                <span class="fw-bold" id="resAddress">{{ $applicant->homeaddress }}</span>
                            </td>
                            <td>
                                <span class="flabel">Barangay</span><br>
                                <span class="fw-bold" id="brgy">{{ $applicant->barangay }}</span>
                            </td>
                            <td>
                                <span class="flabel">City</span><br>
                                <span class="fw-bold" id="city">{{ $applicant->city }}</span>
                            </td>
                            <td>
                                <span class="flabel">Age</span><br>
                                <span class="fw-bold" id="age">{{ $applicant->age }}</span>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span class="flabel">Email address</span><br>
                                <span class="fw-bold" id="email">{{ $applicant->email }}</span>
                            </td>
                            <td>
                                <span class="flabel">Occupation and Income</span><br>
                                <span class="fw-bold" id="occupation">{{ $applicant->occupation }}</span>,<br>
                                <span class="fw-bold" id="income">Php {{ $applicant->income }}</span>
                            </td>
                            <td colspan="2">
                                <span class="flabel">Cellphone No./Landline</span><br>
                                <span class="fw-bold" id="phoneNum">{{ $applicant->phonenum }}</span>
                            </td>
                            <td>
                                <span class="flabel">Birthdate</span><br>
                                <span class="fw-bold"
                                    id="birthDate">{{ \Carbon\Carbon::parse($applicant->birthdate)->format('F j, Y') }}</span>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span class="flabel">Facebook Link</span><br>
                                <a href="{{ $applicant->fblink }}" class="fw-bold link link-success" id="fbName"
                                    target="_blank">{{ $applicant->fblink }}</a>
                            </td>
                            <td colspan="4">
                                <span class="flabel">Are you a member of any indigenous group?</span><br>
                                <span class="fw-bold" id="indigenous">{{ $applicant->isIndigenous }}.
                                    {{ $applicant->indigenousgroup ?? '' }}</span>
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-2">
                    <img src="{{ public_path('storage/' . $applicant->requirements->idpic) }}" alt="Applicant 1x1 Pic"
                        class="border border-dark" style="width: 100%">
                </div>
            </div>
        </div>

        <div class="row mb-2">
            <div class="col-12 text-decoration-underline fw-bold">EDUCATIONAL BACKGROUND</div>
            @if ($iscollege)
                <span class="text-center my-2 fw-bold">COLLEGE</span>
                <div class="row">
                    <div class="col-md-4 row justify-content-between">
                        <div class="col-auto">Name of University</div>
                        <div class="col-auto">:</div>
                    </div>
                    <div class="col-md-8 fw-bold border-bottom border-dark">
                        {{ $applicant->educcollege->univname ?? '' }}
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 row justify-content-between">
                        <div class="col-auto">College Department</div>
                        <div class="col-auto">:</div>
                    </div>
                    <div class="col-md-8 fw-bold border-bottom border-dark">
                        {{ $applicant->educcollege->collegedept ?? '' }}
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 row justify-content-between">
                        <div class="col-auto">Incoming Year Level</div>
                        <div class="col-auto">:</div>
                    </div>
                    <div class="col-md-8 fw-bold border-bottom border-dark">
                        {{ $applicant->educcollege->inyear ?? '' }}
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 row justify-content-between">
                        <div class="col-auto">Course</div>
                        <div class="col-auto">:</div>
                    </div>
                    <div class="col-md-8 fw-bold border-bottom border-dark">
                        {{ $applicant->educcollege->course ?? '' }}
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 row justify-content-between">
                        <div class="col-auto">General Average Last Sem</div>
                        <div class="col-auto">:</div>
                    </div>
                    <div class="col-md-8 fw-bold border-bottom border-dark">
                        {{ $applicant->educcollege->gwa ?? '' }}
                    </div>
                </div>
            @else
                <span class="text-center my-2 fw-bold">ELEMENTARY/HIGH SCHOOL</span>

                <div class="row">
                    <div class="col-md-4 row justify-content-between">
                        <div class="col-auto">Name of Elementary/High School</div>
                        <div class="col-auto">:</div>
                    </div>
                    <div class="col-md-8 fw-bold border-bottom border-dark">
                        {{ $applicant->educelemhs->schoolname ?? '' }}
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 row justify-content-between">
                        <div class="col-auto">Incoming Year Level</div>
                        <div class="col-auto">:</div>
                    </div>
                    <div class="col-md-8 fw-bold border-bottom border-dark">
                        {{ $applicant->educelemhs->ingrade ?? '' }}
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 row justify-content-between">
                        <div class="col-auto">Section</div>
                        <div class="col-auto">:</div>
                    </div>
                    <div class="col-md-8 fw-bold border-bottom border-dark">
                        {{ $applicant->educelemhs->section ?? '' }}
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 row justify-content-between">
                        <div class="col-auto">General Average</div>
                        <div class="col-auto">:</div>
                    </div>
                    <div class="col-md-8 fw-bold border-bottom border-dark">
                        {{ $applicant->educelemhs->gwa ?? '' }}
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 row justify-content-between">
                        <div class="col-auto">Conduct</div>
                        <div class="col-auto">:</div>
                    </div>
                    <div class="col-md-8 fw-bold border-bottom border-dark">
                        {{ $applicant->educelemhs->gwaconduct ?? '' }}
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 row justify-content-between">
                        <div class="col-auto">Strand</div>
                        <div class="col-auto">:</div>
                    </div>
                    <div class="col-md-8 fw-bold border-bottom border-dark">
                        {{ $applicant->educelemhs->strand ?? '' }}
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 row justify-content-between">
                        <div class="col-auto">Chinese Subject - General Average</div>
                        <div class="col-auto">:</div>
                    </div>
                    <div class="col-md-8 fw-bold border-bottom border-dark">
                        {{ $applicant->educelemhs->chinesegwa ?? '' }}
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 row justify-content-between">
                        <div class="col-auto">Chinese Subject - Conduct</div>
                        <div class="col-auto">:</div>
                    </div>
                    <div class="col-md-8 fw-bold border-bottom border-dark">
                        {{ $applicant->educelemhs->chinesegwaconduct ?? '' }}
                    </div>
                </div>
            @endif
        </div>

        <div class="row mb-2">
            <div class="col-12 text-decoration-underline fw-bold mb-2">FAMILY INFORMATION</div>
            <div class="table-responsive">
                <table class="table table-bordered border-dark" id="table">
                    <thead>
                        <tr>
                            <td class="text-center align-center" style="width: 150px;">
                                <strong>Name</strong> <br>
                                <span style="font-size: 0.8em;">(Last Name, First Name)</span>
                            </td>
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
                                {{ \Carbon\Carbon::parse($father->birthdate)->format('m/d/Y') }}
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
                                {{ \Carbon\Carbon::parse($mother->birthdate)->format('m/d/Y') }}
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
                                    {{ \Carbon\Carbon::parse($sib->birthdate)->format('m/d/Y') }}
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

        <div class="row small mb-2">
            <div class="col-12 fw-bold">
                Grant/Assistance from other Government and Non-Government scholarships, School Discount
                (How much per sem?)
            </div>
            <div class="col-12 px-3" style="text-align: justify; text-decoration: underline;">
                {{ $applicant->otherinfo->grant }}</div>
            <div class="col-12 fw-bold mt-2">
                Talents & Skills/ Honor and Recognition/ Extracurricular/Community
                Involvement/Employment
            </div>
            <div class="col-12 px-3" style="text-align: justify; text-decoration: underline;">
                <span id="talents">{{ $applicant->otherinfo->talent }}</span>
            </div>
            <div class="col-12 fw-bold mt-2">
                What are your expectations from Tzu Chi Foundation?
            </div>
            <div class="col-12 px-3" style="text-align: justify; text-decoration: underline;">
                {{ $applicant->otherinfo->expectations }}</div>
        </div>

        <div class="row text-center">
            <div class="mb-5 mt-4">
                I hereby attest that the information I have provided is true and correct. I also consents Tzu
                Chi Foundation to obtain and retain my personal information for the purpose of this
                application.
            </div>
            <div class="col-auto border-top border-dark small mx-auto mt-2">Applicant's Signature over Printed
                Name
                and Date
            </div>
        </div>
    </div>
    <div class="container border border-dark shadow p-5 mb-5">
        <div class="row justify-content-center">
            <img src="{{ public_path('images/appform-header.png') }}" alt="header" style="width: 60%">
        </div>
        <h3 class="text-center my-3">Educational Assistance Application Form</h3>
        <div class="col-12 text-decoration-underline fw-bold mb-2">Sketch of Home Address</div>
        <div class="sketchimg">
            <img src="{{ public_path('storage/' . $applicant->requirements->sketchmap) }}"
                alt="Sketch Map of Home Address" style="width: 100%; max-height: 400px" class="border border-dark">
        </div>
        <div class="row my-2">
            <div class="col-12">
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
                        Submit the following: <span class="fw-bold fst-italic">Photocopy of Report Card or copy
                            of
                            grade
                            form
                            last school year, Registration form, Accomplished Application
                            Form, Two (2) 1x1 ID Pictures, Autobiography, Family Picture, Copies of Utility
                            Bills,
                            Detailed Sketch of Home Address, Certificate
                            of indigence from the Barangay, Pictures of House (Inside and outside), Payslip or
                            Income Tax Return of Both Parents (if working).</span>
                        <strong>Deadline of Submission is on <span class="text-decoration-underline">
                                {{ $form->deadline ? \Carbon\Carbon::parse($form->deadline)->format('F j, Y') : '--' }}</span></strong>
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
        </div>
        <div class="row small fw-bold border-top border-dark mb-2">
            To be filled up by TZU CHI FOUNDATION
        </div>
        <div class="row small fw-bold">
            Case Details
        </div>
        <div class="row table-responsive">
            <table class="table table-bordered border-dark" id="table">
                <tr>
                    <td width="20%">
                        <span class="fw-bold">Nature of Needs</span><br>
                        <span class="px-3 row">
                            @foreach ($needs as $need)
                                <div class="col-12 align-items-center">
                                    <input type="radio" value="{{ $need }}"
                                        {{ isset($applicant->casedetails) && $applicant->casedetails->natureofneeds === $need ? 'checked' : '' }}
                                        disabled>
                                    <label>{{ $need }}</label>
                                </div>
                            @endforeach
                            <div class="col-12 align-items-center">
                                <input type="radio" value="Others"
                                    {{ isset($applicant->casedetails) && $applicant->casedetails->natureofneeds === 'Others' ? 'checked' : '' }}
                                    disabled>
                                <label>Others</label>
                            </div>
                            @if (isset($applicant->casedetails) && !in_array($applicant->casedetails->natureofneeds, $needs))
                                <input type="text" class="form-control border-bottom border-dark"
                                    value="{{ $applicant->casedetails->natureofneeds }}">
                            @endif
                        </span>
                    </td>
                    <td width="80% row">
                        <div class="col-12 fw-bold">Problem Statement</div>
                        <div class="col-12" style="text-align: justify">
                            {{ $applicant->casedetails->problemstatement ?? '' }}</div>
                    </td>
                </tr>
            </table>
        </div>
        <div class="row">
            <div class="column col-md-5">
                <div class="row my-2 d-flex justify-content-between casedeets">
                    Received By:
                    <div class="casedeets-input text-center"
                        style="width: 65% !important; border-bottom: 1px solid #000;">
                        {{ isset($applicant->casedetails->receivedby) ? $applicant->casedetails->receivedby : '' }}
                    </div>
                </div>
                <div class="row my-2 d-flex justify-content-between casedeets">
                    Date Received:
                    <div class="casedeets-input text-center"
                        style="width: 50% !important; border-bottom: 1px solid #000;">
                        {{ isset($applicant->casedetails->datereceived) ? $applicant->casedetails->datereceived : '' }}
                    </div>
                </div>
                <div class="row my-2 d-flex justify-content-between casedeets">
                    Assigned District:
                    <div class="casedeets-input text-center"
                        style="width: 50% !important; border-bottom: 1px solid #000;">
                        {{ isset($applicant->casedetails) && $applicant->casedetails->district ?? null }}
                    </div>
                </div>
                <div class="row my-2 d-flex justify-content-between casedeets">
                    Assigned Volunteer:
                    <div class="casedeets-input text-center"
                        style="width: 50% !important; border-bottom: 1px solid #000;">
                        {{ isset($applicant->casedetails) && $applicant->casedetails->volunteer ?? null }}
                    </div>
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
                        <div class="casedeets-input text-center"
                            style="width: 45% !important; border-bottom: 1px solid #000;">
                            {{ isset($applicant->casedetails) && $applicant->casedetails->referredby ?? null }}
                        </div>
                    </div>
                    <div class="row my-2 d-flex justify-content-between casedeets">
                        Referral Contact no.:
                        <div class="casedeets-input text-center"
                            style="width: 45% !important; border-bottom: 1px solid #000;">
                            {{ isset($applicant->casedetails) && $applicant->casedetails->referphonenum ?? null }}
                        </div>
                    </div>
                    <div class="row my-2 d-flex justify-content-between casedeets">Relationship with
                        Beneficiary:
                        <div class="casedeets-input text-center"
                            style="width: 45% !important; border-bottom: 1px solid #000;">
                            {{ isset($applicant->casedetails) && $applicant->casedetails->relationship ?? null }}
                        </div>
                    </div>
                    <div class="row my-2 d-flex justify-content-between casedeets">
                        Applicant's Signature:
                        <div class="casedeets-input text-center"
                            style="width: 45% !important; border-bottom: 1px solid #000;"></div>
                    </div>
                    <div class="row my-2 d-flex justify-content-between casedeets">
                        Date Reported:
                        <div class="casedeets-input text-center"
                            style="width: 45% !important; border-bottom: 1px solid #000;">
                            {{ isset($applicant->casedetails) && $applicant->casedetails->datereported ?? date('Y-m-d') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
