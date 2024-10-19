<!DOCTYPE html>

<html lang="en">

<head>
    <title>Scholarship Renewal</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/renewal.css') }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body>
    <!-- PAGE HEADER -->
    @include('partials._pageheader')

    <div class="ctnmain">
        <span class="pagetitle">Renewal Overview</span>
        <div class="groupA">
            <div class="groupA1">
                <span class="label" id="lbltotalrenewal">Total Renewal Applications</span>
                <span class="data" id="outtotalrenewal">{{ $totalrenew }}</span>
            </div>
            <div class="groupA1">
                <span class="label" id="lbltotalrenewal">Pending Applications</span>
                <span class="data" id="outpending">{{ $pending }}</span>
            </div>
            <div class="groupA1">
                <span class="label" id="lbltotalrenewal">Approved Applications</span>
                <span class="data" id="outapproved">{{ $approved }}</span>
            </div>
            <div class="groupA1">
                <span class="label" id="lbltotalrenewal">Rejected Applications</span>
                <span class="data" id="outrejected">{{ $rejected }}</span>
            </div>
        </div>
        <div class="divider"></div>
        <span class="pagetitle">Renewal Application</span>
        <span class="lblinstruction">Select which list to view:</span>
        <div class="groupB">
            <a href="{{ route('renewal-college') }}" class="groupB1">College<i class="fas fa-arrow-right"></i></a>
            <a href="{{ route('renewal-highschool') }}" class="groupB1">High School<i
                    class="fas fa-arrow-right"></i></a>
            <a href="{{ route('renewal-elementary') }}" class="groupB1">Elementary<i class="fas fa-arrow-right"></i></a>
        </div>
    </div>

    <script src="{{ asset('js/headercontrol.js') }}"></script>
</body>

</html>
