<!DOCTYPE html>

<html lang="en">

<head>
    <title>Allowance Requests | Regular</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/table.css') }}">
    <link rel="stylesheet" href="{{ asset('css/allowance.css') }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body>
    <!-- PAGE HEADER -->
    @include('partials._pageheader')

    <div class="ctnmain">
        <span class="pagetitle">Regular Allowance Requests</span>
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
        <span class="pagetitle">List of Requests</span>
        <div class="ctnfilter">
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
        <div class="ctntable table-responsive">
            <table class="table table-bordered" id="tblscholarslist">
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

    <script src="{{ asset('js/headercontrol.js') }}"></script>
</body>

</html>
