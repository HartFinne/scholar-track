<!DOCTYPE html>
<html lang="en">

<head>
    <title>Scholarship Overview</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="{{ asset('css/overview.css') }}">
    <link rel="stylesheet" href="{{ asset('css/partial.css') }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
</head>

<body>

    {{-- @if (Auth::check())
        <p>Your Scholar ID (Case Code): {{ Auth::user()->caseCode }}</p>
    @else
        <p>You are not logged in.</p>
    @endif --}}

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
                    <p class="sc-status">Scholarship Status:
                        <span>{{ $user->Scholarshipinfo->scholarshipstatus }}</span>
                    </p>
                    <p class="sc-type">Scholar Type: <span>{{ $user->Scholarshipinfo->scholartype }}</span></p>
                </div>
            </div>
            <div class="sc-info-2">
                <div class="text">
                    <p class="school">{{ $user->education->scSchoolName }}</p>
                    <p class="yrlevel">{{ $user->education->scYearGrade }}</p>
                    <p class="course">{{ $user->education->scCourseStrandSec }}</p>
                    <p class="academicYear">S.Y. {{ $user->education->scAcademicYear }}</p>
                </div>

            </div>
        </div>

        <div class="overview-graph">
            <div class="card1">
                <p>Academic Performance</p>
                <div>
                    <canvas id="myLineChart"></canvas>
                </div>
            </div>
            <div class="card2">
                <p>Community Service</p>
                <div>
                    <canvas id="myChart"></canvas>
                </div>
            </div>
        </div>

        <div class="sc-renewal ">
            <hr>
            <div class="text">
                <h5>SCHOLARSHIP RENEWAL IS NOT YET OPEN.</h5>
            </div>
            <hr>
        </div>

        <div class="sc-renewal hide">
            <hr>
            <div class="text">
                <h5>SCHOLARSHIP RENEWAL IS NOW OPEN.</h5>
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
                        <th class="text-center align-middle">Condition</th>
                        <th class="text-center align-middle">Penalty</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($penalty as $penalty)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($penalty->dateOfPenalty)->format('m/d/Y') }}</td>
                            <!-- Formatting the date -->
                            <td>{{ $penalty->pendCondition }}</td>
                            <td>{{ $penalty->penalty }}</td>
                        </tr>
                    @endforeach
                    @foreach ($penalty as $penalty)
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
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        const ctx = document.getElementById('myChart');

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Completed', 'Remaining'],
                borderColor: '#36A2EB',
                datasets: [{
                    label: 'Hours',
                    data: [{{ $communityServiceChart['completed'] }},
                        {{ $communityServiceChart['remaining'] }}
                    ],
                    borderWidth: 1,
                    borderColor: 'darkgreen', // Bar border color changed to dark green
                    backgroundColor: 'rgba(0, 100, 0, 0.8)', // Bar fill color set to a dark green with transparency
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                }
            }
        });
    </script>

    {{-- for line chart --}}
    <script>
        window.onload = function() {
            var ctx = document.getElementById('myLineChart').getContext('2d');
            var myLineChart = new Chart(ctx, {
                type: 'line', // Specify chart type as 'line'
                data: {
                    labels: @json($chartData['labels']), // Pass labels from chartData
                    datasets: [{
                        label: 'GWA',
                        data: @json($chartData['grades']), // Pass grades from chartData
                        fill: false, // Don't fill under the line
                        borderColor: 'darkgreen', // Line color (changed to dark green)
                        tension: 0.1, // Curve tension for smoothness
                        pointBackgroundColor: 'darkgreen', // Color of the points
                        pointBorderColor: 'darkgreen', // Border color of the points
                    }]
                },
                options: {
                    scales: {
                        x: {
                            beginAtOne: false
                        },
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        };
    </script>


</body>

</html>
