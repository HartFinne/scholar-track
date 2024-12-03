<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Request</title>
    <link href="https://cdn.lineicons.com/4.0/lineicons.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
        integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ asset('css/special.css') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body>
    <!-- PAGE HEADER -->
    @include('partials._pageheader')
    <x-alert />
    <div class="ctnmain">
        <div class="row">
            <span class="h1 text-success text-center fw-bold mt-3 my-4">Special Allowance Request Detail</span>
        </div>
        <div class="request-view">
            @php
                $statusClasses = [
                    'Pending' => 'bg-warning text-white',
                    'Accepted' => 'bg-success text-white',
                    'Completed' => 'bg-success text-white',
                    'Rejected' => 'bg-danger text-white',
                ];

                $statusClass = $statusClasses[$data['requestStatus']] ?? 'bg-secondary text-white';
            @endphp
            <div class="row gx-0 d-flex justify-content-between align-items-center">
                <div class="col-auto">
                    <a href="{{ route('allowancerequests-special') }}" class="btn btn-success h5 px-4 py-2">Go back</a>
                </div>
                <div class="col-auto d-flex align-items-center">
                    <span class="h5 card px-4 py-2 {{ $statusClass }} fw-bold text-center me-3">
                        {{ $data['requestStatus'] }}
                    </span>
                    <button class="btn btn-success h5 px-4 py-2" data-bs-toggle="modal"
                        data-bs-target="#updateStatusModal">
                        Update Status
                    </button>
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
                        <div class="row align-items-center">
                            <div class="col-md-4 label">{{ $field->fieldname }}</div>
                            <div class="col-md-8 data">
                                @if ($field->fieldtype == 'file')
                                    @php
                                        $filePath = $data[$field->fieldname];
                                        $fileExtension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
                                        $modalId = 'fileModal-' . $field->fieldname; // Unique modal ID for each file
                                    @endphp

                                    <!-- Button to trigger the modal -->
                                    @if ($fileExtension == 'zip')
                                        The data is in a ZIP file. Please <a href="{{ url('storage/' . $filePath) }}"
                                            download class="link link-success">download it here</a>.
                                    @else
                                        <button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal"
                                            data-bs-target="#{{ $field->id }}">
                                            View File
                                        </button>
                                    @endif

                                    <!-- Modal -->
                                    <div class="modal fade" id="{{ $field->id }}" tabindex="-1"
                                        aria-labelledby="{{ $field->id }}Label" aria-hidden="true">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header bg-success text-white">
                                                    <h5 class="modal-title" id="{{ $field->id }}Label">
                                                        {{ $field->fieldname }}</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    @if ($fileExtension == 'pdf')
                                                        <iframe src="{{ asset('storage/' . $filePath) }}#zoom=100"
                                                            width="100%" height="500px">
                                                            Your browser does not support iframes. Please download the
                                                            PDF file
                                                            <a href="{{ url('storage/' . $filePath) }}" download
                                                                class="link link-success">here</a>.
                                                        </iframe>
                                                    @elseif ($fileExtension != 'pdf' || $fileExtension != 'zip')
                                                        <img src="{{ asset('storage/' . $filePath) }}" width="100%"
                                                            height="auto" alt="{{ $field->fieldname }}">
                                                    @endif
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-danger"
                                                        data-bs-dismiss="modal">Close</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    {{ $data[$field->fieldname] }}
                                @endif
                            </div>
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
                                <a href="{{ url('storage/' . $data[$file->filename]) }}" class="link link-success"
                                    download>here</a>.
                            </iframe>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div><!-- Modal -->
    <div class="modal fade" id="updateStatusModal" tabindex="-1" aria-labelledby="updateStatusModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title" id="updateStatusModalLabel">Update Request Status</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="updateStatusForm" method="POST" action="{{ route('updatespecreq') }}">
                    <div class="modal-body">
                        @csrf
                        <input type="hidden" name="requestId" value="{{ $data['id'] }}">
                        <input type="hidden" name="requestType" value="{{ $form->formname }}">
                        <input type="hidden" name="oldStatus" value="{{ $data['requestStatus'] }}">
                        <div class="mb-3">
                            <label for="requestStatus" class="form-label fw-bold">Status</label>
                            <select class="form-select border-success" id="requestStatus" name="requestStatus"
                                required>
                                <option value="Accepted" {{ $data['requestStatus'] == 'Accepted' ? 'selected' : '' }}>
                                    Accepted
                                </option>
                                <option value="Completed"
                                    {{ $data['requestStatus'] == 'Completed' ? 'selected' : '' }}>
                                    Completed
                                </option>
                                <option value="Rejected" {{ $data['requestStatus'] == 'Rejected' ? 'selected' : '' }}>
                                    Rejected
                                </option>
                            </select>
                        </div>
                        <div class="mb-3" {{ $data['requestStatus'] == 'Pending' ? '' : 'hidden' }}>
                            <label for="releasedate" class="form-label fw-bold">Date of Release</label>
                            <input type="date" name="releasedate" min="{{ today()->toDateString() }}"
                                value="{{ $data['releaseDate'] ?? '' }}" class="form-control border-success"
                                required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" form="updateStatusForm" class="btn btn-success">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script src="{{ asset('js/headercontrol.js') }}"></script>
</body>

</html>
