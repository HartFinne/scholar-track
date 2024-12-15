<!DOCTYPE html>
<html lang="en">

<head>
    <title>Allowance Requests | Regular</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/table.css') }}">
    <link rel="stylesheet" href="{{ asset('css/allowance.css') }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body>
    <!-- PAGE HEADER -->
    @include('partials._pageheader')

    <div class="ctnmain">
        <span class="text-success fw-bold h2">Regular Allowance Requests</span>
        <div class="groupA">
            <div class="groupA1">
                <span class="label">Total Requests</span>
                <span class="data" id="totalrequests">{{ $data['All'] }}</span>
            </div>
            <div class="groupA1">
                <span class="label">Pending</span>
                <span class="data" id="pending">{{ $data['Pending'] }}</span>
            </div>
            <div class="groupA1">
                <span class="label">Completed</span>
                <span class="data" id="completed">{{ $data['Completed'] }}</span>
            </div>
        </div>
        <div class="divider"></div>
        <span class="text-success fw-bold h2">List of Requests</span>
        <div class="row align-items-center justify-content-between">
            {{-- search bar --}}
            <div class="col-md-3">
                <input type="search" class="border-success form-control" placeholder="Search">
            </div>
            {{-- filter --}}
            <div class="col-auto">
                <div class="row gx-2 align-items-center">
                    <div class="col-auto">
                        <button class="filter btn btn-sm btn-success w-100" id="toggleAll">All</button>
                    </div>
                    <div class="col-auto">
                        <button class="filter btn btn-sm btn-outline-success w-100" id="toggleFirstYear">First
                            Year</button>
                    </div>
                    <div class="col-auto">
                        <button class="filter btn btn-sm btn-outline-success w-100" id="toggleSecondYear">Second
                            Year</button>
                    </div>
                    <div class="col-auto">
                        <button class="filter btn btn-sm btn-outline-success w-100" id="toggleThirdYear">Third
                            Year</button>
                    </div>
                    <div class="col-auto">
                        <button class="filter btn btn-sm btn-outline-success w-100" id="toggleFourthYear">Fourth
                            Year</button>
                    </div>
                    <div class="col-auto">
                        <button class="filter btn btn-sm btn-outline-success w-100" id="toggleFifthYear">Fifth
                            Year</button>
                    </div>
                </div>
            </div>
        </div>

        <div style="min-height: 50vh" class="ctntable table-responsive">
            <table class="table table-bordered" id="tblscholarslist">
                <thead>
                    <tr>
                        <th class="text-center align-middle">Name</th>
                        <th class="text-center align-middle">Date Submitted</th>
                        <th class="text-center align-middle">Year Level</th>
                        <th class="text-center align-middle">Status</th>
                        <th class="text-center align-middle">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($requests as $req)
                        <tr>
                            <td class="text-center align-middle">{{ $req->basicInfo->scLastname }},
                                {{ $req->basicInfo->scFirstname }} {{ $req->basicInfo->scMiddlename }}</td>
                            <td class="text-center align-middle">
                                {{ $req->created_at ? \Carbon\Carbon::parse($req->created_at)->format('F j, Y') : 'Failed to load' }}
                            </td>
                            <td class="text-center align-middle">{{ $req->education->scYearGrade }}</td>
                            <td class="text-center align-middle">
                                {{ $req->status ?? 'Failed to load' }}
                            </td>
                            <td class="text-center align-middle">
                                <a href="{{ route('allowancerequests-regular-info', ['id' => $req->regularID]) }}"
                                    class="btn btn-success btn-sm"><i class="fas fa-eye"></i></a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td class="text-center align-middle" colspan="5">No Records Found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination Links -->
        {{-- <div class="d-flex justify-content-center mt-3">
            {{ $requests->appends(request()->query())->links('pagination::bootstrap-4') }}
        </div> --}}
    </div>

    <script src="{{ asset('js/headercontrol.js') }}"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            // Universal Search Functionality (Search across all columns)
            $("input[type='search']").on("keyup", function() {
                var value = $(this).val().toLowerCase();
                $("#tblscholarslist tbody tr").filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
                });
            });
        });

        document.addEventListener("DOMContentLoaded", function() {
            const tableRows = document.querySelectorAll("#tblscholarslist tbody tr");
            const allButton = document.getElementById("toggleAll");
            const yearButtons = [{
                    id: "toggleFirstYear",
                    year: "First Year"
                },
                {
                    id: "toggleSecondYear",
                    year: "Second Year"
                },
                {
                    id: "toggleThirdYear",
                    year: "Third Year"
                },
                {
                    id: "toggleFourthYear",
                    year: "Fourth Year"
                },
                {
                    id: "toggleFifthYear",
                    year: "Fifth Year"
                },
            ];

            // Function to reset the filters and show all rows
            const showAllRows = () => {
                tableRows.forEach(row => {
                    row.style.display = "";
                });
                resetButtonStyles();
                allButton.classList.remove("btn-outline-success");
                allButton.classList.add("btn-success");
            };

            // Function to filter the table based on year level
            const filterTable = (yearLevel) => {
                tableRows.forEach(row => {
                    const yearCell = row.querySelector("td:nth-child(3)");
                    if (yearCell && yearCell.textContent.trim() === yearLevel) {
                        row.style.display = "";
                    } else {
                        row.style.display = "none";
                    }
                });
            };

            // Function to reset button styles
            const resetButtonStyles = () => {
                document.querySelectorAll(".filter").forEach(button => {
                    button.classList.remove("btn-success");
                    button.classList.add("btn-outline-success");
                });
            };

            // Event listener for the "All" button
            allButton.addEventListener("click", () => {
                showAllRows();
            });

            // Event listeners for the year level buttons
            yearButtons.forEach(({
                id,
                year
            }) => {
                const button = document.getElementById(id);
                button.addEventListener("click", () => {
                    filterTable(year);
                    resetButtonStyles();
                    button.classList.remove("btn-outline-success");
                    button.classList.add("btn-success");
                });
            });

            // Initialize by showing all rows
            showAllRows();
        });
    </script>
</body>

</html>
