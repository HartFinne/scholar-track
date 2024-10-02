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
        <hr>
        <div class="sc-info">
            <div class="sc-info-1">
                <div class="text">
                    <p class="casecode" id="casecode">2021-02312-MINDONG</p>
                    <p class="fullname" id="fullname">DELA CRUZ, JUAN SANTOS</p>
                </div>
                <div class="text">
                    <p class="sc-status">Scholarship Status: <span>CONTINUING</span></p>
                    <p class="sc-type">Old Scholar</p>
                </div>
            </div>
            <div class="sc-info-2">
                <div class="text">
                    <p class="school">Polytechnic University of the Philippines</p>
                    <p class="yrlevel">First Year</p>
                    <p class="course">Bachelor of Science in Information Technology</p>
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

        <div class="sc-renewal">
            <hr>
            <h4>SCHOLARSHIP RENEWAL IS NOT YET OPEN.</h4>
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
                        <th class="text-center align-middle">Penalty</th>
                        <th class="text-center align-middle">Remarks</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>09/11/2024</td>
                        <td>Absent in Community Service</td>
                        <td>1st Offense</td>
                        <!-- <td><a href="scholarinfo.html" id="view">View</a></td> -->
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <script src="{{ asset('js/scholar.js') }}"></script>
</body>

</html>
