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
        <div class="container">
            <div class="col-md-1" style="margin-left: auto;">
                <a href="{{ route('penalty') }}" class="btn btn-success">Go back</a>
            </div>
            <div class="pagetitle mb-4 mt-2">Penalty Details | {{ $scholar->basicInfo->scLastname }},
                {{ $scholar->basicInfo->scFirstname }} {{ $scholar->basicInfo->scMiddlename }}</div>
            @foreach ($penalties as $condition => $group)
                <div class="card mx-auto border border-success rounded shadow-md col-md-6 mb-2 shadow">
                    <div class="card-header bg-success text-white text-center fw-bold h5">
                        {{ $condition }}
                    </div>
                    <div class="card-body p-4">
                        @foreach ($group as $penalty)
                            <div class="row mb-3 border-bottom border-success">
                                <div class="row mb-2">
                                    <div class="text-right font-weight-bold col-md-4">
                                        <span>Remark:</span>
                                    </div>
                                    <div class="col-md-8">
                                        <span>{{ $penalty->remark }}</span>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="text-right font-weight-bold col-md-4">
                                        <span>Date Issued:</span>
                                    </div>
                                    <div class="col-md-8">
                                        <span>{{ \Carbon\Carbon::parse($penalty->date_of_penalty)->format('F d, Y') }}</span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <script src="{{ asset('js/headercontrol.js') }}"></script>
</body>

</html>
