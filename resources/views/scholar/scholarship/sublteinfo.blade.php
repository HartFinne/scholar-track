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
                <h5>LTE SUBMITTED</h5>
                <div class="info">
                    <div class="label">Date Submitted</div>
                    <div class="value">:
                        <span>{{ \Carbon\Carbon::parse($letter->datesubmitted)->format('m/d/Y') }}</span>
                    </div>



                    <div class="label">Concern</div>

                    @if ($concerncsregistration && $concerncsregistration->csrid === $letter->conditionid)
                        <div class="value">: <span>{{ $concerncsregistration->registatus }} in
                                {{ $letter->eventtype }}</span>
                        </div>
                    @elseif ($concernhcattendance && $concernhcattendance->hcaid === $letter->conditionid)
                        <div class="value">: <span>{{ $concernhcattendance->hcastatus }} in
                                {{ $letter->eventtype }}</span>
                        </div>
                    @endif

                    <div class="label">Explanation Letter</div>
                    @if (in_array($fileExtensionExplanation, ['jpeg', 'jpg', 'png']))
                        <img src="{{ url('storage/' . $letter->explanation) }}" alt="Report Card"
                            style="max-width: 100%; height: auto;">
                    @elseif($fileExtensionExplanation === 'pdf')
                        <!-- Display PDF files -->
                        <iframe src="{{ url('storage/' . $letter->explanation) }}" width="100%" height="600px">
                            Your browser does not support iframes. Please download the PDF file
                            <a href="{{ url('storage/' . $letter->explanation) }}">here</a>.
                        </iframe>
                    @endif

                    <div class="label">Reason</div>
                    <div class="value">: <span>{{ $letter->reason }}</span></div>

                    <div class="label">Proof</div>
                    @if (in_array($fileExtensionProof, ['jpeg', 'jpg', 'png']))
                        <img src="{{ url('storage/' . $letter->proof) }}" alt="Report Card"
                            style="max-width: 100%; height: auto;">
                    @elseif($fileExtensionProof === 'pdf')
                        <!-- Display PDF files -->
                        <iframe src="{{ url('storage/' . $letter->proof) }}" width="100%" height="600px">
                            Your browser does not support iframes. Please download the PDF file
                            <a href="{{ url('storage/' . $letter->proof) }}">here</a>.
                        </iframe>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/scholar.js') }}"></script>
</body>

</html>
