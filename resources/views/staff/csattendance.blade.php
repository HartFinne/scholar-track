<!DOCTYPE html>

<html lang="en">

<head>
    <title>Community Service</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/table.css') }}">
    <link rel="stylesheet" href="{{ asset('css/communityservice.css') }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body>
    <!-- PAGE HEADER -->
    @include('partials._pageheader')

    <div class="ctnmain">
        <div class="col-md-1" style="margin-left: auto">
            {{-- <a href="{{ route('viewcseventinfo', $event->csid) }}" class="btn btn-success">Go back</a> --}}
            <a href="" class="btn btn-success">Go back</a>
        </div>
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert" id="success-alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert" id="error-alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        <fieldset>
            <legend class="pagetitle">Event Info</legend>
            <div class="row">
                <!-- Left Column for Event Details -->
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-md-4 col-form-label">Event Title</label>
                        <div class="col-md-8">
                            <p class="form-control-plaintext">Community Clean-Up Day</p>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-4 col-form-label">Event Location</label>
                        <div class="col-md-8">
                            <p class="form-control-plaintext">Riverbank Park</p>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-4 col-form-label">Meeting Place</label>
                        <div class="col-md-8">
                            <p class="form-control-plaintext">Main Entrance Gate</p>
                        </div>
                    </div>
                </div>
                <!-- Right Column for Time Details -->
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-md-4 col-form-label">Start Time</label>
                        <div class="col-md-8">
                            <p class="form-control-plaintext">8:00 AM</p>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-4 col-form-label">End Time</label>
                        <div class="col-md-8">
                            <p class="form-control-plaintext">1:00 PM</p>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-4 col-form-label">Call Time</label>
                        <div class="col-md-8">
                            <p class="form-control-plaintext">7:45 AM</p>
                        </div>
                    </div>
                </div>
            </div>
        </fieldset>

        <fieldset>
            <legend class="pagetitle">Attendance</legend>
            <div class="row">
                <!-- Left Column for Basic Attendance Details -->
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-md-4 col-form-label">Name</label>
                        <div class="col-md-8">
                            <p class="form-control-plaintext">Luisa Gomez</p>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-4 col-form-label">Time In</label>
                        <div class="col-md-8">
                            <p class="form-control-plaintext">8:03 AM</p>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-4 col-form-label">Time Out</label>
                        <div class="col-md-8">
                            <p class="form-control-plaintext">12:57 PM</p>
                        </div>
                    </div>
                </div>
                <!-- Right Column for Additional Attendance Details -->
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-md-4 col-form-label">Tardiness Duration</label>
                        <div class="col-md-8">
                            <p class="form-control-plaintext">3 Minutes Late</p>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-4 col-form-label">Total Hours Rendered</label>
                        <div class="col-md-8">
                            <p class="form-control-plaintext">4.9 Hours</p>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-4 col-form-label">Attendance Status</label>
                        <div class="col-md-8">
                            <p class="form-control-plaintext">Completed</p>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Single Column for Image Proof -->
            <div class="form-group row">
                <label class="col-md-2 col-form-label">Attendance Proof</label>
                <div class="col-md-10">
                    <img src="images/proof.jpg" alt="Attendance Proof Photo" class="img-thumbnail">
                </div>
            </div>
        </fieldset>
        <div class="row justify-content-center align-items-center">
            <div class="col-auto">
                <a href="#" class="btn btn-success">Prev</a>
            </div>
            <div class="col-auto">
                <span>#</span>
            </div>
            <div class="col-auto">
                <a href="#" class="btn btn-success">Next</a>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/headercontrol.js') }}"></script>
</body>

</html>
