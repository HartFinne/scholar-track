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
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="title">Regular Allowance</h1>
            </div>
            <div class="col-md-6">
                <a href="{{ route('regularform') }}" class="btn-request fw-bold" style="margin-left: auto">Regular
                    Allowance Form <i class="fas fa-angle-double-right"></i></a>
            </div>
        </div>

        <p class="table-title"> My Requests </p>
        <div class="ctn-table table-responsive">
            <table class="table table-bordered">
                <thead class="table-success">
                    <tr>
                        <th class="text-center align-middle">#</th>
                        <th class="text-center align-middle">Academic Year</th>
                        <th class="text-center align-middle">Semester</th>
                        <th class="text-center align-middle">Date Submitted</th>
                        <th class="text-center align-middle">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($requests as $index => $req)
                        <tr>
                            <td class="text-center align-middle">{{ $index + 1 }}</td>
                            <td class="text-center align-middle">{{ $req->schoolyear }}</td>
                            <td class="text-center align-middle">{{ $req->semester }}</td>
                            <td class="text-center align-middle">
                                {{ Carbon\Carbon::parse($req->created_at)->format('F j, Y') }}</td>
                            <td class="text-center align-middle">
                                <a href="{{ route('regularforminfo', $req->regularID) }}"
                                    class="btn btn-sm btn-success"><i class="fas fa-eye"></i></a>
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
    </div>
    <script src="{{ asset('js/scholar.js') }}"></script>
</body>

</html>
