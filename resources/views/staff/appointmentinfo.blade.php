<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Appointment</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('css/appointment.css') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body>
    <!-- Include Sidebar -->
    @include('partials._pageheader')
    <x-alert />

    <div class="ctnmain">
        <div class="container-fluid col-md-8 mx-auto border-none">
            <div class="row">
                <div class="col-md-2 mb-3">
                    <a href="{{ route('appointments') }}" class="btn btn-success w-100">Go Back</a>
                </div>
                <div class="col-md-7 mb-2">
                    <input type="text" value="{{ $appointment->status ?? '' }}"
                        class="shadow border-light  form-control text-white fw-bold text-center {{ $appointment->status == 'Pending'
                            ? 'bg-warning border-warning'
                            : ($appointment->status == 'Approved' || $appointment->status == 'Completed'
                                ? 'bg-success border-success'
                                : ($appointment->status == 'Cancelled' || $appointment->status == 'Denied'
                                    ? 'bg-danger border-danger'
                                    : '')) }}"
                        readonly>
                </div>
                <div class="col-md-3">
                    <button type="button" class="btn btn-warning w-100" data-bs-toggle="modal"
                        data-bs-target="#updateStatusModal">
                        Update Status
                    </button>
                </div>
            </div>

            <div class="card mx-auto mt-2 shadow">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">Scheduled Appointment</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p class="mb-2"><strong>Name:</strong> {{ $appointment->basicInfo->scLastname }},
                                {{ $appointment->basicInfo->scFirstname }}
                                {{ $appointment->basicInfo->scMiddlename }}</p>
                            <p class="mb-2"><strong>School:</strong> {{ $appointment->education->scSchoolName }}</p>
                            <p class="mb-2"><strong>Year Level:</strong> {{ $appointment->education->scYearGrade }}
                            </p>
                            <p class="mb-2"><strong>Course/Strand:</strong>
                                {{ $appointment->education->scCourseStrandSec }}</p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-2"><strong>Agenda:</strong> {{ $appointment->reason }}</p>
                            <p class="mb-2"><strong>Date:</strong>
                                {{ \Carbon\Carbon::parse($appointment->date)->format('F d, Y') }}</p>
                            <p class="mb-2"><strong>Time:</strong> {{ $appointment->time }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="updateStatusModal" tabindex="-1" aria-labelledby="updateStatusModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-warning fw-bold">
                        <h5 class="modal-title" id="updateStatusModalLabel">Update Appointment Status</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form method="post" action="{{ route('updateappointmentstatus', $appointment->id) }}">
                        @csrf
                        <div class="modal-body">
                            <label for="status" class="fw-bold mb-2">Update Status:</label>
                            <select class="form-select border-success" name="status">
                                <option value="Accepted" {{ $appointment->status == 'Approved' ? 'selected' : '' }}>
                                    Approve</option>
                                <option value="Rejected" {{ $appointment->status == 'Denied' ? 'selected' : '' }}>
                                    Deny</option>
                                <option value="Completed" {{ $appointment->status == 'Completed' ? 'selected' : '' }}>
                                    Complete</option>
                                <option value="Cancelled" {{ $appointment->status == 'Cancelled' ? 'selected' : '' }}>
                                    Cancel</option>
                            </select>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-warning">Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script src="{{ asset('js/headercontrol.js') }}"></script>
</body>

</html>
