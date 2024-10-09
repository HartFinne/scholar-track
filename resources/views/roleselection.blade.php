<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tzu Chi Philippines - EAP</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/role.css') }}">
</head>

<body>
    <div class="row vh-100 g-0">
        <div class="col-lg-8 position-relative d-none d-lg-block">
            <div class="bg-holder" style="background-image: url('{{ asset('images/tzu-chi-bg.jpg') }}');"></div>

        </div>

        <div class="col-lg-4">
            <div class="row align-items-center justify-content-center h-100 g-0 px-4 px-sm-0">
                <div class="col col-sm-7 col-lg-9 col-xl-9">
                    <a href="#" class="d-flex justify-content-center mb-4">
                        <img src="{{ asset('images/logo.png') }}" alt="" width="150">
                    </a>

                    <div class="text-center mb-3">
                        <h2 class="fw-bold">Welcome to Tzu Chi PH!</h2>
                        <p class="text-secondary">Please click or tap you destination.</p>
                    </div>

                    <form action="{{ route('scholar-login') }}" method="get">
                        <button class="btn btn-outline-secondary bnt-lg w-100 m-2" type="submit">Scholar</button>
                    </form>
                    <button class="btn btn-outline-secondary bnt-lg w-100 m-2">Admin</button>

                    <div class="text-center mt-5">
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
