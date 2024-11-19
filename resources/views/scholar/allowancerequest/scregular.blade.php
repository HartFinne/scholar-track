<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Regular Allowance</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="{{ asset('css/regular.css') }}">
    <link rel="stylesheet" href="{{ asset('css/partial.css') }}">
</head>

<body>
    <!-- Include Sidebar -->
    @include('partials._sidebar')

    <!-- Include Navbar -->
    @include('partials._navbar')
    <x-alert />

    <div class="ctn-main">
        <h1 class="title">Regular Allowance</h1>

        <a href="{{ route('regularform') }}" class="btn-request fw-bold">Request Regular Allowance</a>

        <p class="table-title"> My Requests </p>
        <div class="ctn-table table-responsive">
            <table class="table table-bordered">
                <thead class="table-success">
                    <tr>
                        <th class="text-center align-middle">#</th>
                        <th class="text-center align-middle">Semester</th>
                        <th class="text-center align-middle">Date of Request</th>
                        <th class="text-center align-middle">Status</th>
                        <th class="text-center align-middle">Date of Release</th>
                        <th class="text-center align-middle">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($requests as $request)
                        <tr>
                            <td>{{ $request->regularID }}</td>
                            <td>{{ $request->grades ? $request->grades->SemesterQuarter : 'N/A' }}</td>
                            <td>{{ $request->created_at->format('m/d/Y') }}</td>
                            <td>{{ $request->status }}</td>
                            <td>{{ $request->release_date ? $request->release_date->format('m/d/Y') : 'N/A' }}</td>
                            <td><a href="{{ route('regularforminfo', ['id' => $request->regularID]) }}"
                                    class="btn-view">View</a></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <script src="{{ asset('js/scholar.js') }}"></script>
</body>

</html>
