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
          @if (session('status'))
          <div class="alert alert-success">
              {{ session('status') }}
          </div>
      @endif

      @if ($errors->any())
          <div class="alert alert-danger">
              <ul>
                  @foreach ($errors->all() as $error)
                      <li>{{ $error }}</li>
                  @endforeach
              </ul>
          </div>
      @endif

            <form class="card p-5 shadow-lg" method="POST" action="{{ route('password.update') }}">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">
                <input type="hidden" name="email" value="{{ request()->get('email') }}">


                <span class="text-center mb-4 fw-bold" style="font-family: Arial; font-size: 32px">Reset Password</span>

                <div class="mb-3">
                  <label for="password" class="form-label">New Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
                </div>

                <div class="mb-3">
                  <label for="password_confirmation" class="form-label">Confirm Password</label>
                  <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                </div>

                <button type="submit" class="btn btn-primary">Reset Password</button>
        </form>

          
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
