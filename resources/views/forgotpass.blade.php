<!DOCTYPE html>
<html lang="en">

<head>
    <title>Forgot Password</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
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
    </div>

    <!-- MAIN CONTENT -->
    <div class="container d-flex justify-content-center align-items-center"
        style="min-height: 70vh; padding-top: 20px; padding-bottom: 40px;">
        <div class="col-md-6">
            @if (session('success'))
                <div class="alert alert-success" role="alert">
                    {{ session('success') }}
                </div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger" role="alert">
                    {{ session('error') }}
                </div>
            @endif

            <form class="card p-5 shadow-lg" method="POST" action="{{ route('verifyfprequest') }}">
                @csrf
                <span class="text-center mb-4 fw-bold" style="font-family: Arial; font-size: 32px">Forgot your
                    password?</span>
                <p class="text-center mb-4">Enter your email, and we'll send you a link to reset your password.</p>

                <div class="mb-3">
                    <input type="email" class="form-control text-center" id="email" name="email" required
                        placeholder="example@gmail.com">
                </div>

                <button type="submit" class="btn btn-success w-100 mb-3">Send Email</button>

                <div class="text-center">
                    <a href="{{ route('roleselection') }}" id="btnregister">Back to login</a>
                </div>
            </form>
        </div>
    </div>

</body>

</html>
