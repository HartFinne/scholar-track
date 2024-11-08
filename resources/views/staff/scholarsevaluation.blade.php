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
            {{-- <div class="col-md-9"> --}}
            <span class="pagetitle">Scholars Performance Evaluation (College Only)</span>
            {{-- </div> --}}
            {{-- <div class="col-md-3">
                <a href="{{ route('showmetrics') }}" class="btn btn-success w-100">Model Performance Metrics</a>
            </div> --}}
        </div>
        <div class="row">
            <div class="card mb-3">
                <div class="col-md-2 h5 fw-bold">Accuracy</div>
                <div class="col-md-10 h5">{{ toInteger($data['accuracy']) * 100 }}</div>
            </div>
        </div>
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert" id="success-alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert" id="error-alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if ($acadyears->isEmpty())
            <div class="row">
                <span class="fw-bold text-center">No Available Data.</span>
            </div>
        @else
            @foreach ($acadyears as $acadyear)
                <div class="row">
                    <span class="fw-bold">Academic Year: <strong>{{ $acadyear->acadyear }}</strong></span>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered border-success">
                        <thead>
                            <tr>
                                <th class="text-center align-middle">#</th>
                                <th class="text-center align-middle">Case Code</th>
                                <th class="text-center align-middle">Scholar Name</th>
                                <th class="text-center align-middle">Evaluation Score</th>
                                <th class="text-center align-middle">Remark</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $index = 1; @endphp
                            @foreach ($results as $result)
                                @if ($result->acadyear == $acadyear->acadyear)
                                    <tr>
                                        <td class="text-center align-middle">{{ $index++ }}</td>
                                        <td class="text-center align-middle">{{ $result->caseCode }}</td>
                                        <td class="text-center align-middle">{{ $result->basicInfo->scLastname }},
                                            {{ $result->basicInfo->scFirstname }}
                                            {{ $result->basicInfo->scMiddlename }}
                                        </td>
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
        @endif
    </div>
    <script src="{{ asset('js/headercontrol.js') }}"></script>
</body>

</html>
