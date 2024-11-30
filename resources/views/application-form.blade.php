<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Application Form</title>
    <style>
        * {
            margin: 0;
            padding: 0;
        }

        body {
            margin: 0;
            padding: 25px;
            font-family: Arial, Helvetica, sans-serif;
        }

        .logo {
            height: 50px;
        }
    </style>
</head>

<body>
    <div class="page1">
        <img src="{{ public_path('images/header.png') }}" alt="Logo" class="logo">
        <span class="lbl-eap">Educational Assistance Program</span>
        <span class="lbl-ap">Application Form</span>
        <span class="lbl-sy">S.Y. {{ date('Y') }}-{{ date('Y') + 1 }}</span>
        <div class="group1">
            <span>PERSONAL INFORMATION</span>
            <span>Status: <strong>NEW</strong></span>
            <span>Case Code <strong>{{ $applicant->casecode }}</strong></span>
            <span>Form No.</span>
        </div>
    </div>
</body>

</html>
