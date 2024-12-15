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
            <div class="groupA1">
                <span id="lbltotalscholars" class="label">Scholars per School Level</span>
                <canvas id="scholarsperschoolevel" height="250px"></canvas>
            </div>
        </div>
        <div class="divider"></div>
        <span class="fw-bold text-success h2">List of Scholars</span>
        <div class="row align-items-center justify-content-between">
            <div class="col-md-3">
                <input type="search" name="search" id="search" class="form-control border-success"
                    placeholder="Search">
            </div>
            <div class="col-auto">
                <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#filterModal">
                    Filter Results
                </button>
            </div>
        </div>
        <div class="ctn" id="scholars">
            <div style="min-height: 50vh" class="ctntable table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th class="text-center align-middle">#</th>
                            <th class="text-center align-middle">Name</th>
                            <th class="text-center align-middle">Year Level</th>
                            <th class="text-center align-middle">Scholarship Status</th>
                            <th class="text-center align-middle">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($scholars as $index => $data)
                            <tr>
                                <td class="text-center align-middle">{{ $index + 1 }}</td>
                                <td class="text-center align-middle">
                                    {{ $data->basicInfo->scLastname }},
                                    {{ $data->basicInfo->scFirstname }}
                                    {{ $data->basicInfo->scMiddlename }}
                                </td>
                                <td class="text-center align-middle">
                                    {{ $data->education->scYearGrade }}
                                </td>
                                <td class="text-center align-middle">
                                    {{ $data->scholarshipstatus }}
                                </td>
                                <td class="text-center align-middle">
                                    <a href="{{ route('scholar-viewinfo', ['id' => $data->User->id]) }}"
                                        class="btn btn-sm btn-success"><i class="fas fa-eye"></i></a>
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
        <div class="modal fade" id="filterModal" tabindex="-1" aria-labelledby="filterModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-success text-white">
                        <h5 class="modal-title" id="filterModalLabel">Filter Results</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form id="filter">
                        <div class="modal-body">
                            <div class="row mb-2">
                                <div class="col-md-6">
                                    <span>School Level</span>
                                    <select name="level" id="level" class="form-control border-success">
                                        <option value="All">All</option>
                                        <option value="College">College</option>
                                        <option value="Senior High">Senior High</option>
                                        <option value="Junior High">Junior High</option>
                                        <option value="Elementary">Elementary</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <span>Scholarship Status</span>
                                    <select name="status" id="status" class="form-control border-success">
                                        <option value="All">All</option>
                                        <option value="Continuing">Continuing</option>
                                        <option value="On-Hold">On-Hold</option>
                                        <option value="Terminated">Terminated</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" data-bs-dismiss="modal" class="btn btn-secondary">Cancel</button>
                            <button type="submit" data-bs-dismiss="modal" class="btn btn-success">Apply
                                Filter</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/headercontrol.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Search functionality
            const searchInput = document.getElementById('search');
            const scholarsContainer = document.getElementById('scholars');
            const scholarsTable = scholarsContainer.querySelector('table tbody');

            searchInput.addEventListener('input', function() {
                const query = searchInput.value.trim()
                    .toLowerCase(); // Trim spaces and convert to lowercase
                const queryWords = query.split(
                    /\s+/); // Split the query into words (ignoring multiple spaces)
                const rows = scholarsTable.querySelectorAll('tr');

                rows.forEach(function(row) {
                    const nameCell = row.querySelector('td:nth-child(2)'); // Name column
                    const levelCell = row.querySelector('td:nth-child(3)'); // Year Level column
                    const statusCell = row.querySelector(
                        'td:nth-child(4)'); // Scholarship Status column

                    if (nameCell && levelCell && statusCell) {
                        const name = nameCell.textContent.trim().toLowerCase();
                        const yearLevel = levelCell.textContent.trim().toLowerCase();
                        const status = statusCell.textContent.trim().toLowerCase();

                        // Check if every word in the query matches any cell content
                        const isMatch = queryWords.every(word =>
                            name.includes(word) || yearLevel.includes(word) || status.includes(
                                word)
                        );

                        if (isMatch) {
                            row.style.display = ''; // Show matching row
                        } else {
                            row.style.display = 'none'; // Hide non-matching row
                        }
                    }
                });
            });

            // Filter form functionality
            const filterForm = document.getElementById('filter');
            filterForm.addEventListener('submit', function(e) {
                e.preventDefault();

                const level = document.getElementById('level').value;
                const status = document.getElementById('status').value;

                const rows = scholarsTable.querySelectorAll('tr');

                rows.forEach(function(row) {
                    const levelCell = row.querySelector('td:nth-child(3)'); // Year Level column
                    const statusCell = row.querySelector(
                        'td:nth-child(4)'); // Scholarship Status column

                    if (levelCell && statusCell) {
                        const rowLevel = levelCell.textContent.trim();
                        const rowStatus = statusCell.textContent.trim();

                        let showRow = true;

                        // Filter by school level and year level
                        if (level !== 'All') {
                            if (
                                level === 'College' &&
                                !/First|Second|Third|Fourth|Fifth/.test(rowLevel)
                            ) {
                                showRow = false;
                            } else if (
                                level === 'Elementary' &&
                                !/^Grade (1|2|3|4|5|6)$/.test(rowLevel)
                            ) {
                                showRow = false;
                            } else if (
                                level === 'Senior High' &&
                                !/^Grade (11|12)$/.test(rowLevel)
                            ) {
                                showRow = false;
                            } else if (
                                level === 'Junior High' &&
                                !/^Grade (7|8|9|10)$/.test(rowLevel)
                            ) {
                                showRow = false;
                            }
                        }

                        // Filter by scholarship status
                        if (
                            status !== 'All' &&
                            !rowStatus.toLowerCase().includes(status.toLowerCase())
                        ) {
                            showRow = false;
                        }

                        row.style.display = showRow ? '' : 'none';
                    }
                });

                // Close the modal after applying filters
                const modal = new bootstrap.Modal(document.getElementById('filterModal'));
                modal.hide();
            });

        });

        document.addEventListener('DOMContentLoaded', function() {
            // Convert the PHP array to a JavaScript object for scholars per area
            const scholarsperarea = @json($scholarsperarea);

            // Prepare data for the chart
            const areaLabels = Object.keys(scholarsperarea); // Areas
            const areaData = Object.values(scholarsperarea); // Count per area

            // Add 0 to the end of the data array
            areaData.push(0);

            // Function to generate shades of green dynamically based on the number of bars (data length)
            function generateShades(count) {
                let colors = [];
                const baseColor = {
                    r: 26,
                    g: 83,
                    b: 25
                }; // Base green color

                for (let i = 0; i < count; i++) {
                    const shade = Math.min(255, baseColor.g + i *
                        15); // Increment the green value to get different shades
                    colors.push(`rgb(${baseColor.r}, ${shade}, ${baseColor.b})`);
                }

                return colors;
            }

            // Get dynamic colors based on the length of the areaData
            const backgroundColors = generateShades(areaData.length);

            new Chart("scholarsperarea", {
                type: "bar",
                data: {
                    labels: areaLabels,
                    datasets: [{
                        backgroundColor: backgroundColors, // Use the dynamic colors
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
