<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Regular Allowance</title>
    <link href="https://cdn.lineicons.com/4.0/lineicons.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
        integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ asset('css/regular.css') }}">
    <link rel="stylesheet" href="{{ asset('css/partial.css') }}">
</head>

<body>
    <!-- Include Sidebar -->
    @include('partials._sidebar')

    <!-- Include Navbar -->
    @include('partials._navbar')

    <div class="ctn-main">
        <h1 class="title">Regular Allowance</h1>

        <a href="{{ route('regularform') }}" class="btn-request fw-bold">Request Regular Allowance</a>

        <p class="table-title">My Requests</p>
        <div class="ctn-table table-responsive">
            <table class="table table-bordered">
                <thead class="table-success">
                    <tr>
                        <th class="text-center align-middle">ID</th>
                        <th class="text-center align-middle">Semester</th>
                        <th class="text-center align-middle">Date of Request</th>
                        <th class="text-center align-middle">Date of Release</th>
                        <th class="text-center align-middle">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>1</td>
                        <td>1st Semester</td>
                        <td>01/03/2024</td>
                        <td>01/05/2024</td>
                        <td><a href="{{ route('regularforminfo') }}" class="btn-view">View</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <script src="{{ asset('js/scholar.js') }}"></script>
</body>

</html>
