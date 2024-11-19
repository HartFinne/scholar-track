<!DOCTYPE html>
<html lang="en">

<head>
    <title>Scholarship Report</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js"></script>
    <style>
        * {
            padding: 0;
            margin: 0;
            font-family: Arial, Helvetica, sans-serif;
        }

        body {
            padding: 50px;
        }

        #headerlogo {
            height: 50px;
        }

        .headertitle {
            font-weight: bold;
            font-size: 18px;
        }

        h2 {
            text-align: center;
            margin: 10px 0;
        }

        h3 {
            font-size: 20px;
            border-bottom: 1px solid #d3d3d3;
            margin-bottom: 15px;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }

        .table thead tr th {
            background-color: #303030;
            padding: 5px 0;
            font-size: 15px;
            color: #fff;
        }

        .table tbody tr td {
            border: 1px solid #303030;
            background-color: #fff;
            padding: 5px 0;
            font-size: 14px;
            color: #303030;
            text-align: center;
            width: 50%
        }
    </style>
</head>

<body>
    <div class="header">
        <table width="100%">
            <tr>
                <td width="10%">
                    <img src="{{ public_path('images/logo.png') }}" id="headerlogo" alt="logo.png">
                </td>
                <td width="90%">
                    <div class="headertitle">
                        <span style="color: #2e7c55;">Tzu Chi Philippines</span><br>
                        <span style="color: #1a5319;">Educational Assistance Program</span>
                    </div>
                </td>
            </tr>
        </table>
    </div>
    <h2>Scholarship Summary Report</h2>
    <div class="container">
        <h3>Scholars</h3>
        {{-- scholars per type --}}
        <table class="table">
            <thead>
                <tr>
                    <th colspan="2">Number of Scholars per Type</th>
                </tr>
            </thead>
            <tbody>
                @if ($scpertype->isNotEmpty())
                    @foreach ($scpertype as $type)
                        <tr>
                            <td>{{ $type->scholartype }}</td>
                            <td>{{ $type->sccount }}</td>
                        </tr>
                    @endforeach
                    <tr>
                        <td>Total Scholars</td>
                        <td>{{ $scholars }}</td>
                    </tr>
                @else
                    <tr>
                        <td colspan="2">No data available</td>
                    </tr>
                @endif
            </tbody>
        </table>
        {{-- scholars per area --}}
        <table class="table">
            <thead>
                <tr>
                    <th colspan="2">Number of Scholars per Area</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($scperarea as $area)
                    <tr>
                        <td>{{ $area->area }}</td>
                        <td>{{ $area->sccount }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="2">No data available</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        {{-- scholars per school level --}}
        <table class="table">
            <thead>
                <tr>
                    <th colspan="2">Number of Scholars per School Level</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($scperlevel as $level)
                    <tr>
                        <td>{{ $level->scSchoolLevel }}</td>
                        <td>{{ $level->sccount }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="2">No data available</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        {{-- scholars per school --}}
        <table class="table">
            <thead>
                <tr>
                    <th colspan="2">Number of Scholars per School</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($scperschool as $school)
                    <tr>
                        <td>{{ $school->scSchoolName }}</td>
                        <td>{{ $school->sccount }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="2">No data available</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        {{-- scholars per course --}}
        <table class="table">
            <thead>
                <tr>
                    <th colspan="2">Number of Scholars per Course</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($scpercourse as $course)
                    <tr>
                        <td>{{ $course->scCourseStrandSec }}</td>
                        <td>{{ $course->sccount }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="2">No data available</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        {{-- scholars per strand --}}
        <table class="table">
            <thead>
                <tr>
                    <th colspan="2">Number of Scholars per Strand</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($scperstrand as $strand)
                    <tr>
                        <td>{{ $strand->scCourseStrandSec }}</td>
                        <td>{{ $strand->sccount }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="2">No data available</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <h3>Applicants</h3>
        {{-- applicant per school level --}}
        <table class="table">
            <thead>
                <tr>
                    <th colspan="2">Number of Applicants per School Level</th>
                </tr>
            </thead>
            {{-- <tbody>
                @forelse ($apperlevel as $level)
                    <tr>
                        <td>{{ $level->apSchoolLevel }}</td>
                        <td>{{ $level->apcount }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="2">No data available</td>
                    </tr>
                @endforelse
            </tbody> --}}
        </table>
        {{-- applicant per school --}}
        <table class="table">
            <thead>
                <tr>
                    <th colspan="2">Number of Applicants per School</th>
                </tr>
            </thead>
            {{-- <tbody>
                @forelse ($apperlevel as $level)
                    <tr>
                        <td>{{ $level->apSchoolLevel }}</td>
                        <td>{{ $level->apcount }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="2">No data available</td>
                    </tr>
                @endforelse
            </tbody> --}}
        </table>
        {{-- applicant per region --}}
        <table class="table">
            <thead>
                <tr>
                    <th colspan="2">Number of Applicants per Region</th>
                </tr>
            </thead>
            {{-- <tbody>
                @forelse ($apperlevel as $level)
                    <tr>
                        <td>{{ $level->apSchoolLevel }}</td>
                        <td>{{ $level->apcount }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="2">No data available</td>
                    </tr>
                @endforelse
            </tbody> --}}
        </table>
    </div>
</body>

</html>
