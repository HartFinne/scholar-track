<!DOCTYPE html>
<html lang="en">

<head>
    <title>View Submitted Grades</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="{{ asset('css/partial.css') }}">
    <link rel="stylesheet" href="{{ asset('css/grades.css') }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body>
    <!-- Include Sidebar -->
    @include('partials._sidebar')

    <!-- Include Navbar -->
    @include('partials._navbar')
    <x-alert />

    <!-- MAIN -->
    <div class="ctn-main">
        <a href="{{ route('gradesub') }}" class="goback">&lt Go back</a>
        <div class="text-center">
            <h1 class="sub-title">Grades Submission</h1>
        </div>

        <div class="grades-view">
            <div class="grade-status">
                <h6 class="grade-stat">{{ $grade->GradeStatus }}</h6>
            </div>

            <div class="grades-info">
                <h5>GRADES SUBMITTED</h5>
                <div class="info">
                    <div class="label">School Year</div>
                    <div class="value">: <span>S.Y. {{ $grade->schoolyear }}</span></div>

                    <div class="label">Semester</div>
                    <div class="value">: <span>{{ $grade->SemesterQuarter }}</span></div>

                    <div class="label">GWA</div>
                    <div class="value">: <span>{{ $grade->GWA }}</span></div>

                    @if ($educ->scSchoolLevel != 'College')
                        <div class="label">Conduct</div>
                        <div class="value">: <span>{{ $grade->GWAConduct }}</span></div>
                        <div class="label">GWA (Chinese Subject)</div>
                        <div class="value">: <span>{{ $grade->ChineseGWA ?? 'N/A' }}</span></div>
                        <div class="label">Conduct (Chinese Subject)</div>
                        <div class="value">: <span>{{ $grade->ChineseGWAConduct ?? 'N/A' }}</span></div>
                    @endif

                </div>
                <div class="container">
                    <div class="row fw-bold text-center mb-2">
                        <div>Report Card</div>
                    </div>
                    <div class="grades-img text-center">
                    </div>
                </div>

                @php
                    // Get the file extension
                    $fileExtension = pathinfo($grade->ReportCard, PATHINFO_EXTENSION);

                    // dd($fileExtension);

                @endphp
                @if (in_array($fileExtension, ['jpeg', 'jpg', 'png', 'gif']))
                    <!-- Display image files -->
                    <img src="{{ asset('storage/' . $grade->ReportCard) }}" alt="Report Card"
                        style="max-width: 100%; height: auto;">
                @elseif($fileExtension === 'pdf')
                    <!-- Display PDF files -->
                    <iframe src="{{ asset('storage/' . $grade->ReportCard) }}" width="100%" height="600px">
                        Your browser does not support iframes. Please download the PDF file
                        <a href="{{ asset('storage/' . $grade->ReportCard) }}">here</a>.
                    </iframe>
                @elseif(in_array($fileExtension, ['doc', 'docx']))
                    <!-- Display link for DOC and DOCX files (since browsers can't display these directly) -->
                    <p>Your browser cannot display this document. Please download it below:</p>
                    <a href="{{ asset('storage/' . $grade->ReportCard) }}" target="_blank" class="btn btn-primary">
                        Download Document
                    </a>
                @else
                    <!-- Fallback message for unsupported file types -->
                    <p>File type not supported for preview. Please download the file below:</p>
                    <a href="{{ asset('storage/' . $grade->ReportCard) }}" target="_blank" class="btn btn-primary">
                        Download File
                    </a>
                @endif

            </div>
        </div>
    </div>


    </div>
    <script src="{{ asset('js/scholar.js') }}"></script>
</body>

</html>
