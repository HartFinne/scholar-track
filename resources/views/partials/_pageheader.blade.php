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
            <button id="btnhome" onclick="window.location.href='{{ route('sthome') }}';">Home</button>
            <button id="btnscholars" onclick="togglesubopt1()">Scholars<i class="fas fa-caret-right"></i></button>
            <div class="ctnsuboptions" id="subopt1" style="display: none;">
                <a href="{{ route('stscholars') }}">Overview</a>
                <a href="{{ route('strenewal') }}">Renewal</a>
                <a href="{{ route('stlte') }}">Letter of Explanation</a>
                <a href="{{ route('stpenalty') }}">Penalty</a>
            </div>
            <button id="btnrequests" onclick="togglesubopt2()">Requests<i class="fas fa-caret-right"></i></button>
            <div class="ctnsuboptions" id="subopt2" style="display: none;">
                <a href="{{ route('stregularallowance') }}">Regular Allowance</a>
                <a href="{{ route('stspecialallowance') }}">Special Allowance</a>
            </div>
            <button id="btncs" onclick="window.location.href='{{ route('stmanagecs') }}';">Community
                Service</button>
            <button id="btnhc" onclick="window.location.href='{{ route('stmanagehc') }}';">Humanities Class</button>
            <button id="btncriteria" onclick="togglesubopt3()">Scholarship Application<i
                    class="fas fa-caret-right"></i></button>
            <div class="ctnsuboptions" id="subopt3" style="display: none;">
                <a href="{{ route('stapplicants') }}">Applicants</a>
                <a href="{{ route('stapplicationforms') }}">Application Forms</a>
                <a href="{{ route('stqualificationcollege') }}">College Qualification</a>
                <a href="{{ route('stqualificationshs') }}">Senior High Qualification</a>
                <a href="{{ route('stqualificationjhs') }}">Junior High Qualification</a>
                <a href="{{ route('stqualificationelem') }}">Elementary Qualification</a>
            </div>
        </div>
    </div>

    <!-- PROFILE MENU -->
    <div id="ctnprofilemenu">
        <a id="linkprofile" href="{{ route('staccount') }}"><i class="fas fa-user"></i>Account</a>
        <a id="linksignout" href="{{ route('stlogin') }}"><i class="fa-solid fa-right-from-bracket"></i>Sign out</a>
    </div>
</body>

</html>
