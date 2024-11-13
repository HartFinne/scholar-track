<!DOCTYPE html>
<html lang="en">

<head>
    <title>View LTE</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/lteinfo.css') }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
</head>

<body>
    <!-- PAGE HEADER -->
    @include('partials._pageheader')
    <x-alert />
    <!-- MAIN -->
    <div class="ctnmain">
        <div class="container">
            <div class="container rounded bg-success p-3 mx-auto mb-2" style="width: 80%">
                <div class="row">
                    <div class="col-md-10 text-light fw-bold h4 my-auto">{{ $scholar->basicInfo->scLastname }},
                        {{ $scholar->basicInfo->scFirstname }}
                    </div>
                    <div class="col-md-2">
                        <a href="{{ route('lte') }}" class="btn btn-success w-100">&lt Go back</a>
                    </div>
                </div>
                <div class="border-bottom my-3"></div>
                <form class="row" method="post" action="{{ route('updateltestatus', $letter->lid) }}">
                    @csrf
                    <div class="col-md-4 text-light fw-bold h4 my-auto">LTE Status</div>
                    <div class="col-md-6 ">
                        <select class="fw-bold text-success form-select" name="ltestatus">
                            <option value="No Response" {{ $letter->ltestatus == 'No Response' ? 'selected' : '' }}>No
                                Response
                            </option>
                            <option value="To Review" {{ $letter->ltestatus == 'To Review' ? 'selected' : '' }}>To
                                Review
                            </option>
                            <option value="Excused" {{ $letter->ltestatus == 'Excused' ? 'selected' : '' }}>Excused
                            </option>
                            <option value="Unexcused" {{ $letter->ltestatus == 'Unexcused' ? 'selected' : '' }}>
                                Unexcused
                            </option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-outline-light w-100">Update</button>
                    </div>
                </form>
            </div>

            <div class="lte mb-3 border rounded border-success">
                <div class="container bg-success fw-bold h4 py-2 text-light text-center">Violation Details</div>
                <div class="row mb-2">
                    <div class="col-md-4">Name</div>
                    <div class="col-md-8 fw-bold">{{ $scholar->basicInfo->scLastname }},
                        {{ $scholar->basicInfo->scFirstname }}</div>
                </div>

                <div class="row mb-2">
                    <div class="col-md-4">Concern</div>
                    <div class="col-md-8 fw-bold">
                        {{ $letter->violation }}
                        @if ($letter->eventtype)
                            in {{ $letter->eventtype }}
                        @endif
                    </div>
                </div>

                <div class="row mb-2">
                    <div class="col-md-4">Date Issued</div>
                    <div class="col-md-8 fw-bold">
                        {{ \Carbon\Carbon::parse($letter->dateissued)->format('F j, Y') }}
                    </div>
                </div>

                @if ($letter->ltestatus == 'No Response')
                    <div class="container bg-warning fw-bold h4 py-2 text-dark text-center mt-4">Scholar Have Not Yet
                        Responded</div>
                @else
                    <div class="container bg-success fw-bold h4 py-2 text-light text-center mt-4">Scholar's Response
                    </div>
                    <div class="info">
                        <div class="row mb-2">
                            <div class="col-md-4">Date Submitted</div>
                            <div class="col-md-8 fw-bold">
                                {{ \Carbon\Carbon::parse($letter->datesubmitted)->format('F j, Y') }}
                            </div>
                        </div>

                        <div class="row mb-2">
                            <div class="col-md-4">Reason</div>
                            <div class="col-md-8 fw-bold">
                                {{ $letter->reason }}
                            </div>
                        </div>

                        <div class="container mt-3 mb-1 py-1 fw-bold bg-success text-center text-light">Explanation
                            Letter
                        </div>
                        @php
                            $explanationExtension = pathinfo($letter->explanation, PATHINFO_EXTENSION);
                        @endphp @if (in_array($explanationExtension, ['jpeg', 'jpg', 'png']))
                            <img src="{{ url('storage/' . $letter->explanation) }}" alt="Explanation Letter"
                                style="width: 100%; height: auto; max-height: 600px">
                        @elseif($explanationExtension === 'pdf')
                            <!-- Display PDF files -->
                            <iframe src="{{ url('storage/' . $letter->explanation) }}"
                                style="width: 100%; height: 600px;">
                                Your browser does not support iframes. Please download the PDF file
                                <a href="{{ url('storage/' . $letter->explanation) }}" target="_blank">click here</a>.
                            </iframe>
                        @endif

                        <div class="container mt-3 mb-1 py-1 fw-bold bg-success text-center text-light">Proof
                        </div>
                        @php
                            $proofExtension = pathinfo($letter->proof, PATHINFO_EXTENSION);
                        @endphp
                        @if (in_array($proofExtension, ['jpeg', 'jpg', 'png']))
                            <img src="{{ url('storage/' . $letter->proof) }}" alt="Proof"
                                style="width: 100%; height: auto; max-height: 600px">
                        @elseif($proofExtension === 'pdf')
                            <!-- Display PDF files -->
                            <iframe src="{{ url('storage/' . $letter->proof) }}" width="100%" height="600px">
                                Your browser does not support iframes. Please download the PDF file
                                <a href="{{ url('storage/' . $letter->proof) }}">here</a>.
                            </iframe>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>

    <script src="{{ asset('js/headercontrol.js') }}"></script>
</body>

</html>
