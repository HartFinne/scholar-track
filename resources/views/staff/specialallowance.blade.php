<!DOCTYPE html>

<html lang="en">

<head>
    <title>Allowance Requests | Special</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/table.css') }}">
    <link rel="stylesheet" href="{{ asset('css/allowance.css') }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body>
    <!-- PAGE HEADER -->
    @include('partials._pageheader')
    <x-alert />

    <div class="ctnmain">
        <div class="row">
            <div class="d-flex justify-content-between align-items-center w-100">
                <span class="text-success fw-bold h2">Special Allowance Requests</span>
                <a href="{{ route('manage-special') }}" class="btn btn-success">Manage Forms</a>
            </div>
        </div>
        <div class="groupA">
            <div class="groupA1">
                <span class="label">Total Requests</span>
                <span class="data" id="totalrequests">{{ $summary->totalrequests }}</span>
            </div>
            <div class="groupA1">
                <span class="label">Pending</span>
                <span class="data" id="pending">{{ $summary->pending }}</span>
            </div>
            <div class="groupA1">
                <span class="label">Completed</span>
                <span class="data" id="completed">{{ $summary->completed }}</span>
            </div>
            <div class="groupA1">
                <span class="label">Accepted</span>
                <span class="data" id="completed">{{ $summary->accepted }}</span>
            </div>
            <div class="groupA1">
                <span class="label">Rejected</span>
                <span class="data" id="rejected">{{ $summary->rejected }}</span>
            </div>
        </div>
        <div class="divider"></div>
        <span class="text-success fw-bold h2">List of Requests</span>
        {{-- <div class="ctnfilter" id="ctnfilter">
            <form action="#" class="filterform">
                <span class="filtertitle">Filter Result</span>
                <div class="filtermenu">
                    <span class="filterlabel">School Level</span>
                    <div class="filteroptions">
                        <label class="lbloptions">
                            <input type="checkbox" id="inlevelall" checked>
                            All
                        </label>
                        <label class="lbloptions">
                            <input type="checkbox" id="incollege">
                            College
                        </label>
                        <label class="lbloptions">
                            <input type="checkbox" id="insenior">
                            Senior High
                        </label>
                        <label class="lbloptions">
                            <input type="checkbox" id="injunior">
                            Junior High
                        </label>
                        <label class="lbloptions">
                            <input type="checkbox" id="inelem">
                            Elementary
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
                            <input type="radio" id="incompleted" name="status">
                            Completed
                        </label>
                        <label class="lbloptions">
                            <input type="radio" id="inaccepted" name="status">
                            Accepted
                        </label>
                        <label class="lbloptions">
                            <input type="radio" id="inrejected" name="status">
                            Rejected
                        </label>
                    </div>
                </div>
                <button type="submit" id="btnapply">Apply</button>
            </form>
        </div> --}}
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
                            <th class="text-center align-middle">Requestor Name</th>
                            <th class="text-center align-middle">Request Type</th>
                            <th class="text-center align-middle">Date of Request</th>
                            <th class="text-center align-middle">Status</th>
                            <th class="text-center align-middle">Date of Release</th>
                            <th class="text-center align-middle">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($forms as $form)
                            @php
                                // Pre-filter scholars for College level
                                $collegeScholars = $scholars->filter(function ($scholar) {
                                    return $scholar->education->scSchoolLevel == 'College';
                                });
                            @endphp

                            @forelse ($data[$form->formname] as $row)
                                @php
                                    // Find the scholar by caseCode in the filtered collection
                                    $scholar = $collegeScholars->firstWhere('caseCode', $row['caseCode']);
                                @endphp

                                @if ($scholar)
                                    <tr>
                                        <td class="text-center align-middle">{{ $loop->iteration }}</td>
                                        <td class="text-center align-middle">{{ $scholar->basicInfo->scLastname }},
                                            {{ $scholar->basicInfo->scFirstname }}
                                            {{ $scholar->basicInfo->scMiddlename }}</td>
                                        <td class="text-center align-middle">{{ $row['requestType'] }}</td>
                                        <td class="text-center align-middle">
                                            {{ \Carbon\Carbon::parse($row['requestDate'])->format('F j, Y') }}
                                        </td>
                                        <td class="text-center align-middle">{{ $row['requestStatus'] }}</td>
                                        <td class="text-center align-middle">
                                            {{ $row['releaseDate'] ? \Carbon\Carbon::parse($row['releaseDate'])->format('F j, Y') : 'Not Set Yet' }}
                                        </td>
                                        <td class="text-center align-middle">
                                            <a href="{{ route('showspecrecinfo', ['type' => $form->formname, 'id' => $row['id'], 'caseCode' => $row['caseCode']]) }}"
                                                class="btn btn-sm btn-success"><i class="fas fa-eye"></i></a>
                                        </td>
                                    </tr>
                                @endif
                            @empty
                                <tr>
                                    <td class="text-center align-middle fw-bold" colspan="7">No Records Found.</td>
                                </tr>
                            @endforelse
                        @empty
                            <tr>
                                <td class="text-center align-middle fw-bold" colspan="7">No Records Found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="ctn" id="shs" style="display: none">
            <div class="ctntable table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th class="text-center align-middle">#</th>
                            <th class="text-center align-middle">Requestor Name</th>
                            <th class="text-center align-middle">Request Type</th>
                            <th class="text-center align-middle">Date Submitted</th>
                            <th class="text-center align-middle">Release Date</th>
                            <th class="text-center align-middle">Status</th>
                            <th class="text-center align-middle">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="text-center align-middle fw-bold" colspan="7">No Records Found.</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="ctn" id="jhs" style="display: none">
            <div class="ctntable table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th class="text-center align-middle">#</th>
                            <th class="text-center align-middle">Requestor Name</th>
                            <th class="text-center align-middle">Request Type</th>
                            <th class="text-center align-middle">Date Submitted</th>
                            <th class="text-center align-middle">Release Date</th>
                            <th class="text-center align-middle">Status</th>
                            <th class="text-center align-middle">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="text-center align-middle fw-bold" colspan="7">No Records Found.</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="ctn" id="elem" style="display: none">
            <div class="ctntable table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th class="text-center align-middle">#</th>
                            <th class="text-center align-middle">Requestor Name</th>
                            <th class="text-center align-middle">Request Type</th>
                            <th class="text-center align-middle">Date Submitted</th>
                            <th class="text-center align-middle">Release Date</th>
                            <th class="text-center align-middle">Status</th>
                            <th class="text-center align-middle">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="text-center align-middle fw-bold" colspan="7">No Records Found.</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/headercontrol.js') }}"></script>
    <script src="{{ asset('js/toggleallowance.js') }}"></script>
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
