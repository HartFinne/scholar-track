<!DOCTYPE html>

<html lang="en">

<head>
    <title>Applicants</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/table.css') }}">
    <link rel="stylesheet" href="{{ asset('css/applicants.css') }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js"></script>
</head>

<body>
    <!-- PAGE HEADER -->
    @include('partials._pageheader')

    <div class="ctnmain">
        <span class="text-success fw-bold h2">Applicants Overview</span>
        <div class="groupA">
            <div class="groupA1">
                <span class="label">Total Applicants</span>
                <span class="data">{{ $totalapplicants }}</span>
            </div>
            <div class="groupA1">
                <span class="label">Pending</span>
                <span class="data">{{ $pending }}</span>
            </div>
            <div class="groupA1">
                <span class="label">Accepted</span>
                <span class="data">{{ $accepted }}</span>
            </div>
            <div class="groupA1">
                <span class="label">Rejected</span>
                <span class="data">{{ $rejected }}</span>
            </div>
            <div class="groupA1">
                <span class="label">Withdrawn</span>
                <span class="data">{{ $withdrawn }}</span>
            </div>
        </div>
        <div class="groupA">
            <div class="groupA2">
                <span class="label">Applicants per School Level</span>
                <canvas id="applicantsgraph" height="300px"></canvas>
            </div>
        </div>
        <div class="divider"></div>
        {{-- <div class="ctnfilter">
            <span class="text-success fw-bold h2">List of Applicants</span>
            <form action="#" class="filterform">
                <span class="filtertitle">Filter Result</span>
                <div class="filtermenu">
                    <span class="filterlabel">Year Level</span>
                    <div class="filteroptions">
                        <label class="lbloptions">
                            <input type="checkbox" id="inyearall" checked>
                            All
                        </label>
                        <label class="lbloptions">
                            <input type="checkbox" id="inyear1">
                            First Year
                        </label>
                        <label class="lbloptions">
                            <input type="checkbox" id="inyear2">
                            Second Year
                        </label>
                        <label class="lbloptions">
                            <input type="checkbox" id="inyear2">
                            Third Year
                        </label>
                        <label class="lbloptions">
                            <input type="checkbox" id="inyear2">
                            Fourth Year
                        </label>
                        <label class="lbloptions">
                            <input type="checkbox" id="inyear2">
                            Fifth Year
                        </label>
                    </div>
                </div>
                <div class="filtermenu">
                    <span class="filterlabel">Status</span>
                    <div class="filteroptions">
                        <label class="lbloptions">
                            <input type="radio" id="instatusall" name="status" checked>
                            All
                        </label>
                        <label class="lbloptions">
                            <input type="radio" id="inpending" name="status">
                            Pending
                        </label>
                        <label class="lbloptions">
                            <input type="radio" id="inaccepted" name="status">
                            Accepted
                        </label>
                        <label class="lbloptions">
                            <input type="radio" id="inrejected" name="status">
                            Rejected
                        </label>
                        <label class="lbloptions">
                            <input type="radio" id="inwithdrawn" name="status">
                            Withdrawn
                        </label>
                    </div>
                </div>
                <button type="submit" id="btnapply">Apply</button>
            </form>
        </div> --}}
        <span class="text-success fw-bold h2">List of Applicants</span>
        <div class="row gx-1">
            <div class="" style="width: max-content">
                <button class="filter btn btn-sm btn-success w-100" id="toggleCollege">College</button>
            </div>
            <div class="" style="width: max-content">
                <button class="filter btn btn-sm btn-outline-success w-100" id="toggleSHS">Senior High</button>
            </div>
            <div class="" style="width: max-content">
                <button class="filter btn btn-sm btn-outline-success w-100" id="toggleJHS">Junior High</button>
            </div>
            <div class="" style="width: max-content">
                <button class="filter btn btn-sm btn-outline-success w-100" id="toggleElem">Elementary</button>
            </div>
        </div>
        <div class="ctn" id="college">
            <div class="ctntable table-responsive">
                <table class="table table-bordered" id="tblapplicantslist">
                    <thead>
                        <tr>
                            <th class="text-center align-middle">#</th>
                            <th class="text-center align-middle">Date Submitted</th>
                            <th class="text-center align-middle">Scholar's Name</th>
                            <th class="text-center align-middle">Incoming Year Level</th>
                            <th class="text-center align-middle">Status</th>
                            <th class="text-center align-middle">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($applicants['college'] as $index => $applicant)
                            <tr>
                                <td class="text-center align-middle">{{ $index + 1 }}</td>
                                <td class="text-center align-middle">{{ $applicant->created_at->format('F d, Y') }}</td>
                                <td class="text-center align-middle">{{ $applicant->name }}</td>
                                <td class="text-center align-middle">{{ $applicant->educcollege->inyear }}</td>
                                <td class="text-center align-middle">{{ $applicant->applicationstatus }}</td>
                                <td class="text-center align-middle">
                                    <a href="{{ route('applicantinfo', $applicant->casecode) }}"
                                        class="btn btn-sm btn-success"><i class="fas fa-eye"></i></a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td class="text-center align-middle" colspan="6">No Records Found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <!-- Pagination Links -->
            <div class="d-flex justify-content-center mt-3">
                {{ $applicants['college']->links('pagination::bootstrap-4') }}
            </div>
        </div>
        <div class="ctn" id="shs" style="display: none">
            <div class="ctntable table-responsive">
                <table class="table table-bordered" id="tblapplicantslist">
                    <thead>
                        <tr>
                            <th class="text-center align-middle">#</th>
                            <th class="text-center align-middle">Date Submitted</th>
                            <th class="text-center align-middle">Scholar's Name</th>
                            <th class="text-center align-middle">Incoming Grade Level</th>
                            <th class="text-center align-middle">Status</th>
                            <th class="text-center align-middle">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($applicants['shs'] as $index => $applicant)
                            <tr>
                                <td class="text-center align-middle">{{ $index + 1 }}</td>
                                <td class="text-center align-middle">{{ $applicant->created_at->format('F d, Y') }}
                                </td>
                                <td class="text-center align-middle">{{ $applicant->name }}</td>
                                <td class="text-center align-middle">{{ $applicant->educelemhs->ingrade }}</td>
                                <td class="text-center align-middle">{{ $applicant->applicationstatus }}</td>
                                <td class="text-center align-middle">
                                    <a href="{{ route('applicantinfo', $applicant->casecode) }}"
                                        class="btn btn-sm btn-success"><i class="fas fa-eye"></i></a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td class="text-center align-middle" colspan="6">No Records Found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <!-- Pagination Links -->
            <div class="d-flex justify-content-center mt-3">
                {{ $applicants['shs']->links('pagination::bootstrap-4') }}
            </div>
        </div>
        <div class="ctn" id="jhs" style="display: none">
            <div class="ctntable table-responsive">
                <table class="table table-bordered" id="tblapplicantslist">
                    <thead>
                        <tr>
                            <th class="text-center align-middle">#</th>
                            <th class="text-center align-middle">Date Submitted</th>
                            <th class="text-center align-middle">Scholar's Name</th>
                            <th class="text-center align-middle">Incoming Grade Level</th>
                            <th class="text-center align-middle">Status</th>
                            <th class="text-center align-middle">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($applicants['jhs'] as $index => $applicant)
                            <tr>
                                <td class="text-center align-middle">{{ $index + 1 }}</td>
                                <td class="text-center align-middle">{{ $applicant->created_at->format('F d, Y') }}
                                </td>
                                <td class="text-center align-middle">{{ $applicant->name }}</td>
                                <td class="text-center align-middle">{{ $applicant->educelemhs->ingrade }}</td>
                                <td class="text-center align-middle">{{ $applicant->applicationstatus }}</td>
                                <td class="text-center align-middle">
                                    <a href="{{ route('applicantinfo', $applicant->casecode) }}"
                                        class="btn btn-sm btn-success"><i class="fas fa-eye"></i></a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td class="text-center align-middle" colspan="6">No Records Found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <!-- Pagination Links -->
            <div class="d-flex justify-content-center mt-3">
                {{ $applicants['jhs']->links('pagination::bootstrap-4') }}
            </div>
        </div>
        <div class="ctn" id="elem" style="display: none">
            <div class="ctntable table-responsive">
                <table class="table table-bordered" id="tblapplicantslist">
                    <thead>
                        <tr>
                            <th class="text-center align-middle">#</th>
                            <th class="text-center align-middle">Date Submitted</th>
                            <th class="text-center align-middle">Scholar's Name</th>
                            <th class="text-center align-middle">Incoming Grade Level</th>
                            <th class="text-center align-middle">Status</th>
                            <th class="text-center align-middle">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($applicants['elem'] as $index => $applicant)
                            <tr>
                                <td class="text-center align-middle">{{ $index + 1 }}</td>
                                <td class="text-center align-middle">{{ $applicant->created_at->format('F d, Y') }}
                                </td>
                                <td class="text-center align-middle">{{ $applicant->name }}</td>
                                <td class="text-center align-middle">{{ $applicant->educelemhs->ingrade }}</td>
                                <td class="text-center align-middle">{{ $applicant->applicationstatus }}</td>
                                <td class="text-center align-middle">
                                    <a href="{{ route('applicantinfo', $applicant->casecode) }}"
                                        class="btn btn-sm btn-success"><i class="fas fa-eye"></i></a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td class="text-center align-middle" colspan="6">No Records Found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <!-- Pagination Links -->
        <div class="d-flex justify-content-center mt-3">
            {{ $applicants['elem']->links('pagination::bootstrap-4') }}
        </div>
    </div>

    <script src="{{ asset('js/headercontrol.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            new Chart("applicantsgraph", {
                type: "bar",
                data: {
                    labels: ["College", "Senior High", "Junior High", "Elementary"],
                    datasets: [{
                        backgroundColor: "#1a5319",
                        data: [{{ $college }}, {{ $shs }}, {{ $jhs }},
                            {{ $elem }}, 0
                        ]
                    }]
                },
                options: {
                    legend: {
                        display: false
                    }
                }
            });
        });

        $(document).ready(function() {
            function toggleSection(buttonId, containerId) {
                $(buttonId).click(function() {
                    // Fade out all containers, then fade in the selected one
                    $('.ctn').not(containerId).fadeOut('fast', function() {
                        $(containerId).fadeIn('slow');
                    });

                    // Update button classes to reflect active/inactive states
                    $('.filter').not(buttonId).removeClass('btn-success').addClass('btn-outline-success');
                    $(buttonId).removeClass('btn-outline-success').addClass('btn-success');
                });
            }

            // Attach events to buttons
            toggleSection('#toggleCollege', '#college');
            toggleSection('#toggleSHS', '#shs');
            toggleSection('#toggleJHS', '#jhs');
            toggleSection('#toggleElem', '#elem');
        });
    </script>
</body>

</html>
