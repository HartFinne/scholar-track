<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Application Form</title>
    <link rel="stylesheet" href="{{ asset('css/appconfirmdialog.css') }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
</head>

<body>
    <div class="ctn-navbar">
        <div class="logo">
            <img src="{{ asset('images/logo.png') }}" alt="Logo">
            <h4 class="fw-bold">Tzu Chi Philippines<br>Educational Assistance Program</h4>
        </div>
    </div>

    <div class="dialog">
        <h1><i class="fa-regular fa-circle-check"></i></h1>
        <h3 class="fw-bold">Congratulations!</h3>
        <p>You have successfully submitted your scholarship application.</p>
        <p>To monitor the status of your application, login to your account using the credentials below:</p>
        <ul>
            <li>Case Code: <strong>{{ $casecode }}</strong></li>
            <li>Password: <strong>{{ $password }}</strong></li>
        </ul>
        <p>For your security, please change your password immediately after logging into your account. We recommend
            keeping your password confidential to prevent unauthorized access.</p>
        <a href="{{ route('login-applicant') }}">CLick here to login!</a>
    </div>

</body>

</html>
