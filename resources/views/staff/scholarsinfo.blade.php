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
    <x-alert />
    <!-- MAIN CONTENT -->
    <div class="ctnmain">
        <div class="container d-flex align-items-center">
            <div class="col-md-11">
                <span class="fw-bold text-success h1"><strong>Scholar's Information</strong></span>
            </div>
            <div class="col-md-1">
                <a href="{{ route('scholars-overview') }}" class="btn btn-success">&lt Go back</a>
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
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-4 fw-bold">Case Code</div>
                                    <div class="col-md-8">{{ $data->caseCode }}</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-4 fw-bold">Assigned Area</div>
                                    <div class="col-md-8">{{ $data->scholarshipinfo->area }}</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-4 fw-bold">Scholar Type</div>
                                    <div class="col-md-8">{{ $data->scholarshipinfo->scholartype }}</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-4 fw-bold">Start of Scholarship</div>
                                    <div class="col-md-8">
                                        {{ \Carbon\Carbon::parse($data->scholarshipinfo->startdate)->format('F j, Y') }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-4 fw-bold">End of Scholarship</div>
                                    <div class="col-md-8">
                                        {{ \Carbon\Carbon::parse($data->scholarshipinfo->enddate)->format('F j, Y') }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-4 fw-bold">Scholarship Status</div>
                                    <div class="col-md-4">
                                        @php
                                            $style = '';
                                            if ($data->scholarshipinfo->scholarshipstatus == 'Continuing') {
                                                $style = 'border-success text-success';
                                            } elseif ($data->scholarshipinfo->scholarshipstatus == 'On-Hold') {
                                                $style = 'border-warning text-warning';
                                            } else {
                                                $style = 'border-danger text-danger';
                                            }
                                        @endphp
                                        <span
                                            class="form-control fw-bold {{ $style }}">{{ $data->scholarshipinfo->scholarshipstatus }}</span>
                                    </div>
                                    <div class="col-md-4">
                                        <button type="button" class="btn btn-success w-100" data-bs-toggle="modal"
                                            data-bs-target="#updateScholarshipStatusModal">
                                            Update
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Basic Information Section -->
                    <div class="mb-3">
                        <h5 class="text-success">BASIC INFORMATION</h5>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-4 fw-bold">Name</div>
                                    <div class="col-md-8">{{ $data->basicInfo->scLastname }},
                                        {{ $data->basicInfo->scFirstname }} {{ $data->basicInfo->scMiddlename }}</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-4 fw-bold">Chinese Name</div>
                                    <div class="col-md-8">{{ $data->basicInfo->scChinesename }}</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-4 fw-bold">Date of Birth</div>
                                    <div class="col-md-8">
                                        {{ \Carbon\Carbon::parse($data->basicInfo->scDateOfBirth)->format('F j, Y') }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-4 fw-bold">Age</div>
                                    <div class="col-md-8">{{ $data->basicInfo->scAge }}</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-4 fw-bold">Sex</div>
                                    <div class="col-md-8">{{ $data->basicInfo->scSex }}</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-4 fw-bold">Indigenous Group</div>
                                    <div class="col-md-8">{{ $data->basicInfo->scIndigenousgroup }}</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-4 fw-bold">Occupation</div>
                                    <div class="col-md-8">{{ $data->basicInfo->scOccupation }}</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-4 fw-bold">Income</div>
                                    <div class="col-md-8">{{ $data->basicInfo->scIncome }}</div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="row">
                                    <div class="col-md-2 fw-bold">Facebook Link</div>
                                    <div class="col-md-8">
                                        <a href="{{ $data->basicInfo->scFblink }}" target="_blank"
                                            class="link link-success">
                                            {{ $data->basicInfo->scFblink }}
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="row">
                                    <div class="col-md-2 fw-bold">Email Address</div>
                                    <div class="col-md-8">
                                        <a href="https://mail.google.com/mail/?view=cm&fs=1&to={{ $data->scEmail }}"
                                            class="link link-success" target="_blank">
                                            {{ $data->scEmail }}
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-4 fw-bold">Contact Number</div>
                                    <div class="col-md-8">+{{ $data->scPhoneNum }}</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-4 fw-bold">T-Shirt Size</div>
                                    <div class="col-md-8">{{ $data->clothingSize->scTShirtSize }}</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-4 fw-bold">Pants Size</div>
                                    <div class="col-md-8">{{ $data->clothingSize->scPantsSize }}</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-4 fw-bold">Jogging Pants Size</div>
                                    <div class="col-md-8">{{ $data->clothingSize->scJoggingPantSize }}</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-4 fw-bold">Shoes Size</div>
                                    <div class="col-md-8">{{ $data->clothingSize->scShoesSize }}</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-4 fw-bold">Slippers Size</div>
                                    <div class="col-md-8">{{ $data->clothingSize->scSlipperSize }}</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Address Information Section -->
                    <div class="mb-3">
                        <h5 class="text-success">ADDRESS INFORMATION</h5>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-4 fw-bold">Home Address</div>
                                    <div class="col-md-8">{{ $data->addressinfo->scResidential }}</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-4 fw-bold">Region</div>
                                    <div class="col-md-8" id="region"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-4 fw-bold">City/Municipality</div>
                                    <div class="col-md-8" id="city"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-4 fw-bold">Barangay</div>
                                    <div class="col-md-8" id="brgy"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Emergency Contact Section -->
                    <div class="mb-3">
                        <h5 class="text-success">EMERGENCY CONTACT</h5>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-4 fw-bold">Name</div>
                                    <div class="col-md-8">
                                        {{ old('scGuardianName', $data->basicInfo->scGuardianName) }}</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-4 fw-bold">Relation</div>
                                    <div class="col-md-8">
                                        {{ old('scRelationToGuardian', $data->basicInfo->scRelationToGuardian) }}</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-4 fw-bold">Email Address</div>
                                    <div class="col-md-8">
                                        {{ old('scGuardianEmailAddress', $data->basicInfo->scGuardianEmailAddress) }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-4 fw-bold">Contact Number</div>
                                    <div class="col-md-8">
                                        +{{ old('scGuardianPhoneNumber', $data->basicInfo->scGuardianPhoneNumber) }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Educational Background Section -->
                    <div class="mb-3">
                        <h5 class="text-success">EDUCATIONAL BACKGROUND</h5>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-4 fw-bold">School Level</div>
                                    <div class="col-md-8">{{ $data->education->scSchoolLevel }}</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-4 fw-bold">School Name</div>
                                    <div class="col-md-8">{{ $data->education->scSchoolName }}</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-4 fw-bold">Grade/Year Level</div>
                                    <div class="col-md-8">{{ $data->education->scYearGrade }}</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-4 fw-bold">Course/Strand</div>
                                    <div class="col-md-8">{{ $data->education->scCourseStrandSec }}</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-4 fw-bold">Academic Year</div>
                                    <div class="col-md-8">{{ $data->education->scAcademicYear }}</div>
                                </div>
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
                                @if ($data->education->scSchoolLevel != 'College')
                                    <th class="text-center align-middle">Conduct</th>
                                    <th class="text-center align-middle">GWA (Chinese Subject)</th>
                                    <th class="text-center align-middle">Conduct (Chinese Subject)</th>
                                @endif
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
                                        @if ($data->education->scSchoolLevel != 'College')
                                            <td class="text-center align-middle">{{ $grade->GWAConduct }}</td>
                                            <td class="text-center align-middle">{{ $grade->ChineseGWA }}</td>
                                            <td class="text-center align-middle">{{ $grade->ChineseGWAConduct }}</td>
                                        @endif
                                        <td class="text-center align-middle">{{ $grade->GradeStatus }}</td>
                                        <td class="text-center align-middle">
                                            <a href="{{ route('scholar-gradesinfo', ['gid' => $grade->gid]) }}"
                                                class="btn btn-sm btn-success"><i class="fas fa-eye"></i></a>
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
    <!-- Update Scholarship Status Modal -->
    <div class="modal fade" id="updateScholarshipStatusModal" tabindex="-1"
        aria-labelledby="updateScholarshipStatusModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-success text-white fw-bold">
                    <h5 class="modal-title" id="updateScholarshipStatusModalLabel">Update Scholarship Status</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('updatescholarshipstatus', $data->caseCode) }}" method="POST">
                    <div class="modal-body">
                        @csrf
                        <div class="row align-items-center">
                            <div class="col-6">
                                <label for="scholarshipstatus" class="form-label fw-bold">Select New Status</label>
                            </div>
                            <div class="col-6">
                                <select name="scholarshipstatus" id="scholarshipstatus"
                                    class="form-select border-success">
                                    <option value="Continuing"
                                        {{ $data->scholarshipinfo->scholarshipstatus == 'Continuing' ? 'selected' : '' }}>
                                        Continuing</option>
                                    <option value="Terminated"
                                        {{ $data->scholarshipinfo->scholarshipstatus == 'Terminated' ? 'selected' : '' }}>
                                        Terminated</option>
                                    <option value="On-Hold"
                                        {{ $data->scholarshipinfo->scholarshipstatus == 'On-Hold' ? 'selected' : '' }}>
                                        On-Hold</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" data-bs-dismiss="modal" class="btn btn-secondary">Cancel</button>
                        <button type="submit" class="btn btn-success">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        const regionCode = '{{ $data->addressInfo->scRegion }}';
        const cityCode = '{{ $data->addressInfo->scCity }}';
        const barangayCode = '{{ $data->addressInfo->scBarangay }}';

        // Base API URLs
        const regionApi = 'https://psgc.gitlab.io/api/regions/';
        const cityApi = `https://psgc.gitlab.io/api/regions/${regionCode}/cities-municipalities/`;
        const barangayApi = `https://psgc.gitlab.io/api/cities-municipalities/${cityCode}/barangays/`;

        // Populate region
        fetch(regionApi + regionCode)
            .then(response => response.json())
            .then(data => {
                document.getElementById('region').textContent = data.name || 'Unknown Region';
            })
            .catch(error => {
                console.error('Error fetching region:', error);
                document.getElementById('region').textContent = 'Error loading region';
            });

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
    <script src="{{ asset('js/headercontrol.js') }}"></script>
</body>

</html>
