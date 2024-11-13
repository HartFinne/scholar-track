<!DOCTYPE html>
<html lang="en">

<head>
    <title>Profile</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/table.css') }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body>
    <!-- Include Sidebar -->
    @include('partials._pageheader')

    <!-- MAIN CONTENT -->
    <div class="ctnmain">
        <div class="container d-flex align-items-center">
            <div class="col-md-11">
                <h1 class="title"><strong>Scholar's Information</strong></h1>
            </div>
            <div class="col-md-1">
                <a href="{{ route('scholars-college') }}" class="btn btn-success">&lt Go back</a>
            </div>
        </div>
        <div class="container">
            <div class="row">
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert" id="success-alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert" id="error-alert">
                        {!! session('error') !!}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
            </div>
            <div class="card mb-4">
                <div class="card-header bg-success text-white">
                    <span style="font-size: 18px; font-weight: bold"><strong>Personal Information</strong></span>
                </div>
                <div class="card-body">
                    <!-- Scholarship Information Section -->
                    <div class="mb-3">
                        <h5 class="text-success">SCHOLARSHIP INFORMATION</h5>
                        <form class="row" action="{{ route('updatescholarshipstatus', $data->caseCode) }}"
                            method="POST">
                            @csrf
                            <div class="row col-md-6">
                                <div class="col-md-4 fw-bold">Case Code</div>
                                <div class="col-md-8">: {{ $data->caseCode }}</div>
                            </div>
                            <div class="row col-md-6">
                                <div class="col-md-4 fw-bold">Assigned Area</div>
                                <div class="col-md-8">: {{ $data->scholarshipinfo->area }}</div>
                            </div>
                            <div class="row col-md-6">
                                <div class="col-md-4 fw-bold">Scholar Type</div>
                                <div class="col-md-8">: {{ $data->scholarshipinfo->scholartype }}</div>
                            </div>
                            <div class="row col-md-6">
                                <div class="col-md-4 fw-bold">Start of Scholarship</div>
                                <div class="col-md-8">:
                                    {{ \Carbon\Carbon::parse($data->scholarshipinfo->startdate)->format('m - d - Y') }}
                                </div>
                            </div>
                            <div class="row col-md-6">
                                <div class="col-md-4 fw-bold">End of Scholarship</div>
                                <div class="col-md-8">:
                                    {{ \Carbon\Carbon::parse($data->scholarshipinfo->enddate)->format('m - d - Y') }}
                                </div>
                            </div>
                            <div class="row col-md-6 d-flex align-items-center">
                                <div class="col-md-4 fw-bold">Scholarship Status</div>
                                <div class="col-md-6 d-flex align-items-center">
                                    <span>:&nbsp;</span>
                                    @php
                                        if ($data->scholarshipinfo->scholarshipstatus == 'Continuing') {
                                            $style = 'border-success text-success';
                                        } elseif ($data->scholarshipinfo->scholarshipstatus == 'On-Hold') {
                                            $style = 'border-warning text-warning';
                                        } else {
                                            $style = 'border-danger text-danger';
                                        }
                                    @endphp
                                    <select name="scholarshipstatus"
                                        class="form-select border {{ $style }} fw-bold">
                                        <option value="Continuing"
                                            {{ $data->scholarshipinfo->scholarshipstatus == 'Continuing' ? 'selected' : '' }}>
                                            Continuing
                                        </option>
                                        <option value="On-Hold"
                                            {{ $data->scholarshipinfo->scholarshipstatus == 'On-Hold' ? 'selected' : '' }}>
                                            On-Hold
                                        </option>
                                        <option value="Terminated"
                                            {{ $data->scholarshipinfo->scholarshipstatus == 'Terminated' ? 'selected' : '' }}>
                                            Terminated
                                        </option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <button type="submit" class="btn btn-success">Update</button>
                                </div>
                            </div>
                        </form>
                    </div>

                    <!-- Basic Information Section -->
                    <div class="mb-3">
                        <h5 class="text-success">BASIC INFORMATION</h5>
                        <div class="row">
                            <div class="row col-md-6">
                                <div class="col-md-4 fw-bold">Name</div>
                                <div class="col-md-8">: {{ $data->basicInfo->scLastname }},
                                    {{ $data->basicInfo->scFirstname }} {{ $data->basicInfo->scMiddlename }}</div>
                            </div>
                            <div class="row col-md-6">
                                <div class="col-md-4 fw-bold">Date of Birth</div>
                                <div class="col-md-8">:
                                    {{ \Carbon\Carbon::parse($data->basicInfo->scDateOfBirth)->format('m - d - Y') }}
                                </div>
                            </div>
                            <div class="row col-md-6">
                                <div class="col-md-4 fw-bold">Sex</div>
                                <div class="col-md-8">: {{ $data->basicInfo->scSex }}</div>
                            </div>
                            <div class="row col-md-6">
                                <div class="col-md-4 fw-bold">T-Shirt Size</div>
                                <div class="col-md-8">: {{ $data->clothingSize->scTShirtSize }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- Address Information Section -->
                    <div class="mb-3">
                        <h5 class="text-success">ADDRESS INFORMATION</h5>
                        <div class="row">
                            <div class="row col-md-6">
                                <div class="col-md-4 fw-bold">Home Address</div>
                                <div class="col-md-8">: {{ old('scResidential', $data->addressinfo->scResidential) }}
                                </div>
                            </div>
                            <div class="row col-md-6">
                                <div class="col-md-4 fw-bold">Barangay</div>
                                <div class="col-md-8">: {{ $data->addressinfo->scBarangay }}</div>
                            </div>
                            <div class="row col-md-6">
                                <div class="col-md-4 fw-bold">City/Municipality</div>
                                <div class="col-md-8">: {{ $data->addressinfo->scCity }}</div>
                            </div>
                            <div class="row col-md-6">
                                <div class="col-md-4 fw-bold">Permanent Address</div>
                                <div class="col-md-8">: {{ old('scResidential', $data->addressinfo->scResidential) }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Emergency Contact Section -->
                    <div class="mb-3">
                        <h5 class="text-success">EMERGENCY CONTACT</h5>
                        <div class="row">
                            <div class="row col-md-6">
                                <div class="col-md-4 fw-bold">Name</div>
                                <div class="col-md-8">: {{ old('scGuardianName', $data->basicInfo->scGuardianName) }}
                                </div>
                            </div>
                            <div class="row col-md-6">
                                <div class="col-md-4 fw-bold">Relation</div>
                                <div class="col-md-8">:
                                    {{ old('scRelationToGuardian', $data->basicInfo->scRelationToGuardian) }}</div>
                            </div>
                            <div class="row col-md-6">
                                <div class="col-md-4 fw-bold">Email Address</div>
                                <div class="col-md-8">:
                                    {{ old('scGuardianEmailAddress', $data->basicInfo->scGuardianEmailAddress) }}</div>
                            </div>
                            <div class="row col-md-6">
                                <div class="col-md-4 fw-bold">Contact Number</div>
                                <div class="col-md-8">:
                                    {{ old('scGuardianPhoneNumber', $data->basicInfo->scGuardianPhoneNumber) }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- Educational Background Section -->
                    <div>
                        <h5 class="text-success">EDUCATIONAL BACKGROUND</h5>
                        <div class="row">
                            <div class="row col-md-6">
                                <div class="col-md-4 fw-bold">School Level</div>
                                <div class="col-md-8">: {{ $data->education->scSchoolLevel }}</div>
                            </div>
                            <div class="row col-md-6">
                                <div class="col-md-4 fw-bold">School Name</div>
                                <div class="col-md-8">: {{ $data->education->scSchoolName }}</div>
                            </div>
                            <div class="row col-md-6">
                                <div class="col-md-4 fw-bold">Grade/Year Level</div>
                                <div class="col-md-8">: {{ $data->education->scYearGrade }}</div>
                            </div>
                            <div class="row col-md-6">
                                <div class="col-md-4 fw-bold">Course/Strand</div>
                                <div class="col-md-8">: {{ $data->education->scCourseStrandSec }}</div>
                            </div>
                            <div class="row col-md-6">
                                <div class="col-md-4 fw-bold">Academic Year</div>
                                <div class="col-md-8">: {{ $data->education->scAcademicYear }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card mb-4">
                <div class="card-header bg-success text-white">
                    <span style="font-size: 18px; font-weight: bold"><strong>Grades Report</strong></span>
                </div>
                <div class="ctntable card-body table-responsive">
                    <table class=" table table-bordered">
                        <thead>
                            <tr>
                                <th class="text-center align-middle">#</th>
                                <th class="text-center align-middle">School Year</th>
                                <th class="text-center align-middle">
                                    {{ $data->education->scSchoolLevel == 'College' ? 'Semester' : 'Quarter' }}
                                </th>
                                <th class="text-center align-middle">GWA</th>
                                <th class="text-center align-middle">Status</th>
                                <th class="text-center align-middle">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($grades == null || $grades->isEmpty())
                                <tr>
                                    <td class="text-center align-middle" colspan="6">No data available</td>
                                </tr>
                            @else
                                @foreach ($grades as $index => $grade)
                                    <tr>
                                        <td class="text-center align-middle">{{ $index + 1 }}</td>
                                        <td class="text-center align-middle">{{ $grade->schoolyear }}</td>
                                        <td class="text-center align-middle">{{ $grade->SemesterQuarter }}</td>
                                        <td class="text-center align-middle">{{ $grade->GWA }}</td>
                                        <td class="text-center align-middle">{{ $grade->GradeStatus }}</td>
                                        <td class="text-center align-middle">
                                            <a href="{{ route('scholar-gradesinfo', ['gid' => $grade->gid]) }}"
                                                class="btn btn-success">View</a>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
            @if ($data->education->scSchoolLevel == 'College')
                <div class="card mb-4">
                    <div class="card-header bg-success text-white">
                        <span style="font-size: 18px; font-weight: bold"><strong>Community Service Attendance
                                Report</strong></span>
                    </div>
                    <div class="ctntable card-body table-responsive">
                        <table class=" table table-bordered">
                            <thead>
                                <tr>
                                    <th class="text-center align-middle">#</th>
                                    <th class="text-center align-middle">Date of Event</th>
                                    <th class="text-center align-middle">Time In</th>
                                    <th class="text-center align-middle">Time Out</th>
                                    <th class="text-center align-middle">Facilitator Name</th>
                                    <th class="text-center align-middle">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($csattendances == null || $csattendances->isEmpty())
                                    <tr>
                                        <td class="text-center align-middle" colspan="6">No data available</td>
                                    </tr>
                                @else
                                    @foreach ($csattendances as $index => $attendance)
                                        <tr>
                                            <td class="text-center align-middle">{{ $index + 1 }}</td>
                                            <td class="text-center align-middle">
                                                {{ \Carbon\Carbon::parse($attendance->communityservice->eventdate)->format('F d, Y') }}
                                            </td>
                                            <td class="text-center align-middle">{{ $attendance->timein }}</td>
                                            <td class="text-center align-middle">{{ $attendance->timeout }}</td>
                                            <td class="text-center align-middle">{{ $attendance->facilitator }}</td>
                                            <td class="text-center align-middle">{{ $attendance->csastatus }}</td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
            <div class="card mb-4">
                <div class="card-header bg-success text-white">
                    <span style="font-size: 18px; font-weight: bold"><strong>Humanities Class Attendance
                            Report</strong></span>
                </div>
                <div class="ctntable card-body table-responsive">
                    <table class=" table table-bordered">
                        <thead>
                            <tr>
                                <th class="text-center align-middle">#</th>
                                <th class="text-center align-middle">Date of Event</th>
                                <th class="text-center align-middle">Time In</th>
                                <th class="text-center align-middle">Time Out</th>
                                <th class="text-center align-middle">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($hcattendances == null || $hcattendances->isEmpty())
                                <tr>
                                    <td class="text-center align-middle" colspan="6">No data available</td>
                                </tr>
                            @else
                                @foreach ($hcattendances as $index => $attendance)
                                    <tr>
                                        <td class="text-center align-middle">{{ $index + 1 }}</td>
                                        <td class="text-center align-middle">
                                            {{ \Carbon\Carbon::parse($attendance->humanitiesclass->hcdate)->format('F d, Y') }}
                                        </td>
                                        <td class="text-center align-middle">{{ $attendance->timein }}</td>
                                        <td class="text-center align-middle">{{ $attendance->timeout }}</td>
                                        <td class="text-center align-middle">{{ $attendance->status }}</td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <script src="{{ asset('js/headercontrol.js') }}"></script>
</body>

</html>
