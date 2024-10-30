<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tzu Chi Philippines - EAP</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/role.css') }}">
</head>

<body>
    <div class="row vh-100 g-0">
        <div class="col-lg-8 position-relative d-none d-lg-block">
            <div class="bg-holder"></div>
        </div>

        <div class="col-lg-4">
            <div class="row">
                <div class="col-md-1 m-2" style="position: absolute">
                    <a href="{{ route('mainhome') }}" class="btn btn-success"><i
                            class="fas fa-arrow-left text-white"></i></a>
                </div>
            </div>
            <div class="row align-items-center justify-content-center h-100 g-0 px-4 px-sm-0">
                <div class="col col-sm-7 col-lg-9 col-xl-9">
                    <a href="#" class="d-flex justify-content-center mb-4">
                        <img src="{{ asset('images/logo.png') }}" alt="" width="130">
                    </a>

                    <div class="text-center mb-2">
                        <h2 class="fw-bold">Welcome to Tzu Chi PH!</h2>
                        <p class="text-secondary">Please click or tap you destination.</p>
                    </div>

                    <a href="{{ route('scholar-login') }}" class="btn btn-outline-secondary bnt-lg w-100 m-2"
                        type="submit">Scholar</a>
                    <a href="{{ route('login-sw') }}" class="btn btn-outline-secondary bnt-lg w-100 m-2"
                        type="submit">Admin</a>

                    <a href="{{ route('login-applicant') }}" class="btn btn-outline-secondary bnt-lg w-100 m-2"
                        type="submit">Applicant</a>


                    <div class="text-center mt-3">
                        <p id="footer">By using this service, you understood and agree to the Tzu Chi Online Services
                            <a href="#">Terms of Use</a> and <a href="#">Privacy Statement</a>.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
