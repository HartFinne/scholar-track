<!DOCTYPE html>

<html lang="en">

<head>
    <title>Applicants</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/table.css') }}">
    <link rel="stylesheet" href="{{ asset('css/applicants.css') }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js"></script>
</head>

<body>
    <!-- PAGE HEADER -->
    @include('partials._pageheader')

    <div class="ctnmain">
        <span class="text-success fw-bold h2">Applicants Overview</span>
        <div class="row g-3 justify-content-center">
            <div class="col-md-2">
                <div class="container rounded border border-success custom-card-bg border-2 p-2">
                    <div class="text-success fw-bold pb-2">Total Applicants</div>
                    <div class="h1 text-success text-center fw-bold">{{ $data['totalapplicants'] }}</div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="container rounded border border-success custom-card-bg border-2 p-2">
                    <div class="text-success fw-bold pb-2">Under Review</div>
                    <div class="h1 text-success text-center fw-bold">{{ $data['review'] }}</div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="container rounded border border-success custom-card-bg border-2 p-2">
                    <div class="text-success fw-bold pb-2">Initial Interview</div>
                    <div class="h1 text-success text-center fw-bold">{{ $data['initial'] }}</div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="container rounded border border-success custom-card-bg border-2 p-2">
                    <div class="text-success fw-bold pb-2">Home Visit</div>
                    <div class="h1 text-success text-center fw-bold">{{ $data['home'] }}</div>
                </div>
            </div>
        </div>

        <div class="row g-3 justify-content-center">
            <div class="col-md-2">
                <div class="container rounded border border-success custom-card-bg border-2 p-2">
                    <div class="text-success fw-bold pb-2">Panel Interview</div>
                    <div class="h1 text-success text-center fw-bold">{{ $data['panel'] }}</div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="container rounded border border-success custom-card-bg border-2 p-2">
                    <div class="text-success fw-bold pb-2">Accepted</div>
                    <div class="h1 text-success text-center fw-bold">{{ $data['accepted'] }}</div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="container rounded border border-success custom-card-bg border-2 p-2">
                    <div class="text-success fw-bold pb-2">Denied</div>
                    <div class="h1 text-success text-center fw-bold">{{ $data['denied'] }}</div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="container rounded border border-success custom-card-bg border-2 p-2">
                    <div class="text-success fw-bold pb-2">Withdrawn</div>
                    <div class="h1 text-success text-center fw-bold">{{ $data['withdrawn'] }}</div>
                </div>
            </div>
        </div>

        <div class="groupA">
            <div class="groupA2">
                <span class="label">Applicants per School Level</span>
                <canvas id="applicantsgraph" height="300px"></canvas>
            </div>
        </div>
        <div class="divider"></div>
        <span class="text-success fw-bold h2">List of Applicants</span>
        <div class="row justify-content-between align-items-center">
            <div class="col-md-3">
                <input type="search" name="search" id="search" placeholder="Search"
                    class="form-control border-success">
            </div>
            <div class="col-auto">
                <button class="btn btn-success px-3" data-bs-toggle="modal" data-bs-target="#filterModal">Filter
                    Result</button>
            </div>
        </div>
        <div class="ctntable table-responsive">
            <table class="table table-bordered" id="tblapplicantslist">
                <thead>
                    <tr>
                        <th class="text-center align-middle">#</th>
                        <th class="text-center align-middle">Date Submitted</th>
                        <th class="text-center align-middle">Scholar's Name</th>
                        <th class="text-center align-middle">Incoming Year Level</th>
                        <th class="text-center align-middle">Status</th>
                        <th class="text-center align-middle">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($applicants as $index => $applicant)
                        <tr>
                            <td class="text-center align-middle">{{ $index + 1 }}</td>
                            <td class="text-center align-middle">{{ $applicant->created_at->format('F d, Y') }}</td>
                            <td class="text-center align-middle">{{ $applicant->name }}</td>
                            <td class="text-center align-middle">
                                {{ $applicant->educcollege->inyear ?? $applicant->educelemhs->ingrade }}</td>
                            <td class="text-center align-middle">{{ $applicant->applicationstatus }}</td>
                            <td class="text-center align-middle">
                                <a href="{{ route('applicantinfo', $applicant->casecode) }}"
                                    class="btn btn-sm btn-success"><i class="fas fa-eye"></i></a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td class="text-center align-middle" colspan="6">No Records Found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <!-- Pagination Links -->
        <div class="d-flex justify-content-center mt-3">
            {{ $applicants->links('pagination::bootstrap-4') }}
        </div>
    </div>
    <!-- Modal Structure -->
    <div class="modal fade" id="filterModal" tabindex="-1" aria-labelledby="filterModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-success text-white fw-bold">
                    <h5 class="modal-title" id="filterModalLabel">Filter Options</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-md-6 fw-bold">School Level</div>
                        <div class="col-md-6">
                            <select name="schoollevel" id="schoollevel" class="form-select border-success" required>
                                <option value="" selected hidden>-- Select school level --</option>
                                <option value="All">All</option>
                                <option value="College">College</option>
                                <option value="Senior High">Senior High</option>
                                <option value="Junior High">Junior High</option>
                                <option value="Elementary">Elementary</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6 fw-bold">Application Status</div>
                        <div class="col-md-6">
                            <select name="status" id="status" class="form-select border-success" required>
                                <option value="" selected hidden>-- Select status --</option>
                                <option value="All">All</option>
                                <option value="Under Review">Under Review</option>
                                <option value="Initial Interview">Initial Interview</option>
                                <option value="Home Visit">Home Visit</option>
                                <option value="Panel Interview">Panel Interview</option>
                                <option value="Accepted">Accepted</option>
                                <option value="Denied">Denied</option>
                                <option value="Withdrawn">Withdrawn</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Apply Filter</button>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/headercontrol.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            new Chart("applicantsgraph", {
                type: "bar",
                data: {
                    labels: ["College", "Senior High", "Junior High", "Elementary"],
                    datasets: [{
                        backgroundColor: "#1a5319",
                        data: [{{ $college }}, {{ $shs }}, {{ $jhs }},
                            {{ $elem }}, 0
                        ]
                    }]
                },
                options: {
                    legend: {
                        display: false
                    }
                }
            });
        });

        document.getElementById('search').addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            const tableRows = document.querySelectorAll(
                '#tblapplicantslist tbody tr'); // Select all the rows in the body of the applicants table

            tableRows.forEach(row => {
                // Get text content from the 2nd to 4th columns (index 1, 2, 3)
                const dateSubmitted = row.cells[1].textContent.toLowerCase();
                const scholarsName = row.cells[2].textContent.toLowerCase();
                const incomingYearLevel = row.cells[3].textContent.toLowerCase();
                const status = row.cells[4].textContent.toLowerCase();

                // Check if any of these columns include the search term
                if (dateSubmitted.includes(searchTerm) || scholarsName.includes(searchTerm) ||
                    incomingYearLevel.includes(searchTerm) || status.includes(searchTerm)) {
                    row.style.display = ''; // Show the row
                } else {
                    row.style.display = 'none'; // Hide the row
                }
            });
        });
    </script>
</body>

</html>
