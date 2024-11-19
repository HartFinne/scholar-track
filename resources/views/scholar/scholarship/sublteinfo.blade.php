<!DOCTYPE html>
<html lang="en">

<head>
    <title>View Submitted LTE</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="{{ asset('css/overview.css') }}">
    <link rel="stylesheet" href="{{ asset('css/lteinfo.css') }}">
    <link rel="stylesheet" href="{{ asset('css/partial.css') }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
</head>

<body>
    <!-- Include Sidebar -->
    @include('partials._sidebar')

    <!-- Include Navbar -->
    @include('partials._navbar')
    <x-alert />

    <!-- MAIN -->
    <div class="ctn-main">
        <a href="{{ route('sclte') }}" class="goback">&lt Go back</a>
        <div class="text-center">
            <h1 class="sub-title">LTE Submission</h1>
        </div>

        <div class="lte-view">
            <div class="lte-status">
                <h6 class="lte-stat">{{ $letter->ltestatus }}</h6>
            </div>

            <div class="lte-info">
                <h5 class="mb-4">LTE SUBMITTED</h5>
                {{-- <div class="info"> --}}
                <!-- Date Submitted -->
                <div class="row mb-2">
                    <div class="col-md-3">Date Submitted</div>
                    <div class="col-md-6 fw-bold">{{ \Carbon\Carbon::parse($letter->datesubmitted)->format('F j, Y') }}
                    </div>
                </div>

                <!-- Concern -->
                <div class="row mb-2">
                    <div class="col-md-3">Concern</div>
                    <div class="col-md-6 fw-bold">
                        @if ($letter->eventtype)
                            {{ $letter->violation }} in {{ $letter->eventtype }}
                        @else
                            {{ $letter->violation }}
                        @endif
                    </div>
                </div>

                <!-- Reason -->
                <div class="row mb-2">
                    <div class="col-md-3">Reason</div>
                    <div class="col-md-6 fw-bold">{{ $letter->reason }}</div>
                </div>

                <!-- Explanation Letter -->
                <div class="row my-3">
                    <div class="col-md-12 text-center">Explanation Letter</div>
                    <div class="col-md-12">
                        @if (in_array($fileExtensionExplanation, ['jpeg', 'jpg', 'png']))
                            <img src="{{ url('storage/' . $letter->explanation) }}" alt="Explanation Letter"
                                class="img-fluid mt-2">
                        @elseif($fileExtensionExplanation === 'pdf')
                            <iframe src="{{ url('storage/' . $letter->explanation) }}#zoom=100" width="100%"
                                height="600px" class="mt-2 border">
                                Your browser does not support iframes. Please download the PDF file
                                <a href="{{ url('storage/' . $letter->explanation) }}">here</a>.
                            </iframe>
                        @endif
                    </div>
                </div>

                <!-- Proof -->
                <div class="row my-3">
                    <div class="col-md-12 text-center">Proof</div>
                    <div class="col-md-12">
                        @if (in_array($fileExtensionProof, ['jpeg', 'jpg', 'png']))
                            <img src="{{ url('storage/' . $letter->proof) }}" alt="Proof" class="img-fluid mt-2">
                        @elseif($fileExtensionProof === 'pdf')
                            <iframe src="{{ url('storage/' . $letter->proof) }}#zoom=100" width="100%" height="600px"
                                class="mt-2 border">
                                Your browser does not support iframes. Please download the PDF file
                                <a href="{{ url('storage/' . $letter->proof) }}">here</a>.
                            </iframe>
                        @endif
                    </div>
                </div>
            </div>
        </div>

    </div>
    </div>

    <script src="{{ asset('js/scholar.js') }}"></script>
</body>

</html>
