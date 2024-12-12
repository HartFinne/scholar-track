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
                                                    <button type="button" class="btn btn-sm btn-success open-modal-btn"
                                                        data-bs-toggle="modal" data-bs-target="#openApplicationModal"
                                                        data-formname="{{ $form->formname }}">
                                                        Open
                                                    </button>
                                                @else
                                                    <button type="button" class="btn btn-sm btn-danger close-modal-btn"
                                                        data-bs-toggle="modal" data-bs-target="#closeApplicationModal"
                                                        data-formname="{{ $form->formname }}">
                                                        Close
                                                    </button>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach

                                    <!-- Close Form Confirmation Modal -->
                                    <div class="modal fade" id="closeApplicationModal" tabindex="-1"
                                        aria-labelledby="closeApplicationModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header bg-danger text-white fw-bold">
                                                    <h5 class="modal-title" id="closeApplicationModalLabel">Confirm
                                                        Closure</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    Are you sure you want to close the application form "<span
                                                        id="closeFormName"></span>"?
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-bs-dismiss="modal">Cancel</button>
                                                    <form id="closeApplicationForm" method="POST">
                                                        @csrf
                                                        <button type="submit" class="btn btn-danger">Confirm</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- open application form -->
                                    <div class="modal fade" id="openApplicationModal" tabindex="-1"
                                        aria-labelledby="openApplicationModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header bg-success text-white fw-bold">
                                                    <h5 class="modal-title" id="openApplicationModalLabel">Open
                                                        Application Form</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <form id="openapplicationform" method="POST">
                                                    @csrf
                                                    <div class="modal-body">
                                                        <div class="container">
                                                            <div class="row mb-1">
                                                                Set deadline for submission of hard copy of documents:
                                                            </div>
                                                            <div class="row mb-3">
                                                                <input required type="date" id="deadline"
                                                                    name="deadline" class="form-control border-success">
                                                            </div>
                                                            <div class="row small">
                                                                Note: The form automatically closes after the end date.
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-bs-dismiss="modal">Cancel</button>
                                                        <button type="submit" class="btn btn-success">Confirm</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                    <script>
                                        document.addEventListener('DOMContentLoaded', function() {
                                            // Open Modal Dynamic Action
                                            const openModal = document.getElementById('openApplicationModal');
                                            const openForm = document.getElementById('openapplicationform');

                                            openModal.addEventListener('show.bs.modal', function(event) {
                                                const button = event.relatedTarget;
                                                const formname = button.getAttribute('data-formname');

                                                openForm.action =
                                                    `{{ route('updateappformstatus', ['formname' => '__FORMNAME__', 'status' => 'Open']) }}`
                                                    .replace('__FORMNAME__', formname);
                                            });

                                            // Close Modal Dynamic Action
                                            const closeModal = document.getElementById('closeApplicationModal');
                                            const closeForm = document.getElementById('closeApplicationForm');
                                            const closeFormNameSpan = document.getElementById('closeFormName');

                                            closeModal.addEventListener('show.bs.modal', function(event) {
                                                const button = event.relatedTarget;
                                                const formname = button.getAttribute('data-formname');

                                                closeFormNameSpan.textContent = formname;

                                                closeForm.action =
                                                    `{{ route('updateappformstatus', ['formname' => '__FORMNAME__', 'status' => 'Closed']) }}`
                                                    .replace('__FORMNAME__', formname);
                                            });
                                        });
                                    </script>
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
                                                        <button type="button" class="btn-close"
                                                            data-bs-dismiss="modal" aria-label="Close"></button>
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
                <div class="row mx-auto mb-3 mx-auto">
                    <div class="col-md-6 mb-2">
                        <legend class="fw-bold text-success h4 my-auto">Scholarship Requirements</legend>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex justify-content-end">
                            <div class="me-2">
                                <button type="button" class="btn btn-warning w-100" data-bs-toggle="modal"
                                    data-bs-target="#editRequirementsModal">
                                    Edit Requirements
                                </button>
                            </div>
                            <div>
                                <button type="button" class="btn btn-success w-100" data-bs-toggle="modal"
                                    data-bs-target="#addRequirementsModal">
                                    Add Requirement
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mx-auto">
                    @foreach ($criteria as $criterion)
                        <div class="col-md-3 mb-3">
                            <label for="{{ $criterion->criteria_name }}" class="form-label">
                                {{ ucwords(str_replace('_', ' ', $criterion->criteria_name)) }}
                            </label>
                            <input type="text" class="form-control border-success"
                                id="{{ $criterion->criteria_name }}"
                                value="{{ $criterion->criteria_value ?? 'Not Set' }}" readonly>
                        </div>
                    @endforeach
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
                                                data-bs-toggle="modal" data-bs-target="#editAreaModal"
                                                data-id="{{ $area->id }}" data-name="{{ $area->areaname }}"
                                                data-code="{{ $area->areacode }}">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-danger"
                                                data-bs-toggle="modal" data-bs-target="#deleteAreaModal"
                                                data-id="{{ $area->id }}" data-name="{{ $area->areaname }}">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="text-center align-middle" colspan="4">No Records Found.</td>
                                    </tr>
                                @endforelse

                                <!-- Edit Area Modal Template -->
                                <div class="modal fade" id="editAreaModal" tabindex="-1"
                                    aria-labelledby="editAreaModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header bg-warning fw-bold">
                                                <h5 class="modal-title" id="editAreaModalLabel">Edit Area</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <form method="POST" id="editAreaForm">
                                                @csrf
                                                <div class="modal-body">
                                                    <div class="row mb-3 align-items-center justify-content-center">
                                                        <div class="col-md-4">
                                                            <label for="editAreaName" class="form-label fw-bold">Area
                                                                Name</label>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <input type="text" class="form-control border-success"
                                                                id="editAreaName" name="newareaname" required>
                                                        </div>
                                                    </div>
                                                    <div class="row mb-3 align-items-center justify-content-center">
                                                        <div class="col-md-4">
                                                            <label for="editAreaCode" class="form-label fw-bold">Area
                                                                Code</label>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <input type="text" class="form-control border-success"
                                                                id="editAreaCode" name="newareacode" required>
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

                                <!-- Delete Area Modal Template -->
                                <div class="modal fade" id="deleteAreaModal" tabindex="-1"
                                    aria-labelledby="deleteAreaModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header bg-danger text-white fw-bold">
                                                <h5 class="modal-title" id="deleteAreaModalLabel">Confirm Delete</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body" id="deleteAreaBody">
                                                <!-- Content dynamically inserted -->
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-bs-dismiss="modal">Cancel</button>
                                                <form method="POST" id="deleteAreaForm" style="display:inline;">
                                                    @csrf
                                                    <button type="submit" class="btn btn-danger">Delete</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <script>
                                    document.addEventListener('DOMContentLoaded', () => {
                                        // Edit Area Modal
                                        const editAreaModal = document.getElementById('editAreaModal');
                                        editAreaModal.addEventListener('show.bs.modal', (event) => {
                                            const button = event.relatedTarget; // Button that triggered the modal
                                            const id = button.getAttribute('data-id');
                                            const name = button.getAttribute('data-name');
                                            const code = button.getAttribute('data-code');

                                            const editForm = document.getElementById('editAreaForm');
                                            editForm.action =
                                                `{{ route('updateArea', ['id' => '_FORMID_']) }}`
                                                .replace('_FORMID_', id);
                                            document.getElementById('editAreaName').value = name;
                                            document.getElementById('editAreaCode').value = code;
                                        });

                                        // Delete Area Modal
                                        const deleteAreaModal = document.getElementById('deleteAreaModal');
                                        deleteAreaModal.addEventListener('show.bs.modal', (event) => {
                                            const button = event.relatedTarget; // Button that triggered the modal
                                            const id = button.getAttribute('data-id');
                                            const name = button.getAttribute('data-name');

                                            const deleteForm = document.getElementById('deleteAreaForm');
                                            deleteForm.action =
                                                `{{ route('deleteArea', ['id' => '_FORMID_']) }}`
                                                .replace('_FORMID_', id);
                                            document.getElementById('deleteAreaBody').textContent =
                                                `Are you sure you want to delete this area: ${name}?`;
                                        });
                                    });
                                </script>
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
                                @forelse ($institutions as $institution)
                                    <tr>
                                        <td class="text-center align-middle">{{ $institution->schoolname }}</td>
                                        <td class="text-center align-middle">{{ $institution->schoollevel }}</td>
                                        <td class="text-center align-middle">{{ $institution->academiccycle }}</td>
                                        <td class="text-center align-middle">{{ $institution->highestgwa }}</td>
                                        <td class="text-center align-middle">
                                            <button class="btn btn-sm btn-warning" data-bs-toggle="modal"
                                                data-bs-target="#editInstitutionModal"
                                                data-id="{{ $institution->inid }}"
                                                data-name="{{ $institution->schoolname }}"
                                                data-level="{{ $institution->schoollevel }}"
                                                data-cycle="{{ $institution->academiccycle }}"
                                                data-gwa="{{ $institution->highestgwa }}">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-sm btn-danger" data-bs-toggle="modal"
                                                data-bs-target="#deleteInstitutionModal"
                                                data-id="{{ $institution->inid }}"
                                                data-name="{{ $institution->schoolname }}">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="text-center align-middle" colspan="5">No Records Found.</td>
                                    </tr>
                                @endforelse
                            </tbody>

                            <!-- Edit Institution Modal Template -->
                            <div class="modal fade" id="editInstitutionModal" tabindex="-1"
                                aria-labelledby="editInstitutionModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header bg-warning fw-bold">
                                            <h5 class="modal-title" id="editInstitutionModalLabel">Edit Institution
                                            </h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <form method="POST" id="editInstitutionForm">
                                            @csrf
                                            <div class="modal-body row">
                                                <div class="col-md-12 mb-3">
                                                    <label for="editSchoolName" class="form-label fw-bold">Institution
                                                        Name</label>
                                                    <input class="form-control border-success" id="editSchoolName"
                                                        name="newschoolname" maxlength="255" required>
                                                </div>
                                                <div class="col-md-4 mb-3">
                                                    <label for="editSchoolLevel" class="form-label fw-bold">School
                                                        Level</label>
                                                    <select class="form-select border-success" id="editSchoolLevel"
                                                        name="newschoollevel" required>
                                                        <option value="College">College</option>
                                                        <option value="Senior High">Senior High</option>
                                                        <option value="Junior High">Junior High</option>
                                                        <option value="Elementary">Elementary</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-4 mb-3">
                                                    <label for="editAcademicCycle" class="form-label fw-bold">Academic
                                                        Cycle</label>
                                                    <select class="form-select border-success" id="editAcademicCycle"
                                                        name="newacademiccycle" required>
                                                        <option value="Semester">Semester</option>
                                                        <option value="Trimester">Trimester</option>
                                                        <option value="Quarter">Quarter</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-4 mb-3">
                                                    <label for="editGWA" class="form-label fw-bold">Highest
                                                        GWA</label>
                                                    <input type="number" class="form-control border-success"
                                                        id="editGWA" name="newgwa" required min="1"
                                                        max="100" step="0.01">
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-bs-dismiss="modal">Cancel</button>
                                                <button type="submit" class="btn btn-warning">Save Changes</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <!-- Delete Institution Modal Template -->
                            <div class="modal fade" id="deleteInstitutionModal" tabindex="-1"
                                aria-labelledby="deleteInstitutionModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header bg-danger text-white fw-bold">
                                            <h5 class="modal-title" id="deleteInstitutionModalLabel">Confirm Delete
                                            </h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body" id="deleteInstitutionBody">
                                            <!-- Content dynamically inserted -->
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-bs-dismiss="modal">Cancel</button>
                                            <form method="POST" id="deleteInstitutionForm" style="display:inline;">
                                                @csrf
                                                <button type="submit" class="btn btn-danger">Delete</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <script>
                                document.addEventListener('DOMContentLoaded', () => {
                                    // Edit Institution Modal
                                    const editInstitutionModal = document.getElementById('editInstitutionModal');
                                    editInstitutionModal.addEventListener('show.bs.modal', (event) => {
                                        const button = event.relatedTarget; // Button that triggered the modal
                                        const id = button.getAttribute('data-id');
                                        const name = button.getAttribute('data-name');
                                        const level = button.getAttribute('data-level');
                                        const cycle = button.getAttribute('data-cycle');
                                        const gwa = button.getAttribute('data-gwa');

                                        const editForm = document.getElementById('editInstitutionForm');
                                        editForm.action = `{{ route('updateinstitution', ['inid' => '_FORMID_']) }}`.replace(
                                            '_FORMID_', id);

                                        document.getElementById('editSchoolName').value = name;
                                        document.getElementById('editSchoolLevel').value = level;
                                        document.getElementById('editAcademicCycle').value = cycle;
                                        document.getElementById('editGWA').value = gwa;
                                    });

                                    // Delete Institution Modal
                                    const deleteInstitutionModal = document.getElementById('deleteInstitutionModal');
                                    deleteInstitutionModal.addEventListener('show.bs.modal', (event) => {
                                        const button = event.relatedTarget; // Button that triggered the modal
                                        const id = button.getAttribute('data-id');
                                        const name = button.getAttribute('data-name');

                                        const deleteForm = document.getElementById('deleteInstitutionForm');
                                        deleteForm.action = `{{ route('deleteinstitution', ['inid' => '_FORMID_']) }}`.replace(
                                            '_FORMID_', id);

                                        document.getElementById('deleteInstitutionBody').textContent =
                                            `Are you sure you want to delete this institution: ${name}?`;
                                    });
                                });
                            </script>

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
                                @forelse ($courses as $course)
                                    <tr>
                                        <td class="text-center align-middle">{{ $course->coursename }}</td>
                                        <td class="text-center align-middle">
                                            <button class="btn btn-sm btn-warning" data-bs-toggle="modal"
                                                data-bs-target="#editCourseModal" data-id="{{ $course->coid }}"
                                                data-name="{{ $course->coursename }}">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-sm btn-danger" data-bs-toggle="modal"
                                                data-bs-target="#deleteCourseModal" data-id="{{ $course->coid }}"
                                                data-name="{{ $course->coursename }}">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="text-center align-middle" colspan="2">No Records Found.</td>
                                    </tr>
                                @endforelse
                            </tbody>

                            <!-- Edit Course Modal Template -->
                            <div class="modal fade" id="editCourseModal" tabindex="-1"
                                aria-labelledby="editCourseModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header bg-warning fw-bold">
                                            <h5 class="modal-title" id="editCourseModalLabel">Edit Course</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <form method="POST" id="editCourseForm">
                                            @csrf
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label for="editCourseName" class="form-label fw-bold">Course
                                                        Name</label>
                                                    <input class="form-control border-success" id="editCourseName"
                                                        name="newcoursename" required>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-bs-dismiss="modal">Cancel</button>
                                                <button type="submit" class="btn btn-warning">Save Changes</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <!-- Delete Course Modal Template -->
                            <div class="modal fade" id="deleteCourseModal" tabindex="-1"
                                aria-labelledby="deleteCourseModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header bg-danger text-white fw-bold">
                                            <h5 class="modal-title" id="deleteCourseModalLabel">Confirm Delete</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body" id="deleteCourseBody">
                                            <!-- Content dynamically inserted -->
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-bs-dismiss="modal">Cancel</button>
                                            <form method="POST" id="deleteCourseForm" style="display:inline;">
                                                @csrf
                                                <button type="submit" class="btn btn-danger">Delete</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <script>
                                document.addEventListener('DOMContentLoaded', () => {
                                    // Edit Course Modal
                                    const editCourseModal = document.getElementById('editCourseModal');
                                    editCourseModal.addEventListener('show.bs.modal', (event) => {
                                        const button = event.relatedTarget; // Button that triggered the modal
                                        const id = button.getAttribute('data-id');
                                        const name = button.getAttribute('data-name');

                                        const editForm = document.getElementById('editCourseForm');
                                        editForm.action = `{{ route('updatecourse', ['coid' => '_FORMID_']) }}`.replace('_FORMID_',
                                            id);
                                        document.getElementById('editCourseName').value = name;
                                    });

                                    // Delete Course Modal
                                    const deleteCourseModal = document.getElementById('deleteCourseModal');
                                    deleteCourseModal.addEventListener('show.bs.modal', (event) => {
                                        const button = event.relatedTarget; // Button that triggered the modal
                                        const id = button.getAttribute('data-id');
                                        const name = button.getAttribute('data-name');

                                        const deleteForm = document.getElementById('deleteCourseForm');
                                        deleteForm.action = `{{ route('deletecourse', ['coid' => '_FORMID_']) }}`.replace(
                                            '_FORMID_', id);
                                        document.getElementById('deleteCourseBody').textContent =
                                            `Are you sure you want to delete this course: ${name}?`;
                                    });
                                });
                            </script>
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
                                                data-bs-target="#editStrandModal" data-id="{{ $strand->coid }}"
                                                data-name="{{ $strand->coursename }}">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-sm btn-danger" data-bs-toggle="modal"
                                                data-bs-target="#deleteStrandModal" data-id="{{ $strand->coid }}"
                                                data-name="{{ $strand->coursename }}">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="text-center align-middle" colspan="2">No Records Found.</td>
                                    </tr>
                                @endforelse

                                <!-- Edit Strand Modal Template -->
                                <div class="modal fade" id="editStrandModal" tabindex="-1"
                                    aria-labelledby="editStrandModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header bg-warning fw-bold">
                                                <h5 class="modal-title" id="editStrandModalLabel">Edit Strand</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <form method="POST" id="editStrandForm">
                                                @csrf
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label for="editStrandName" class="form-label fw-bold">Strand
                                                            Name</label>
                                                        <input class="form-control border-success" id="editStrandName"
                                                            name="newcoursename" required>
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

                                <!-- Delete Strand Modal Template -->
                                <div class="modal fade" id="deleteStrandModal" tabindex="-1"
                                    aria-labelledby="deleteStrandModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header bg-danger text-white fw-bold">
                                                <h5 class="modal-title" id="deleteStrandModalLabel">Confirm Delete
                                                </h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body" id="deleteStrandBody">
                                                <!-- Content dynamically inserted -->
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-bs-dismiss="modal">Cancel</button>
                                                <form method="POST" id="deleteStrandForm" style="display:inline;">
                                                    @csrf
                                                    <button type="submit" class="btn btn-danger">Delete</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <script>
                                    document.addEventListener('DOMContentLoaded', () => {
                                        // Edit Strand Modal
                                        const editStrandModal = document.getElementById('editStrandModal');
                                        editStrandModal.addEventListener('show.bs.modal', (event) => {
                                            const button = event.relatedTarget; // Button that triggered the modal
                                            const id = button.getAttribute('data-id');
                                            const name = button.getAttribute('data-name');

                                            const editForm = document.getElementById('editStrandForm');
                                            editForm.action = `{{ route('updatecourse', ['coid' => '_FORMID_']) }}`.replace(
                                                '_FORMID_',
                                                id);
                                            document.getElementById('editStrandName').value = name;
                                        });

                                        // Delete Strand Modal
                                        const deleteStrandModal = document.getElementById('deleteStrandModal');
                                        deleteStrandModal.addEventListener('show.bs.modal', (event) => {
                                            const button = event.relatedTarget; // Button that triggered the modal
                                            const id = button.getAttribute('data-id');
                                            const name = button.getAttribute('data-name');

                                            const deleteForm = document.getElementById('deleteStrandForm');
                                            deleteForm.action = `{{ route('deletecourse', ['coid' => '_FORMID_']) }}`.replace(
                                                '_FORMID_', id);
                                            document.getElementById('deleteStrandBody').textContent =
                                                `Are you sure you want to delete this strand: ${name}?`;
                                        });
                                    });
                                </script>
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
                    <div class="modal-body">
                        <div class="row">
                            @if ($criteria->isNotEmpty())
                                @foreach ($criteria as $criterion)
                                    <div class="row align-items-center mb-3">
                                        <!-- Criteria Name -->
                                        <div class="col-md-5">
                                            <label for="criteria_name_{{ $criterion->crid }}" class="form-label">
                                                Criteria Name
                                            </label>
                                            <input type="text" class="form-control border-primary"
                                                id="criteria_name_{{ $criterion->crid }}"
                                                name="criteria[{{ $criterion->crid }}][name]"
                                                value="{{ $criterion->criteria_name ?? '' }}" required>
                                        </div>

                                        <!-- Criteria Value -->
                                        <div class="col-md-5">
                                            <label for="criteria_value_{{ $criterion->crid }}" class="form-label">
                                                Criteria Value
                                            </label>
                                            <input type="number" class="form-control border-success"
                                                id="criteria_value_{{ $criterion->crid }}"
                                                name="criteria[{{ $criterion->crid }}][value]"
                                                value="{{ $criterion->criteria_value ?? '' }}" step="0.01"
                                                required>
                                        </div>

                                        <!-- Delete Button -->
                                        <div class="col-md-2 text-end">
                                            <form method="POST"
                                                action="{{ route('deletecriteria', $criterion->crid) }}"
                                                onsubmit="return confirm('Are you sure you want to delete this criterion?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger mt-4">
                                                    Delete
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <p>No criteria available to update.</p>
                            @endif
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal for add Requirements -->
    <div class="modal fade" id="addRequirementsModal" tabindex="-1" aria-labelledby="addRequirementsModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title" id="addRequirementsModalLabel">Add Scholarship Requirements</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action="{{ route('addcriteria') }}">
                    @csrf
                    <div class="modal-body row justify-content-evenly">
                        <div class="row align-items-center">
                            <!-- Input for Criteria Name -->
                            <div class="col-md-6 mb-3">
                                <label for="criteriaName" class="form-label">Criteria Name</label>
                                <input type="text" id="criteriaName" name="criteriaName" class="form-control"
                                    placeholder="e.g., English Grade" required>
                            </div>

                            <!-- Input for Initial Value -->
                            <div class="col-md-6 mb-3">
                                <label for="criteriaValue" class="form-label">Initial Value</label>
                                <input type="number" id="criteriaValue" name="criteriaValue" class="form-control"
                                    placeholder="e.g., 85" required step="0.01" min="0">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-warning">Save Criteria</button>
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
