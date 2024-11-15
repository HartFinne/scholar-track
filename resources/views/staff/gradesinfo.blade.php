<!DOCTYPE html>
<html lang="en">

<head>
    <title>View Submitted Grades</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="{{ asset('css/grades.css') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body>
    <!-- Include Sidebar -->
    @include('partials._pageheader')
    <x-alert />
    <!-- MAIN -->
    <div class="ctnmain">
        <div class="container">
            <div class="row my-2 d-flex align-items-end">
                <div class="col-md-10 px-4">
                    <span class="fw-bold" style="font-size: 20px">{{ $scholar->caseCode }} |
                        {{ $scholar->basicInfo->scLastname }},
                        {{ $scholar->basicInfo->scFirstname }}
                        {{ $scholar->basicInfo->scMiddlename }}</span>
                </div>
                <div class="col-md-2">
                    <a href="{{ route('scholar-viewinfo', $scholar->id) }}" class="btn btn-success w-100">&lt Go
                        back</a>
                </div>
            </div>
            <div class="container rounded mx-auto p-4" style="background-color: #eaebea">
                <h5 class="px-4">
                    <form class="row d-flex align-items-center mb-3"
                        action="{{ route('updategradestatus', $grade->gid) }}" method="POST">
                        @csrf
                        <div class="col-md-2">Grade Status</div>
                        <div class="col-md-8">
                            <select name="gradestatus" class="form-control" style="cursor: pointer">
                                <option value="Pending" {{ $grade->GradeStatus == 'Pending' ? 'selected' : '' }}>
                                    Pending
                                </option>
                                <option value="Passed" {{ $grade->GradeStatus == 'Passed' ? 'selected' : '' }}>Passed
                                </option>
                                <option value="Failed GWA" {{ $grade->GradeStatus == 'Failed GWA' ? 'selected' : '' }}>
                                    Failed GWA</option>
                                <option value="Failed GWA (Chinese Subject)"
                                    {{ $grade->GradeStatus == 'Failed GWA (Chinese Subject)' ? 'selected' : '' }}>
                                    Failed GWA (Chinese Subject)</option>
                                <option value="Failed Grade"
                                    {{ $grade->GradeStatus == 'Failed Grade' ? 'selected' : '' }}>
                                    Failed Grade
                                </option>
                                <option value="Mismatched GWA"
                                    {{ $grade->GradeStatus == 'Mismatched GWA' ? 'selected' : '' }}>
                                    Mismatched GWA</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-success w-100">Update Status</button>
                        </div>
                    </form>
                </h5>

                <div class="row my-2">
                    <div class="col-md-3">School Year</div>
                    <div class="col-md-9 fw-bold">: <span>S.Y. {{ $grade->schoolyear }}</span></div>
                </div>

                <div class="row my-2">
                    <div class="col-md-3">Semester</div>
                    <div class="col-md-9 fw-bold">: <span>{{ $grade->SemesterQuarter }}</span></div>
                </div>

                <div class="row my-2">
                    <div class="col-md-3">
                        {{ $scholar->education->scSchoolLevel == 'College' ? 'GWA' : 'General Average' }}</div>
                    <div class="col-md-9 fw-bold">: <span>{{ $grade->GWA }}</span></div>
                </div>

                @if ($scholar->education->scSchoolLevel != 'College')
                    <div class="row my-2">
                        <div class="col-md-3">Conduct</div>
                        <div class="col-md-9 fw-bold">: <span>{{ $grade->GWAConduct }}</span></div>
                    </div>
                    <div class="row my-2">
                        <div class="col-md-3">General Average (Chinese Subject)</div>
                        <div class="col-md-9 fw-bold">: <span>{{ $grade->ChineseGWA }}</span></div>
                    </div>
                    <div class="row my-2">
                        <div class="col-md-3">Conduct (Chinese Subject)</div>
                        <div class="col-md-9 fw-bold">: <span>{{ $grade->ChineseGWAConduct }}</span></div>
                    </div>
                @endif

                <div class="row fw-bold text-center my-2">
                    <div>Report Card</div>
                </div>

                @php
                    $filePath = Storage::url($grade->ReportCard);
                    $fileExtension = pathinfo($filePath, PATHINFO_EXTENSION);
                @endphp

                <div class="grades-img text-center row my-2">
                    @if ($fileExtension === 'pdf')
                        <!-- Display PDF using object tag -->
                        <object data="{{ $filePath }}" type="application/pdf" width="100%" height="600px">
                            <p>Your browser does not support PDFs. <a href="{{ $filePath }}">Download the PDF</a>.
                            </p>
                        </object>
                    @else
                        <!-- Display image -->
                        <img src="{{ $filePath }}" alt="Report Card" style="max-width: 100%; height: auto;">
                    @endif
                </div>

            </div>
        </div>
    </div>
    <script src="{{ asset('js/headercontrol.js') }}"></script>
</body>

</html>
