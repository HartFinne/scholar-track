<!DOCTYPE html>
<html lang="en">

<head>
    <title>Scholarship Overview</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="{{ asset('css/scholar.css') }}">
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


    <!-- MAIN -->
    <div class="ctn-main">
        <a href="{{ route('schome') }}" class="goback">&lt Go back</a>
        <h1 class="title">Scholarship Overview</h1>
        <div class="sc-info">
            <div class="sc-info-1">
                <div class="text">
                    <p class="casecode" id="casecode">{{ $user->caseCode }}</p>
                    <p class="fullname" id="fullname">
                        {{ $user->basicInfo->scLastname }}, {{ $user->basicInfo->scFirstname }}
                        {{ $user->basicInfo->scMiddlename }}</p>
                </div>
                <div class="text">
                    <p class="sc-status">Scholarship Status: <span>{{ $user->basicInfo->scScholarshipStatus }}</span>
                    </p>
                    <p class="sc-type">Old Scholar</p>
                </div>
            </div>
            <div class="sc-info-2">
                <div class="text">
                    <p class="school">{{ $user->education->scSchoolName }}</p>
                    <p class="yrlevel">{{ $user->education->scYearLevel }}</p>
                    <p class="course">{{ $user->education->scCourseStrand }}</p>
                </div>



            </div>
        </div>

        <div class="overview-graph">
            <div class="card1">
                <p>Academic Performance</p>
            </div>
            <div class="card2">
                <p>Community Service</p>
            </div>
        </div>

        <div class="sc-renewal close">
            <hr>
            <div class="text">
                <h4>SCHOLARSHIP RENEWAL IS NOT YET OPEN.</h4>
            </div>
            <hr>
        </div>

        <div class="sc-renewal open">
            <hr>
            <div class="text">
                <h4>SCHOLARSHIP RENEWAL IS NOW OPEN.</h4>
                <button onclick="window.location.href='screnewal.html'">Renew Scholarship</button>
            </div>
            <hr>
        </div>

        <p class="sub-title">Summary of Penalties/Deductions</p>
        <div class="filter">
            <button class="filter-btn">All</button>
            <button class="filter-btn">1st offense</button>
            <button class="filter-btn">2nd offense</button>
            <button class="filter-btn">3rd offense</button>
            <button class="filter-btn">4th offense</button>
        </div>
        <div class="ctn-table table-responsive">
            <table class="table table-bordered" id="table">
                <thead>
                    <tr>
                        <th class="text-center align-middle">Date</th>
                        <th class="text-center align-middle">Condition</th>
                        <th class="text-center align-middle">Penalty</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($penalties as $penalty)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($penalty->dateOfPenalty)->format('m/d/Y') }}</td>
                            <!-- Formatting the date -->
                            <td>{{ $penalty->pendCondition }}</td>
                            <td>{{ $penalty->penalty }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <p class="sub-title">Scholarship Renewal</p>
        <div class="filter">
            <button class="filter-btn">All</button>
            <button class="filter-btn">Pending</button>
            <button class="filter-btn">Accepted</button>
        </div>
        <div class="ctn-table table-responsive">
            <table class="table table-bordered" id="table">
                <thead>
                    <tr>
                        <th class="text-center align-middle">Date Submitted</th>
                        <th class="text-center align-middle">School Year</th>
                        <th class="text-center align-middle">Status</th>
                        <th class="text-center align-middle">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>09/11/2024</td>
                        <td>--</td>
                        <td>--</td>
                        <td><a href="{{ route('subrenewal') }}" id="view">View</a></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <script src="{{ asset('js/scholar.js') }}"></script>
</body>

</html>
