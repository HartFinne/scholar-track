<!DOCTYPE html>

<html lang="en">

<head>
    <title>Elementary Scholars</title>
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
            <span class="pagetitle">School Level: ELEMENTARY</span>
            <div class="groupA1">
                <form action="#" class="searchbar">
                    <input type="search" placeholder="Search" id="insearch" required>
                    <button type="submit" id="btnsearch"><i class="fas fa-magnifying-glass"></i></button>
                </form>
                <a href="{{ route('scholars-overview') }}" id="btnback">Go Back</a>
            </div>
        </div>
        <div class="ctntable table-responsive">
            <table class="table table-bordered" id="tblscholarslist">
                <thead>
                    <tr>
                        <th class="text-center align-middle">Scholar's Name</th>
                        <th class="text-center align-middle">Grade Level</th>
                        <th class="text-center align-middle">GWA</th>
                        <th class="text-center align-middle">Conduct</th>
                        <th class="text-center align-middle">GWA (Chinese Subject)</th>
                        <th class="text-center align-middle">Conduct (Chinese Subject)</th>
                        <th class="text-center align-middle">HC Attendance Count</th>
                        <th class="text-center align-middle">Penalty Count</th>
                        <th class="text-center align-middle">Scholarship Status</th>
                        <th class="text-center align-middle">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($scholars as $index => $data)
                        <tr>
                            <td class="text-center align-middle">{{ $data->basicInfo->scLastname }},
                                {{ $data->basicInfo->scFirstname }} {{ $data->basicInfo->scMiddlename }}</td>
                            <td class="text-center align-middle">{{ $data->education->scYearGrade }}</td>
                            <td class="text-center align-middle">{{ $data->latestgenave ?? 'No Data Available' }}</td>
                            <td class="text-center align-middle">{{ $data->latestconduct ?? 'No Data Available' }}</td>
                            <td class="text-center align-middle">
                                {{ $data->latestchinesegenave ?? 'No Data Available' }}</td>
                            <td class="text-center align-middle">
                                {{ $data->latestchineseconduct ?? 'No Data Available' }}</td>
                            <td class="text-center align-middle">{{ $data->totalhcattendance }}/{{ $hcevents }}
                            </td>
                            <td class="text-center align-middle">{{ $data->penaltycount }}</td>
                            <td class="text-center align-middle">{{ $data->scholarshipinfo->scholarshipstatus }}</td>
                            <td class="text-center align-middle">
                                <a href="{{ route('scholar-viewinfo', $data->id) }}" class="btn btn-primary">View</a>
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
