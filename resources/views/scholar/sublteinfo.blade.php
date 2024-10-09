<!DOCTYPE html>
<html lang="en">

<head>
    <title>View Submitted LTE</title>
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
        <a href="{{ route('sclte') }}" class="goback">&lt Go back</a>
        <div class="text-center">
            <h1 class="sub-title">LTE Submission</h1>
        </div>

        <div class="lte-view">
            <div class="lte-status">
                <h5 class="lte-stat">PENDING</h5>
            </div>

            <div class="lte-info">
                <h4>LTE SUBMITTED</h4>
                <div class="info">
                    <div class="label">Date Submitted</div>
                    <div class="value">: <span>MM/DD/YYYY</span></div>

                    <div class="label">Concern</div>
                    <div class="value">: <span>Absent in Humanities Class</span></div>

                    <div class="label">Explanation Letter</div>
                    <div class="value">: <span>file.pdf</span></div>

                    <div class="label">Reason</div>
                    <div class="value">: <span>Medical</span></div>

                    <div class="label">Proof</div>
                    <div class="value">: <span>file.pdf</span></div>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/scholar.js') }}"></script>
</body>

</html>
