<!DOCTYPE html>

<html lang="en">

<head>
    <title>Scholars Overview</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/scholars.css') }}">
    <link rel="stylesheet" href="{{ asset('css/table.css') }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js"></script>
</head>

<body>
    <!-- PAGE HEADER -->
    @include('partials._pageheader')
    <x-alert />

    <div class="ctnmain">
        <span class="fw-bold text-success h2">Scholars Overview</span>
        <div class="groupA">
            <div class="groupA1">
                <span id="lbltotalscholars" class="label">Total of Scholars</span>
                <span id="totalscholars" class="data">{{ $totalscholars }}</span>
            </div>
            <div class="groupA1">
                <span id="lbltotalscholars" class="label">Total of New Scholars</span>
                <span id="totalnewscholars" class="data">{{ $totalnewscholars }}</span>
            </div>
            <div class="groupA1">
                <span id="lbltotalscholars" class="label">Scholars per Area</span>
                <canvas id="scholarsperarea" height="250px"></canvas>
            </div>
            {{-- <div class="groupA1">
                <span id="lbltotalscholars" class="label">Scholars Admitted per Area</span>
                <span id="outtotalscholars" class="data">0</span>
            </div> --}}
            {{-- <div class="groupA1">
                <span id="lbltotalscholars" class="label">Scholars per School</span>
                <span id="outtotalscholars" class="data">0</span>
            </div> --}}
            <div class="groupA1">
                <span id="lbltotalscholars" class="label">Scholars per School Level</span>
                <canvas id="scholarsperschoolevel" height="250px"></canvas>
            </div>
        </div>
        <div class="divider"></div>
        <span class="fw-bold text-success h2">List of Scholars</span>
        <div class="row gx-0 align-items-center">
            {{-- <div class="col-md-2 mx-1">
                <span class="text-success">Select which list to view:</span>
            </div> --}}
            <div class="col-md-1">
                <button class="btn btn-sm btn-success w-100" id="toggleCollege">College</button>
            </div>
            <div class="col-md-1 mx-1">
                <button class="btn btn-sm btn-outline-success w-100" id="toggleSHS">Senior High</button>
            </div>
            <div class="col-md-1">
                <button class="btn btn-sm btn-outline-success w-100" id="toggleJHS">Junior High</button>
            </div>
            <div class="col-md-1 mx-1">
                <button class="btn btn-sm btn-outline-success w-100" id="toggleElem">Elementary</button>
            </div>
        </div>
        <div class="ctn" id="college">
            <div class="ctntable table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th class="text-center align-middle">#</th>
                            <th class="text-center align-middle">Name</th>
                            <th class="text-center align-middle">Year Level</th>
                            <th class="text-center align-middle">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($colleges as $index => $data)
                            <tr>
                                <td class="text-center align-middle">{{ $index + 1 }}</td>
                                <td class="text-center align-middle">
                                    {{ $data->basicInfo->scLastname }},
                                    {{ $data->basicInfo->scFirstname }}
                                    {{ $data->basicInfo->scMiddlename }}
                                </td>
                                <td class="text-center align-middle">
                                    {{ optional($data->education)->scYearGrade }}
                                </td>
                                <td class="text-center align-middle">
                                    <a href="{{ route('scholar-viewinfo', ['id' => $data->id]) }}"
                                        class="btn btn-success">View</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center">No data available</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="ctn" id="shs" style="display: none;">
            <div class="ctntable table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th class="text-center align-middle">#</th>
                            <th class="text-center align-middle">Name</th>
                            <th class="text-center align-middle">Grade Level</th>
                            <th class="text-center align-middle">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($shs as $index => $data)
                            <tr>
                                <td class="text-center align-middle">{{ $index + 1 }}</td>
                                <td class="text-center align-middle">
                                    {{ $data->basicInfo->scLastname }},
                                    {{ $data->basicInfo->scFirstname }}
                                    {{ $data->basicInfo->scMiddlename }}
                                </td>
                                <td class="text-center align-middle">
                                    {{ optional($data->education)->scSchoolLevel }}
                                </td>
                                <td class="text-center align-middle">
                                    <a href="{{ route('scholar-viewinfo', ['id' => $data->id]) }}"
                                        class="btn btn-success">View</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center">No data available</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="ctn" id="jhs" style="display: none;">
            <div class="ctntable table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th class="text-center align-middle">#</th>
                            <th class="text-center align-middle">Name</th>
                            <th class="text-center align-middle">Grade Level</th>
                            <th class="text-center align-middle">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($jhs as $index => $data)
                            <tr>
                                <td class="text-center align-middle">{{ $index + 1 }}</td>
                                <td class="text-center align-middle">
                                    {{ $data->basicInfo->scLastname }},
                                    {{ $data->basicInfo->scFirstname }}
                                    {{ $data->basicInfo->scMiddlename }}
                                </td>
                                <td class="text-center align-middle">
                                    {{ optional($data->education)->scSchoolLevel }}
                                </td>
                                <td class="text-center align-middle">
                                    <a href="{{ route('scholar-viewinfo', ['id' => $data->id]) }}"
                                        class="btn btn-success">View</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center">No data available</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="ctn" id="elem" style="display: none;">
            <div class="ctntable table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th class="text-center align-middle">#</th>
                            <th class="text-center align-middle">Name</th>
                            <th class="text-center align-middle">Grade Level</th>
                            <th class="text-center align-middle">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($elem as $index => $data)
                            <tr>
                                <td class="text-center align-middle">{{ $index + 1 }}</td>
                                <td class="text-center align-middle">
                                    {{ $data->basicInfo->scLastname }},
                                    {{ $data->basicInfo->scFirstname }}
                                    {{ $data->basicInfo->scMiddlename }}
                                </td>
                                <td class="text-center align-middle">
                                    {{ optional($data->education)->scSchoolLevel }}
                                </td>
                                <td class="text-center align-middle">
                                    <a href="{{ route('scholar-viewinfo', ['id' => $data->id]) }}"
                                        class="btn btn-success">View</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center">No data available</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            function toggleSection(buttonId, containerId) {
                $(buttonId).click(function() {
                    // Fade out all containers, then fade in the selected one
                    $('.ctn').not(containerId).fadeOut('fast', function() {
                        $(containerId).fadeIn('slow');
                    });

                    // Update button classes to reflect active/inactive states
                    $('.btn').not(buttonId).removeClass('btn-success').addClass('btn-outline-success');
                    $(buttonId).removeClass('btn-outline-success').addClass('btn-success');
                });
            }

            // Attach events to buttons
            toggleSection('#toggleCollege', '#college');
            toggleSection('#toggleSHS', '#shs');
            toggleSection('#toggleJHS', '#jhs');
            toggleSection('#toggleElem', '#elem');
        });
    </script>
    <script src="{{ asset('js/headercontrol.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Convert the PHP array to a JavaScript object for scholars per area
            const scholarsperarea = @json($scholarsperarea);

            // Prepare data for the chart
            const areaLabels = Object.keys(scholarsperarea); // Areas
            const areaData = Object.values(scholarsperarea); // Count per area

            // Add 0 to the end of the data array
            areaData.push(0);

            new Chart("scholarsperarea", {
                type: "bar",
                data: {
                    labels: areaLabels,
                    datasets: [{
                        backgroundColor: ['#1a5319', '#599f58', '#9de19c', '#a5d6a7', '#4caf50'],
                        data: areaData
                    }]
                },
                options: {
                    legend: {
                        display: false
                    },
                    scales: {
                        y: {
                            beginAtZero: true // Ensure the y-axis starts from 0
                        }
                    }
                }
            });
        });

        document.addEventListener('DOMContentLoaded', function() {
            // Convert the PHP array to a JavaScript object for levels
            const scholarsperlevel = @json($scholarsperlevel);

            // Prepare data for the chart
            const levelLabels = Object.keys(scholarsperlevel); // Education levels
            const levelData = Object.values(scholarsperlevel); // Count per level

            // Add 0 to the end of the data array
            levelData.push(0);

            new Chart("scholarsperschoolevel", {
                type: "bar",
                data: {
                    labels: levelLabels,
                    datasets: [{
                        backgroundColor: "#1a5319",
                        data: levelData
                    }]
                },
                options: {
                    legend: {
                        display: false
                    },
                    scales: {
                        y: {
                            beginAtZero: true // Ensure the y-axis starts from 0
                        }
                    }
                }
            });
        });
    </script>

</body>

</html>
