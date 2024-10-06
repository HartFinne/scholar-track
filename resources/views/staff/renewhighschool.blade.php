<!DOCTYPE html>

<html lang="en">

<head>
    <title>Renewal | Senior High</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/table.css') }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body>
    <!-- PAGE HEADER -->
    @include('partials._pageheader')

    <div class="ctnmain">
        <div class="header">
            <span class="pagetitle">School Level: HIGH SCHOOL</span>
            <a href="{{ route('strenewal') }}" id="btngoback">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
        </div>
        <div class="ctnfilter">
            <form action="#" class="filterform">
                <span class="filtertitle">Filter Result</span>
                <div class="filtermenu">
                    <span class="filterlabel">Grade Level</span>
                    <div class="filteroptions">
                        <label class="lbloptions">
                            <input type="checkbox" id="ingradeall" checked>
                            All
                        </label>
                        <label class="lbloptions">
                            <input type="checkbox" id="ingrade7">
                            Grade 7
                        </label>
                        <label class="lbloptions">
                            <input type="checkbox" id="ingrade8">
                            Grade 8
                        </label>
                        <label class="lbloptions">
                            <input type="checkbox" id="ingrade9">
                            Grade 9
                        </label>
                        <label class="lbloptions">
                            <input type="checkbox" id="ingrade10">
                            Grade 10
                        </label>
                        <label class="lbloptions">
                            <input type="checkbox" id="ingrade11">
                            Grade 11
                        </label>
                        <label class="lbloptions">
                            <input type="checkbox" id="ingrade12">
                            Grade 12
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
                            <input type="radio" id="inapproved" name="status">
                            Approved
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
                        <th class="text-center align-middle">#</th>
                        <th class="text-center align-middle">Date Submitted</th>
                        <th class="text-center align-middle">Scholar's Name</th>
                        <th class="text-center align-middle">Status</th>
                        <th class="text-center align-middle">Action</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>

    <script src="{{ asset('js/headercontrol.js') }}"></script>
</body>

</html>
