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
        font-family: Arial, Helvetica, sans-serif;
    }

    .appform-view {
        font-size: 16px;
    }

    .page1, .page2{
        padding: 50px;
    }

    .header, .app-title{
        text-align: center;
    }

    .app-title{
    margin: 20px 0;
    }

    .app-title p{
    margin-bottom: 5px;
    }

    .personal-info .psec1{
    display: flex;
    gap: 100px;
    }

    .psec1-info{
        display: flex;
        gap: 50px;
    }

    .personal-info .psec2{
        display: flex;
        gap: 10px;
    }

    .psec2 img{
        width: 1in;
        height: 1in;
    }

    .fvalue, .elabel span{
    font-weight: 600;
    }

    .educbg .esec2{
    padding: 5px;
    }

    .esec2 .elabel{
    padding-bottom: 7px;
    }

    .educbg-info p{
    font-size: 14px;
    font-weight: 600;
    margin: 5px 0;
    text-align: center;
    }

    .table{
    width: 100%;
    border-collapse: collapse;
    }

    .table td, .table th {
        border: 1px solid black;
    }

    .table tr td {
        padding: 2px;
    }

    .table td .flabel, .fvalue, .elabel, .evalue{
        font-size: 14px;
    }

    .table thead tr th{
        font-size: 13px;
        padding: 5px;
    }

    .table thead tr th span{
        font-size: 11px;
    }

    .table tbody tr td{
        font-size: 13px;
    }

    #familyTableBody td{
        text-align: center;
    }

    .other-info p{
    font-size: 14px;
    font-weight: 600;
    margin-top: 10px;
    text-decoration: underline;
    }

    .other-info span{
        font-size: 14px;
        text-align: justify;
    }

    .signature{
    text-align: center;
    font-size: 12px;
    margin-top: 20px;
    }

    .sign{
        text-decoration: overline;
        font-weight: 600;
        padding-top: 30px;
    }

    .page2 h3{
    text-align: center;
    padding-top: 20px;
    }

    .sketchome .sec-title{
        padding: 10px 0;
    }

    .sketchimg{
        width: 100%;
        height: 250px;
        border: 1px solid;
        text-align: center;
    }

    .instructions{
        font-size: 13px;
        padding-bottom: 10px;
    }

    .instructions ol li{
        margin-left: 10px;
        margin-bottom: 5px;
    }

    .fillstaff{
    border-top: 1px solid;
    }

    .fillstaff .heading{
        font-weight: 600;
        font-size: 12px;
    }

    .table3{
        font-size: 13px;
    }

    .table3 tr td{
        padding: 0 10px;
    }

    .page2 .group{
    gap: 20px;
    padding-top: 10px;
    }

    .groupa span, .groupb span{
        margin-left: 10px;
        font-weight: 600;
        border-bottom: 1px solid;
    }

    .groupa .slabel, .group1 .slabel{
        padding-bottom: 10px;
    }

    .page2 .groupa{
        width: 500px;
    }

    .groupa .slabel, .groupb1 .slabel{
        font-size: 14px;
    }

    .groupb1 .group1, .groupb1 .group2{
        padding-bottom: 5px;
    }

    .note{
        font-style: italic;
        font-size: 12px;
        margin-bottom: 20px;
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
                <p id="schoolYear">S.Y. {{ date('Y') }}-{{ date('Y') + 1 }}</p>
            </div>
            <div class="personal-info">
                <div class="psec1">
                    <p class="sec-title"><strong><u>PERSONAL INFORMATION</u></strong></p>
                    <div class="psec1-info">
                        <p>Status: <span><strong>New</strong></span></p>
                        <p>Case Code: <span id="caseCode"><strong>{{ $applicant->casecode }}</strong></span></p>
                        <p>Form No.: <span id="formNo"><strong></strong></span></p>
                    </div>
                </div>
                <!-- <div class="row mb-2">
                    <span class="col-md-4"></span>
                    <span class="col-md-2"><strong>New</strong></span>
                    <span class="col-md-3"><strong>{{ $applicant->casecode }}</strong></span>
                    <span class="col-md-3"></span>
                </div> -->
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
                                    <span class="flabel">Facebook Link</span><br>
                                    <a href="{{ $applicant->fblink }}" class="fw-bold link link-success" id="fbName"
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
                    <img src="{{ public_path('storage/' . $applicant->requirements->idpic) }}" alt="Applicant 1x1 Pic">
                </div>
            </div>

            <div class="educbg">
                <div class="esec1">
                    <span class="my-2"><strong><u>EDUCATIONAL BACKGROUND</u></strong></span>
                </div>
                <div class="educbg-info">
                    @if ($iscollege)
                        <p style="text-align: center;"><strong>COLLEGE</strong></p>
                        <div class="esec2">
                            <div class="elabel">Name of University: 
                                <span id="university">{{ $applicant->educcollege->univname ?? '' }}</span>
                            </div>                                      
                            <div class="elabel">College Department: 
                                <span id="collegeDept">{{ $applicant->educcollege->collegedept ?? '' }}</span>
                            </div>                             
                            <div class="elabel">Incoming Year Level: 
                                <span id="yrLevel">{{ $applicant->educcollege->inyear ?? '' }}</span>
                            </div>                                      
                            <div class="elabel">Course: 
                                <span id="course">{{ $applicant->educcollege->course ?? '' }}</span>
                            </div>                                       
                            <div class="elabel">General Average Last Sem: 
                                <span id="gwa">{{ $applicant->educcollege->gwa ?? '' }}</span>
                            </div>                          
                        </div>

                        <!-- <span class="text-center my-2 fw-bold"><strong>COLLEGE</strong></span>
                        <div class="row">
                            Name of University:
                            <span id="university">
                                <strong>{{ $applicant->educcollege->univname ?? '' }}</strong>
                            </span>
                        </div>

                        <div class="row">
                            College Department:
                            <span id="collegeDept">
                                <strong>{{ $applicant->educcollege->collegedept ?? '' }}</strong>
                            </span>
                        </div>

                        <div class="row">
                             Incoming Year Level:
                            <span id="yrLevel">
                                <strong>{{ $applicant->educcollege->inyear ?? '' }}</strong>
                            </span>
                        </div>

                        <div class="row">
                            Course:
                            <span id="course">
                                <strong>{{ $applicant->educcollege->course ?? '' }}</strong>
                            </span>
                        </div>

                        <div class="row">
                            General Average Last Sem:
                            <span id="gwa">
                                <strong>{{ $applicant->educcollege->gwa ?? '' }}</strong>
                            </span>
                        </div> -->
                    @else
                        <p style="text-align: center;"><strong>ELEMENTARY/HIGH SCHOOL</strong></p>
                        <div class="esec2">
                            <div class="elabel">Name of Elementary/ High School: 
                                <span id="university">{{ $applicant->educelemhs->schoolname ?? '' }}</span>
                            </div>                                      
                            <div class="elabel">Incoming Year Level: 
                                <span id="collegeDept">{{ $applicant->educelemhs->ingrade ?? '' }}</span>
                            </div>                             
                            <div class="elabel">Section:  
                                <span id="yrLevel">{{ $applicant->educelemhs->section ?? '' }}</span>
                            </div>                                      
                            <div class="elabel">General Average:  
                                <span id="course">{{ $applicant->educelemhs->gwa ?? '' }}</span>
                            </div>                                       
                            <div class="elabel">Conduct:  
                                <span id="gwa">{{ $applicant->educelemhs->gwaconduct ?? '' }}</span>
                            </div>  
                            <div class="elabel">Strand:   
                                <span id="gwa">{{ $applicant->educelemhs->strand ?? '' }}</span>
                            </div>
                            <div class="elabel">Chinese Subject General Average:  
                                <span id="gwa">{{ $applicant->educelemhs->chinesegwa ?? '' }}</span>
                            </div>
                            <div class="elabel">Conduct:  
                                <span id="gwa">{{ $applicant->educelemhs->chinesegwaconduct ?? '' }}</span>
                            </div>                        
                        </div>

                        <!-- <span class="text-center my-2 fw-bold">ELEMENTARY/HIGH SCHOOL</span>

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
                        </div> -->
                    @endif
                </div>
            </div>

            <div class="fam-info">
                <p class="sec-title fam" style="margin-bottom: 5px;"><strong><u>FAMILY INFORMATION</u></strong></p>
                <div class="table1">
                    <table class="table table-bordered" id="table">
                        <thead>
                            <tr>
                                <th style="width: 150px;">Name <br><span>(Last Name, First Name)</span></th>
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
                                <td >{{ $father->occupation }}</td>
                                <td >{{ $father->company }}</td>
                                <td >{{ $father->income }}</td>
                            </tr>
                            {{-- MOTHER --}}
                            <tr>
                                <td >{{ $mother->name }}</td>
                                <td >{{ $mother->age }}</td>
                                <td >{{ $mother->sex }}</td>
                                <td >
                                    {{ \Carbon\Carbon::parse($mother->birthdate)->format('m/d/Y') }}
                                </td>
                                <td >{{ $mother->relationship }}</td>
                                <td >{{ $mother->religion }}</td>
                                <td >{{ $mother->educattainment }}</td>
                                <td >{{ $mother->occupation }}</td>
                                <td >{{ $mother->company }}</td>
                                <td >{{ $mother->income }}</td>
                            </tr>
                            {{-- SIBLING/S --}}
                            @foreach ($siblings as $sib)
                                <tr>
                                    <td >{{ $sib->name }}</td>
                                    <td >{{ $sib->age }}</td>
                                    <td >{{ $sib->sex }}</td>
                                    <td >
                                        {{ \Carbon\Carbon::parse($sib->birthdate)->format('m/d/Y') }}
                                    </td>
                                    <td >{{ $sib->relationship }}</td>
                                    <td >{{ $sib->religion }}</td>
                                    <td >{{ $sib->educattainment }}</td>
                                    <td >{{ $sib->occupation }}</td>
                                    <td >{{ $sib->company }}</td>
                                    <td >{{ $sib->income }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="other-info">
                <p>Grant/Assistance from other Government and Non-Government scholarships, School Discount (How much per sem?)</p>
                <span id="grant">{{ $applicant->otherinfo->grant }}</span>
                <p>Talents & Skills/ Honor and Recognition/ Extracurricular/Community Involvement/Employment</p>
                <span id="talents">{{ $applicant->otherinfo->talent }}</span>
                <p>What are your expectations from Tzu Chi Foundation?</p>
                <span id="expectations">{{ $applicant->otherinfo->expectations }}</span>

                <!-- <div class="col-12 fw-bold">
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
                    {{ $applicant->otherinfo->expectations }}</div> -->
            </div>

            <div class="signature">
                <p>I hereby attest that the information I have provided is true and correct. 
                    I also consents Tzu Chi Foundation to obtain and retain my personal information 
                    for the purpose of this application.</p>
                <p class="sign">Applicant's Signature over Printed Name and Date</p>
            </div>
        </div>

        <div class="page2">
            <div class="header">
                <img src="{{ public_path('images/appform-header.png') }}" alt="header" style="width: 60%">
            </div>
            <h3>Educational Assistance Application Form</h3>
            <div class="sketchome">
                <p class="sec-title"><strong><u>SKETCH OF HOME ADDRESS</u></strong></p>
                <div class="sketchimg">
                    <img src="{{ public_path('storage/' . $applicant->requirements->sketchmap) }}"
                        alt="Sketch Map of Home Address" style="height: 250px;" >
                </div>
            </div>

            <div class="instructions">
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
                            Submit the following: <span style="font-style: italic;"><strong>Photocopy of Report Card or copy
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
            </div>

            <div class="fillstaff">
                <div class="heading">
                    <p>To be filled up by TZU CHI FOUNDATION</p>
                    <p>Case Details</p>
                </div>
            </div>
            <div class="table3">
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
                            <div class="col-12 fw-bold"><strong>Problem Statement</strong></div>
                            <div class="col-12" style="text-align: justify">
                                {{ $applicant->casedetails->problemstatement ?? '' }}</div>
                        </td>
                    </tr>
                </table>
            </div>

            <div class="group">
                <div class="groupa casedeets">
                    <div class="slabel">Received By:
                        <span id="receivedBy">{{ isset($applicant->casedetails->receivedby) ? $applicant->casedetails->receivedby : '' }}</span>
                    </div>
                    <div class="slabel">Date Received:
                        <span id="dateReceived">{{ isset($applicant->casedetails->datereceived) ? $applicant->casedetails->datereceived : '' }}</span>
                    </div> 
                    <div class="slabel">Assigned District:
                        <span id="assignedDistrict">{{ isset($applicant->casedetails) && $applicant->casedetails->district ?? null }}</span>
                    </div>
                    <div class="slabel">Assigned Volunteer:
                        <span id="assignedVol">{{ isset($applicant->casedetails) && $applicant->casedetails->volunteer ?? null }}</span>
                    </div>
                </div>
                <div class="groupb">
                    <p class="note">Note: Important information are essential to be able to assess and evaluate students' situation. 
                        Also please note significant details for home visitation purposes. You may use the back page if necessary.</p>
                    
                    <div class="groupb1">
                        <div class="group1">
                            <div class="slabel">Case Referred By:
                                <span>{{ isset($applicant->casedetails) && $applicant->casedetails->referredby ?? null }}</span>
                            </div> 
                            <div class="slabel">Referral Contact no. :
                                <span>{{ isset($applicant->casedetails) && $applicant->casedetails->referphonenum ?? null }}</span>
                            </div>           
                            <div class="slabel">Relationship with Beneficiary:
                                <span>{{ isset($applicant->casedetails) && $applicant->casedetails->relationship ?? null }}</span>
                            </div>
                            <div class="slabel">Applicant's Signature:
                                <span></span>
                            </div>           
                            <div class="slabel">Date Reported:
                                <span>{{ isset($applicant->casedetails) && $applicant->casedetails->datereported ?? date('Y-m-d') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- <div class="row">
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
            </div> -->
        </div>
    </div>
</body>

</html>
