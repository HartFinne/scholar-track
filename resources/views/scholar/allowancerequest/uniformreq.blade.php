<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Uniform Allowance Request</title>
    <link href="https://cdn.lineicons.com/4.0/lineicons.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
        integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ asset('css/allowanceinstructions.css') }}">
    <link rel="stylesheet" href="{{ asset('css/partial.css') }}">
</head>

<body>
    <!-- Include Sidebar -->
    @include('partials._sidebar')

    <!-- Include Navbar -->
    @include('partials._navbar')

    <!-- UNIFORM ALLOWANCE REQUEST -->
    <div class="ctn-main">
        <a href="{{ route('scspecial') }}" class="goback">&lt Go back</a>
        <h2 class="title">Uniform Allowance Request</h2>
        <div class="instructions">
            <h6>Step 1:</h6>
            <p>Ihanda ang Certificate of Enrollment or OJT Certificate (Screenshot o patunay na
                kinakailangang bilhin ang Uniform sa OJT). I-compile sa isang PDF File kung marami.
                Tandaan na maari lamang mag-request ng Uniform Allowance kung ito ang iyong Una o
                Ikatlong Taon bilang Scholar.</p>

            <h6>Step 2:</h6>
            <p>Ihanda ang resibo at picture ng mga biniling uniform. Kung walang resibong ibinibigay ang tindahan o
                binilhan,
                ihanda ang Acknowledgement Receipt at papirmahan sa tindahan o sa binilhan.</p>

            <h6>Step 3:</h6>
            <p>I-download at ihanda ang Liquidation Form,
                siguraduhing nasagutan ang lahat ng kinakailangan sa Liquidation Form.</p>

            <h6>Step 4:</h6>
            <p>I-submit ang mga details kasama ang picture at documents sa Uniform Request Form.</p>

            <div class="reminder">
                <div class="reminder1">
                    <p><strong> Reminder: DO NOT DISCARD OR MISPLACE THE HARDCOPY OF THE RECEIPTS AND DOCUMENTS</strong>
                    </p>
                    <p><strong><em>PAALALA: HUWAG ITAPON O IWALA ANG MGA ORIHINAL NA RESIBO AT DOKUMENTO.</em></strong>
                    </p>
                </div>

                <div class="reminder2">
                    <p>Failure to do so may result in a delay or non-processing of your special allowance request.</p>
                    <p><em>Ang hindi paggawa nito ay maaaring magresulta sa pagkaantala o hindi pagproseso ng iyong
                            request para sa special allowance.</em></p>
                </div>
            </div>

            <h6 class="text-center pt-2">Downloadable files:</h6>
            <div class="file-download">
                <a href="{{ asset('storage/' . $acknowledgement->pathname) }}" class="download-btn"
                    download>Acknowledgement
                    Receipt<i class="fas fa-download"></i></a>
                <a href="{{ asset('storage/' . $liquidation->pathname) }}" class="download-btn" download>Liquidation
                    Form<i class="fas fa-download"></i></a>
            </div>

            <div class="sub-form text-center">
                <a href="{{ route('showrequestform', 'UAR') }}" class="form-btn">Go to submission form</a>
            </div>
        </div>
    </div>
    <script src="{{ asset('js/scholar.js') }}"></script>
</body>

</html>
