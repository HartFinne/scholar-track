<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration</title>
    <link rel="stylesheet" href="{{ asset('css/appconfirmdialog.css') }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
</head>

<body style="height: 100vh">
    <div class="ctn-navbar">
        <div class="logo">
            <img src="{{ asset('images/logo.png') }}" alt="Logo">
            <h4 class="fw-bold">Tzu Chi Philippines<br>Educational Assistance Program</h4>
        </div>
    </div>

    <div class="dialog">
        <h1><i class="fa-regular fa-circle-check" style="color: green;"></i></h1>
        <h3 class="fw-bold">Account Successfully Created!</h3>
        <p>Welcome aboard! Your account has been set up, and you're almost ready to dive in.</p>
        <p>Please use the following credentials to log in:</p>
        <ul>
            <li>Case Code: <strong>{{ $casecode }}</strong></li>
            <li>Password: <strong>{{ $password }}</strong></li>
        </ul>
        <p>Keep your login details safe to ensure your account remains secure.</p>
        <a href="{{ route('scholar-login') }}" class="btn btn-success mb-4" style="color: white;">Login Now</a>
        <p>If you have any issues logging in or any questions, please do not hesitate to contact our support team at
            inquiriescholartrack@gmail.com.</p>
        <p>Thank you!</p>
    </div>
</body>

</html>
