<!DOCTYPE html>
<html lang="en">

<head>
    <title>Appointments</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/table.css') }}">
    <link rel="stylesheet" href="{{ asset('css/applicationforms.css') }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body>
    <!-- PAGE HEADER -->
    @include('partials._pageheader')
    <x-alert />

    <div class="ctnmain">
        <span class="text-success fw-bold h2">Appointments</span>
        <div style="min-height: 50vh" class="ctntable table-responsive">
            <table class="table table-bordered" id="tblapplicationforms">
                <thead>
                    <tr>
                        <th class="text-center align-middle">#</th>
                        <th class="text-center align-middle">Scholar</th>
                        <th class="text-center align-middle">Agenda</th>
                        <th class="text-center align-middle">Schedule</th>
                        <th class="text-center align-middle">Status</th>
                        <th class="text-center align-middle">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($appointments as $index => $appointment)
                        <tr>
                            <td class="text-center align-middle">{{ $index + 1 }}</td>
                            <td class="text-center align-middle">{{ $appointment->basicInfo->scLastname }},
                                {{ $appointment->basicInfo->scFirstname }} {{ $appointment->basicInfo->scMiddlename }}
                            </td>
                            <td class="text-center align-middle">{{ $appointment->reason }}</td>
                            <td class="text-center align-middle">
                                {{ \Carbon\Carbon::parse($appointment->date)->format('F d, Y') }}
                                <br>{{ $appointment->time }}
                            </td>
                            <td class="text-center align-middle">{{ $appointment->status }}</td>
                            <td class="text-center align-middle">
                                <a href="{{ route('viewappointmentinfo', $appointment->id) }}"
                                    class="btn btn-sm btn-success"><i class="fas fa-eye"></i></a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <!-- Pagination Links -->
        {{-- <div class="d-flex justify-content-center mt-3">
            {{ $appointments->links('pagination::bootstrap-4') }}
        </div> --}}
    </div>

    <script src="{{ asset('js/headercontrol.js') }}"></script>
    <script src="{{ asset('js/toggleapplicationforms.js') }}"></script>
</body>

</html>
