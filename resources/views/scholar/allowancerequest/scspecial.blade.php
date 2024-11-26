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
                <form action="{{ route('scspecial') }}" method="GET" id="filter-form">
                    <button type="submit" name="status" value="all"
                        class="filter-btn {{ request('status', 'all') == 'all' ? 'active' : '' }}">All</button>
                    <button type="submit" name="status" value="Pending"
                        class="filter-btn {{ request('status') == 'Pending' ? 'active' : '' }}">Pending</button>
                    <button type="submit" name="status" value="Accepted"
                        class="filter-btn {{ request('status') == 'Accepted' ? 'active' : '' }}">Accepted</button>
                    <button type="submit" name="status" value="Completed"
                        class="filter-btn {{ request('status') == 'Completed' ? 'active' : '' }}">Completed</button>
                    <button type="submit" name="status" value="Rejected"
                        class="filter-btn {{ request('status') == 'Rejected' ? 'active' : '' }}">Rejected</button>
                </form>
            </div>
        </div>
        <div class="ctn-table table-responsive">
            <table class="table table-bordered">
                <thead class="table-success">
                    <tr>
                        <th class="text-center align-middle">#</th>
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
                                <td class="text-center align-middle">{{ $loop->iteration }}</td>
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
</body>

</html>
