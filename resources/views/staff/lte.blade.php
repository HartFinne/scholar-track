<!DOCTYPE html>

<html lang="en">

<head>
    <title>Letter of Explanation</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/table.css') }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body>
    <!-- PAGE HEADER -->
    @include('partials._pageheader')

    <div class="ctnmain">
        <span class="text-success fw-bold h2">Letter of Explanation</span>
        <div class="row justify-content-between">
            <div class="col-md-3">
                <input type="search" class="form-control border border-success" placeholder="Search">
            </div>
            <div class="col-auto">
                <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#filterModal">
                    Filter Results
                </button>
            </div>
        </div>
        <div style="min-height: 50vh" class="ctntable table-responsive">
            <table class="table table-bordered" id="tblscholarslist">
                <thead>
                    <tr>
                        <th class="text-center align-middle">#</th>
                        <th class="text-center align-middle">Date Issued</th>
                        <th class="text-center align-middle">Scholar's Name</th>
                        <th class="text-center align-middle">Year Level</th>
                        <th class="text-center align-middle">Date Submitted</th>
                        <th class="text-center align-middle">Concern</th>
                        <th class="text-center align-middle">Reason</th>
                        <th class="text-center align-middle">Status</th>
                        <th class="text-center align-middle">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($lte as $index => $letter)
                        <tr>
                            <td class="text-center align-middle">{{ $index + 1 }}</td>
                            <td class="text-center align-middle">
                                {{ \Carbon\Carbon::parse($letter->dateissued)->format('F d, Y') }}
                            </td>
                            @php
                                $scholar = $scholars->firstWhere('caseCode', $letter->caseCode);
                            @endphp
                            <td class="text-center align-middle">
                                {{ $scholar->basicInfo->scLastname ?? '--' }},
                                {{ $scholar->basicInfo->scFirstname ?? '' }}
                                {{ $scholar->basicInfo->scMiddlename ?? '' }}
                            </td>
                            <td class="text-center align-middle">
                                {{ $scholar->education->scYearGrade ?? '--' }}
                            </td>
                            <td class="text-center align-middle">
                                {{ $letter->datesubmitted ? \Carbon\Carbon::parse($letter->datesubmitted)->format('F d, Y') : '--' }}
                            </td>
                            <td class="text-center align-middle">
                                {{ $letter->violation }}{{ $letter->eventtype ? ' in ' . $letter->eventtype : '' }}
                            </td>
                            <td class="text-center align-middle">{{ $letter->reason ?? '--' }}</td>
                            <td class="text-center align-middle">{{ $letter->ltestatus }}</td>
                            <td class="text-center align-middle">
                                <a href="{{ route('showlteinfo', $letter->lid) }}" class="btn btn-sm btn-success"><i
                                        class="fas fa-eye"></i></a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td class="text-center align-middle" colspan="8">No Records Found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            {{-- <div class="d-flex justify-content-center mt-3">
                {{ $lte->links('pagination::bootstrap-4') }}
            </div> --}}
        </div>
        <!-- Filter Modal -->
        <div class="modal fade" id="filterModal" tabindex="-1" aria-labelledby="filterModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-success text-white">
                        <h5 class="modal-title" id="filterModalLabel">Filter Options</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form id="filterForm">
                        @csrf
                        <div class="modal-body">
                            <!-- Filter by School Level -->
                            <div class="row gx-0 align-items-center mb-3">
                                <div class="col-md-6">
                                    <span class="fw-bold my-auto">School Level</span>
                                </div>
                                <div class="col-md-6">
                                    <select class="form-select border-success" id="level" name="level">
                                        <option value="All" selected>All
                                        </option>
                                        <option value="College">College</option>
                                        <option value="Senior High">Senior High
                                        </option>
                                        <option value="Junior High">Junior High
                                        </option>
                                        <option value="Elementary">Elementary
                                        </option>
                                    </select>
                                </div>
                            </div>

                            <!-- Filter by Status -->
                            <div class="row gx-0 align-items-center mb-3">
                                <div class="col-md-6">
                                    <span class="fw-bold my-auto">Status</span>
                                </div>
                                <div class="col-md-6">
                                    <select class="form-select border-success" id="status" name="status">
                                        <option value="All" selected>All</option>
                                        <option value="No Response">No Response
                                        </option>
                                        <option value="To Review">To Review
                                        </option>
                                        <option value="Unexcused">Unexcused</option>
                                        <option value="Excused">
                                            Excused</option>
                                        <option value="Terminated">Terminated
                                        </option>
                                        <option value="Continuing">Continuing
                                        </option>
                                    </select>
                                </div>
                            </div>

                            <!-- Filter by Violation -->
                            <div class="row gx-0 align-items-center mb-3">
                                <div class="col-md-6">
                                    <span class="fw-bold my-auto">Concern</span>
                                </div>
                                <div class="col-md-6">
                                    <select class="form-select border-success" id="concern" name="concern">
                                        <option value="All" selected>All</option>
                                        {{-- to bbe populated based on records on the table --}}
                                    </select>
                                </div>
                            </div>

                            <!-- Modal Footer -->
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary"
                                    data-bs-dismiss="modal">Close</button>
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
        document.addEventListener("DOMContentLoaded", function() {
            const searchInput = document.querySelector('input[type="search"]');
            const filterForm = document.getElementById('filterForm');
            const tableRows = document.querySelectorAll('#tblscholarslist tbody tr');
            const concernSelect = document.getElementById('concern');

            // Dynamically populate concern filter options with distinct values
            const concernSet = new Set(); // To store unique concerns
            tableRows.forEach(row => {
                const concernCell = row.querySelector('td:nth-child(6)'); // 6th column (Concern)
                if (concernCell) {
                    const concern = concernCell.textContent.trim();
                    if (concern && concern !== '--') {
                        concernSet.add(concern);
                    }
                }
            });

            // Populate concern dropdown with distinct values
            concernSet.forEach(concern => {
                const option = document.createElement('option');
                option.value = concern;
                option.textContent = concern;
                concernSelect.appendChild(option);
            });

            // Function to filter the table rows based on the search input
            function filterTable() {
                const searchTerm = searchInput.value.toLowerCase();

                tableRows.forEach(row => {
                    let isMatch = false;

                    // Iterate over all cells in the row (for all columns)
                    row.querySelectorAll('td').forEach(cell => {
                        if (cell.textContent.toLowerCase().includes(searchTerm)) {
                            isMatch = true;
                        }
                    });

                    // Show or hide the row based on whether a match was found in any column
                    if (isMatch) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            }

            // Event listener for the search input
            searchInput.addEventListener('input', filterTable);

            // Function to apply filters based on the selected criteria
            filterForm.addEventListener('submit', function(event) {
                event.preventDefault(); // Prevent page reload on form submit

                const level = document.getElementById('level').value;
                const status = document.getElementById('status').value;
                const concern = document.getElementById('concern').value;

                tableRows.forEach(row => {
                    const levelCell = row.querySelector('td:nth-child(4)').textContent.trim();
                    const statusCell = row.querySelector('td:nth-child(8)').textContent.trim();
                    const concernCell = row.querySelector('td:nth-child(6)').textContent.trim();

                    // Get the Year Level cell value
                    const yearLevelCell = row.querySelector('td:nth-child(4)').textContent.trim();

                    // Define the year level ranges based on the selected school level
                    let validYearLevels = [];

                    switch (level) {
                        case 'College':
                            validYearLevels = ['First Year', 'Second Year', 'Third Year',
                                'Fourth Year', 'Fifth Year'
                            ];
                            break;
                        case 'Senior High':
                            validYearLevels = ['Grade 11', 'Grade 12'];
                            break;
                        case 'Junior High':
                            validYearLevels = ['Grade 7', 'Grade 8', 'Grade 9', 'Grade 10'];
                            break;
                        case 'Elementary':
                            validYearLevels = ['Grade 1', 'Grade 2', 'Grade 3', 'Grade 4',
                                'Grade 5', 'Grade 6'
                            ];
                            break;
                        default:
                            validYearLevels = ['All']; // Default to "All"
                            break;
                    }

                    // Apply filtering based on selected criteria
                    const isLevelMatch = (level === 'All' || validYearLevels.includes(
                        yearLevelCell));
                    const isStatusMatch = (status === 'All' || statusCell === status);
                    const isConcernMatch = (concern === 'All' || concernCell === concern);

                    if (isLevelMatch && isStatusMatch && isConcernMatch) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            });
        });
    </script>

</body>

</html>
