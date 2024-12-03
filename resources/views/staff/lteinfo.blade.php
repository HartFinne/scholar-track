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
            <div class="container rounded border border-success bg-light p-3 mx-auto mb-2" style="width: 80%">
                <div class="row">
                    <div class="col-md-10 text-success fw-bold h4 my-auto">
                        {{ $scholar->basicInfo->scLastname }},
                        {{ $scholar->basicInfo->scFirstname }}
                    </div>
                    <div class="col-md-2">
                        <a href="{{ route('lte') }}" class="btn btn-success w-100">&lt Go back</a>
                    </div>
                </div>
                <div class="border-bottom border-success my-3"></div>
                <div class="row justify-content-between">
                    <!-- Letter Status Display -->
                    <div class="col-md-10 d-flex align-items-center justify-content-between">
                        <div class="col-auto text-success fw-bold h4">Letter of Explanation
                            Status</div>
                        <div class="col-auto">
                            @php
                                if ($letter->ltestatus == 'Terminated' || $letter->ltestatus == 'Unexcused') {
                                    $css = 'bg-danger';
                                } elseif ($letter->ltestatus == 'Continuing' || $letter->ltestatus == 'Excused') {
                                    $css = 'bg-success';
                                } else {
                                    $css = 'bg-warning';
                                }
                            @endphp
                            <span class="fw-bold text-white text-center h4 form-control {{ $css }}">
                                {{ $letter->ltestatus }}
                            </span>
                        </div>
                    </div>
                    <!-- Update Button -->
                    <div class="col-md-2 text-end">
                        <button type="button" class="btn btn-outline-success w-100" data-bs-toggle="modal"
                            data-bs-target="#updateStatusModal">
                            Update
                        </button>
                    </div>
                </div>
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
                                <a href="{{ url('storage/' . $letter->explanation) }}" target="_blank" download>click
                                    here</a>.
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
                                <a href="{{ url('storage/' . $letter->proof) }}" target="_blank" download>here</a>.
                            </iframe>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Update Status Modal -->
    <div class="modal fade" id="updateStatusModal" tabindex="-1" aria-labelledby="updateStatusModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title" id="updateStatusModalLabel">Update Letter of Explanation Status</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="post" action="{{ route('updateltestatus', $letter->lid) }}">
                    @csrf
                    <div class="modal-body">
                        <div class="row gx-0 align-items-center mb-3">
                            <div class="col-md-6">
                                <span class="fw-bold my-auto">Status</span>
                            </div>
                            <div class="col-md-6">
                                <select class="fw-bold text-success form-select" name="ltestatus">
                                    @if ($letter->eventtype === null)
                                        <option value="Terminated"
                                            {{ $letter->ltestatus == 'Terminated' ? 'selected' : '' }}>
                                            Terminated</option>
                                        <option value="Continuing"
                                            {{ $letter->ltestatus == 'Continuing' ? 'selected' : '' }}>
                                            Continuing</option>
                                    @else
                                        <option value="Unexcused"
                                            {{ $letter->ltestatus == 'Unexcused' ? 'selected' : '' }}>Unexcused
                                        </option>
                                        <option value="Excused"
                                            {{ $letter->ltestatus == 'Excused' ? 'selected' : '' }}>Excused
                                        </option>
                                    @endif
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/headercontrol.js') }}"></script>
</body>

</html>
