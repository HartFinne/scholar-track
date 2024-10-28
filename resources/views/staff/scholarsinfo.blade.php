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
            <div class="card mb-4">
                <div class="card-header bg-success text-white">
                    <span style="font-size: 18px; font-weight: bold"><strong>Personal Information</strong></span>
                </div>
                <div class="card-body">
                    <!-- Scholarship Information Section -->
                    <div class="mb-3">
                        <h5 class="text-success">SCHOLARSHIP INFORMATION</h5>
                        <div class="row">
                            <div class="col-md-6"><strong>Case Code:</strong> {{ $data->caseCode }}</div>
                            <div class="col-md-6"><strong>Assigned Area:</strong> {{ $data->scholarshipinfo->area }}
                            </div>
                            <div class="col-md-6"><strong>Scholar Type:</strong>
                                {{ $data->scholarshipinfo->scholartype }}</div>
                            <div class="col-md-6"><strong>Start of Scholarship:</strong>
                                {{ \Carbon\Carbon::parse($data->scholarshipinfo->startdate)->format('m - d - Y') }}
                            </div>
                            <div class="col-md-6"><strong>End of Scholarship:</strong>
                                {{ \Carbon\Carbon::parse($data->scholarshipinfo->enddate)->format('m - d - Y') }}</div>
                            <div class="col-md-6"><strong>Scholarship Status:</strong>
                                {{ $data->scholarshipinfo->scholarshipstatus }}</div>
                        </div>
                    </div>

                    <!-- Basic Information Section -->
                    <div class="mb-3">
                        <h5 class="text-success">BASIC INFORMATION</h5>
                        <div class="row">
                            <div class="col-md-6"><strong>Name:</strong> {{ $data->basicInfo->scLastname }},
                                {{ $data->basicInfo->scFirstname }} {{ $data->basicInfo->scMiddlename }}</div>
                            <div class="col-md-6"><strong>Date of Birth:</strong>
                                {{ \Carbon\Carbon::parse($data->basicInfo->scDateOfBirth)->format('m - d - Y') }}</div>
                            <div class="col-md-6"><strong>Sex:</strong> {{ $data->basicInfo->scSex }}</div>
                            <div class="col-md-6"><strong>T-Shirt Size:</strong>
                                {{ $data->clothingSize->scTShirtSize }}</div>
                        </div>
                    </div>

                    <!-- Address Information Section -->
                    <div class="mb-3">
                        <h5 class="text-success">ADDRESS INFORMATION</h5>
                        <div class="row">
                            <div class="col-md-6"><strong>Home Address:</strong>
                                {{ old('scResidential', $data->addressinfo->scResidential) }}</div>
                            <div class="col-md-6"><strong>Barangay:</strong> {{ $data->addressinfo->scBarangay }}</div>
                            <div class="col-md-6"><strong>City/Municipality:</strong> {{ $data->addressinfo->scCity }}
                            </div>
                            <div class="col-md-6"><strong>Permanent Address:</strong>
                                {{ old('scResidential', $data->addressinfo->scResidential) }}</div>
                        </div>
                    </div>

                    <!-- Emergency Contact Section -->
                    <div class="mb-3">
                        <h5 class="text-success">EMERGENCY CONTACT</h5>
                        <div class="row">
                            <div class="col-md-6"><strong>Name:</strong>
                                {{ old('scGuardianName', $data->basicInfo->scGuardianName) }}</div>
                            <div class="col-md-6"><strong>Relation:</strong>
                                {{ old('scRelationToGuardian', $data->basicInfo->scRelationToGuardian) }}</div>
                            <div class="col-md-6"><strong>Email Address:</strong>
                                {{ old('scGuardianEmailAddress', $data->basicInfo->scGuardianEmailAddress) }}</div>
                            <div class="col-md-6"><strong>Contact Number:</strong>
                                {{ old('scGuardianPhoneNumber', $data->basicInfo->scGuardianPhoneNumber) }}</div>
                        </div>
                    </div>

                    <!-- Educational Background Section -->
                    <div>
                        <h5 class="text-success">EDUCATIONAL BACKGROUND</h5>
                        <div class="row">
                            <div class="col-md-6"><strong>School Level:</strong> {{ $data->education->scSchoolLevel }}
                            </div>
                            <div class="col-md-6"><strong>School Name:</strong> {{ $data->education->scSchoolName }}
                            </div>
                            <div class="col-md-6"><strong>Grade/Year Level:</strong>
                                {{ $data->education->scYearGrade }}</div>
                            <div class="col-md-6"><strong>Course/Strand:</strong>
                                {{ $data->education->scCourseStrandSec }}</div>
                            <div class="col-md-6"><strong>Academic Year:</strong>
                                {{ $data->education->scAcademicYear }}</div>
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
                                        <td class="text-center align-middle">School Year</td>
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
                                        <td class="text-center align-middle">{{ $attendance->status }}</td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
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
