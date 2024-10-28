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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
</head>

<body>
    <!-- Include Sidebar -->
    @include('partials._pageheader')

    <!-- MAIN CONTENT -->
    <div class="ctnmain">
        <div class="row">
            <span class="pagetitle">Scholars Performance Evaluation (College Only)</span>
        </div>
        @foreach ($acadyears as $acadyear)
            <div class="row">
                <span class="fw-bold">Academic Year: <strong>{{ $acadyear->acadyear }}</strong></span>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th class="text-center align-middle">#</th>
                            <th class="text-center align-middle">Case Code</th>
                            <th class="text-center align-middle">Evaluation Score</th>
                            <th class="text-center align-middle">Remark</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $index = 1; @endphp <!-- Reset index for each acadyear -->
                        @foreach ($results as $result)
                            @if ($result->acadyear == $acadyear->acadyear)
                                <tr>
                                    <td class="text-center align-middle">{{ $index++ }}</td>
                                    <td class="text-center align-middle">{{ $result->caseCode }}</td>
                                    <td class="text-center align-middle">{{ $result->evalscore }}%</td>
                                    <td class="text-center align-middle">
                                        {{ $result->isPassed == 1 ? 'Qualified' : 'Unqualified' }}
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endforeach
    </div>
    <script src="{{ asset('js/headercontrol.js') }}"></script>
</body>

</html>
