<!DOCTYPE html>

<html lang="en">

<head>
    <title>Allowance Requests | Special</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/table.css') }}">
    <link rel="stylesheet" href="{{ asset('css/allowance.css') }}">
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
        <div class="row">
            <div class="d-flex justify-content-between align-items-center w-100">
                <span class="text-success fw-bold h2">Special Allowance Requests</span>
                <a href="{{ route('manage-special') }}" class="btn btn-success">Manage Forms</a>
            </div>
        </div>
        <div class="groupA">
            <div class="groupA1">
                <span class="label">Total Requests</span>
                <span class="data" id="totalrequests">{{ $data['total'] }}</span>
            </div>
            <div class="groupA1">
                <span class="label">Pending</span>
                <span class="data" id="pending">{{ $data['pending'] }}</span>
            </div>
            <div class="groupA1">
                <span class="label">Completed</span>
                <span class="data" id="completed">{{ $data['completed'] }}</span>
            </div>
            <div class="groupA1">
                <span class="label">Accepted</span>
                <span class="data" id="completed">{{ $data['accepted'] }}</span>
            </div>
            <div class="groupA1">
                <span class="label">Rejected</span>
                <span class="data" id="rejected">{{ $data['rejected'] }}</span>
            </div>
        </div>
        <div class="divider"></div>
        <div class="groupB">
            <span class="text-success fw-bold h2">List of Requests</span>
            <div class="groupB1" id="ctnfiltertable active" style="display: flex;">
                <div class="ctnfilter" id="ctnfilter">
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
                </div>
                <div class="ctntable table-responsive" id="ctntable">
                    <table class="table table-bordered" id="tblrequestlist">
                        <thead>
                            <tr>
                                <th class="text-center align-middle">Name</th>
                                <th class="text-center align-middle">Request Type</th>
                                <th class="text-center align-middle">Request Date</th>
                                <th class="text-center align-middle">Status</th>
                                <th class="text-center align-middle">Release Date</th>
                                <th class="text-center align-middle">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($requests as $index => $request)
                                <tr>
                                    <td class="text-center align-middle">{{ $index + 1 }}</td>
                                    <td class="text-center align-middle">
                                        @if ($request instanceof App\Models\allowancebook)
                                            Book Allowance Request
                                        @elseif ($request instanceof App\Models\allowanceevent)
                                            Event Allowance Request
                                        @elseif ($request instanceof App\Models\allowancegraduation)
                                            Graduation Allowance Request
                                        @elseif ($request instanceof App\Models\allowanceproject)
                                            Project Allowance Request
                                        @elseif ($request instanceof App\Models\allowancethesis)
                                            Thesis Allowance Request
                                        @elseif ($request instanceof App\Models\allowancetranspo)
                                            Transportation Reimbursement Request
                                        @elseif ($request instanceof App\Models\allowanceuniform)
                                            Uniform Allowance Request
                                        @endif
                                    </td>
                                    <td class="text-center align-middle">
                                        {{ $request->created_at ? \Carbon\Carbon::parse($request->created_at)->format('F d, Y') : '--' }}
                                    </td>
                                    <td class="text-center align-middle">{{ $request->status }}</td>
                                    <td class="text-center align-middle">
                                        {{ $request->releasedate ? \Carbon\Carbon::parse($request->releasedate)->format('F d, Y') : '--' }}
                                    </td>
                                    <td class="text-center align-middle">
                                        @if ($request instanceof App\Models\allowancebook)
                                            <a href="{{ route('showspecrecinfo', ['requesttype' => 'BAR', 'id' => $request->id]) }}"
                                                class="btn btn-success">View</a>
                                        @elseif ($request instanceof App\Models\allowanceevent)
                                            <a href="{{ route('showspecrecinfo', ['requesttype' => 'FTTSAR', 'id' => $request->id]) }}"
                                                class="btn btn-success">View</a>
                                        @elseif ($request instanceof App\Models\allowancegraduation)
                                            <a href="{{ route('showspecrecinfo', ['requesttype' => 'GAR', 'id' => $request->id]) }}"
                                                class="btn btn-success">View</a>
                                        @elseif ($request instanceof App\Models\allowanceproject)
                                            <a href="{{ route('showspecrecinfo', ['requesttype' => 'PAR', 'id' => $request->id]) }}"
                                                class="btn btn-success">View</a>
                                        @elseif ($request instanceof App\Models\allowancethesis)
                                            <a href="{{ route('showspecrecinfo', ['requesttype' => 'TAR', 'id' => $request->id]) }}"
                                                class="btn btn-success">View</a>
                                        @elseif ($request instanceof App\Models\allowancetranspo)
                                            <a href="{{ route('showspecrecinfo', ['requesttype' => 'TRF', 'id' => $request->id]) }}"
                                                class="btn btn-success">View</a>
                                        @elseif ($request instanceof App\Models\allowanceuniform)
                                            <a href="{{ route('showspecrecinfo', ['requesttype' => 'UAR', 'id' => $request->id]) }}"
                                                class="btn btn-success">View</a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/headercontrol.js') }}"></script>
    <script src="{{ asset('js/toggleallowance.js') }}"></script>
</body>

</html>
