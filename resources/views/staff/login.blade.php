<!DOCTYPE html>

<html lang="en">

<head>
    <title>Login</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body style="background-color: #eaebea;">
    <!-- PAGE HEADER -->
    <div class="header">
        <div class="ctnlogo">
            <img src="{{ asset('images/logo.png') }}" id="headerlogo" alt="logo.png">
        </div>
        <div class="headertitle">
            <span style="color: #2e7c55;">Tzu Chi Philippines</span>
            <span style="color: #1a5319;">Educational Assistance Program</span>
        </div>
        <a href="{{ route('roleselection') }}" id="btnexit"><i class="fas fa-arrow-left"></i></a>
    </div>

    <!-- MAIN CONTENT -->
    <div class="maincontent">
        <!-- Display error message if login fails -->
        @if (session('error'))
            <div class="alert alert-danger" role="alert" id="ctnerrormsg">
                {{ session('error') }}
            </div>
        @endif

        <!-- Update the form action and method -->
        <form class="groupA" method="POST" action="{{ route('log-worker') }}">
            @csrf <!-- Add CSRF token for security -->
            <span id="formtitle">Staff Portal</span>
            <span id="formsubtitle">Sign in to start your session.</span>

            <!-- Email Input -->
            <span class="label">Email Address</span>
            <input class="input" type="email" name="email" id="inemail" required>

            <!-- Password Input -->
            <span class="label">Password</span>
            <input class="input" type="password" name="password" id="inpassword" required>

            <!-- Forgot password link -->
            <a href="{{ route('forgotpass') }}" id="btnforgotpass">Forgot password?</a>

            <!-- Submit Button -->
            <button type="submit" id="btnlogin">Sign In</button>
        </form>
    </div>

    <script src="{{ asset('js/hcattendancecontrol.js') }}"></script>
</body>

</html>
