<!DOCTYPE html>
<html lang="en">

<head>
    <title>Scholars Evaluation</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/table.css') }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body>
    @include('partials._pageheader')

    <div class="ctnmain">
        <div class="row align-items-center">
            <span class="pagetitle">Scholars Performance Evaluation</span>
        </div>
        <div class="row align-items-center text-center">
            <span class="pagetitle m-0">College</span>
        </div>
        @php
            $cycles = [
                'Semester' => ['columns' => ['1st Sem GWA', '2nd Sem GWA']],
                'Trimester' => ['columns' => ['1st Sem GWA', '2nd Sem GWA', '3rd Sem GWA']],
            ];
        @endphp

        @foreach ($cycles as $cycle => $data)
            <div class="row">
                <span class="pagetitle">Academic Cycle: {{ $cycle }}</span>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered border-success">
                    <thead>
                        <tr>
                            <th class="text-center align-middle">#</th>
                            {{-- <th class="text-center align-middle">Case Code</th> --}}
                            <th class="text-center align-middle">Scholar Name</th>
                            @foreach ($data['columns'] as $column)
                                <th class="text-center align-middle">{{ $column }}</th>
                            @endforeach
                            <th class="text-center align-middle">Rendered CS Hours</th>
                            <th class="text-center align-middle">Penalty Count</th>
                            <th class="text-center align-middle">Remark</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $index = 1; @endphp
                        @php $cycleResults = $results->filter(fn($result) => $result->acadcycle == $cycle); @endphp

                        @if ($cycleResults->isEmpty())
                            <tr>
                                <td class="text-center align-middle" colspan="{{ 5 + count($data['columns']) }}">No data
                                    available</td>
                            </tr>
                        @else
                            @foreach ($cycleResults as $result)
                                <tr>
                                    <td class="text-center align-middle">{{ $index++ }}</td>
                                    {{-- <td class="text-center align-middle">{{ $result->caseCode }}</td> --}}
                                    <td class="text-center align-middle">{{ $result->basicInfo->scLastname }},
                                        {{ $result->basicInfo->scFirstname }}
                                        {{ $result->basicInfo->scMiddlename }}
                                    </td>
                                    @foreach ($data['columns'] as $key => $column)
                                        <td class="text-center align-middle">
                                            {{ $result->{'gwasem' . ($key + 1)} ?? 'N/A' }}
                                        </td>
                                    @endforeach
                                    <td class="text-center align-middle">{{ $result->cshours ?? 'N/A' }}</td>
                                    <td class="text-center align-middle">{{ $result->penaltycount ?? 'N/A' }}</td>
                                    <td class="text-center align-middle">{{ $result->remark ?? 'N/A' }}</td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        @endforeach

        {{-- @endforeach
        @endif --}}
    </div>
    <script src="{{ asset('js/headercontrol.js') }}"></script>
</body>

</html>
