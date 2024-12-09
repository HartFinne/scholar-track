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
    <!-- Include Sidebar -->
    @include('partials._sidebar')

    <!-- Include Navbar -->
    @include('partials._navbar')
    <x-alert />


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
                        @php
                            $statusClass = [
                                'On-Hold' => 'bg-warning',
                                'Continuing' => '',
                            ];

                            // Default to 'bg-danger' if the status is not 'On-Hold' or 'Continuing'
                            $statusColor = $statusClass[$user->Scholarshipinfo->scholarshipstatus] ?? 'bg-danger';
                        @endphp

                        <span class="{{ $statusColor }}">{{ $user->Scholarshipinfo->scholarshipstatus }}</span>
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

        @if ($renewal->status == 'Open' && $user->Scholarshipinfo->scholarshipstatus == 'Continuing')
            @if ($appliedRenewal)
                <div class="sc-renewal">
                    <hr>
                    <div class="text">
                        <h5 class="fw-bold">YOU HAVE ALREADY APPLIED FOR SCHOLARSHIP RENEWAL.</h5>
                    </div>
                    <hr>
                </div>
            @else
                <div class="sc-renewal">
                    <hr>
                    <div class="text">
                        <h5 class="fw-bold">SCHOLARSHIP RENEWAL IS NOW OPEN.</h5>
                        <button onclick="window.location.href='{{ route('screnewal') }}'">Renew Scholarship</button>
                    </div>
                    <hr>
                </div>
            @endif
        @else
            <div class="sc-renewal">
                <hr>
                <div class="text">
                    <h5 class="fw-bold">This form is currently not accepting any responses.</h5>
                </div>
                <hr>
            </div>
        @endif


        <p class="sub-title">Summary of Penalties/Deductions</p>
        <div class="filter" id="filter-penalty">
            <form action="{{ route('overview') }}" method="GET" id="penalty-filter-form">
                <button type="submit" name="penalty_status" value="all"
                    class="filter-btn {{ request('penalty_status', 'all') == 'all' ? 'active' : '' }}">All</button>
                <button type="submit" name="penalty_status" value="1st offense"
                    class="filter-btn {{ request('penalty_status') == '1st offense' ? 'active' : '' }}">1st
                    offense</button>
                <button type="submit" name="penalty_status" value="2nd offense"
                    class="filter-btn {{ request('penalty_status') == '2nd offense' ? 'active' : '' }}">2nd
                    offense</button>
                <button type="submit" name="penalty_status" value="3rd offense"
                    class="filter-btn {{ request('penalty_status') == '3rd offense' ? 'active' : '' }}">3rd
                    offense</button>
                <button type="submit" name="penalty_status" value="4th offense"
                    class="filter-btn {{ request('penalty_status') == '4th offense' ? 'active' : '' }}">4th
                    offense</button>
            </form>
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
                    @foreach ($penalty as $penalty)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($penalty->dateofpenalty)->format('m/d/Y') }}</td>
                            <!-- Formatting the date -->
                            <td>{{ $penalty->condition }}</td>
                            <td>{{ $penalty->remark }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <p class="sub-title">Scholarship Renewal</p>
        <div class="filter" id="filter-renewal">
            <form action="{{ route('overview') }}" method="GET" id="renewal-filter-form">
                <button type="submit" name="renewal_status" value="all"
                    class="filter-btn {{ request('renewal_status', 'all') == 'all' ? 'active' : '' }}">All</button>
                <button type="submit" name="renewal_status" value="Pending"
                    class="filter-btn {{ request('renewal_status') == 'Pending' ? 'active' : '' }}">Pending</button>
                <button type="submit" name="renewal_status" value="Accepted"
                    class="filter-btn {{ request('renewal_status') == 'Accepted' ? 'active' : '' }}">Accepted</button>
                <button type="submit" name="renewal_status" value="Rejected"
                    class="filter-btn {{ request('renewal_status') == 'Rejected' ? 'active' : '' }}">Rejected</button>
            </form>
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
                    @foreach ($renewals as $rnw)
                        <tr>
                            <td class="text-center align-middle">
                                {{ \Carbon\Carbon::parse($rnw->datesubmitted)->format('F j, Y') }}</td>
                            <td class="text-center align-middle">{{ $rnw->grade->schoolyear }}</td>
                            <td class="text-center align-middle">{{ $rnw->status }}</td>
                            <td class="text-center align-middle">
                                <a href="{{ route('showRenewForm', $rnw->rid) }}" class="btn btn-sm btn-success"><i
                                        class="fas fa-eye"></i></a>
                            </td>
                        </tr>
                    @endforeach
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

    <script>
        function handleFilterButtons(filterContainerId) {
            document.querySelectorAll(`#${filterContainerId} .filter-btn`).forEach(button => {
                button.addEventListener('click', function() {
                    // Remove "active" class from all buttons in this filter set
                    document.querySelectorAll(`#${filterContainerId} .filter-btn`).forEach(btn => btn
                        .classList.remove('active'));

                    // Add "active" class to the clicked button
                    this.classList.add('active');
                });
            });
        }

        // Initialize for each filter container
        handleFilterButtons('filter-penalty');
        handleFilterButtons('filter-renewal');
    </script>
</body>

</html>
