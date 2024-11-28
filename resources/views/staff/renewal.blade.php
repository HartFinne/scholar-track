<!DOCTYPE html>

<html lang="en">

<head>
    <title>Scholarship Renewal</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/renewal.css') }}">
    <link rel="stylesheet" href="{{ asset('css/table.css') }}">
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
        <span class="text-success fw-bold h2">Renewal Overview</span>
        <div class="groupA">
            <div class="groupA1">
                <span class="label" id="lbltotalrenewal">Total Renewal Applications</span>
                <span class="data" id="outtotalrenewal">{{ $summary['totalrenew'] }}</span>
            </div>
            <div class="groupA1">
                <span class="label" id="lbltotalrenewal">Pending Applications</span>
                <span class="data" id="outpending">{{ $summary['pending'] }}</span>
            </div>
            <div class="groupA1">
                <span class="label" id="lbltotalrenewal">Approved Applications</span>
                <span class="data" id="outapproved">{{ $summary['approved'] }}</span>
            </div>
            <div class="groupA1">
                <span class="label" id="lbltotalrenewal">Rejected Applications</span>
                <span class="data" id="outrejected">{{ $summary['rejected'] }}</span>
            </div>
        </div>
        <div class="divider"></div>
        <span class="text-success fw-bold h2">Renewal Application</span>
        <div class="row gx-0 align-items-center">
            <div class="col-md-1">
                <button class="filter btn btn-sm btn-success w-100" id="toggleCollege">College</button>
            </div>
            <div class="col-md-1 mx-1">
                <button class="filter btn btn-sm btn-outline-success w-100" id="toggleSHS">Senior High</button>
            </div>
            <div class="col-md-1">
                <button class="filter btn btn-sm btn-outline-success w-100" id="toggleJHS">Junior High</button>
            </div>
            <div class="col-md-1 mx-1">
                <button class="filter btn btn-sm btn-outline-success w-100" id="toggleElem">Elementary</button>
            </div>
        </div>
        <div class="ctn" id="college">
            <div class="ctntable table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th class="text-center align-middle">#</th>
                            <th class="text-center align-middle">Scholar's Name</th>
                            <th class="text-center align-middle">Year Level</th>
                            <th class="text-center align-middle">Application Status</th>
                            <th class="text-center align-middle">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($college->isEmpty())
                            <tr>
                                <td class="text-center align-middle fw-bold" colspan="5">No Records Found.</td>
                            </tr>
                        @else
                            @foreach ($college as $index => $data)
                                <tr>
                                    <td class="text-center align-middle">{{ $index++ }}</td>
                                    <td class="text-center align-middle">{{ $data->basicInfo->scLastname }},
                                        {{ $data->basicInfo->scFirstname }} {{ $data->basicInfo->scMiddlename }}</td>
                                    <td class="text-center align-middle">{{ $data->education->scYearGrade }}
                                    </td>
                                    <td class="text-center align-middle">{{ $data->renewal->renewalstatus }}
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
        <div class="ctn" id="shs" style="display: none;">
            <div class="ctntable table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th class="text-center align-middle">#</th>
                            <th class="text-center align-middle">Scholar's Name</th>
                            <th class="text-center align-middle">Year Level</th>
                            <th class="text-center align-middle">Application Status</th>
                            <th class="text-center align-middle">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($shs->isEmpty())
                            <tr>
                                <td class="text-center align-middle fw-bold" colspan="5">No Records Found.</td>
                            </tr>
                        @else
                            @foreach ($shs as $index => $data)
                                <tr>
                                    <td class="text-center align-middle">{{ $index++ }}</td>
                                    <td class="text-center align-middle">{{ $data->basicInfo->scLastname }},
                                        {{ $data->basicInfo->scFirstname }} {{ $data->basicInfo->scMiddlename }}</td>
                                    <td class="text-center align-middle">{{ $data->education->scYearGrade }}
                                    </td>
                                    <td class="text-center align-middle">{{ $data->renewal->renewalstatus }}
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
        <div class="ctn" id="jhs" style="display: none;">
            <div class="ctntable table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th class="text-center align-middle">#</th>
                            <th class="text-center align-middle">Scholar's Name</th>
                            <th class="text-center align-middle">Year Level</th>
                            <th class="text-center align-middle">Application Status</th>
                            <th class="text-center align-middle">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($jhs->isEmpty())
                            <tr>
                                <td class="text-center align-middle fw-bold" colspan="5">No Records Found.</td>
                            </tr>
                        @else
                            @foreach ($jhs as $index => $data)
                                <tr>
                                    <td class="text-center align-middle">{{ $index++ }}</td>
                                    <td class="text-center align-middle">{{ $data->basicInfo->scLastname }},
                                        {{ $data->basicInfo->scFirstname }} {{ $data->basicInfo->scMiddlename }}</td>
                                    <td class="text-center align-middle">{{ $data->education->scYearGrade }}
                                    </td>
                                    <td class="text-center align-middle">{{ $data->renewal->renewalstatus }}
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
        <div class="ctn" id="elem" style="display: none;">
            <div class="ctntable table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th class="text-center align-middle">#</th>
                            <th class="text-center align-middle">Scholar's Name</th>
                            <th class="text-center align-middle">Year Level</th>
                            <th class="text-center align-middle">Application Status</th>
                            <th class="text-center align-middle">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($elem->isEmpty())
                            <tr>
                                <td class="text-center align-middle fw-bold" colspan="5">No Records Found.</td>
                            </tr>
                        @else
                            @foreach ($elem as $index => $data)
                                <tr>
                                    <td class="text-center align-middle">{{ $index++ }}</td>
                                    <td class="text-center align-middle">{{ $data->basicInfo->scLastname }},
                                        {{ $data->basicInfo->scFirstname }} {{ $data->basicInfo->scMiddlename }}</td>
                                    <td class="text-center align-middle">{{ $data->education->scYearGrade }}
                                    </td>
                                    <td class="text-center align-middle">{{ $data->renewal->renewalstatus }}
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/headercontrol.js') }}"></script>
    <script>
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
