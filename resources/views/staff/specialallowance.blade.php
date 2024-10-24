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

    <div class="ctnmain">
        <span class="pagetitle">Special Allowance Requests</span>
        <div class="groupA">
            <div class="groupA1">
                <span class="label">Total Requests</span>
                <span class="data" id="totalrequests">0</span>
            </div>
            <div class="groupA1">
                <span class="label">Pending</span>
                <span class="data" id="pending">0</span>
            </div>
            <div class="groupA1">
                <span class="label">Completed</span>
                <span class="data" id="completed">0</span>
            </div>
            <div class="groupA1">
                <span class="label">Accepted</span>
                <span class="data" id="completed">0</span>
            </div>
            <div class="groupA1">
                <span class="label">Rejected</span>
                <span class="data" id="rejected">0</span>
            </div>
        </div>
        <div class="divider"></div>
        <div class="groupB">
            <button class="togglebuttons" id="btnmanageform" onclick="toggleforms()">
                Manage Special Allowance Forms
                <i class="fas fa-caret-right"></i>
            </button>
            <div class="groupB1 my-3" id="ctnmanageform" style="display: flex;">
                <div class="row" id="confirmmsg">
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert" id="success-alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"
                                aria-label="Close"></button>
                        </div>
                    @endif
                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert" id="error-alert">
                            {!! session('error') !!}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"
                                aria-label="Close"></button>
                        </div>
                    @endif
                </div>
                <p class="text-muted">Note: Uploading a new file will replace the existing file with the updated
                    version. This action cannot be undone.</p>
                <form method="POST" action="{{ route('transporeimbursement') }}" enctype="multipart/form-data"
                    class="row g-3 align-items-center mb-3 justify-content-center">
                    @csrf
                    <div class="col-md-3">
                        <label for="transpoReimbursement"><strong>Transportation Reimbursement Form</strong></label>
                    </div>
                    <div class="col-md-8">
                        <input type="file" name="transporeimbursement" class="form-control" id="transpoReimbursement"
                            required>
                    </div>
                    <div class="col-md-1">
                        <button type="submit" class="btn btn-success w-100">Update File</button>
                    </div>
                </form>
                <form method="POST" action="{{ route('acknowledgement') }}" enctype="multipart/form-data"
                    class="row g-3 align-items-center mb-3 justify-content-center">
                    @csrf
                    <div class="col-md-3">
                        <label for="acknowledgementReceipt"><strong>Acknowledgement Receipt</strong></label>
                    </div>
                    <div class="col-md-8">
                        <input type="file" name="acknowledgementreceipt" class="form-control"
                            id="acknowledgementReceipt" required>
                    </div>
                    <div class="col-md-1">
                        <button type="submit" class="btn btn-success w-100">Update File</button>
                    </div>
                </form>
                <form method="POST" action="{{ route('liquidation') }}" enctype="multipart/form-data"
                    class="row g-3 align-items-center mb-3 justify-content-center">
                    @csrf
                    <div class="col-md-3">
                        <label for="liquidationForm"><strong>Liquidation Form</strong></label>
                    </div>
                    <div class="col-md-8">
                        <input type="file" name="liquidationform" class="form-control" id="liquidationForm"
                            required>
                    </div>
                    <div class="col-md-1">
                        <button type="submit" class="btn btn-success w-100">Update File</button>
                    </div>
                </form>
                <form method="POST" action="{{ route('certform') }}" enctype="multipart/form-data"
                    class="row g-3 align-items-center justify-content-center">
                    @csrf
                    <div class="col-md-3">
                        <label for="certificationForm"><strong>Project/Book Certification Form</strong></label>
                    </div>
                    <div class="col-sm-8">
                        <input type="file" name="certificationform" class="form-control" id="certificationForm"
                            required>
                    </div>
                    <div class="col-sm-1">
                        <button type="submit" class="btn btn-success w-100">Update File</button>
                    </div>
                </form>
            </div>
            <button class="togglebuttons" id="btnshowlist" onclick="togglerequestlist()">
                List of Requests
                <i class="fas fa-caret-right"></i>
            </button>
            <div class="groupB1" id="ctnfiltertable" style="display: none;">
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
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/headercontrol.js') }}"></script>
    <script src="{{ asset('js/toggleallowance.js') }}"></script>
</body>

</html>
