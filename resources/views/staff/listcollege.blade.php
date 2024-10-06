<!DOCTYPE html>

<html lang="en">

<head>
    <title>College Scholars</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/table.css') }}">
    <link rel="stylesheet" href="{{ asset('css/scholarslist.css') }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body>
    <!-- PAGE HEADER -->
    @include('partials._pageheader')

    <div class="ctnmain">
        <div class="groupA">
            <span class="pagetitle">School Level: COLLEGE</span>
            <div class="groupA1">
                <form action="#" class="searchbar">
                    <input type="search" placeholder="Search" id="insearch" required>
                    <button type="submit" id="btnsearch"><i class="fas fa-magnifying-glass"></i></button>
                </form>
                <a href="{{ route('stscholars') }}" id="btnback">Go Back</a>
            </div>
        </div>
        <div class="ctntable table-responsive">
            <table class="table table-bordered" id="tblscholarslist">
                <thead>
                    <tr>
                        <th class="text-center align-middle">Scholar's Name</th>
                        <th class="text-center align-middle">Year Level</th>
                        <th class="text-center align-middle">GWA</th>
                        <th class="text-center align-middle">CS Hours Rendered</th>
                        <th class="text-center align-middle">HC Attendance</th>
                        <th class="text-center align-middle">Penalty Count</th>
                        <th class="text-center align-middle">Status</th>
                        <th class="text-center align-middle">Remark</th>
                        <th class="text-center align-middle">Action</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>

    <script src="{{ asset('js/headercontrol.js') }}"></script>
</body>

</html>
