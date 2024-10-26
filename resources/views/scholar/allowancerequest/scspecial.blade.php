<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Special Allowance</title>
    <link href="https://cdn.lineicons.com/4.0/lineicons.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
        integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ asset('css/special.css') }}">
    <link rel="stylesheet" href="{{ asset('css/partial.css') }}">
</head>

<body>
    <!-- Include Sidebar -->
    @include('partials._sidebar')

    <!-- Include Navbar -->
    @include('partials._navbar')

    <main class="ctn-main">
        <h1 class="title">Special Allowances:</h1>

        <!-- ALLOWANCE BUTTONS -->
        <div class="sallowance">
            <a href="{{ route('specialreqs', 'TRF') }}" class="btn-request">Transportation Reimbursement Request</a>
            <a href="{{ route('specialreqs', 'BAR') }}" class="btn-request">Book Allowance Request</a>
            <a href="{{ route('specialreqs', 'TAR') }}" class="btn-request">Thesis Allowance Request</a>
            <a href="{{ route('specialreqs', 'PAR') }}" class="btn-request">Project Allowance Request</a>
            <a href="{{ route('specialreqs', 'UAR') }}" class="btn-request">Uniform Allowance Request</a>
            <a href="{{ route('specialreqs', 'GAR') }}" class="btn-request">Graduation Allowance Request</a>
            <a href="{{ route('specialreqs', 'FTTSAR') }}" class="btn-request">Field Trip, Training, Seminar Allowance
                Request</a>
        </div>
        <!-- REQUESTS -->
        <div class="status">
            <p class="table-title"> STATUS : </p>
            <div class="filter">
                <button class="filter-btn">All</button>
                <button class="filter-btn">Pending</button>
                <button class="filter-btn">Accepted</button>
                <button class="filter-btn">Completed</button>
                <button class="filter-btn">Rejected</button>
            </div>
        </div>
        <div class="row">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert" id="success-alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert" id="error-alert">
                    {!! session('error') !!}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
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
                    @foreach ($requests as $index => $request)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>
                                @if ($request instanceof App\Models\allowancebook)
                                    Book Allowance Request
                                @elseif ($request instanceof App\Models\allowanceevent)
                                    Event Allowance Request
                                @elseif ($request instanceof App\Models\allowancegraduation)
                                    Graduation Allowance Request
                                @elseif ($request instanceof App\Models\allowanceproject)
                                    Project Allowance Request
                                @elseif ($request instanceof App\Models\allowancethesis)
                                    Thesis Allowance Request
                                @elseif ($request instanceof App\Models\allowancetranspo)
                                    Transportation Reimbursement Request
                                @elseif ($request instanceof App\Models\allowanceuniform)
                                    Uniform Allowance Request
                                @endif
                            </td>
                            <td>{{ $request->created_at->format('F d, Y') }}</td>
                            <td>{{ $request->status }}</td>
                            <td>{{ $request->releasedate ? $request->releasedate->format('F d, Y') : '--' }}</td>
                            <td>
                                @if ($request instanceof App\Models\allowancebook)
                                    <a href="{{ route('showrequestinfo', ['requesttype' => 'BAR', 'id' => $request->id]) }}"
                                        class="btn btn-success">View</a>
                                @elseif ($request instanceof App\Models\allowanceevent)
                                    <a href="{{ route('showrequestinfo', ['requesttype' => 'FTTSAR', 'id' => $request->id]) }}"
                                        class="btn btn-success">View</a>
                                @elseif ($request instanceof App\Models\allowancegraduation)
                                    <a href="{{ route('showrequestinfo', ['requesttype' => 'GAR', 'id' => $request->id]) }}"
                                        class="btn btn-success">View</a>
                                @elseif ($request instanceof App\Models\allowanceproject)
                                    <a href="{{ route('showrequestinfo', ['requesttype' => 'PAR', 'id' => $request->id]) }}"
                                        class="btn btn-success">View</a>
                                @elseif ($request instanceof App\Models\allowancethesis)
                                    <a href="{{ route('showrequestinfo', ['requesttype' => 'TAR', 'id' => $request->id]) }}"
                                        class="btn btn-success">View</a>
                                @elseif ($request instanceof App\Models\allowancetranspo)
                                    <a href="{{ route('showrequestinfo', ['requesttype' => 'TRF', 'id' => $request->id]) }}"
                                        class="btn btn-success">View</a>
                                @elseif ($request instanceof App\Models\allowanceuniform)
                                    <a href="{{ route('showrequestinfo', ['requesttype' => 'UAR', 'id' => $request->id]) }}"
                                        class="btn btn-success">View</a>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </main>
    <script src="{{ asset('js/scholar.js') }}"></script>
</body>

</html>
