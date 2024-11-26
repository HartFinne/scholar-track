<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Request</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="{{ asset('css/special.css') }}">
    <link rel="stylesheet" href="{{ asset('css/partial.css') }}">
</head>

<body>
    <!-- Include Sidebar -->
    @include('partials._sidebar')

    <!-- Include Navbar -->
    @include('partials._navbar')
    <x-alert />

    <div class="ctn-main">
        <a href="{{ route('scspecial') }}" class="goback">&lt Go back</a>

        <div class="request-view">
            <div class="row">
                @php
                    $statusClasses = [
                        'Pending' => 'bg-warning',
                        'Accepted' => 'bg-success',
                        'Completed' => 'bg-success',
                        'Rejected' => 'bg-danger',
                    ];

                    $statusClass = $statusClasses[$data['requestStatus']] ?? 'bg-secondary text-white';
                @endphp
                <div class="col-auto" style="margin-left: auto">
                    <span class="h5 card px-4 py-2 {{ $statusClass }} text-white fw-bold">
                        {{ $data['requestStatus'] }}</span>
                </div>
            </div>

            <div class="card bg-light p-5 rounded border border-success">
                <div class="row mb-2">
                    <span class="col-12 py-2 h4 fw-bold header">{{ $form->formname }}</span>
                </div>
                {{-- REQUESTOR INFO --}}
                <div class="row mb-3">
                    <div class="row fw-bold h5 subheader">Requestor Info:</div>
                    <div class="row">
                        <div class="col-md-4 label">Name</div>
                        <div class="col-md-8 data">{{ $scholar->basicInfo->scFirstname }}
                            {{ $scholar->basicInfo->scLastname }}</div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 label">Area</div>
                        <div class="col-md-8 data">{{ $scholar->scholarshipinfo->area }}</div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 label">Year Level</div>
                        <div class="col-md-8 data">{{ $scholar->education->scYearGrade }}</div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 label">Course/Strand/Section</div>
                        <div class="col-md-8 data">{{ $scholar->education->scCourseStrandSec }}</div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 label">School</div>
                        <div class="col-md-8 data">{{ $scholar->education->scSchoolName }}</div>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="row fw-bold h5 subheader">Request Detail:</div>
                    <div class="row">
                        <div class="col-md-4 label">Request Type</div>
                        <div class="col-md-8 data">{{ $data['requestType'] }}</div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 label">Date of Request</div>
                        <div class="col-md-8 data">{{ \Carbon\Carbon::parse($data['requestDate'])->format('F j, Y') }}
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 label">Date of Release</div>
                        <div class="col-md-8 data">
                            {{ $data['releaseDate'] ? \Carbon\Carbon::parse($data['releaseDate'])->format('F j, Y') : 'Not Set Yet' }}
                        </div>
                    </div>
                    @foreach ($fields as $field)
                        <div class="row">
                            <div class="col-md-4 label">{{ $field->fieldname }}</div>
                            <div class="col-md-8 data">{{ $data[$field->fieldname] }}</div>
                        </div>
                    @endforeach
                </div>
                <div class="row mb-3">
                    <div class="row fw-bold h5 subheader">Required Documents:</div>
                    @foreach ($files as $file)
                        <div class="row">
                            <div class="col-12 label text-center">{{ $file->filename }}</div>
                        </div>
                        <div class="row">
                            <iframe src="{{ asset('storage/' . $data[$file->filename]) }}#zoom=100" width="100%"
                                height="700px">
                                Your browser does not support iframes. Please download the PDF file
                                <a href="{{ url('storage/' . $data[$file->filename]) }}">here</a>.
                            </iframe>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    <script src="{{ asset('js/scholar.js') }}"></script>
</body>

</html>
