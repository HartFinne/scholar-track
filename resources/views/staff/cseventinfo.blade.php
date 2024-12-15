<!DOCTYPE html>

<html lang="en">

<head>
    <title>Community Service</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/table.css') }}">
    <link rel="stylesheet" href="{{ asset('css/csinfo.css') }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body>
    <!-- PAGE HEADER -->
    @include('partials._pageheader')
    <x-alert />

    <div class="ctnmain">
        <div class="groupA">
            <span class="pagetitle">Community Service Details</span>
            <a href="{{ route('communityservice') }}" class="btn btn-success">Go back</a>
        </div>

        <div class="container mt-4">
            <!-- First Row -->
            <div class="row mb-3 justify-content-center">
                <div class="col-md-4">
                    <div class="fw-bold">Title:</div>
                    <div class="border-bottom border-dark">{{ $event->title }}</div>
                </div>
                <div class="col-md-4">
                    <div class="fw-bold">Date:</div>
                    <div class="border-bottom border-dark">
                        {{ Carbon\Carbon::parse($event->eventdate)->format('F j, Y') }}
                    </div>
                </div>
            </div>

            <!-- Second Row -->
            <div class="row mb-3 justify-content-center">
                <div class="col-md-4">
                    <div class="fw-bold">Location:</div>
                    <div class="border-bottom border-dark">{{ $event->eventloc }}</div>
                </div>
                <div class="col-md-4">
                    <div class="fw-bold">Start Time:</div>
                    <div class="border-bottom border-dark">{{ $event->starttime }}</div>
                </div>
            </div>

            <!-- Third Row -->
            <div class="row mb-3 justify-content-center">
                <div class="col-md-4">
                    <div class="fw-bold">Meeting Place:</div>
                    <div class="border-bottom border-dark">{{ $event->meetingplace }}</div>
                </div>
                <div class="col-md-4">
                    <div class="fw-bold">Call Time:</div>
                    <div class="border-bottom border-dark">{{ $event->calltime }}</div>
                </div>
            </div>

            <!-- Fourth Row -->
            <div class="row mb-3 justify-content-center">
                <div class="col-md-4">
                    <div class="fw-bold">Volunteers Needed:</div>
                    <div class="border-bottom border-dark">{{ $event->slotnum }}</div>
                </div>
                <div class="col-md-4">
                    <div class="fw-bold">Registered Volunteers:</div>
                    <div class="border-bottom border-dark">{{ $event->volunteersnum }}</div>
                </div>
            </div>

            <!-- Fifth Row -->
            <div class="row mb-3 justify-content-center">
                <div class="col-md-4">
                    <div class="fw-bold">Facilitator:</div>
                    <div class="border-bottom border-dark">{{ $event->facilitator }}</div>
                </div>
                <div class="col-md-4">
                    <div class="fw-bold">Event Status:</div>
                    <div class="border-bottom border-dark">{{ $event->eventstatus }}</div>
                </div>
            </div>

            <!-- Edit Button -->
            <div class="row">
                <div class="col-md-10 text-end mt-3">
                    <button class="btn btn-warning fw-bold" data-bs-toggle="modal" data-bs-target="#editEventModal">
                        Edit Event
                    </button>
                </div>
            </div>
        </div>

        <div class="divider"></div>

        <div class="groupC">
            <span class="pagetitle">List of Registered Volunteers</span>
        </div>

        <div class="ctntable table-responsive">
            <table class="table table-bordered" id="tblcsevents">
                <thead>
                    <tr>
                        <th class="text-center align-middle">#</th>
                        <th class="text-center align-middle">Name</th>
                        <th class="text-center align-middle">Status</th>
                        <th class="text-center align-middle">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($volunteers as $index => $volunteer)
                        <tr>
                            <td class="text-center align-middle">{{ $index + 1 }}</td>
                            <td class="text-center align-middle">{{ $volunteer->basicInfo->scLastname }},
                                {{ $volunteer->basicInfo->scFirstname }} {{ $volunteer->basicInfo->scMiddlename }}
                            </td>
                            <td class="text-center align-middle">{{ $volunteer->registatus }}</td>
                            <td class="text-center align-middle">
                                @php
                                    $matchingAttendance = $attendances->firstWhere('caseCode', $volunteer->caseCode);
                                @endphp

                                @if ($matchingAttendance)
                                    <a href="{{ route('viewcsattendance', ['csid' => $volunteer->csid, 'casecode' => $volunteer->caseCode]) }}"
                                        class="btn btn-success">View Attendance</a>
                                @else
                                    <span class="text-danger">No Attendance Submitted</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="modal fade" id="editEventModal" tabindex="-1" aria-labelledby="editEventModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header bg-warning">
                        <h5 class="modal-title" id="editEventModalLabel">Edit Event</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form method="POST" action="{{ route('updatecsevent', $event->csid) }}"
                            enctype="multipart/form-data">
                            @csrf
                            <!-- First Row -->
                            <div class="row mb-3 justify-content-center">
                                <div class="col-md-6">
                                    <label for="title" class="form-label fw-bold">Title</label>
                                    <input type="text" class="form-control border-success" id="title"
                                        name="title" value="{{ $event->title }}">
                                </div>
                                <div class="col-md-6">
                                    <label for="eventdate" class="form-label fw-bold">Date</label>
                                    <input type="date" class="form-control border-success" id="eventdate"
                                        name="eventdate" value="{{ $event->eventdate }}">
                                </div>
                            </div>

                            <!-- Second Row -->
                            <div class="row mb-3 justify-content-center">
                                <div class="col-md-6">
                                    <label for="eventloc" class="form-label fw-bold">Location</label>
                                    <input type="text" class="form-control border-success" id="eventloc"
                                        name="eventloc" value="{{ $event->eventloc }}">
                                </div>
                                <div class="col-md-6">
                                    <label for="starttime" class="form-label fw-bold">Start Time</label>
                                    <input type="time" class="form-control border-success" id="starttime"
                                        name="starttime" value="{{ $event->starttime }}">
                                </div>
                            </div>

                            <!-- Third Row -->
                            <div class="row mb-3 justify-content-center">
                                <div class="col-md-6">
                                    <label for="meetingplace" class="form-label fw-bold">Meeting Place</label>
                                    <input type="text" class="form-control border-success" id="meetingplace"
                                        name="meetingplace" value="{{ $event->meetingplace }}">
                                </div>
                                <div class="col-md-6">
                                    <label for="calltime" class="form-label fw-bold">Call Time</label>
                                    <input type="time" class="form-control border-success" id="calltime"
                                        name="calltime" value="{{ $event->calltime }}">
                                </div>
                            </div>

                            <!-- Fourth Row -->
                            <div class="row mb-3 justify-content-center">
                                <div class="col-md-6">
                                    <label for="slotnum" class="form-label fw-bold">Volunteers Needed</label>
                                    <input type="number" class="form-control border-success" id="slotnum"
                                        name="slotnum" value="{{ $event->slotnum }}">
                                </div>
                                <div class="col-md-6">
                                    <label for="facilitator" class="form-label fw-bold">Facilitator</label>
                                    <input type="text" class="form-control border-success" id="facilitator"
                                        name="facilitator" value="{{ $event->facilitator }}">
                                </div>
                            </div>

                            <!-- Fifth Row -->
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="eventstatus" class="form-label fw-bold">Event Status</label>
                                    <select class="form-select border-success" id="eventstatus" name="eventstatus">
                                        <option value="Open" {{ $event->eventstatus == 'Open' ? 'selected' : '' }}>
                                            Open
                                        </option>
                                        <option value="Closed"
                                            {{ $event->eventstatus == 'Closed' ? 'selected' : '' }}>
                                            Closed
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" data-bs-dismiss="modal"
                                    class="btn btn-secondary fw-bold">Cancel</button>
                                <button type="submit" class="btn btn-success fw-bold">Save Changes</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <script src="{{ asset('js/headercontrol.js') }}"></script>

</body>

</html>
