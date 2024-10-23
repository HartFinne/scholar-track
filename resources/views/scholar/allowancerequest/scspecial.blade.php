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
        <a href="" class="goback">&lt Go back</a>
        <h1 class="title">Special Allowances:</h1>

        <!-- ALLOWANCE BUTTONS -->
        <div class="sallowance">
            <a href="{{ route('transpo') }}" class="btn-request">Transportation Reimbursement Request</a>
            <a href="{{ route('book') }}" class="btn-request">Book Allowance Request</a>
            <a href="{{ route('thesis') }}" class="btn-request">Thesis Allowance Request</a>
            <a href="{{ route('project') }}" class="btn-request">Project Allowance Request</a>
            <a href="{{ route('uniform') }}" class="btn-request">Uniform Allowance Request</a>
            <a href="{{ route('grad') }}" class="btn-request">Graduation Allowance Request</a>
            <a href="{{ route('fieldtrip') }}" class="btn-request">Field Trip, Training, Seminar Allowance Request</a>
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
        <div class="ctn-table table-responsive">
            <table class="table table-bordered">
                <thead class="table-success">
                    <tr>
                        <th class="text-center align-middle">ID</th>
                        <th class="text-center align-middle">Type of Request</th>
                        <th class="text-center align-middle">Date of Request</th>
                        <th class="text-center align-middle">Status</th>
                        <th class="text-center align-middle">Date of Release</th>
                        <th class="text-center align-middle">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>1</td>
                        <td>Transportation Reimbursement Request</td>
                        <td>02/04/2024</td>
                        <td>Completed</td>
                        <td>-</td>
                        <td><a href="{{ route('transpoinfo') }}" class="btn-view">View</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </main>
    <script src="{{ asset('js/scholar.js') }}"></script>
</body>
</html>
