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

    <div class="ctnmain">
        <div class="groupA">
            <span class="pagetitle">Community Service Details</span>
            <a href="{{ route('communityservice') }}" id="btngoback">Go back</a>
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

        <form class="groupB" method="POST" action="{{ route('updatecsevent', $event->csid) }}"
            enctype="multipart/form-data">
            @csrf
            <div class="groupB1">
                <span class="label">Title</span>
                <input class="data" value="{{ $event->title }}" name="title">
            </div>
            <div class="groupB1">
                <span class="label">Location</span>
                <input class="data" value="{{ $event->eventloc }}" name="eventloc">
            </div>
            <div class="groupB1">
                <span class="label">Date</span>
                <input class="data" value="{{ $event->eventdate }}" name="eventdate">
            </div>
            <div class="groupB1">
                <span class="label">Start Time</span>
                <input class="data" value="{{ $event->starttime }}" name="starttime">
            </div>
            <div class="groupB1">
                <span class="label">Meeting Place</span>
                <input class="data" value="{{ $event->meetingplace }}" name="meetingplace">
            </div>
            <div class="groupB1">
                <span class="label">Call Time</span>
                <input class="data" value="{{ $event->calltime }}" name="calltime">
            </div>
            <div class="groupB1">
                <span class="label">Facilitator</span>
                <input class="data" value="{{ $event->facilitator }}" name="facilitator">
            </div>
            <div class="groupB1">
                <span class="label">Number of Volunteers Needed</span>
                <input class="data" value="{{ $event->slotnum }}" name="slotnum">
            </div>
            <div class="groupB1">
                <span class="label">Number of Registered Volunteers</span>
                <input class="data" value="{{ $event->volunteersnum }}" name="volunteersnum" disabled>
            </div>
            <div class="groupB1">
                <span class="label">Event Status</span>
                <select class="data" name="eventstatus">
                    <option value="Open" {{ $event->eventstatus == 'Open' ? 'selected' : '' }}>Open</option>
                    <option value="Closed" {{ $event->eventstatus == 'Closed' ? 'selected' : '' }}>Closed</option>
                    <option value="Completed" {{ $event->eventstatus == 'Completed' ? 'selected' : '' }}>Completed
                    </option>
                </select>
            </div>
            <button type='submit' id="btnsave">Save</button>
        </form>

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
                    {{-- @foreach ($volunteers as $index => $volunteer)
                        <tr>
                            <td class="text-center align-middle">{{ $index + 1 }}</td>
                            <td class="text-center align-middle">{{ $volunteer->caseCode }}</td>
                            <td class="text-center align-middle">{{ $volunteer->registatus }}</td>
                            <td class="text-center align-middle">
                                <a href="{{ route('viewcsattendance', [$volunteer->csid, $volunteer->casecode]) }}" class="btn btn-success">View
                                    Attendance</a>
                            </td>
                        </tr>
                    @endforeach --}}
                    <tr>
                        <td class="text-center align-middle">1</td>
                        <td class="text-center align-middle">Juan Dela Cruz</td>
                        <td class="text-center align-middle">Going</td>
                        <td class="text-center align-middle">
                            <a href="{{ route('viewcsattendance') }}" class="btn btn-success">View
                                Attendance</a>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <script src="{{ asset('js/headercontrol.js') }}"></script>
    </div>
</body>

</html>
