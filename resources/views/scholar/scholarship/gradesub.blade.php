<!DOCTYPE html>
<html lang="en">

<head>
    <title>Grades Submission</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="{{ asset('css/overview.css') }}">
    <link rel="stylesheet" href="{{ asset('css/grades.css') }}">
    <link rel="stylesheet" href="{{ asset('css/partial.css') }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</head>

<body>
    <!-- Include Sidebar -->
    @include('partials._sidebar')

    <!-- Include Navbar -->
    @include('partials._navbar')
    <x-alert />
    <!-- MAIN -->
    <div class="ctn-main">
        <div class="text-center">
            <h1 class="sub-title">Grades Submission</h1>
            <p class="desc">Submit your General Weighted Average (GWA) and the scanned copy of pdf file of your
                grades.</p>
        </div>

        <form action="{{ route('gradesub.post') }}" class="container text-center" method="POST"
            enctype="multipart/form-data" id="gradeForm">
            @csrf

            <div class="scinfo">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="fullName" class="fw-bold">Full Name</label>
                        <input type="text" class="form-control" id="fullName" name="fullName"
                            value="{{ $user->basicInfo->scLastname }}, {{ $user->basicInfo->scFirstname }} {{ $user->basicInfo->scMiddlename }}"
                            readonly>
                    </div>

                    <div class="col-md-6">
                        <label for="yearLevel" class="fw-bold">Year Level</label>
                        <input type="text" class="form-control" id="yearLevel" name="yearLevel"
                            value="{{ $user->education->scYearGrade }}" readonly>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="acadYear" class="fw-bold">Academic Year</label>
                        <input type="text" class="form-control" id="acadYear" name="acadYear"
                            value="S.Y. {{ $user->education->scAcademicYear }}" readonly>
                    </div>

                    <div class="col-md-6">
                        <label for="school" class="fw-bold">School</label>
                        <input type="text" class="form-control" id="school" name="school"
                            value="{{ $user->education->scSchoolName }}" readonly>
                    </div>
                </div>


                <div class="row mb-3">
                    <div class="{{ $institution->schoollevel == 'College' ? 'col-md-5' : 'col-md-6' }} mb-2">
                        <label for="sem" class="fw-bold text-left">{{ $institution->academiccycle }}</label>
                        <input type="text" class="form-control" id="sem" name="semester"
                            value="{{ $term }}" readonly>
                    </div>

                    @if ($institution->schoollevel == 'College')
                        <div class="col-md-2 mb-2">
                            <label for="gwa" class="fw-bold text-left">GWA</label>
                            <input type="number" class="form-control" id="gwa" name="gwa" min="1"
                                max="{{ $institution->highestgwa != 100 ? '5' : '100' }}"
                                value="{{ old('gwa') ?? '' }}"
                                {{ $institution->schoollevel == 'College' ? 'required' : '' }} step="0.01">
                        </div>
                    @endif

                    <div class="{{ $institution->schoollevel == 'College' ? 'col-md-5' : 'col-md-6' }}">
                        <label for="grades" class="fw-bold text-left">Copy of Report Card</label>
                        <input type="file" id="grades" class="form-control" name="gradeImage"
                            accept="application/pdf, image/jpeg, image/png" required>
                    </div>

                    @if ($institution->schoollevel != 'College')
                        <div class="{{ $institution->schoollevel == 'Senior High' ? 'col-md-3' : 'col-md-1' }} mb-2">
                            <label for="genave" class="fw-bold text-left">GWA</label>
                            <input type="number" class="form-control" id="genave" name="genave" min="1"
                                max="{{ $institution->highestgwa != 100 ? '5' : '100' }}" value="{{ old('genave') }}"
                                {{ $institution->schoollevel != 'College' ? 'required' : '' }} step="0.01">
                        </div>
                        @if (in_array($user->education->scSchoolLevel, ['Junior High', 'Elementary']))
                            <div class="col-md-3 mb-2">
                                <label for="gwaremark" class="fw-bold text-left">GWA Remark</label>
                                <input type="text" class="form-control" id="gwaremark" name="gwaremark" readonly>
                            </div>
                        @endif
                        <script>
                            document.addEventListener('input', function() {
                                const gwa = parseFloat(document.getElementById('genave').value);
                                const gwaremarkInput = document.getElementById('gwaremark');
                                let gwaremark = '';

                                if (gwa < 75) {
                                    gwaremark = 'Did Not Meet Expectations';
                                } else if (gwa < 80) {
                                    gwaremark = 'Fairly Satisfactory';
                                } else if (gwa < 85) {
                                    gwaremark = 'Satisfactory';
                                } else if (gwa < 90) {
                                    gwaremark = 'Very Satisfactory';
                                } else {
                                    gwaremark = 'Outstanding';
                                }

                                gwaremarkInput.value = gwaremark;
                            });
                        </script>
                        <div class="col-md-3">
                            <label for="gwaconduct" class="fw-bold text-left">Conduct</label>
                            <select name="gwaconduct" id="gwaconduct" required class="form-select">
                                <option value="" selected hidden>Select conduct</option>
                                <option value="AO"
                                    {{ old('gwaconduct') == 'AO (Always Observed)' ? 'selected' : '' }}>AO
                                    (Always
                                    Observed)</option>
                                <option value="SO"
                                    {{ old('gwaconduct') == 'SO (Sometimes Observed)' ? 'selected' : '' }}>SO
                                    (Sometimes Observed)</option>
                                <option value="RO"
                                    {{ old('gwaconduct') == 'RO (Rarely Observed)' ? 'selected' : '' }}>RO
                                    (Rarely
                                    Observed)</option>
                                <option value="NO"
                                    {{ old('gwaconduct') == 'NO (Not Observed)' ? 'selected' : '' }}>NO (Not
                                    Observed)
                                </option>
                            </select>
                            {{-- <input type="text" class="form-control" id="gwaconduct" name="gwaconduct"
                            minlength="1" value="{{ old('gwaconduct') }}"
                            {{ $institution->schoollevel != 'College' ? 'required' : '' }}> --}}
                        </div>
                        <div class="{{ $institution->schoollevel == 'Senior High' ? 'col-md-3' : 'col-md-2' }} mb-2">
                            <label for="chinesegenave" class="fw-bold text-left">Chinese Subject GWA</label>
                            <input type="number" class="form-control" id="chinesegenave" name="chinesegenave"
                                min="1" max="{{ $institution->highestgwa != 100 ? '5' : '100' }}"
                                value="{{ old('chinesegenave') }}" step="0.01">
                        </div>
                        <div class="col-md-3">
                            <label for="gwaconduct" class="fw-bold text-left">Chinese Subject Conduct</label>
                            <select name="chineseconduct" id="chineseconduct" class="form-select">
                                <option value="" selected hidden>Select conduct</option>
                                <option value="AO"
                                    {{ old('chineseconduct') == 'AO (Always Observed)' ? 'selected' : '' }}>AO
                                    (Always
                                    Observed)</option>
                                <option value="SO"
                                    {{ old('chineseconduct') == 'SO (Sometimes Observed)' ? 'selected' : '' }}>
                                    SO
                                    (Sometimes Observed)</option>
                                <option value="RO"
                                    {{ old('chineseconduct') == 'RO (Rarely Observed)' ? 'selected' : '' }}>RO
                                    (Rarely
                                    Observed)</option>
                                <option value="NO"
                                    {{ old('chineseconduct') == 'NO (Not Observed)' ? 'selected' : '' }}>NO
                                    (Not
                                    Observed)
                                </option>
                            </select>
                            {{-- <input type="text" class="form-control" id="chineseconduct" name="chineseconduct"
                            minlength="1" placeholder="Conduct" value="{{ old('chineseconduct') }}"> --}}
                        </div>
                    @endif
                </div>

                <!-- Submit Button -->
                <button type="submit" class="btn-submit fw-bold">Submit</button>
            </div>
        </form>

        <div class="status mt-2">
            <p class="table-title">Grades Status</p>
            <div class="filter" id="filter-grades">
                <form action="{{ route('gradesub') }}" method="GET" id="filter-form">
                    <button type="submit" name="status" value="all"
                        class="filter-btn {{ request('status', 'all') == 'all' ? 'active' : '' }}">All</button>
                    <button type="submit" name="status" value="Pending"
                        class="filter-btn {{ request('status') == 'Pending' ? 'active' : '' }}">Pending</button>
                    <button type="submit" name="status" value="Passed"
                        class="filter-btn {{ request('status') == 'Passed' ? 'active' : '' }}">Passed</button>
                    <button type="submit" name="status" value="Failed"
                        class="filter-btn {{ request('status') == 'Failed' ? 'active' : '' }}">Failed</button>
                </form>
            </div>
        </div>

        <div class="ctn-table table-responsive">
            <table class="table table-bordered" id="table">
                <thead>
                    <tr>
                        <th class="text-center align-middle">School Year</th>
                        <th class="text-center align-middle">Semester</th>
                        <th class="text-center align-middle">GWA</th>
                        @if (in_array($user->education->scSchoolLevel, ['Junior High', 'Elementary']))
                            <th class="text-center align-middle">GWA Remark</th>
                        @endif
                        @if ($user->education->scSchoolLevel != 'College')
                            <th class="text-center align-middle">Conduct</th>
                            <th class="text-center align-middle">GWA (Chinese Subject)</th>
                            <th class="text-center align-middle">Conduct (Chinese Subject)</th>
                        @endif
                        <th class="text-center align-middle">Status</th>
                        <th class="text-center align-middle">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($grades as $grade)
                        <tr>
                            <td>S.Y. {{ $grade->schoolyear }}</td>
                            <td>{{ $grade->SemesterQuarter }}</td>
                            <td>{{ $grade->GWA }}</td>
                            @php
                                if ($grade->GWA < 75) {
                                    $gwaremark = 'Did Not Meet Expectations';
                                } elseif ($grade->GWA < 80) {
                                    $gwaremark = 'Fairly Satisfactory';
                                } elseif ($grade->GWA < 85) {
                                    $gwaremark = 'Satisfactory';
                                } elseif ($grade->GWA < 90) {
                                    $gwaremark = 'Very Satisfactory';
                                } else {
                                    $gwaremark = 'Outstanding';
                                }
                            @endphp

                            @if (in_array($user->education->scSchoolLevel, ['Junior High', 'Elementary']))
                                <td>{{ $gwaremark }}</td>
                            @endif

                            @if ($user->education->scSchoolLevel != 'College')
                                <td>{{ $grade->GWAConduct ?? 'N/A' }}</td>
                                <td>{{ $grade->ChineseGWA ?? 'N/A' }}</td>
                                <td>{{ $grade->ChineseGWAConduct ?? 'N/A' }}</td>
                            @endif
                            <td>{{ $grade->GradeStatus }}</td>
                            <td><a href="{{ route('gradesinfo', ['id' => $grade->gid]) }}" id="view">View</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Loading Modal -->
    <div class="modal fade" id="loadingModal" tabindex="-1" aria-labelledby="loadingModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border border-success rounded">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold" id="loadingModalLabel">Please Wait</h5>
                </div>
                <div class="modal-body text-center">
                    <p>The system is verifying the data you have submitted...</p>
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/scholar.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('gradeForm');
            const loadingModal = new bootstrap.Modal(document.getElementById('loadingModal'));

            form.addEventListener('submit', function(event) {
                // Show the loading modal
                loadingModal.show();
            });
        });
    </script>
</body>

</html>
