<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Application Form</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    {{-- <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet"> --}}
    <style>
        textarea {
            resize: none;
        }
    </style>
</head>

<body>
    <!-- PAGE HEADER -->
    @include('partials._pageheader')
    <x-alert />
    <div class="ctnmain">
        <div class="container">
            <div class="row justify-content-between align-items-center mb-2">
                <div class="col-auto">
                    <a href="{{ route('applicants') }}" class="btn btn-success w-100">Go back</a>
                </div>
                @if (!$applicant->applicationstatus == 'Denied' || !$applicant->applicationstatus == 'Withdrawn')
                    <div class="col-auto">
                        <button class="btn btn-warning fw-bold w-100" data-bs-toggle="modal"
                            data-bs-target="#actionModal">
                            Action Taken
                        </button>
                    </div>
                @endif
                <div class="col-auto row">
                    <div class="col-auto">
                        <a href="{{ route('generateapplicantform', ['casecode' => $applicant->casecode]) }}"
                            class="btn btn-success" target="_blank">Download Form</a>
                    </div>
                </div>
            </div>
            @php
                $stages = [
                    'Under Review' => $progress['Under Review']->status ?? null,
                    'Initial Interview' => $progress['Initial Interview']->status ?? null,
                    'Home Visit' => $progress['Home Visit']->status ?? null,
                    'Panel Interview' => $progress['Panel Interview']->status ?? null,
                ];

                function getStatusClass($status)
                {
                    return match ($status) {
                        'Passed' => 'bg-success text-white',
                        'Failed' => 'bg-danger text-white',
                        null => 'bg-light text-muted',
                        default => 'bg-warning text-dark',
                    };
                }
            @endphp

            <div class="container p-4 border border-dark shadow mb-4">
                <div class="row mb-2">
                    <div class="col-12 fw-bold text-center h4">Application Progress</div>
                </div>

                <div class="row">
                    @foreach ($stages as $stage => $status)
                        @if (!empty($progress[$stage]))
                            <div class="col-md-3 text-muted">
                                <div class="px-2 text-center fw-bold">
                                    {{ \Carbon\Carbon::parse($progress[$stage]->created_at)->format('F d, h:i A') ?? '' }}
                                </div>
                            </div>
                        @else
                            @continue
                        @endif
                    @endforeach
                </div>

                {{-- Progress Bar --}}
                <div class="row gx-0 px-2">
                    @foreach ($stages as $stage => $status)
                        @php $statusClass = getStatusClass($status); @endphp
                        <div style="width: 25%" class="text-center h5 fw-bold p-2 {{ $statusClass }}">
                            {{ $stage }}
                        </div>
                    @endforeach
                </div>

                {{-- Detailed Information for Each Stage --}}
                <div class="row">
                    @foreach ($stages as $stage => $status)
                        @if (!empty($progress[$stage]))
                            <div class="col-md-3 text-muted">
                                <div class="px-2">
                                    <div class="small"><b>Status:</b> {{ $status ?? 'Pending' }}</div>
                                    <div class="small"><b>Remark:</b>
                                        {{ $progress[$stage]->remark ?? 'No remarks yet' }}</div>
                                    <div class="small"><b>Message to Applicant:</b>
                                        {{ $progress[$stage]->msg ?? 'No comments yet' }}
                                    </div>
                                </div>
                            </div>
                        @else
                            @continue
                        @endif
                    @endforeach
                </div>
            </div>
            <div class="container border border-dark shadow p-5 mb-5">
                <div class="row justify-content-center">
                    <img src="{{ asset('images/appform-header.png') }}" alt="header" style="width: 60%">
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
                                        <a href="{{ $applicant->fblink }}" class="fw-bold link link-success"
                                            id="fbName" target="_blank">{{ $applicant->fblink }}</a>
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
                            <img src="{{ asset('storage/' . $applicant->requirements->idpic) }}"
                                alt="Applicant 1x1 Pic" class="border border-dark" style="width: 100%">
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
                    <img src="{{ asset('images/appform-header.png') }}" alt="header" style="width: 60%">
                </div>
                <h3 class="text-center my-3">Educational Assistance Application Form</h3>
                <div class="col-12 text-decoration-underline fw-bold mb-2">Sketch of Home Address</div>
                <div class="sketchimg">
                    <img src="{{ asset('storage/' . $applicant->requirements->sketchmap) }}"
                        alt="Sketch Map of Home Address" style="width: 100%; max-height: 400px"
                        class="border border-dark">
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
            <div class="card mx-auto mt-3 mb-5 shadow-sm">
                <div class="card-header py-3 bg-success text-white">
                    <span class="h5 fw-bold">Submitted Documents</span>
                </div>
                <div class="card-body">
                    <div class="row mb-2 pb-2 border-bottom">
                        <div class="col-md-5 file">Latest Report Card</div>
                        <div class="col-md-5 text-center">
                            <a href="{{ asset('storage/' . $applicant->requirements->reportcard) }}" target="_blank"
                                class="btn btn-sm btn-success px-4">
                                <i class="fa-solid fa-file-lines"></i> <span>View document</span>
                            </a>
                        </div>
                    </div>
                    <div class="row mb-2 py-2 border-bottom">
                        <div class="col-md-5 file">Latest Registration Card</div>
                        <div class="col-md-5 text-center">
                            <a href="{{ asset('storage/' . $applicant->requirements->regiform) }}" target="_blank"
                                class="btn btn-sm btn-success px-4">
                                <i class="fa-solid fa-file-lines"></i> <span>View document</span>
                            </a>
                        </div>
                    </div>
                    <div class="row mb-2 py-2 border-bottom">
                        <div class="col-md-5 file">Autobiography</div>
                        <div class="col-md-5 text-center">
                            <a href="{{ asset('storage/' . $applicant->requirements->autobio) }}" target="_blank"
                                class="btn btn-sm btn-success px-4">
                                <i class="fa-solid fa-file-lines"></i> <span>View document</span>
                            </a>
                        </div>
                    </div>
                    <div class="row mb-2 py-2 border-bottom">
                        <div class="col-md-5 file">Family Picture</div>
                        <div class="col-md-5 text-center">
                            <a href="{{ asset('storage/' . $applicant->requirements->familypic) }}" target="_blank"
                                class="btn btn-sm btn-success px-4">
                                <i class="fa-solid fa-file-lines"></i> <span>View document</span>
                            </a>
                        </div>
                    </div>
                    <div class="row mb-2 py-2 border-bottom">
                        <div class="col-md-5 file">Picture of House (Inside)</div>
                        <div class="col-md-5 text-center">
                            <a href="{{ asset('storage/' . $applicant->requirements->houseinside) }}" target="_blank"
                                class="btn btn-sm btn-success px-4">
                                <i class="fa-solid fa-file-lines"></i> <span>View document</span>
                            </a>
                        </div>
                    </div>
                    <div class="row mb-2 py-2 border-bottom">
                        <div class="col-md-5 file">Picture of House (Outside)</div>
                        <div class="col-md-5 text-center">
                            <a href="{{ asset('storage/' . $applicant->requirements->houseoutside) }}"
                                target="_blank" class="btn btn-sm btn-success px-4">
                                <i class="fa-solid fa-file-lines"></i> <span>View document</span>
                            </a>
                        </div>
                    </div>
                    <div class="row mb-2 py-2 border-bottom">
                        <div class="col-md-5 file">Latest Utility Bill</div>
                        <div class="col-md-5 text-center">
                            <a href="{{ asset('storage/' . $applicant->requirements->utilitybill) }}"
                                target="_blank" class="btn btn-sm btn-success px-4">
                                <i class="fa-solid fa-file-lines"></i> <span>View document</span>
                            </a>
                        </div>
                    </div>
                    <div class="row mb-2 py-2 border-bottom">
                        <div class="col-md-5 file">Sketch Map of Home Address</div>
                        <div class="col-md-5 text-center">
                            <a href="{{ asset('storage/' . $applicant->requirements->sketchmap) }}" target="_blank"
                                class="btn btn-sm btn-success px-4">
                                <i class="fa-solid fa-file-lines"></i> <span>View document</span>
                            </a>
                        </div>
                    </div>
                    <div class="row mb-2 py-2 border-bottom">
                        <div class="col-md-5 file">Latest Pay Slip of Parent/s</div>
                        <div class="col-md-5 text-center">
                            @if (!empty($applicant->requirements) && !empty($applicant->requirements->payslip))
                                <a href="{{ asset('storage/' . $applicant->requirements->payslip) }}"
                                    target="_blank" class="btn btn-sm btn-success px-4">
                                    <i class="fa-solid fa-file-lines"></i> <span>View document</span>
                                </a>
                            @else
                                <span class="text-muted">No document available</span>
                            @endif
                        </div>
                    </div>
                    <div class="row mb-2 py-2 border-bottom">
                        <div class="col-md-5 file">Certificate of Indigency</div>
                        <div class="col-md-5 text-center">
                            <a href="{{ asset('storage/' . $applicant->requirements->indigencycert) }}"
                                target="_blank" class="btn btn-sm btn-success px-4">
                                <i class="fa-solid fa-file-lines"></i> <span>View document</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="actionModal" tabindex="-1" aria-labelledby="actionModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header bg-warning">
                    <h5 class="modal-title fw-bold text-dark" id="actionModalLabel">Action Form</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('updateapplicantstatus', $applicant->casecode) }}" method="post">
                    @csrf <!-- Add this for Laravel to handle CSRF -->
                    <!-- Modal Body -->
                    <div class="modal-body row">
                        <div class="container">
                            <div class="row mb-3">
                                <div class="col-md-6 fw-bold">Current Phase</div>
                                <div class="col-md-6">
                                    <input type="text" name="curphase" id="curphase"
                                        value="{{ $applicant->applicationstatus }}" readonly
                                        class="form-control border-success">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6 fw-bold">Status</div>
                                <div class="col-md-6">
                                    <select name="status" id="status" class="form-select border-success"
                                        required>
                                        <option value="" hidden {{ old('status') ? '' : 'selected' }}>
                                            Select status</option>
                                        <option value="Passed" {{ old('status') === 'Passed' ? 'selected' : '' }}>
                                            Passed</option>
                                        <option value="Failed" {{ old('status') === 'Failed' ? 'selected' : '' }}>
                                            Failed</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6 fw-bold">Remark</div>
                                <div class="col-md-6">
                                    <textarea name="remark" id="remark" rows="3" maxlength="255" required class="form-control border-success"
                                        placeholder="Type here...">{{ old('remark') }}</textarea>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6 fw-bold">Message to Applicant</div>
                                <div class="col-md-6">
                                    <textarea name="comment" id="comment" rows="3" maxlength="255" required class="form-control border-success"
                                        placeholder="Type here...">{{ old('comment') }}</textarea>
                                </div>
                            </div>
                            <div class="row justify-content-center">
                                <div class="col-auto">
                                    <input type="checkbox" name="cbcasedeets" id="cbcasedeets"
                                        class="form-check-input border-success" style="cursor: pointer"
                                        {{ old('cbcasedeets', '') === 'on' ? 'checked' : '' }} value="on">
                                </div>
                                <div class="col-auto">
                                    <label for="cbcasedeets" style="cursor: pointer">Fill up case
                                        details</label>
                                </div>
                            </div>
                        </div>
                        <div class="container collapse" id="casedetails">
                            <div class="row mb-3">
                                <div class="col-md-6 fw-bold">
                                    Nature of Needs
                                </div>
                                <div class="col-md-6">
                                    <select name="natureofneeds" id="natureofneeds"
                                        class="form-select border-success">
                                        <option value="" {{ old('natureofneeds') ? '' : 'selected' }} hidden>
                                            Select needs</option>
                                        <option value="Financial"
                                            {{ old('natureofneeds') == 'Financial' ? 'selected' : '' }}>
                                            Financial</option>
                                        <option value="Medical"
                                            {{ old('natureofneeds') == 'Medical' ? 'selected' : '' }}>Medical
                                        </option>
                                        <option value="Food"
                                            {{ old('natureofneeds') == 'Food' ? 'selected' : '' }}>Food
                                        </option>
                                        <option value="Material"
                                            {{ old('natureofneeds') == 'Material' ? 'selected' : '' }}>Material
                                        </option>
                                        <option value="Education"
                                            {{ old('natureofneeds') == 'Education' ? 'selected' : '' }}>
                                            Education</option>
                                        <option value="Others"
                                            {{ old('natureofneeds') == 'Others' ? 'selected' : '' }}>Others
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-3 collapse" id="othersnon">
                                <div class="col-md-6 fw-bold">
                                    Nature of Needs (Others)
                                </div>
                                <div class="col-md-6">
                                    <input type="text" name="othersnatureofneeds"
                                        class="form-control border-success" maxlength="50"
                                        value="{{ old('othersnatureofneeds') }}">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6 fw-bold">
                                    Problem Statement
                                </div>
                                <div class="col-md-6">
                                    <textarea name="problemstatement" id="problemstatement" rows="3" class="form-control border-success"
                                        maxlength="255">{{ old('problemstatement') }}</textarea>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6 fw-bold">
                                    Received By
                                </div>
                                <div class="col-md-6">
                                    <input type="text" name="receivedby" maxlength="255"
                                        class="form-control border-success" value="{{ old('receivedby') }}">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6 fw-bold">
                                    Date Received
                                </div>
                                <div class="col-md-6">
                                    <input type="date" name="datereceived" class="form-control border-success"
                                        value="{{ old('datereceived') }}">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6 fw-bold">
                                    Assigned District
                                </div>
                                <div class="col-md-6 fw-bold">
                                    <input type="text" name="district" maxlength="50"
                                        class="form-control border-success" value="{{ old('district') }}">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6 fw-bold">
                                    Assigned Volunteer
                                </div>
                                <div class="col-md-6">
                                    <input type="text" name="volunteer" maxlength="255"
                                        class="form-control border-success" value="{{ old('volunteer') }}">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6 fw-bold">
                                    Case Referred By
                                </div>
                                <div class="col-md-6">
                                    <input type="text" name="referredby" maxlength="255"
                                        class="form-control border-success" value="{{ old('referredby') }}">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6 fw-bold">
                                    Referral Contact No.
                                </div>
                                <div class="col-md-6">
                                    <input type="text" name="referphonenum" maxlength="12" minlength="11"
                                        class="form-control border-success" pattern="^(09\d{9}|63\d{10})$"
                                        value="{{ old('referphonenum') }}">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6 fw-bold">
                                    Relationship with Beneficiary
                                </div>
                                <div class="col-md-6">
                                    <input type="text" name="relationship" maxlength="50"
                                        class="form-control border-success" value="{{ old('relationship') }}">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6 fw-bold">
                                    Date Reported
                                </div>
                                <div class="col-md-6">
                                    <input type="date" name="datereported" class="form-control border-success"
                                        value="{{ old('datereported') }}">
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Modal Footer -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success">Submit</button>
                    </div>
                </form>
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

        document.addEventListener('DOMContentLoaded', function() {
            const checkbox = document.getElementById('cbcasedeets');
            const caseDetailsSection = document.getElementById('casedetails');
            const natureOfNeeds = document.getElementById('natureofneeds');
            const othersnon = document.getElementById('othersnatureofneeds');
            const othersNatureOfNeeds = document.getElementById('othersnon');

            const requiredFields = [
                'natureofneeds',
                'problemstatement',
                'datereceived',
                'receivedby',
                'district',
                'volunteer',
            ];

            // Function to toggle required attribute
            function toggleRequiredFields(add) {
                requiredFields.forEach((fieldId) => {
                    const field = document.getElementById(fieldId) || document.querySelector(
                        `[name="${fieldId}"]`);
                    if (field) {
                        if (add) {
                            field.setAttribute('required', 'required');
                        } else {
                            field.removeAttribute('required');
                        }
                    }
                });
            }

            // Initialize the checkbox event listener for the collapse effect
            if (checkbox) {
                checkbox.addEventListener('change', function() {
                    const collapseInstance = new bootstrap.Collapse(caseDetailsSection, {
                        toggle: false,
                    });

                    if (this.checked) {
                        collapseInstance.show(); // Show with fade effect
                        toggleRequiredFields(true); // Add required attribute
                    } else {
                        collapseInstance.hide(); // Hide with fade effect
                        toggleRequiredFields(false); // Remove required attribute
                    }
                });
            }

            if (natureOfNeeds) {
                natureOfNeeds.addEventListener('change', function() {
                    const collapseInstance = new bootstrap.Collapse(othersNatureOfNeeds, {
                        toggle: false,
                    });

                    if (natureOfNeeds.value == 'Others') {
                        collapseInstance.show();
                        othersnon.setAttribute('required', 'required');
                    } else {
                        collapseInstance.hide();
                        othersnon.removeAttribute('required');
                    }
                });
            }
        });
    </script>
</body>

</html>
