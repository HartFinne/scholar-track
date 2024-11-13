<!DOCTYPE html>

<html lang="en">

<head>
    <title>Letter of Explanation</title>
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
        <span class="pagetitle">Letter of Explanation</span>
        <div class="ctnfilter">
            <form action="#" class="filterform">
                <span class="filtertitle">Filter Result</span>
                <div class="filtermenu">
                    <span class="filterlabel">School Level</span>
                    <div class="filteroptions">
                        <label class="lbloptions">
                            <input type="checkbox" id="inyearall" checked>
                            All
                        </label>
                        <label class="lbloptions">
                            <input type="checkbox" id="incollege">
                            College
                        </label>
                        <label class="lbloptions">
                            <input type="checkbox" id="inseniorhigh">
                            Senior High
                        </label>
                        <label class="lbloptions">
                            <input type="checkbox" id="injuniorhigh">
                            Junior High
                        </label>
                        <label class="lbloptions">
                            <input type="checkbox" id="inelementary">
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
                            <input type="radio" id="inexcused" name="status">
                            Excused
                        </label>
                        <label class="lbloptions">
                            <input type="radio" id="innotexcused" name="status">
                            Not Excused
                        </label>
                        <label class="lbloptions">
                            <input type="radio" id="innoresponse" name="status">
                            No Response
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
                        <th class="text-center align-middle">Date Issued</th>
                        <th class="text-center align-middle">Scholar's Name</th>
                        <th class="text-center align-middle">Date Submitted</th>
                        <th class="text-center align-middle">Concern</th>
                        <th class="text-center align-middle">Reason</th>
                        <th class="text-center align-middle">Status</th>
                        <th class="text-center align-middle">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($lte as $index => $letter)
                        <tr>
                            <td class="text-center align-middle">{{ $index + 1 }}</td>
                            <td class="text-center align-middle">
                                {{ \Carbon\Carbon::parse($letter->dateissued)->format('F d, Y') }}</td>
                            @foreach ($scholars as $scholar)
                                @if ($letter->caseCode == $scholar->caseCode)
                                    <td class="text-center align-middle">{{ $scholar->basicInfo->scLastname }},
                                        {{ $scholar->basicInfo->scFirstname }} {{ $scholar->basicInfo->scMiddlename }}
                                    </td>
                                @endif
                            @endforeach
                            <td class="text-center align-middle">
                                {{ $letter->datesubmitted ? \Carbon\Carbon::parse($letter->datesubmitted)->format('F d, Y') : '--' }}
                            </td>
                            @if ($letter->eventtype == null)
                                <td class="text-center align-middle">{{ $letter->violation }}</td>
                            @else
                                <td class="text-center align-middle">{{ $letter->violation }} in
                                    {{ $letter->eventtype }}</td>
                            @endif
                            <td class="text-center align-middle">{{ $letter->reason ?? '--' }}</td>
                            <td class="text-center align-middle">{{ $letter->ltestatus }}</td>
                            <td class="text-center align-middle">
                                <a href="{{ route('showlteinfo', $letter->lid) }}" class="btn btn-primary">View</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <script src="{{ asset('js/headercontrol.js') }}"></script>
</body>

</html>
