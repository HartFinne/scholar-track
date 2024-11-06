<!DOCTYPE html>
<html lang="en">

<head>
    <title>Letter of Explanation</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="{{ asset('css/overview.css') }}">
    <link rel="stylesheet" href="{{ asset('css/lteinfo.css') }}">
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

    <!-- MAIN -->
    <div class="ctn-main">
        <a href="{{ route('schome') }}" class="goback">&lt Go back</a>
        <h1 class="sub-title text-center">Letter of Explanation</h1>

        @foreach ($noresponseletters as $nonletter)
            @if (\Carbon\Carbon::parse($nonletter->deadline)->toDateString() == now()->addDay()->toDateString())
                <div class="card px-4 py-3 mb-3 border-0" style="background-color: #ffeeac;">
                    <div class="row d-flex align-items-center">
                        <div class="col-md-10">
                            <span class="fw-bold" style="font-size: 18px; color: #1e5430;">
                                You have not yet responded to the LTE of
                                {{ $nonletter->violation }}
                                @if (in_array($nonletter->eventtype, ['Humanities Class', 'Community Service']))
                                    in {{ $nonletter->eventtype }}
                                @endif
                                that is due tomorrow.
                            </span>
                        </div>
                        <div class="col-md-2">
                            <a class="btn w-100 text-white fw-bold"
                                style="background-color: #1e5430; border-color: #1e5430;"
                                href="{{ route('lteinfo', $nonletter->lid) }}">
                                <!-- Replace href="" with actual URL if available -->
                                View here
                            </a>
                        </div>
                    </div>
                </div>
            @endif
        @endforeach

        <p class="table-title">Unsubmitted Letter of Explanation</p>
        <div class="ctn-table table-responsive">
            <table class="table table-bordered" id="table">
                <thead>
                    <tr>
                        <th class="text-center align-middle">Date Received</th>
                        <th class="text-center align-middle">Concern</th>
                        <th class="text-center align-middle">Deadline</th>
                        <th class="text-center align-middle">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($noresponseletters as $nonletter)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($nonletter->dateissued)->format('F d, Y') }}</td>
                            @if ($nonletter->eventtype == 'Humanities Class')
                                <td>{{ $nonletter->violation }} in
                                    {{ $nonletter->eventtype }}</td>
                            @elseif ($nonletter->eventtype == 'Community Service')
                                <td>
                                    {{ $nonletter->violation }}
                                    in {{ $nonletter->eventtype }}

                                </td>
                            @else
                                <td>
                                    {{ $nonletter->violation }}
                                </td>
                            @endif
                            <td>{{ \Carbon\Carbon::parse($nonletter->deadline)->format('F d, Y') }}</td>
                            <td>
                                <a href="{{ route('lteinfo', $nonletter->lid) }}" id="view">View</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="status">
            <p class="table-title">Letter Status</p>
            <div class="filter">
                <button class="filter-btn">All</button>
                <button class="filter-btn">To Review</button>
                <button class="filter-btn">Excused</button>
                <button class="filter-btn">Unexcused</button>
            </div>
        </div>

        <div class="ctn-table table-responsive">
            <table class="table table-bordered" id="table">
                <thead>
                    <tr>
                        <th class="text-center align-middle">Date Submitted</th>
                        <th class="text-center align-middle">Reason</th>
                        <th class="text-center align-middle">Status</th>
                        <th class="text-center align-middle">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($letters as $letter)
                        <tr>
                            <td>{{ $letter->datesubmitted }}</td>
                            <td>{{ $letter->reason }}</td>
                            <td>{{ $letter->ltestatus }}</td>
                            <td><a href="{{ route('subtleinfo', $letter->lid) }}" id="view">View</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <script src="{{ asset('js/scholar.js') }}"></script>
</body>

</html>
