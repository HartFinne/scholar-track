<!DOCTYPE html>

<html lang="en">

<head>
    <title>Header</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link href="{{ asset('css/style.css') }}" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body>
    <!-- PAGE HEADER -->
    <div class="pageheader">
        <button id="btnsidebar" onclick="togglesidebar()"><i class="fas fa-bars"></i></button>
        <button id="btnprofilemenu" onclick="toggleprofilemenu()"><i class="fas fa-user"></i></button>
    </div>

    <!-- SIDE BAR -->
    <div id="ctnsidebar">
        <button id="btnclosesidebar" onclick="togglesidebar()"><i class="fas fa-xmark"></i></button>
        <div id="ctnoptions">
            <button id="btnhome" class="sbarmainopt"
                onclick="window.location.href='{{ route('home-sw') }}';">Home</button>
            <button id="btnscholars" class="sbarmainopt" onclick="togglesubopt1()">Scholars<i
                    class="fas fa-caret-right"></i></button>
            <div class="ctnsuboptions" id="subopt1" style="display: none;">
                <a href="{{ route('scholars-overview') }}" class="sbarsubopt">Overview</a>
                <a href="{{ route('scholarshiprenewal') }}" class="sbarsubopt">Renewal</a>
                <a href="{{ route('lte') }}" class="sbarsubopt">Letter of Explanation</a>
                <a href="{{ route('penalty') }}" class="sbarsubopt">Penalty</a>
            </div>
            <button id="btnrequests" class="sbarmainopt" onclick="togglesubopt2()">Requests<i
                    class="fas fa-caret-right"></i></button>
            <div class="ctnsuboptions" id="subopt2" style="display: none;">
                <a href="{{ route('allowancerequests-regular') }}" class="sbarsubopt">Regular Allowance</a>
                <a href="{{ route('allowancerequests-special') }}" class="sbarsubopt">Special Allowance</a>
            </div>
            <button id="btncs" class="sbarmainopt"
                onclick="window.location.href='{{ route('communityservice') }}';">Community
                Service</button>
            <button id="btnhc" class="sbarmainopt"
                onclick="window.location.href='{{ route('humanitiesclass') }}';">Humanities Class</button>
            <a href="{{ route('qualification') }}" class="sbarmainopt">Scholarship Criteria</a>
            <button id="btncriteria" class="sbarmainopt" onclick="togglesubopt3()">Application<i
                    class="fas fa-caret-right"></i></button>
            <div class="ctnsuboptions" id="subopt3" style="display: none;">
                <a href="{{ route('applicants') }}" class="sbarsubopt">Applicants</a>
                <a href="{{ route('applicationforms') }}" class="sbarsubopt">Application Forms</a>
            </div>
            <a id="btnhc" class="sbarmainopt" href='{{ route('generatescholarshipreport') }}'
                target="_blank">Generate Report</a>
        </div>
    </div>

    <!-- PROFILE MENU -->
    <div id="ctnprofilemenu">
        <a id="linkprofile" href="{{ route('account-sw') }}"><i class="fas fa-user"></i>Account</a>
        <a id="linksignout" href="{{ route('logout-sw') }}"><i class="fa-solid fa-right-from-bracket"></i>Sign out</a>
    </div>
</body>

</html>
