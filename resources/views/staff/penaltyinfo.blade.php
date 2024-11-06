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
        <span class="pagetitle mb-4">Penalty Details | {{ $scholar->basicInfo->scLastname }},
            {{ $scholar->basicInfo->scFirstname }} {{ $scholar->basicInfo->scMiddlename }}</span>

        <div class="card mx-auto border border-success rounded shadow-md col-md-6">
            <div class="card-header bg-success text-white text-center font-weight-bold">
                {{ $penalty->condition }}
            </div>
            <div class="card-body p-4">
                <div class="row mb-3">
                    <div class="text-right font-weight-bold col-md-4">
                        <span>Remark:</span>
                    </div>
                    <div class=" col-md-8">
                        <span>{{ $penalty->remark }}</span>
                    </div>
                </div>
                <div class="row">
                    <div class="text-right font-weight-bold col-md-4">
                        <span>Date Issued:</span>
                    </div>
                    <div class=" col-md-8">
                        <span>{{ \Carbon\Carbon::parse($penalty->dateofpenalty)->format('F d, Y') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/headercontrol.js') }}"></script>
</body>

</html>
