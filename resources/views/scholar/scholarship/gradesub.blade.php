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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
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
            <p class="desc">Submit your GWA and the scanned copy of pdf file of your grades.</p>
        </div>

        <form action="{{ route('gradesub.post') }}" class="container text-center" method="POST"
            enctype="multipart/form-data">
            @csrf

            <!-- Display general error messages -->
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <span>{{ $error }}</span><br>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="row mb-3">
                <!-- Dropdown for Quarter/Semester -->
                <div class="{{ $institution->schoollevel == 'College' ? 'col-md-4' : 'col-md-6' }} mb-2">
                    <select class="form-select" aria-label="qtrsem" name="semester" required>
                        <option value="" disabled selected hidden>
                            Select {{ $institution->academiccycle == 'Quarter' ? 'Quarter' : 'Term' }}
                        </option>
                        @if ($institution->academiccycle == 'Quarter')
                            <option value="1st Quarter">1ST QUARTER</option>
                            <option value="2nd Quarter">2ND QUARTER</option>
                            <option value="3rd Quarter">3RD QUARTER</option>
                            <option value="4th Quarter">4TH QUARTER</option>
                        @else
                            <option value="1st Semester">1ST SEMESTER</option>
                            <option value="2nd Semester">2ND SEMESTER</option>
                            @if ($institution->academiccycle == 'Trimester')
                                <option value="3rd Semester">3RD SEMESTER</option>
                            @endif
                        @endif
                    </select>
                </div>

                @if ($institution->schoollevel == 'College')
                    <!-- Input for GWA -->
                    <div class="col-md-4 mb-2">
                        <input type="number" class="form-control" id="gwa" name="gwa" min="1"
                            max="{{ $institution->highestgwa != 100 ? '5' : '100' }}"
                            placeholder="General Weighted Average" value="{{ old('gwa') }}"
                            {{ $institution->schoollevel == 'College' ? 'required' : '' }} step="0.01">
                    </div>
                @endif

                <!-- File Input for Grade Image -->
                <div class="{{ $institution->schoollevel == 'College' ? 'col-md-4' : 'col-md-6' }}">
                    <input type="file" class="form-control" name="gradeImage"
                        accept="application/pdf, image/jpeg, image/png" required>
                </div>
            </div>
            @if ($institution->schoollevel != 'College')
                <div class="row mb-3">
                    <div class="col-md-6 mb-2">
                        <input type="number" class="form-control" id="genave" name="genave" min="1"
                            max="{{ $institution->highestgwa != 100 ? '5' : '100' }}" placeholder="General Average"
                            value="{{ old('genave') }}" {{ $institution->schoollevel != 'College' ? 'required' : '' }}
                            step="0.01">
                    </div>
                    <div class="col-md-6">
                        <input type="text" class="form-control" id="gwaconduct" name="gwaconduct" minlength="1"
                            placeholder="Conduct" value="{{ old('gwaconduct') }}"
                            {{ $institution->schoollevel != 'College' ? 'required' : '' }}>
                    </div>
                </div>
                <div class="row fw-bold text-center mb-2">
                    <span>For Chinese Subject</span>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6 mb-2">
                        <input type="number" class="form-control" id="chinesegenave" name="chinesegenave"
                            min="1" max="{{ $institution->highestgwa != 100 ? '5' : '100' }}"
                            placeholder="General Average" value="{{ old('chinesegenave') }}" step="0.01">
                    </div>
                    <div class="col-md-6">
                        <input type="text" class="form-control" id="chineseconduct" name="chineseconduct"
                            minlength="1" placeholder="Conduct" value="{{ old('chineseconduct') }}">
                    </div>
                </div>
            @endif

            <!-- Submit Button -->
            <button type="submit" class="btn-submit fw-bold">Submit</button>
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
                        @if ($educ->scSchoolLevel == 'College')
                            <th class="text-center align-middle">GWA</th>
                        @else
                            <th class="text-center align-middle">GWA</th>
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
                            @if ($educ->scSchoolLevel != 'College')
                                <td>{{ $grade->GWAConduct ?? '' }}</td>
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

    <script src="{{ asset('js/scholar.js') }}"></script>
</body>

</html>
