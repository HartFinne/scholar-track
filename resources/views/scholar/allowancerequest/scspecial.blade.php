<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Special Allowance</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="{{ asset('css/special.css') }}">
    <link rel="stylesheet" href="{{ asset('css/partial.css') }}">
</head>

<body>
    <!-- Include Sidebar -->
    @include('partials._sidebar')

    <!-- Include Navbar -->
    @include('partials._navbar')
    <x-alert />
    <main class="ctn-main">
        <h1 class="title">Special Allowances:</h1>
        <!-- ALLOWANCE BUTTONS -->
        <div class="sallowance">
            @forelse ($forms as $form)
                <a href="{{ route('specialreqs', $form->csafid) }}" class="btn-request">{{ $form->formname }}</a>
            @empty
                <div class="row">No Forms Found.</div>
            @endforelse
        </div>
        <!-- REQUESTS -->
        <div class="status">
            <p class="table-title"> STATUS : </p>
            <div class="filter">
                <div id="filter-form">
                    <button type="button" name="status" value="all" class="filter-btn active">All</button>
                    <button type="button" name="status" value="Pending" class="filter-btn">Pending</button>
                    <button type="button" name="status" value="Accepted" class="filter-btn">Accepted</button>
                    <button type="button" name="status" value="Completed" class="filter-btn">Completed</button>
                    <button type="button" name="status" value="Rejected" class="filter-btn">Rejected</button>
                </div>
            </div>
        </div>
        <div class="ctn-table table-responsive">
            <table class="table table-bordered">
                <thead class="table-success">
                    <tr>
                        <th class="text-center align-middle">Type of Request</th>
                        <th class="text-center align-middle">Date of Request</th>
                        <th class="text-center align-middle">Status</th>
                        <th class="text-center align-middle">Date of Release</th>
                        <th class="text-center align-middle">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($forms as $form)
                        @foreach ($data[$form->formname] as $row)
                            <tr>
                                <td class="text-center align-middle">{{ $row['requestType'] }}</td>
                                <td class="text-center align-middle">
                                    {{ \Carbon\Carbon::parse($row['requestDate'])->format('F j, Y') }}
                                </td>
                                <td class="text-center align-middle">{{ $row['requestStatus'] }}</td>
                                <td class="text-center align-middle">
                                    {{ $row['releaseDate'] ? \Carbon\Carbon::parse($row['releaseDate'])->format('F j, Y') : 'Not Set Yet' }}
                                </td>
                                <td class="text-center align-middle">
                                    <a href="{{ route('specialRequestInfo', ['type' => $form->formname, 'id' => $row['id']]) }}"
                                        class="btn btn-sm btn-success"><i class="fas fa-eye"></i></a>
                                    {{-- <a href="{{ url('your-action-url') }}" class="btn btn-primary">Action</a> --}}
                                </td>
                            </tr>
                        @endforeach
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">No data available.</td>
                        </tr>
                    @endforelse
                </tbody>
                <tbody>
                </tbody>
            </table>
        </div>
    </main>
    <script src="{{ asset('js/scholar.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const filterButtons = document.querySelectorAll('.filter-btn');
            const tableRows = document.querySelectorAll('.ctn-table tbody tr');

            // Attach click event to each filter button
            filterButtons.forEach(button => {
                button.addEventListener('click', () => {
                    // Get the filter value (status) from the button
                    const filter = button.textContent.trim();

                    // Update button active state
                    filterButtons.forEach(btn => btn.classList.remove('active'));
                    button.classList.add('active');

                    // Loop through table rows and toggle visibility
                    tableRows.forEach(row => {
                        const statusCell = row.querySelector(
                            'td:nth-child(4)'); // Adjust to Status column
                        const statusText = statusCell.textContent.trim();

                        // Show row if status matches or filter is "All"
                        if (filter === 'All' || statusText === filter) {
                            row.style.display = '';
                        } else {
                            row.style.display = 'none';
                        }
                    });
                });
            });
        });
    </script>
</body>

</html>
