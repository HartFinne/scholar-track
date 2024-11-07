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

        <form action="{{ route('gradesub.post') }}" class="grade-form text-center" method="POST"
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

            <!-- Semester Selection -->
            <select class="sem" aria-label="qtrsem" name="semester" required>
                @if ($educ->scSchoolLevel == 'College' || $educ->scSchoolLevel == 'Senior High')
                    <option value="" disabled selected hidden>Semester</option>
                    <option value="1st Semester">1ST SEMESTER</option>
                    <option value="2nd Semester">2ND SEMESTER</option>
                @else
                    <option value="" disabled selected hidden>Quarter</option>
                    <option value="1st Semester">1ST QUARTER</option>
                    <option value="2nd Semester">2ND QUARTER</option>
                    <option value="2nd Semester">3RD QUARTER</option>
                    <option value="2nd Semester">4TH QUARTER</option>
                @endif
            </select>

            <!-- GWA Input -->
            <input type="number" class="gwa" id="gwa" name="gwa" placeholder="General Weighted Average"
                value="{{ old('gwa') }}" required>

            <!-- Grade Image Input -->
            <input type="file" class="file" name="gradeImage" accept="application/pdf, image/jpeg, image/png"
                required>

            <!-- Submit Button -->
            <button type="submit" class="btn-submit fw-bold">Submit</button>
        </form>



        <div class="status">
            <p class="table-title">Grades Status</p>
            <div class="filter" id="filter-grades">
                <form action="{{ route('gradesub') }}" method="GET" id="filter-form">
                    <button type="submit" name="status" value="all" class="filter-btn {{ request('status', 'all') == 'all' ? 'active' : '' }}">All</button>
                    <button type="submit" name="status" value="Pending" class="filter-btn {{ request('status') == 'Pending' ? 'active' : '' }}">Pending</button>
                    <button type="submit" name="status" value="Passed" class="filter-btn {{ request('status') == 'Passed' ? 'active' : '' }}">Passed</button>
                    <button type="submit" name="status" value="Failed GWA" class="filter-btn {{ request('status') == 'Failed GWA' ? 'active' : '' }}">Failed GWA</button>
                    <button type="submit" name="status" value="Failed Grade" class="filter-btn {{ request('status') == 'Failed Grade' ? 'active' : '' }}">Failed Grade</button>
                    <button type="submit" name="status" value="Mismatched GWA" class="filter-btn {{ request('status') == 'Mismatched GWA' ? 'active' : '' }}">Mismatched GWA</button>
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
                            <td>{{ $grade->GradeStatus }}</td>
                            <td><a href="{{ route('gradesinfo', ['id' => $grade->gid]) }}" id="view">View</a></td>
                        </tr>
                    @endforeach

                </tbody>
            </table>
        </div>
    </div>

    <script src="{{ asset('js/scholar.js') }}"></script>
</body>

</html>
