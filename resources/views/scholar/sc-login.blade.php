<!DOCTYPE html>
<html>

<head>
    <title>Login</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body style="background-color: #eaebea;">

    @include('components.alert') <!-- Include the alert modal here -->

    <div class="header">
        <div class="ctnlogo">
            <img src="{{ asset('images/logo.png') }}" id="headerlogo" alt="logo.png">
        </div>
        <div class="headertitle">
            <span style="color: #2e7c55;">Tzu Chi Philippines</span>
            <span style="color: #1a5319;">Educational Assistance Program</span>
        </div>
        <a href="{{ route('mainhome') }}" id="btnexit"><i class="fas fa-arrow-left"></i></a>
    </div>


    <div class="maincontent">
        <form class="groupA" action="{{ route('scholar-login.post') }}" method="POST">
            @csrf
            <span id="formtitle">Scholar Portal</span>
            <span id="formsubtitle">Sign in to start your session.</span>
            @if ($errors->has('caseCode'))
                <div class="alert alert-danger">
                    <strong>{{ $errors->first('caseCode') }}</strong>
                </div>
            @endif
            <span class="label">Case Code</span>
            <input class="input" type="text" name="caseCode" id="caseCode" value="{{ old('caseCode') }}" required>
            <span class="label">Password</span>
            <input class="input" type="password" name="password" id="inpassword" value="{{ old('password') }}"
                required>
            <a href="forgotpass.html" id="btnforgotpass">Forgot password?</a>
            <button type="submit" id="btnlogin">Sign In</button>
            <a href=" {{ route('registration') }} ">Existing Scholar?</a>
        </form>
    </div>


</body>

</html>
