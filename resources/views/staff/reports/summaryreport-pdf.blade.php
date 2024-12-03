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

        h4 {
            width: 100%;
            text-align: center;
            margin: 5px 0;
            color: #000;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }

        .table thead tr th {
            background-color: #084908;
            padding: 5px 0;
            font-size: 15px;
            color: #fff;
        }

        #tblhead {
            background-color: transparent;
            border: none;
            padding: 5px 0;
            font-size: 15px;
            color: #000;
        }

        .table tbody tr td {
            border: 1px solid #084908;
            background-color: #ebffeb;
            padding: 5px 0;
            font-size: 14px;
            color: #303030;
            text-align: center;
            width: 50%
        }

        #status-success {
            color: green;
            font-weight: bold;
        }

        #status-warning {
            color: orange;
            font-weight: bold;
        }

        #status-danger {
            color: red;
            font-weight: bold;
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
    <table style="margin: 10px 0; width: 100%">
        <tbody>
            <tr>
                <td style="text-align: left; font-size: 18px; border: none; width: 40%">School Level:
                    {{ $level }}</td>
                <td style="text-align: left; font-size: 18px; border: none; width: 60%">Date Scope:
                    {{ $scope }}</td>
            </tr>
        </tbody>
    </table>
    <div class="container">
        <h3>Scholars</h3>
        {{-- scholars per type --}}
        <h4>Number of Scholars per Type and Status</h4>
        <table class="table">
            <thead>
                <tr>
                    <th>School Type</th>
                    <th>School Status</th>
                    <th>Scholar Count</th>
                </tr>
            </thead>
            <tbody>
                @if ($scpertypestatus->isNotEmpty())
                    @foreach ($scpertypestatus as $data)
                        <tr>
                            <td>{{ $data->scholartype }}</td>
                            <td>{{ $data->scholarshipstatus }}</td>
                            <td>{{ $data->sccount }}</td>
                        </tr>
                    @endforeach
                    <tr>
                        <td colspan="2">Total Scholars</td>
                        <td>{{ $totalscholars }}</td>
                    </tr>
                @else
                    <tr>
                        <td colspan="3">No Records Found.</td>
                    </tr>
                @endif
            </tbody>
        </table>
        {{-- scholars per area --}}
        <h4>Number of Scholars per Area</h4>
        <table class="table">
            <thead>
                <tr>
                    <th>Area Name</th>
                    <th>Scholar Count</th>
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
                        <td colspan="2">No Records Found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        {{-- scholars per school/level --}}
        @if ($level == 'All')
            <h4>Number of Scholars per School and Level</h4>
            <table class="table">
                <thead>
                    <tr>
                        <th colspan="1">School Name</th>
                        <th colspan="2">School Level</th>
                        <th colspan="2">Scholar Count</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($scperlevelschool as $data)
                        <tr>
                            <td colspan="1">{{ $data->scSchoolName }}</td>
                            <td colspan="2">{{ $data->scSchoolLevel }}</td>
                            <td colspan="2">{{ $data->sccount }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">No Records Found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        @else
            <h4>Number of {{ $level }} Scholars per School</h4>
            <table class="table">
                <thead>
                    <tr>
                        <th>School Name</th>
                        <th>Scholar Count</th>
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
                            <td colspan="2">No Records Found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        @endif
        {{-- scholars per course/strand --}}
        @if ($level == 'College' || $level == 'Senior High')
            <h4>Number of Scholars per {{ $level == 'College' ? 'Course' : 'Strand' }}</h4>
            <table class="table">
                <thead>
                    <tr>
                        <th>{{ $level == 'College' ? 'Course' : 'Strand' }} Name</th>
                        <th>Scholar Count</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($scpercoursestrand as $data)
                        <tr>
                            <td>{{ $data->scCourseStrandSec }}</td>
                            <td>{{ $data->sccount }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="2">No Records Found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        @endif
        {{-- renewals per status --}}
        {{-- <h4>Number of Renewal Applications per Status</h4>
        <table class="table">
            <thead>
                <tr>
                    <th>Renewal Status</th>
                    <th>Scholar Count</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($renewalsperstatus as $data)
                    <tr>
                        <td>{{ $data->status }}</td>
                        <td>{{ $data->sccount }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="2">No Records Found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table> --}}
        @if ($level == 'All')
            {{-- list of college scholars --}}
            <h4>List of College Scholars</h4>
            <table class="table">
                <thead>
                    <tr>
                        <th class="text-center align-middle" colspan="2">#</th>
                        <th class="text-center align-middle" colspan="1">Name</th>
                        <th class="text-center align-middle" colspan="3">Year Level</th>
                        <th class="text-center align-middle" colspan="4">Scholarship Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($college as $index => $data)
                        @php
                            // Determine the appropriate status class
                            $statusClass = '';
                            switch ($data->scholarshipinfo->scholarshipstatus) {
                                case 'Continuing':
                                    $statusClass = 'status-success';
                                    break;
                                case 'On-Hold':
                                    $statusClass = 'status-warning';
                                    break;
                                case 'Terminated':
                                    $statusClass = 'status-danger';
                                    break;
                                default:
                                    $statusClass = '';
                                    break;
                            }
                        @endphp
                        <tr>
                            <td class="text-center align-middle" colspan="2">{{ $index + 1 }}</td>
                            <td class="text-center align-middle" colspan="1">{{ $data->basicInfo->scLastname }},
                                {{ $data->basicInfo->scFirstname }} {{ $data->basicInfo->scMiddlename }}</td>
                            <td class="text-center align-middle" colspan="3">{{ $data->education->scYearGrade }}
                            </td>
                            <td class="text-center align-middle" colspan="4" id="{{ $statusClass }}">
                                {{ $data->scholarshipinfo->scholarshipstatus }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td class="text-center align-middle" colspan="10">No Records Found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            {{-- list of Senior High Scholars --}}
            <h4>List of Senior High Scholars</h4>
            <table class="table">
                <thead>
                    <tr>
                        <th class="text-center align-middle" colspan="2">#</th>
                        <th class="text-center align-middle" colspan="1">Name</th>
                        <th class="text-center align-middle" colspan="3">Grade Level</th>
                        <th class="text-center align-middle" colspan="4">Scholarship Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($shs as $index => $data)
                        @php
                            $statusClass = '';
                            switch ($data->scholarshipinfo->scholarshipstatus) {
                                case 'Continuing':
                                    $statusClass = 'status-success';
                                    break;
                                case 'On-Hold':
                                    $statusClass = 'status-warning';
                                    break;
                                case 'Terminated':
                                    $statusClass = 'status-danger';
                                    break;
                                default:
                                    $statusClass = '';
                                    break;
                            }
                        @endphp
                        <tr>
                            <td class="text-center align-middle" colspan="2">{{ $index + 1 }}</td>
                            <td class="text-center align-middle" colspan="1">{{ $data->basicInfo->scLastname }},
                                {{ $data->basicInfo->scFirstname }} {{ $data->basicInfo->scMiddlename }}</td>
                            <td class="text-center align-middle" colspan="3">{{ $data->education->scYearGrade }}
                            </td>
                            <td class="text-center align-middle" colspan="4" id="{{ $statusClass }}">
                                {{ $data->scholarshipinfo->scholarshipstatus }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td class="text-center align-middle" colspan="10">No Records Found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            {{-- list of Junior High Scholars --}}
            <h4>List of Junior High Scholars</h4>
            <table class="table">
                <thead>
                    <tr>
                        <th class="text-center align-middle" colspan="2">#</th>
                        <th class="text-center align-middle" colspan="1">Name</th>
                        <th class="text-center align-middle" colspan="3">Grade Level</th>
                        <th class="text-center align-middle" colspan="4">Scholarship Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($jhs as $index => $data)
                        @php
                            $statusClass = '';
                            switch ($data->scholarshipinfo->scholarshipstatus) {
                                case 'Continuing':
                                    $statusClass = 'status-success';
                                    break;
                                case 'On-Hold':
                                    $statusClass = 'status-warning';
                                    break;
                                case 'Terminated':
                                    $statusClass = 'status-danger';
                                    break;
                                default:
                                    $statusClass = '';
                                    break;
                            }
                        @endphp
                        <tr>
                            <td class="text-center align-middle" colspan="2">{{ $index + 1 }}</td>
                            <td class="text-center align-middle" colspan="1">{{ $data->basicInfo->scLastname }},
                                {{ $data->basicInfo->scFirstname }} {{ $data->basicInfo->scMiddlename }}</td>
                            <td class="text-center align-middle" colspan="3">{{ $data->education->scYearGrade }}
                            </td>
                            <td class="text-center align-middle" colspan="4" id="{{ $statusClass }}">
                                {{ $data->scholarshipinfo->scholarshipstatus }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td class="text-center align-middle" colspan="10">No Records Found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            {{-- list of Elementary Scholars --}}
            <h4>List of Elementary Scholars</h4>
            <table class="table">
                <thead>
                    <tr>
                        <th class="text-center align-middle" colspan="2">#</th>
                        <th class="text-center align-middle" colspan="1">Name</th>
                        <th class="text-center align-middle" colspan="3">Grade Level</th>
                        <th class="text-center align-middle" colspan="4">Scholarship Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($elem as $index => $data)
                        @php
                            $statusClass = '';
                            switch ($data->scholarshipinfo->scholarshipstatus) {
                                case 'Continuing':
                                    $statusClass = 'status-success';
                                    break;
                                case 'On-Hold':
                                    $statusClass = 'status-warning';
                                    break;
                                case 'Terminated':
                                    $statusClass = 'status-danger';
                                    break;
                                default:
                                    $statusClass = '';
                                    break;
                            }
                        @endphp
                        <tr>
                            <td class="text-center align-middle" colspan="2">{{ $index + 1 }}</td>
                            <td class="text-center align-middle" colspan="1">{{ $data->basicInfo->scLastname }},
                                {{ $data->basicInfo->scFirstname }} {{ $data->basicInfo->scMiddlename }}</td>
                            <td class="text-center align-middle" colspan="3">{{ $data->education->scYearGrade }}
                            </td>
                            <td class="text-center align-middle" colspan="4" id="{{ $statusClass }}">
                                {{ $data->scholarshipinfo->scholarshipstatus }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td class="text-center align-middle" colspan="10">No Records Found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        @else
            {{-- list of selected scholars --}}
            <h4>List of {{ $level }} Scholars</h4>
            <table class="table">
                <thead>
                    <tr>
                        <th class="text-center align-middle" colspan="2">#</th>
                        <th class="text-center align-middle" colspan="1">Name</th>
                        <th class="text-center align-middle" colspan="3">Year Level</th>
                        <th class="text-center align-middle" colspan="4">Scholarship Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($scholars as $index => $data)
                        @php
                            // Determine the appropriate status class
                            $statusClass = '';
                            switch ($data->scholarshipinfo->scholarshipstatus) {
                                case 'Continuing':
                                    $statusClass = 'status-success';
                                    break;
                                case 'On-Hold':
                                    $statusClass = 'status-warning';
                                    break;
                                case 'Terminated':
                                    $statusClass = 'status-danger';
                                    break;
                                default:
                                    $statusClass = '';
                                    break;
                            }
                        @endphp
                        <tr>
                            <td class="text-center align-middle" colspan="2">{{ $index + 1 }}</td>
                            <td class="text-center align-middle" colspan="1">{{ $data->basicInfo->scLastname }},
                                {{ $data->basicInfo->scFirstname }} {{ $data->basicInfo->scMiddlename }}</td>
                            <td class="text-center align-middle" colspan="3">{{ $data->education->scYearGrade }}
                            </td>
                            <td class="text-center align-middle" colspan="4" id="{{ $statusClass }}">
                                {{ $data->scholarshipinfo->scholarshipstatus }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td class="text-center align-middle" colspan="10">No Records Found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        @endif
    </div>
</body>

</html>
