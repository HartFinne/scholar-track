<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Appointment</title>
    <link href="https://cdn.lineicons.com/4.0/lineicons.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
        <link rel="stylesheet" href="{{ asset('css/appointment.css') }}">
        <link rel="stylesheet" href="{{ asset('css/partial.css') }}">
</head>

<body>
    <!-- Include Sidebar -->
    @include('partials._sidebar')

    <!-- Include Navbar -->
    @include('partials._navbar')

    <div class="ctn-main">
        <a href="" class="goback">&lt Go back</a>

        <div class="appointment-view">
            <div class="appointment-status">
                <h6 class="appointment-stat">PENDING</h6>
            </div>

            <div class="appointment-info">
                <h5>SCHEDULED APPOINTMENT</h5>
                <div class="info">
                    <div class="label">Name:</div>
                    <div class="value"><span>Dela Cruz, Juan, Santos</span></div>

                    <div class="label">School:</div>
                    <div class="value"><span>Polytechnic University of the Philippines</span></div>

                    <div class="label">Year Level:</div>
                    <div class="value"><span>--</span></div>

                    <div class="label">Course/Strand:</div>
                    <div class="value"><span>--</span></div>

                    <div class="label">Appointment:</div>
                    <div class="value"><span>--</span></div>

                    <div class="label">Schedule:</div>
                    <div class="value"><span>--</span></div>
                </div>
            </div>
        </div>
    </div>
</body>
 </html>
