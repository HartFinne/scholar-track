<!DOCTYPE html>
<html lang="en">

<head>
    <title>Scholarship Criteria | College</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/table.css') }}">
    <link rel="stylesheet" href="{{ asset('css/applicationforms.css') }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdn.ckeditor.com/ckeditor5/43.3.1/ckeditor5.css" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        .tblinput {
            width: 100%;
            height: fit-content;
            resize: none;
            border: 1px solid transparent !important;
            outline: none;
            padding: 2px
        }

        .tblinput:focus {
            background-color: #fff;
            border: 1px solid #ced4da;
        }
    </style>
</head>

<body>
    <!-- PAGE HEADER -->
    @include('partials._pageheader')
    <x-alert />

    <div class="ctnmain">
        <div class="container">
            <div class="row g-5 d-flex align-items-stretch">
                {{-- APPLICATION FORMS --}}
                <div class="col-md-6 d-flex flex-column">
                    <fieldset class="row mb-3 p-3 rounded border border-success flex-fill">
                        <legend class="fw-bold text-success h4">Manage Application Forms</legend>
                        <div class="ctntable table-responsive">
                            <table class="table table-bordered" id="tblapplicationforms">
                                <thead>
                                    <tr>
                                        <th class="text-center align-middle">Form</th>
                                        <th class="text-center align-middle">Deadline of Application</th>
                                        <th class="text-center align-middle">Status</th>
                                        <th class="text-center align-middle">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($forms as $form)
                                        <tr>
                                            <td class="text-center align-middle">{{ $form->formname }}</td>
                                            <td class="text-center align-middle">
                                                {{ $form->deadline ? \Carbon\Carbon::parse($form->deadline)->format('F j, Y') : '--' }}
                                            </td>
                                            <td class="text-center align-middle">{{ $form->status }}</td>
                                            <td class="text-center align-middle">
                                                @if ($form->status == 'Closed')
                                                    <button type="button" class="btn btn-success open-modal-btn"
                                                        data-bs-toggle="modal" data-bs-target="#openApplicationModal"
                                                        data-formname="{{ $form->formname }}">
                                                        Open
                                                    </button>
                                                @else
                                                    <button type="button" class="btn btn-sm btn-danger"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#closeApplicationModal{{ $form->formname }}">
                                                        Close
                                                    </button>
                                                @endif
                                            </td>
                                        </tr>
                                        <!-- Close Form Confirmation Modal -->
                                        <div class="modal fade" id="closeApplicationModal{{ $form->formname }}"
                                            tabindex="-1"
                                            aria-labelledby="closeApplicationModalLabel{{ $form->formname }}"
                                            aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header bg-danger text-white fw-bold">
                                                        <h5 class="modal-title"
                                                            id="closeApplicationModalLabel{{ $form->formname }}">
                                                            Confirm
                                                            Closure</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        Are you sure you want to close the application form
                                                        "{{ $form->formname }}"?
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-bs-dismiss="modal">Cancel</button>
                                                        <form method="post"
                                                            action="{{ route('updateappformstatus', ['formname' => $form->formname, 'status' => 'Closed']) }}">
                                                            @csrf
                                                            <button type="submit"
                                                                class="btn btn-danger">Confirm</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </fieldset>
                </div>

                {{-- APPLICATION INSTRUCTIONS --}}
                <div class="col-md-6 d-flex flex-column">
                    <fieldset class="row mb-3 p-3 rounded border border-success flex-fill">
                        <legend class="fw-bold text-success h4">Manage Application Instructions</legend>
                        <div class="ctntable table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th class="text-center align-middle">Application Instructions For</th>
                                        <th class="text-center align-middle">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($instruction as $level => $details)
                                        <tr>
                                            <td class="text-center align-middle">{{ $level }}</td>
                                            <td class="text-center align-middle">
                                                <button class="btn btn-warning btn-sm open-modal-btn"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#{{ Str::slug($level) }}InstructionModal"><i
                                                        class="fas fa-edit"></i></button>
                                            </td>
                                        </tr>
                                        {{-- Modal for Each Instruction --}}
                                        <div class="modal fade" id="{{ Str::slug($level) }}InstructionModal"
                                            tabindex="-1"
                                            aria-labelledby="{{ Str::slug($level) }}InstructionModalLabel"
                                            aria-hidden="true">
                                            <div class="modal-dialog modal-lg">
                                                <div class="modal-content">
                                                    <div class="modal-header bg-warning fw-bold">
                                                        <h5 class="modal-title"
                                                            id="{{ Str::slug($level) }}InstructionModalLabel">
                                                            {{ $level }} Application Instructions
                                                        </h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <form
                                                        action="{{ route('updateapplicationinstructions', ['level' => $level]) }}"
                                                        method="post">
                                                        @csrf
                                                        <div class="modal-body">
                                                            {{-- Who can apply? --}}
                                                            <div class="mb-3">
                                                                <label for="{{ Str::slug($level) }}Applicants"
                                                                    class="form-label fw-bold">Who can apply?</label>
                                                                <textarea class="form-control border-success" name="applicants" id="{{ Str::slug($level) }}Applicants"
                                                                    rows="4">{!! $details->applicants ?? '' !!}</textarea>
                                                            </div>
                                                            {{-- What are the qualifications? --}}
                                                            <div class="mb-3">
                                                                <label for="{{ Str::slug($level) }}Qualifications"
                                                                    class="form-label fw-bold">What are the
                                                                    qualifications?</label>
                                                                <textarea class="form-control border-success" name="qualifications" id="{{ Str::slug($level) }}Qualifications"
                                                                    rows="4">{!! $details->qualifications ?? '' !!}</textarea>
                                                            </div>
                                                            {{-- Documents to prepare --}}
                                                            <div class="mb-3">
                                                                <label for="{{ Str::slug($level) }}Documents"
                                                                    class="form-label fw-bold">Documents to
                                                                    prepare:</label>
                                                                <textarea class="form-control border-success" name="documents" id="{{ Str::slug($level) }}Documents"
                                                                    rows="4">{!! $details->requireddocuments ?? '' !!}</textarea>
                                                            </div>
                                                            {{-- Application Process --}}
                                                            <div class="mb-3">
                                                                <label for="{{ Str::slug($level) }}Process"
                                                                    class="form-label fw-bold">Application
                                                                    Process:</label>
                                                                <textarea class="form-control border-success" name="process" id="{{ Str::slug($level) }}Process" rows="4">{!! $details->applicationprocess ?? '' !!}</textarea>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary"
                                                                data-bs-dismiss="modal">Cancel</button>
                                                            <button type="submit" class="btn btn-warning">Save
                                                                Changes</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </fieldset>
                </div>
            </div>

            {{-- EMAIL IMPORT --}}
            <fieldset class="row mb-3 p-3 rounded border border-success">
                <legend class="fw-bold text-success h4">Import Scholar Emails</legend>
                <span class="col-12 mb-2">
                    Please upload the Excel file containing scholar emails for registration verification.
                    Ensure the file contains only email addresses, and they must be placed in the first column of the
                    sheet.
                </span>
                <form action="{{ route('importemails') }}" method="POST" enctype="multipart/form-data"
                    class="col-12" id="importForm">
                    @csrf
                    <div class="input-group">
                        <input type="file" class="form-control border-success" id="file" name="file"
                            required accept=".xlsx,.xls" aria-describedby="fileHelp">
                        <button class="btn btn-success" type="submit" style="z-index: 1">Upload</button>
                    </div>
                    <small id="fileHelp" class="form-text text-muted">
                        File must be in .xlsx or .xls format.
                    </small>
                </form>
            </fieldset>

            {{-- SCHOLARSHIP REQUIREMENTS --}}
            <fieldset class="row mb-3 p-3 rounded border border-success">
                <div class="row justify-content-between align-items-center mx-auto">
                    <div class="col-md-6 mb-2">
                        <legend class="fw-bold text-success h4">Scholarship Requirements</legend>
                    </div>
                    <div class="col-md-2 mb-2">
                        <button type="button" class="btn btn-warning w-100" data-bs-toggle="modal"
                            data-bs-target="#editRequirementsModal">
                            Edit Requirements
                        </button>
                    </div>
                </div>
                <div class="row mx-auto">
                    <div class="col-md-2 mb-3">
                        <label for="cshours" class="form-label fw-bold">Required CS Hours</label>
                        <input type="number" class="form-control border-success" id="cshours"
                            value="{{ $criteria->cshours ?? 'Not Set' }}" readonly>
                    </div>
                    <div class="col-md-2 mb-3">
                        <label for="cgwa" class="form-label fw-bold">College GWA</label>
                        <input type="number" class="form-control border-success" id="cgwa"
                            value="{{ $criteria->cgwa ?? 'Not Set' }}" readonly>
                    </div>
                    <div class="col-md-2 mb-3">
                        <label for="shsgwa" class="form-label fw-bold">Senior High GWA</label>
                        <input type="number" class="form-control border-success" id="shsgwa"
                            value="{{ $criteria->shsgwa ?? 'Not Set' }}" readonly>
                    </div>
                    <div class="col-md-2 mb-3">
                        <label for="jhsgwa" class="form-label fw-bold">Junior High GWA</label>
                        <input type="number" class="form-control border-success" id="jhsgwa"
                            value="{{ $criteria->jhsgwa ?? 'Not Set' }}" readonly>
                    </div>
                    <div class="col-md-2 mb-3">
                        <label for="elemgwa" class="form-label fw-bold">Elementary GWA</label>
                        <input type="number" class="form-control border-success" id="elemgwa"
                            value="{{ $criteria->elemgwa ?? 'Not Set' }}" readonly>
                    </div>
                </div>
                <div class="row mx-auto">
                    <div class="col-md-2 mb-3">
                        <label for="fincome" class="form-label fw-bold">Father's Income</label>
                        <input type="number" class="form-control border-success" id="fincome"
                            value="{{ $criteria->fincome ?? 'Not Set' }}" readonly>
                    </div>
                    <div class="col-md-2 mb-3">
                        <label for="mincome" class="form-label fw-bold">Mother's Income</label>
                        <input type="number" class="form-control border-success" id="mincome"
                            value="{{ $criteria->mincome ?? 'Not Set' }}" readonly>
                    </div>
                    <div class="col-md-2 mb-3">
                        <label for="sincome" class="form-label fw-bold">Siblings' Income</label>
                        <input type="number" class="form-control border-success" id="sincome"
                            value="{{ $criteria->sincome ?? 'Not Set' }}" readonly>
                    </div>
                    <div class="col-md-2 mb-3">
                        <label for="aincome" class="form-label fw-bold">Applicant's Income</label>
                        <input type="number" class="form-control border-success" id="aincome"
                            value="{{ $criteria->aincome ?? 'Not Set' }}" readonly>
                    </div>
                </div>
            </fieldset>

            <!-- Areas Section -->
            <fieldset class="row mb-3 p-3 rounded border border-success">
                <div class="row justify-content-between align-items-center mb-2 mx-auto">
                    <div class="col-auto">
                        <legend class="fw-bold text-success h4">Areas</legend>
                    </div>
                    <div class="col-auto">
                        <button type="button" class="btn btn-success" data-bs-toggle="modal"
                            data-bs-target="#addAreaModal">
                            Add Area
                        </button>
                    </div>
                </div>
                <div class="row mx-auto">
                    <div class="ctntable table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th class="text-center align-middle">Area Name</th>
                                    <th class="text-center align-middle">Area Code</th>
                                    <th class="text-center align-middle">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($areas as $area)
                                    <tr>
                                        <td class="text-center align-middle">{{ $area->areaname }}</td>
                                        <td class="text-center align-middle">{{ $area->areacode }}</td>
                                        <td class="text-center align-middle">
                                            <button type="button" class="btn btn-sm btn-warning"
                                                data-bs-toggle="modal"
                                                data-bs-target="#editAreaModal{{ $area->id }}">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-danger"
                                                data-bs-toggle="modal"
                                                data-bs-target="#deleteAreaModal{{ $area->id }}">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <!-- Edit Area Modal -->
                                    <div class="modal fade" id="editAreaModal{{ $area->id }}" tabindex="-1"
                                        aria-labelledby="editAreaModalLabel{{ $area->id }}" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header bg-warning fw-bold">
                                                    <h5 class="modal-title"
                                                        id="editAreaModalLabel{{ $area->id }}">
                                                        Edit Area</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <form method="POST" action="{{ route('updateArea', $area->id) }}">
                                                    @csrf
                                                    <div class="modal-body">
                                                        <div
                                                            class="row mb-3 align-items-center justify-content-center">
                                                            <div class="col-md-4">
                                                                <label for="areaName{{ $area->id }}"
                                                                    class="form-label fw-bold">Area Name</label>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <input type="text"
                                                                    class="form-control border-success"
                                                                    id="areaName{{ $area->id }}"
                                                                    name="newareaname" value="{{ $area->areaname }}"
                                                                    required>
                                                            </div>
                                                        </div>
                                                        <div
                                                            class="row mb-3 align-items-center justify-content-center">
                                                            <div class="col-md-4">
                                                                <label for="areaCode{{ $area->id }}"
                                                                    class="form-label fw-bold">Area Code</label>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <input type="text"
                                                                    class="form-control border-success"
                                                                    id="areaCode{{ $area->id }}"
                                                                    name="newareacode" value="{{ $area->areacode }}"
                                                                    required>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-bs-dismiss="modal">Cancel</button>
                                                        <button type="submit" class="btn btn-warning">Save
                                                            Changes</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Delete Area Confirmation Modal -->
                                    <div class="modal fade" id="deleteAreaModal{{ $area->id }}" tabindex="-1"
                                        aria-labelledby="deleteAreaModalLabel{{ $area->id }}" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header bg-danger text-white fw-bold">
                                                    <h5 class="modal-title"
                                                        id="deleteAreaModalLabel{{ $area->id }}">
                                                        Confirm Delete</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    Are you sure you want to delete this area: {{ $area->areaname }}?
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-bs-dismiss="modal">Cancel</button>
                                                    <form method="POST"
                                                        action="{{ route('deleteArea', $area->id) }}"
                                                        style="display:inline;">
                                                        @csrf
                                                        <button type="submit" class="btn btn-danger">Delete</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <tr>
                                        <td class="text-center align-middle" colspan="4">No Records Found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                        <!-- Pagination Links -->
                        <div class="d-flex justify-content-center mt-3">
                            {{ $areas->links('pagination::bootstrap-4') }}
                        </div>
                    </div>
                </div>
            </fieldset>

            <!-- Institutions Section -->
            <fieldset class="row mb-3 p-3 rounded border border-success">
                <div class="row justify-content-between align-items-center mb-2 mx-auto">
                    <div class="col-auto">
                        <span class="fw-bold text-success h4">Institutions</span>
                    </div>
                    <div class="col-auto">
                        <button class="btn btn-success" data-bs-toggle="modal"
                            data-bs-target="#addInstitutionModal">Add
                            Institution</button>
                    </div>
                </div>
                <div class="row mx-auto">
                    <div class="ctntable table-responsive">
                        <table class="table table-bordered" id="tblinstitutions">
                            <thead>
                                <tr>
                                    <th class="text-center w-50">Institution Name</th>
                                    <th class="text-center">School Level</th>
                                    <th class="text-center">Academic Cycle</th>
                                    <th class="text-center">Highest GWA</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($institutions as $institution)
                                    <tr>
                                        <td class="text-center align-middle">{{ $institution->schoolname }}</td>
                                        <td class="text-center align-middle">{{ $institution->schoollevel }}</td>
                                        <td class="text-center align-middle">{{ $institution->academiccycle }}</td>
                                        <td class="text-center align-middle">{{ $institution->highestgwa }}</td>
                                        <td class="text-center align-middle">
                                            <button class="btn btn-sm btn-warning" data-bs-toggle="modal"
                                                data-bs-target="#editInstitutionModal{{ $institution->inid }}"><i
                                                    class="fas fa-edit"></i></button>
                                            <button class="btn btn-sm btn-danger" data-bs-toggle="modal"
                                                data-bs-target="#deleteInstitutionModal{{ $institution->inid }}"><i
                                                    class="fas fa-trash"></i></button>
                                        </td>
                                    </tr>
                                    <!-- Edit Institution Modal -->
                                    <div class="modal fade" id="editInstitutionModal{{ $institution->inid }}"
                                        tabindex="-1"
                                        aria-labelledby="editInstitutionModalLabel{{ $institution->inid }}"
                                        aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header bg-warning fw-bold">
                                                    <h5 class="modal-title"
                                                        id="editInstitutionModalLabel{{ $institution->inid }}">Edit
                                                        Institution</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <form method="POST"
                                                    action="{{ route('updateinstitution', $institution->inid) }}">
                                                    @csrf
                                                    <div class="modal-body row">
                                                        <div class="col-md-12 mb-3">
                                                            <label for="newSchoolName{{ $institution->inid }}"
                                                                class="form-label fw-bold">Institution Name</label>
                                                            <input class="form-control border-success"
                                                                id="newSchoolName{{ $institution->inid }}"
                                                                name="newschoolname" maxlength="255" required
                                                                value="{{ $institution->schoolname }}">
                                                        </div>
                                                        <div class="col-md-4 mb-3">
                                                            <label for="newSchoolLevel{{ $institution->inid }}"
                                                                class="form-label fw-bold">School Level</label>
                                                            <select class="form-select border-success"
                                                                id="newSchoolLevel{{ $institution->inid }}"
                                                                name="newschoollevel" required>
                                                                <option value="College"
                                                                    {{ $institution->schoollevel == 'College' ? 'selected' : '' }}>
                                                                    College</option>
                                                                <option value="Senior High"
                                                                    {{ $institution->schoollevel == 'Senior High' ? 'selected' : '' }}>
                                                                    Senior High</option>
                                                                <option value="Junior High"
                                                                    {{ $institution->schoollevel == 'Junior High' ? 'selected' : '' }}>
                                                                    Junior High</option>
                                                                <option value="Elementary"
                                                                    {{ $institution->schoollevel == 'Elementary' ? 'selected' : '' }}>
                                                                    Elementary</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-4 mb-3">
                                                            <label for="newAcademicCycle{{ $institution->inid }}"
                                                                class="form-label fw-bold">Academic Cycle</label>
                                                            <select class="form-select border-success"
                                                                id="newAcademicCycle{{ $institution->inid }}"
                                                                name="newacademiccycle" required>
                                                                <option value="Semester"
                                                                    {{ $institution->academiccycle == 'Semester' ? 'selected' : '' }}>
                                                                    Semester</option>
                                                                <option value="Trimester"
                                                                    {{ $institution->academiccycle == 'Trimester' ? 'selected' : '' }}>
                                                                    Trimester</option>
                                                                <option value="Quarter"
                                                                    {{ $institution->academiccycle == 'Quarter' ? 'selected' : '' }}>
                                                                    Quarter</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-4 mb-3">
                                                            <label for="newGWA{{ $institution->inid }}"
                                                                class="form-label fw-bold">Highest GWA</label>
                                                            <input type="number" class="form-control border-success"
                                                                id="newGWA{{ $institution->inid }}" name="newgwa"
                                                                required min="1" max="100" step="0.01"
                                                                value="{{ $institution->highestgwa }}">
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-bs-dismiss="modal">Cancel</button>
                                                        <button type="submit" the class="btn btn-warning">Save
                                                            Changes</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Delete Institution Confirmation Modal -->
                                    <div class="modal fade" id="deleteInstitutionModal{{ $institution->inid }}"
                                        tabindex="-1"
                                        aria-labelledby="deleteInstitutionModalLabel{{ $institution->inid }}"
                                        aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header bg-danger text-white fw-bold">
                                                    <h5 class="modal-title"
                                                        id="deleteInstitutionModalLabel{{ $institution->inid }}">
                                                        Confirm
                                                        Delete</h5>
                                                    <button type="button" the class="btn-close"
                                                        data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    Are you sure you want to delete this institution:
                                                    {{ $institution->schoolname }}?
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-bs-dismiss="modal">Cancel</button>
                                                    <form method="POST"
                                                        action="{{ route('deleteinstitution', $institution->inid) }}"
                                                        style="display:inline;">
                                                        @csrf
                                                        <button type="submit" class="btn btn-danger">Delete</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </tbody>
                        </table>
                        <!-- Pagination Links -->
                        <div class="d-flex justify-content-center mt-3">
                            {{ $institutions->links('pagination::bootstrap-4') }}
                        </div>
                    </div>
                </div>
            </fieldset>

            <!-- Courses Section -->
            <fieldset class="row mb-3 p-3 rounded border border-success">
                <div class="row justify-content-between align-items-center mb-2 mx-auto">
                    <div class="col-auto">
                        <span class="fw-bold text-success h4">Courses</span>
                    </div>
                    <div class="col-auto">
                        <button class="btn btn-success w-100" data-bs-toggle="modal"
                            data-bs-target="#addCourseModal">Add
                            Course</button>
                    </div>
                </div>
                <div class="row mx-auto">
                    <div class="ctntable table-responsive">
                        <table class="table table-bordered" id="tblcourses">
                            <thead>
                                <tr>
                                    <th class="text-center" style="width: 75%;">Course</th>
                                    <th class="text-center" style="width: 25%;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($courses as $course)
                                    <tr>
                                        <td class="text-center align-middle">{{ $course->coursename }}</td>
                                        <td class="text-center align-middle">
                                            <button class="btn btn-sm btn-warning" data-bs-toggle="modal"
                                                data-bs-target="#editCourseModal{{ $course->coid }}"><i
                                                    class="fas fa-edit"></i></button>
                                            <button class="btn btn-sm btn-danger" data-bs-toggle="modal"
                                                data-bs-target="#deleteCourseModal{{ $course->coid }}"><i
                                                    class="fas fa-trash"></i></button>
                                        </td>
                                    </tr>
                                    <!-- Edit course modal -->
                                    <div class="modal fade" id="editCourseModal{{ $course->coid }}" tabindex="-1"
                                        aria-labelledby="editCourseModalLabel{{ $course->coid }}" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header bg-warning fw-bold">
                                                    <h5 class="modal-title"
                                                        id="editCourseModalLabel{{ $course->coid }}">
                                                        Edit Course</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <form method="POST"
                                                    action="{{ route('updatecourse', $course->coid) }}">
                                                    @csrf
                                                    <div class="modal-body">
                                                        <div class="mb-3">
                                                            <label for="newCourseName{{ $course->coid }}"
                                                                class="form-label fw-bold">Course Name</label>
                                                            <input class="form-control border-success"
                                                                id="newCourseName{{ $course->coid }}"
                                                                name="newcoursename" required
                                                                value="{{ $course->coursename }}">
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-bs-dismiss="modal">Cancel</button>
                                                        <button type="submit" class="btn btn-warning">Save
                                                            Changes</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Delete Course Confirmation Modal -->
                                    <div class="modal fade" id="deleteCourseModal{{ $course->coid }}"
                                        tabindex="-1" aria-labelledby="deleteCourseModalLabel{{ $course->coid }}"
                                        aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header bg-danger text-white fw-bold">
                                                    <h5 class="modal-title"
                                                        id="deleteCourseModalLabel{{ $course->coid }}">Confirm Delete
                                                    </h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    Are you sure you want to delete this course:
                                                    {{ $course->coursename }}?
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-bs-dismiss="modal">Cancel</button>
                                                    <form method="POST"
                                                        action="{{ route('deletecourse', $course->coid) }}"
                                                        style="display:inline;">
                                                        @csrf
                                                        <button type="submit" class="btn btn-danger">Delete</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </tbody>
                        </table>
                        <!-- Pagination Links -->
                        <div class="d-flex justify-content-center mt-3">
                            {{ $courses->links('pagination::bootstrap-4') }}
                        </div>
                    </div>
                </div>
            </fieldset>

            {{-- STRANDS --}}
            <fieldset class="row mb-3 p-3 rounded border border-success">
                <div class="row justify-content-between align-items-center mb-2 mx-auto">
                    <div class="col-auto">
                        <span class="fw-bold text-success h4">Strand</span>
                    </div>
                    <div class="col-auto">
                        <button class="btn btn-success w-100" data-bs-toggle="modal"
                            data-bs-target="#addStrandModal">Add
                            Strand</button>
                    </div>
                </div>
                <div class="row mx-auto">
                    <div class="ctntable table-responsive">
                        <table class="table table-bordered" id="tblstrands">
                            <thead>
                                <tr>
                                    <th class="text-center" style="width: 75%;">Strand</th>
                                    <th class="text-center" style="width: 25%;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($strands as $strand)
                                    <tr>
                                        <td class="text-center align-middle">{{ $strand->coursename }}</td>
                                        <td class="text-center align-middle">
                                            <button class="btn btn-sm btn-warning" data-bs-toggle="modal"
                                                data-bs-target="#editStrandModal{{ $strand->coid }}"><i
                                                    class="fas fa-edit"></i></button>
                                            <button class="btn btn-sm btn-danger" data-bs-toggle="modal"
                                                data-bs-target="#deleteStrandModal{{ $strand->coid }}"><i
                                                    class="fas fa-trash"></i></button>
                                        </td>
                                    </tr>
                                    <!-- Edit Strand Modal -->
                                    <div class="modal fade" id="editStrandModal{{ $strand->coid }}" tabindex="-1"
                                        aria-labelledby="editStrandModalLabel{{ $strand->coid }}"
                                        aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header bg-warning fw-bold">
                                                    <h5 class="modal-title"
                                                        id="editStrandModalLabel{{ $strand->coid }}">
                                                        Edit Strand</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <form method="POST"
                                                    action="{{ route('updatecourse', $strand->coid) }}">
                                                    @csrf
                                                    <div class="modal-body">
                                                        <div class="mb-3">
                                                            <label for="newStrandName{{ $strand->coid }}"
                                                                class="form-label fw-bold">Strand Name</label>
                                                            <input class="form-control border-success"
                                                                id="newStrandName{{ $strand->coid }}"
                                                                name="newcoursename" required
                                                                value="{{ $strand->coursename }}">
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-bs-dismiss="modal">Cancel</button>
                                                        <button type="submit" class="btn btn-warning">Save
                                                            Changes</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Delete Strand Confirmation Modal -->
                                    <div class="modal fade" id="deleteStrandModal{{ $strand->coid }}"
                                        tabindex="-1" aria-labelledby="deleteStrandModalLabel{{ $strand->coid }}"
                                        aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header bg-danger text-white fw-bold">
                                                    <h5 class="modal-title"
                                                        id="deleteStrandModalLabel{{ $strand->coid }}">Confirm Delete
                                                    </h5>
                                                    <button type="button" the class="btn-close"
                                                        data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    Are you sure you want to delete this strand:
                                                    {{ $strand->coursename }}?
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-bs-dismiss="modal">Cancel</button>
                                                    <form method="POST"
                                                        action="{{ route('deletecourse', $strand->coid) }}"
                                                        style="display:inline;">
                                                        @csrf
                                                        <button type="submit" class="btn btn-danger">Delete</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <tr>
                                        <td class="text-center align-middle" colspan="2">No Records Found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                        <!-- Pagination Links -->
                        <div class="d-flex justify-content-center mt-3">
                            {{ $strands->links('pagination::bootstrap-4') }}
                        </div>
                    </div>
                </div>
            </fieldset>
        </div>
    </div>

    <!-- Modal for Editing Requirements -->
    <div class="modal fade" id="editRequirementsModal" tabindex="-1" aria-labelledby="editRequirementsModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-warning fw-bold">
                    <h5 class="modal-title" id="editRequirementsModalLabel">Edit Scholarship Requirements</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action="{{ route('updatecriteria') }}">
                    @csrf
                    <div class="modal-body row justify-content-evenly">
                        <div class="col-md-5">
                            <div class="row align-items-center">
                                <div class="col-md-8">
                                    <label for="cshours" class="form-label fw-bold">Required CS Hours</label>
                                </div>
                                <div class="col-md-4">
                                    <input type="number" min="0" class="form-control border-success"
                                        id="cshours" name="cshours" value="{{ $criteria->cshours ?? '' }}"
                                        placeholder="Not Set" required>
                                </div>
                            </div>
                            <div class="row align-items-center mt-3">
                                <div class="col-md-8">
                                    <label for="cgwa" class="form-label fw-bold">College GWA</label>
                                </div>
                                <div class="col-md-4">
                                    <input type="number" min="1" max="5"
                                        class="form-control border-success" id="cgwa" name="cgwa"
                                        value="{{ $criteria->cgwa ?? '' }}" placeholder="Not Set" required
                                        step="0.01">
                                </div>
                            </div>
                            <div class="row align-items-center mt-3">
                                <div class="col-md-8">
                                    <label for="shsgwa" class="form-label fw-bold">Senior High GWA</label>
                                </div>
                                <div class="col-md-4">
                                    <input type="number" min="1" max="100"
                                        class="form-control border-success" id="shsgwa" name="shsgwa"
                                        value="{{ $criteria->shsgwa ?? '' }}" placeholder="Not Set" required
                                        step="0.01">
                                </div>
                            </div>
                            <div class="row align-items-center mt-3">
                                <div class="col-md-8">
                                    <label for="jhsgwa" class="form-label fw-bold">Junior High GWA</label>
                                </div>
                                <div class="col-md-4">
                                    <input type="number" min="1" max="100"
                                        class="form-control border-success" id="jhsgwa" name="jhsgwa"
                                        value="{{ $criteria->jhsgwa ?? '' }}" placeholder="Not Set" required
                                        step="0.01">
                                </div>
                            </div>
                            <div class="row align-items-center mt-3">
                                <div class="col-md-8">
                                    <label for="elemgwa" class="form-label fw-bold">Elementary GWA</label>
                                </div>
                                <div class="col-md-4">
                                    <input type="number" min="1" max="100"
                                        class="form-control border-success" id="elemgwa" name="elemgwa"
                                        value="{{ $criteria->elemgwa ?? '' }}" placeholder="Not Set" required
                                        step="0.01">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="row align-items-center">
                                <div class="col-md-8">
                                    <label for="fincome" class="form-label fw-bold">Father's Income</label>
                                </div>
                                <div class="col-md-4">
                                    <input type="number" min="1" class="form-control border-success"
                                        id="fincome" name="fincome" value="{{ $criteria->fincome ?? '' }}"
                                        placeholder="Not Set" required>
                                </div>
                            </div>
                            <div class="row align-items-center mt-3">
                                <div class="col-md-8">
                                    <label for="mincome" class="form-label fw-bold">Mother's Income</label>
                                </div>
                                <div class="col-md-4">
                                    <input type="number" min="1" class="form-control border-success"
                                        id="mincome" name="mincome" value="{{ $criteria->mincome ?? '' }}"
                                        placeholder="Not Set" required>
                                </div>
                            </div>
                            <div class="row align-items-center mt-3">
                                <div class="col-md-8">
                                    <label for="sincome" class="form-label fw-bold">Siblings' Income</label>
                                </div>
                                <div class="col-md-4">
                                    <input type="number" min="1" class="form-control border-success"
                                        id="sincome" name="sincome" value="{{ $criteria->sincome ?? '' }}"
                                        placeholder="Not Set" required>
                                </div>
                            </div>
                            <div class="row align-items-center mt-3">
                                <div class="col-md-8">
                                    <label for="aincome" class="form-label fw-bold">Applicant's Income</label>
                                </div>
                                <div class="col-md-4">
                                    <input type="number" min="1" class="form-control border-success"
                                        id="aincome" name="aincome" value="{{ $criteria->aincome ?? '' }}"
                                        placeholder="Not Set" required>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-warning">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Add Area Modal -->
    <div class="modal fade" id="addAreaModal" tabindex="-1" aria-labelledby="addAreaModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-success text-white fw-bold">
                    <h5 class="modal-title" id="addAreaModalLabel">Add Area</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action="{{ route('addArea') }}">
                    @csrf
                    <div class="modal-body">
                        <div class="row mb-3 align-items-center justify-content-center">
                            <div class="col-md-4">
                                <label for="areaName" class="form-label fw-bold">Area Name</label>
                            </div>
                            <div class="col-md-6">
                                <input type="text" class="form-control border-success" id="areaName"
                                    name="areaname" placeholder="Enter area name" required
                                    value="{{ old('areaname') }}">
                            </div>
                        </div>
                        <div class="row mb-3 align-items-center justify-content-center">
                            <div class="col-md-4">
                                <label for="areaCode" class="form-label fw-bold">Area Code</label>
                            </div>
                            <div class="col-md-6">
                                <input type="text" class="form-control border-success" id="areaCode"
                                    name="areacode" placeholder="Enter area code" required
                                    value="{{ old('areacode') }}">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success">Add Area</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Add Institution Modal -->
    <div class="modal fade" id="addInstitutionModal" tabindex="-1" aria-labelledby="addInstitutionModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-success text-white fw-bold">
                    <h5 class="modal-title" id="addInstitutionModalLabel">Add New Institution</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action="{{ route('addinstitution') }}">
                    @csrf
                    <div class="modal-body row">
                        <div class="col-md-12 mb-3">
                            <label for="institutionName" class="form-label fw-bold">Institution Name</label>
                            <input type="text" class="form-control border-success" id="institutionName"
                                name="institute" maxlength="255" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="schoolLevel" class="form-label fw-bold">School Level</label>
                            <select class="form-select border-success" id="schoolLevel" name="schoollevel" required>
                                <option value="" selected hidden>Select Level</option>
                                <option value="College">College</option>
                                <option value="Senior High">Senior High</option>
                                <option value="Junior High">Junior High</option>
                                <option value="Elementary">Elementary</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="academicCycle" class="form-label fw-bold">Academic Cycle</label>
                            <select class="form-select border-success" id="academicCycle" name="academiccycle"
                                required>
                                <option value="" selected hidden>Select Cycle</option>
                                <option value="Semester">Semester</option>
                                <option value="Trimester">Trimester</option>
                                <option value="Quarter">Quarter</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="highestGWA" class="form-label fw-bold">Highest GWA</label>
                            <input type="number" class="form-control border-success" id="highestGWA"
                                name="highestgwa" required step="0.01" min="1" max="100">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success">Add Institution</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Add Course Modal -->
    <div class="modal fade" id="addCourseModal" tabindex="-1" aria-labelledby="addCourseModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-success text-white fw-bold">
                    <h5 class="modal-title" id="addCourseModalLabel">Add New Course</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action="{{ route('addcourse', 'College') }}">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="courseName" class="form-label fw-bold">Course Name</label>
                            <input type="text" class="form-control border-success" id="courseName" name="course"
                                required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success">Add Course</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Add Strand Modal -->
    <div class="modal fade" id="addStrandModal" tabindex="-1" aria-labelledby="addStrandModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-success text-white fw-bold">
                    <h5 class="modal-title" id="addStrandModalLabel">Add New Strand</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action="{{ route('addcourse', 'Senior High') }}">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="strandName" class="form-label fw-bold">Strand Name</label>
                            <input type="text" class="form-control border-success" id="strandName" name="strand"
                                required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success">Add Strand</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- loading import --}}
    <div class="modal fade" id="loadingImport" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-success">
                <div class="modal-body text-center p-4">
                    <div class="spinner-border text-success mt-3 mb-4" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p style="font-size: 1.25em; color: #28a745; font-weight: 500;">
                        Importing your file...
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- open application form -->
    <div class="modal fade" id="openApplicationModal" tabindex="-1" aria-labelledby="openApplicationModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-success text-white fw-bold">
                    <h5 class="modal-title" id="openApplicationModalLabel">Open Application Form</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="openapplicationform" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="container">
                            <div class="row mb-1">
                                Set deadline for submission of hard copy of documents:
                            </div>
                            <div class="row mb-3">
                                <input required type="date" id="deadline" name="deadline"
                                    class="form-control border-success">
                            </div>
                            <div class="row mb-1">
                                Set end date of application:
                            </div>
                            <div class="row mb-3">
                                <input required type="date" id="enddate" name="enddate"
                                    class="form-control border-success">
                            </div>
                            <div class="row small">
                                Note: The form automatically close after the end.
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success">Confirm</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/headercontrol.js') }}"></script>
    <script src="{{ asset('js/criteriacontrol.js') }}"></script>
    <script src="https://cdn.ckeditor.com/ckeditor5/43.3.1/ckeditor5.umd.js"></script>
    <script>
        const {
            ClassicEditor,
            Essentials,
            Bold,
            Italic,
            Font,
            Paragraph,
            List,
            Link // Import the Link plugin
        } = CKEDITOR;

        document.addEventListener('DOMContentLoaded', function() {
            // Initialize CKEditor for all dynamically loaded textareas
            initializeEditors();
        });

        function initializeEditors() {
            const textareas = document.querySelectorAll('textarea.form-control'); // Target all textareas
            textareas.forEach((textarea) => {
                ClassicEditor
                    .create(textarea, {
                        plugins: [Essentials, Bold, Italic, Font, Paragraph, List, Link],
                        toolbar: [
                            'undo', 'redo', '|', 'bold', 'italic', '|',
                            'fontSize', 'fontFamily', 'fontColor', 'fontBackgroundColor', '|',
                            'bulletedList', 'numberedList', '|',
                            'link' // Ensure the 'link' plugin is included
                        ],
                        link: {
                            addTargetToExternalLinks: true // Optionally add target=_blank for external links
                        }
                    })
                    .then(editor => {
                        textarea.editorInstance = editor; // Store editor instance on the textarea element
                    })
                    .catch(error => {
                        console.error(error);
                    });
            });

            // Update textarea content before form submission
            document.querySelectorAll('form').forEach(form => {
                form.onsubmit = function() {
                    const editors = form.querySelectorAll('textarea.form-control');
                    editors.forEach(textarea => {
                        if (textarea.editorInstance) {
                            textarea.value = textarea.editorInstance
                                .getData(); // Sync editor content to textarea
                        }
                    });
                };
            });
        }

        // Function to save form data to localStorage
        function saveFormData() {
            const collegeApplicants = document.getElementById('collegeApplicants').value;
            const collegeQualifications = document.getElementById('collegeQualifications').value;
            const collegeDocuments = document.getElementById('collegeDocuments').value;
            const collegeProcess = document.getElementById('collegeProcess').value;

            const hsApplicants = document.getElementById('hsApplicants').value;
            const hsQualifications = document.getElementById('hsQualifications').value;
            const hsDocuments = document.getElementById('hsDocuments').value;
            const hsProcess = document.getElementById('hsProcess').value;

            const elemApplicants = document.getElementById('elemApplicants').value;
            const elemQualifications = document.getElementById('elemQualifications').value;
            const elemDocuments = document.getElementById('elemDocuments').value;
            const elemProcess = document.getElementById('elemProcess').value;

            // Save each value to localStorage
            localStorage.setItem('collegeApplicants', collegeApplicants);
            localStorage.setItem('collegeQualifications', collegeQualifications);
            localStorage.setItem('collegeDocuments', collegeDocuments);
            localStorage.setItem('collegeProcess', collegeProcess);

            localStorage.setItem('hsApplicants', hsApplicants);
            localStorage.setItem('hsQualifications', hsQualifications);
            localStorage.setItem('hsDocuments', hsDocuments);
            localStorage.setItem('hsProcess', hsProcess);

            localStorage.setItem('elemApplicants', elemApplicants);
            localStorage.setItem('elemQualifications', elemQualifications);
            localStorage.setItem('elemDocuments', elemDocuments);
            localStorage.setItem('elemProcess', elemProcess);
        }

        // Function to load form data from localStorage
        function loadFormData() {
            if (localStorage.getItem('collegeApplicants')) {
                document.getElementById('collegeApplicants').value = localStorage.getItem('collegeApplicants');
            }
            if (localStorage.getItem('collegeQualifications')) {
                document.getElementById('collegeQualifications').value = localStorage.getItem('collegeQualifications');
            }
            if (localStorage.getItem('collegeDocuments')) {
                document.getElementById('collegeDocuments').value = localStorage.getItem('collegeDocuments');
            }
            if (localStorage.getItem('collegeProcess')) {
                document.getElementById('collegeProcess').value = localStorage.getItem('collegeProcess');
            }

            if (localStorage.getItem('hsApplicants')) {
                document.getElementById('hsApplicants').value = localStorage.getItem('hsApplicants');
            }
            if (localStorage.getItem('hsQualifications')) {
                document.getElementById('hsQualifications').value = localStorage.getItem('hsQualifications');
            }
            if (localStorage.getItem('hsDocuments')) {
                document.getElementById('hsDocuments').value = localStorage.getItem('hsDocuments');
            }
            if (localStorage.getItem('hsProcess')) {
                document.getElementById('hsProcess').value = localStorage.getItem('hsProcess');
            }

            if (localStorage.getItem('elemApplicants')) {
                document.getElementById('elemApplicants').value = localStorage.getItem('elemApplicants');
            }
            if (localStorage.getItem('elemQualifications')) {
                document.getElementById('elemQualifications').value = localStorage.getItem('elemQualifications');
            }
            if (localStorage.getItem('elemDocuments')) {
                document.getElementById('elemDocuments').value = localStorage.getItem('elemDocuments');
            }
            if (localStorage.getItem('elemProcess')) {
                document.getElementById('elemProcess').value = localStorage.getItem('elemProcess');
            }
        }

        // Event listener to save the form data whenever user types in the fields
        document.addEventListener('input', function(event) {
            if (event.target.tagName.toLowerCase() === 'textarea') {
                saveFormData();
            }
        });

        // Load form data on page load
        document.addEventListener('DOMContentLoaded', function() {
            loadFormData();
        });

        document.addEventListener('DOMContentLoaded', function() {
            const modal = document.getElementById('openApplicationModal');
            const form = document.getElementById('openapplicationform');

            modal.addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget; // Button that triggered the modal
                const formname = button.getAttribute('data-formname'); // Extract form name

                // Set the action dynamically based on the form name
                form.action =
                    `{{ route('updateappformstatus', ['formname' => '__FORMNAME__', 'status' => 'Open']) }}`
                    .replace('__FORMNAME__', formname);
            });
        });

        document.addEventListener("DOMContentLoaded", function() {
            const importForm = document.getElementById("importForm");

            if (importForm) {
                importForm.addEventListener("submit", function(event) {
                    const loadingModal = new bootstrap.Modal(document.getElementById('loadingImport'));
                    loadingModal.show();
                });
            }
        });
    </script>
    <script>
        document.getElementById('institutionName').addEventListener('input', function() {
            const inputInstitution = this.value.trim();

            if (!inputInstitution) {
                // Clear fields if input is empty
                clearFields();
                return;
            }

            // Perform AJAX request to fetch school details
            fetch('/api/school', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    },
                    body: JSON.stringify({
                        institute: inputInstitution
                    }),
                })
                .then(response => response.json())
                .then(data => {
                    if (data) {
                        populateFields(data.academiccycle, data.highestgwa, 'College');
                    } else {
                        clearFields();
                    }
                })
                .catch(error => {
                    console.error('Error fetching school data:', error);
                    clearFields(); // Clear fields on error for consistency
                });
        });

        document.getElementById('schoolLevel').addEventListener('change', function() {
            const selectedLevel = this.value;
            const inputInstitution = document.getElementById('institutionName').value.trim();

            if (selectedLevel === 'College' && inputInstitution) {
                // Fetch school details again if College is selected
                fetch('/api/school', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        },
                        body: JSON.stringify({
                            institute: inputInstitution
                        }),
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data) {
                            populateFields(data.academiccycle, data.highestgwa);
                        } else {
                            clearFields();
                        }
                    })
                    .catch(error => {
                        console.error('Error fetching school data:', error);
                        clearFields();
                    });
            } else if (selectedLevel === 'Senior High') {
                populateFields('Semester', 100);
            } else {
                populateFields('Quarter', 100);
            }
        });

        // Function to populate form fields
        function populateFields(academicCycle, highestGWA, schoolLevel = '') {
            const schoolLevelSelect = document.getElementById('schoolLevel');
            const academicCycleSelect = document.getElementById('academicCycle');
            const highestGWAInput = document.getElementById('highestGWA');

            if (schoolLevel) schoolLevelSelect.value = schoolLevel;
            academicCycleSelect.value = academicCycle;
            highestGWAInput.value = highestGWA;
        }

        // Function to clear form fields
        function clearFields() {
            document.getElementById('schoolLevel').value = '';
            document.getElementById('academicCycle').value = '';
            document.getElementById('highestGWA').value = '';
        }
    </script>

</body>

</html>
