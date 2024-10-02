<!DOCTYPE html>
<html lang="en">

<head>
    <title>Grades Submission</title>
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
        <div class="text-center">
            <h1 class="sub-title">Grades Submission</h1>
            <p class="desc">Submit your GWA and the scanned copy of pdf file of your grades.</p>
        </div>

        <form action="" class="grade-form text-center">
            <input type="text" class="gwa" id="gwa" placeholder="General Weighted Average">
            <input type="file" class="file">
            <button type="submit" class="btn-submit fw-bold">Submit</button>
        </form>

        <div class="status">
            <p class="table-title">Grades Status</p>
            <div class="filter">
                <button class="filter-btn">All</button>
                <button class="filter-btn">Passed</button>
                <button class="filter-btn">Failed</button>
            </div>
        </div>

        <div class="ctn-table table-responsive">
            <table class="table table-bordered" id="table">
                <thead>
                    <tr>
                        <th class="text-center align-middle">School Year</th>
                        <th class="text-center align-middle">Semester</th>
                        <th class="text-center align-middle">GWA</th>
                        <th class="text-center align-middle">Status</th>
                        <th class="text-center align-middle">Action</th>

                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>S.Y. 2022-2023</td>
                        <td>1ST SEMESTER</td>
                        <td>1.25</td>
                        <td>PASSED</td>
                        <td><a href="gradesinfo.html" id="view">View</a></td>
                </tbody>
            </table>
        </div>
    </div>

    <script src="{{ asset('js/scholar.js') }}"></script>
</body>

</html>
