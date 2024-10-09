<!DOCTYPE html>
<html lang="en">

<head>
    <title>Letter of Explanation</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="{{ asset('css/scholar.css') }}">
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
                    <tr>
                        <td>09/11/2024</td>
                        <td>Absent in Community Service</td>
                        <td>09/14/2024</td>
                        <td><a href="{{ route('lteinfo') }}" id="view">View</a></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="status">
            <p class="table-title">Letter Status</p>
            <div class="filter">
                <button class="filter-btn">All</button>
                <button class="filter-btn">Pending</button>
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
                    <tr>
                        <td>09/12/2024</td>
                        <td>Absent in Humanities Class</td>
                        <td>PENDING</td>
                        <td><a href="{{ route('subtleinfo') }}" id="view">View</a></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <script src="{{ asset('js/scholar.js') }}"></script>
</body>

</html>
