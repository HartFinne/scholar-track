<!DOCTYPE html>

<html lang="en">

<head>
    <title>Renewal | Elementary</title>
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
            <span class="pagetitle">School Level: COLLEGE</span>
            <a href="{{ route('scholarshiprenewal') }}" id="btngoback">
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
                            <input type="checkbox" id="ingrade1">
                            Grade 1
                        </label>
                        <label class="lbloptions">
                            <input type="checkbox" id="ingrade2">
                            Grade 2
                        </label>
                        <label class="lbloptions">
                            <input type="checkbox" id="ingrade3">
                            Grade 3
                        </label>
                        <label class="lbloptions">
                            <input type="checkbox" id="ingrade4">
                            Grade 4
                        </label>
                        <label class="lbloptions">
                            <input type="checkbox" id="ingrade5">
                            Grade 5
                        </label>
                        <label class="lbloptions">
                            <input type="checkbox" id="ingrade6">
                            Grade 6
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
                <tbody>
                    @foreach ($renewals as $index->$renewal)
                        @if ($renewals->caseCode == $scholars->CaseCode && $scholars->education->scSchoolLevel == 'College')
                            <td class="text-center align-middle"> {{ $index + 1 }} </th>
                            <td class="text-center align-middle"> {{ $renewal->datesubmitted }} </td>
                            <td class="text-center align-middle"> {{ $scholar->basicInfo->scLastname }},
                                {{ $scholar->basicInfo->scFirstname }} {{ $scholar->basicInfo->scMiddlename }}</td>
                            <td class="text-center align-middle"> {{ $renewal->status }}</td>
                            <td class="text-center align-middle">
                                <a href="#">View</a>
                            </td>
                        @endif
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <script src="{{ asset('js/headercontrol.js') }}"></script>
</body>

</html>
