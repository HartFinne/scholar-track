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
            {{-- APPLICATION FORMS --}}
            <fieldset class="row mb-3 p-3 rounded border border-success">
                <legend class="fw-bold text-success h4">Manage Application Forms</legend>
                <div class="ctntable table-responsive">
                    <table class="table table-bordered" id="tblapplicationforms">
                        <thead>
                            <tr>
                                <th class="text-center align-middle">Form</th>
                                <th class="text-center align-middle">Deadline for Submission of Documents</th>
                                <th class="text-center align-middle">End Date of Application</th>
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
                                    <td class="text-center align-middle">
                                        {{ $form->deadline ? \Carbon\Carbon::parse($form->enddate)->format('F j, Y') : '--' }}
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
                                            <form method="post"
                                                action="{{ route('updateappformstatus', ['formname' => $form->formname, 'status' => 'Closed']) }}">
                                                @csrf
                                                <button type="submit" class="btn btn-danger">Close</button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </fieldset>

            {{-- EMAIL IMPORT --}}
            <fieldset class="row mb-3 p-3 rounded border border-success">
                <legend class="fw-bold text-success h4">Import Scholar Emails</legend>
                <span class="col-12 mb-2">
                    Please upload the Excel file containing scholar emails for registration verification.
                    Ensure the file contains only email addresses, and they must be placed in the first column of the
                    sheet.
                </span>
                <form action="{{ route('importemails') }}" method="POST" enctype="multipart/form-data" class="col-12"
                    id="importForm">
                    @csrf
                    <div class="input-group">
                        <input type="file" class="form-control" id="file" name="file" required
                            accept=".xlsx,.xls" aria-describedby="fileHelp">
                        <button class="btn btn-success" type="submit" style="z-index: 1">Upload</button>
                    </div>
                    <small id="fileHelp" class="form-text text-muted">
                        File must be in .xlsx or .xls format.
                    </small>
                </form>
            </fieldset>

            {{-- SCHOLARSHIP REQUIREMENTS --}}
            <fieldset class="row mb-3 p-3 rounded border border-success">
                <form method="POST" action="{{ route('updatecriteria') }}">
                    @csrf
                    <div class="row">
                        <div class="col-md-11">
                            <legend class="fw-bold text-success h4">Scholarship Requirements</legend>
                        </div>
                        <div class="col-md-1">
                            <button type="submit" class="btn btn-success" style="width: 100%">Save</button>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="cshours" class="form-label">Required CS Hours</label>
                                <input type="number" min="1" class="form-control" id="cshours" name="cshours"
                                    value="{{ $criteria->cshours ?? '' }}" placeholder="Not Set" required>
                            </div>
                            <div class="mb-3">
                                <label for="cgwa" min="1" max="5" class="form-label">College
                                    GWA</label>
                                <input type="number" class="form-control" id="cgwa" name="cgwa"
                                    value="{{ $criteria->cgwa ?? '' }}" placeholder="Not Set" required>
                            </div>
                            <div class="mb-3">
                                <label for="shsgwa" min="1" max="100" class="form-label">Senior
                                    High
                                    GWA</label>
                                <input type="number" class="form-control" id="shsgwa" name="shsgwa"
                                    value="{{ $criteria->shsgwa ?? '' }}" placeholder="Not Set" required>
                            </div>
                            <div class="mb-3">
                                <label for="jhsgwa" min="1" max="100" class="form-label">Junior
                                    High
                                    GWA</label>
                                <input type="number" class="form-control" id="jhsgwa" name="jhsgwa"
                                    value="{{ $criteria->jhsgwa ?? '' }}" placeholder="Not Set" required>
                            </div>
                            <div class="mb-3">
                                <label for="elemgwa" min="1" max="100" class="form-label">Elementary
                                    GWA</label>
                                <input type="number" class="form-control" id="elemgwa" name="elemgwa"
                                    value="{{ $criteria->elemgwa ?? '' }}" placeholder="Not Set" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="fincome" min="1" class="form-label">Father's Income</label>
                                <input type="number" class="form-control" id="fincome" name="fincome"
                                    value="{{ $criteria->fincome ?? '' }}" placeholder="Not Set" required>
                            </div>
                            <div class="mb-3">
                                <label for="mincome" min="1" class="form-label">Mother's Income</label>
                                <input type="number" class="form-control" id="mincome" name="mincome"
                                    value="{{ $criteria->mincome ?? '' }}" placeholder="Not Set" required>
                            </div>
                            <div class="mb-3">
                                <label for="sincome" min="1" class="form-label">Siblings' Income</label>
                                <input type="number" class="form-control" id="sincome" name="sincome"
                                    value="{{ $criteria->sincome ?? '' }}" placeholder="Not Set" required>
                            </div>
                            <div class="mb-3">
                                <label for="aincome" min="1" class="form-label">Applicant's
                                    Income</label>
                                <input type="number" class="form-control" id="aincome" name="aincome"
                                    value="{{ $criteria->aincome ?? '' }}" placeholder="Not Set" required>
                            </div>
                        </div>
                    </div>
                </form>
            </fieldset>

            {{-- APPLICATION INSTRUCTIONS --}}
            <fieldset class="row mb-3 p-3 rounded border border-success">
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
                            <tr>
                                <td class="text-center align-middle">College</td>
                                <td class="text-center align-middle">
                                    <button class="btn btn-success open-modal-btn" data-bs-toggle="modal"
                                        data-bs-target="#collegeInstructionModal">Edit</button>
                                </td>
                            </tr>
                            <tr>
                                <td class="text-center align-middle">Senior High</td>
                                <td class="text-center align-middle">
                                    <button class="btn btn-success open-modal-btn" data-bs-toggle="modal"
                                        data-bs-target="#shsInstructionModal">Edit</button>
                                </td>
                            </tr>
                            <tr>
                                <td class="text-center align-middle">Junior High</td>
                                <td class="text-center align-middle">
                                    <button class="btn btn-success open-modal-btn" data-bs-toggle="modal"
                                        data-bs-target="#jhsInstructionModal">Edit</button>
                                </td>
                            </tr>
                            <tr>
                                <td class="text-center align-middle">Elementary</td>
                                <td class="text-center align-middle">
                                    <button class="btn btn-success open-modal-btn" data-bs-toggle="modal"
                                        data-bs-target="#elemInstructionModal">Edit</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </fieldset>

            {{-- SCHOOLS --}}
            <fieldset class="row mb-3 p-3 rounded border border-success">
                <legend class="fw-bold text-success h4">Institutions</legend>
                <form class="mb-3" method="POST" action="{{ route('addinstitution') }}">
                    @csrf
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" placeholder="Institution Name" name="institute"
                            required>
                        <select type="text" class="form-control" style="cursor: pointer" name="schoollevel"
                            required>
                            <option value="" selected hidden>Select Level</option>
                            <option value="College">College</option>
                            <option value="Senior High">Senior High</option>
                            <option value="Junior High">Junior High</option>
                            <option value="Elementary">Elementary</option>
                        </select>
                        <select type="text" class="form-control" style="cursor: pointer" name="academiccycle"
                            required>
                            <option value="" selected hidden>Select Cycle</option>
                            <option value="Semester">Semester</option>
                            <option value="Trimester">Trimester</option>
                            <option value="Quarter">Quarter</option>
                        </select>
                        <input type="number" class="form-control" placeholder="Highest GWA" name="highestgwa"
                            required step="0.01" min="1" max="100">
                        {{-- <input type="file" class="form-control" name="logo" required
                            accept="image/jpg, image/png"> --}}
                        <button class="btn btn-success" type="submit" id="btnaddinsti"
                            style="z-index: 1">Add</button>
                    </div>
                </form>
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
                                    <form method="POST"
                                        action="{{ route('updateinstitution', $institution->inid) }}">
                                        @csrf
                                        <td>
                                            <textarea class="tblinput form-control text-center align-middle" name="newschoolname" required>{{ $institution->schoolname }}</textarea>
                                        </td>
                                        <td>
                                            <select type="text"
                                                class="tblinput form-control text-center align-middle"
                                                style="cursor: pointer" name="newschoollevel" required>
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
                                        </td>
                                        <td>
                                            <select type="text"
                                                class="tblinput form-control text-center align-middle"
                                                style="cursor: pointer" name="newacademiccycle" required>
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
                                        </td>
                                        <td>
                                            <input class="tblinput form-control text-center align-middle"
                                                name="newgwa" required type="number" min='1' max='100'
                                                step="0.01" value="{{ $institution->highestgwa }}">
                                        </td>
                                        <td class="col-2 text-center align-middle">
                                            <div class="d-flex justify-content-around">
                                                <button type="submit" class="btn btn-success">
                                                    <i class="fa-solid fa-floppy-disk"></i>
                                                </button>
                                    </form>
                                    <form method="POST"
                                        action="{{ route('deleteinstitution', $institution->inid) }}">
                                        @csrf
                                        <button type="submit" class="btn btn-danger"><i
                                                class="fa-solid fa-trash"></i></button>
                                    </form>
                </div>
                </td>
                </tr>
                @endforeach
                </tbody>
                </table>

            </fieldset>

            {{-- COURSES --}}
            <fieldset class="row mb-3 p-3 rounded border border-success">
                <legend class="fw-bold text-success h4">Courses</legend>
                <form class="mb-3" method="POST" action="{{ route('addcourse', 'College') }}">
                    @csrf
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" placeholder="Enter Course" name="course"
                            required>
                        <button class="btn btn-success" type="submit" id="btnaddcourse"
                            style="z-index: 1">Add</button>
                    </div>
                </form>
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
                                    <form method="POST" action="{{ route('updatecourse', $course->coid) }}">
                                        @csrf
                                        <td>
                                            <textarea class="tblinput form-control text-center align-middle" name="newcoursename" required>{{ $course->coursename }}</textarea>
                                        </td>
                                        <td class="col-2 text-center align-middle">
                                            <div class="d-flex justify-content-around">
                                                <button type="submit" class="btn btn-success">
                                                    <i class="fa-solid fa-floppy-disk"></i>
                                                </button>
                                    </form>
                                    <form method="POST" action="{{ route('deletecourse', $course->coid) }}">
                                        @csrf
                                        <button type="submit" class="btn btn-danger"><i
                                                class="fa-solid fa-trash"></i></button>
                                    </form>
                </div>
                </td>
                </tr>
                @endforeach
                </tbody>
                </table>

            </fieldset>

            {{-- STRANDS --}}
            <fieldset class="row mb-3 p-3 rounded border border-success">
                <legend class="fw-bold text-success h4">Strand</legend>
                <form class="mb-3" method="POST" action="{{ route('addcourse', 'Senior High') }}">
                    @csrf
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" placeholder="Enter Strand" name="strand"
                            required>
                        <button class="btn btn-success" type="submit" id="btnaddcourse"
                            style="z-index: 1">Add</button>
                    </div>
                </form>
                <div class="ctntable table-responsive">
                    <table class="table table-bordered" id="tblcourses">
                        <thead>
                            <tr>
                                <th class="text-center" style="width: 75%;">Strand</th>
                                <th class="text-center" style="width: 25%;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($strands as $strand)
                                <tr>
                                    <form method="POST" action="{{ route('updatecourse', $strand->coid) }}">
                                        @csrf
                                        <td>
                                            <textarea class="tblinput form-control text-center align-middle" name="newcoursename" required>{{ $strand->coursename }}</textarea>
                                        </td>
                                        <td class="col-2 text-center align-middle">
                                            <div class="d-flex justify-content-around">
                                                <button type="submit" class="btn btn-success">
                                                    <i class="fa-solid fa-floppy-disk"></i>
                                                </button>
                                    </form>
                                    <form method="POST" action="{{ route('deletecourse', $strand->coid) }}">
                                        @csrf
                                        <button type="submit" class="btn btn-danger"><i
                                                class="fa-solid fa-trash"></i></button>
                                    </form>
                </div>
                </td>
                </tr>
                @endforeach
                </tbody>
                </table>
            </fieldset>
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
                <div class="modal-header">
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
                                <input required type="date" id="deadline" name="deadline" class="form-control">
                            </div>
                            <div class="row mb-1">
                                Set end date of application:
                            </div>
                            <div class="row mb-3">
                                <input required type="date" id="enddate" name="enddate" class="form-control">
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

    <!-- College Instruction Modal -->
    <div class="modal fade" id="collegeInstructionModal" tabindex="-1"
        aria-labelledby="collegeInstructionModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title" id="collegeInstructionModalLabel">College Application Instructions</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('updateapplicationinstructions', ['level' => 'College']) }}" method="post">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="collegeApplicants" class="form-label">Who can apply?</label>
                            <textarea class="form-control" name="applicants" id="collegeApplicants" rows="4">{{ old('applicants') ?? $instruction['College']->applicants }}</textarea>
                        </div>
                        <div class="mb-3">
                            <label for="collegeQualifications" class="form-label">What are the qualifications?</label>
                            <textarea class="form-control" name="qualifications" id="collegeQualifications" rows="4">{{ old('qualifications') ?? $instruction['College']->qualifications }}</textarea>
                        </div>
                        <div class="mb-3">
                            <label for="collegeDocuments" class="form-label">Documents to prepare:</label>
                            <textarea class="form-control" name="documents" id="collegeDocuments" rows="4">{{ old('documents') ?? $instruction['College']->requireddocuments }}</textarea>
                        </div>
                        <div class="mb-3">
                            <label for="collegeProcess" class="form-label">Application Process:</label>
                            <textarea class="form-control" name="process" id="collegeProcess" rows="4">{{ old('process') ?? $instruction['College']->applicationprocess }}</textarea>
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

    <!-- Senior High Instruction Modal -->
    <!-- Senior High School (SHS) Application Instructions Modal -->
    <div class="modal fade" id="shsInstructionModal" tabindex="-1" aria-labelledby="shsInstructionModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title" id="shsInstructionModalLabel">Senior High Application Instructions</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('updateapplicationinstructions', ['level' => 'Senior High']) }}"
                    method="post">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="shsApplicants" class="form-label">Who can apply?</label>
                            <textarea class="form-control" name="applicants" id="shsApplicants" rows="4">{{ old('applicants') ?? $instruction['Senior High']->applicants }}</textarea>
                        </div>
                        <div class="mb-3">
                            <label for="shsQualifications" class="form-label">What are the qualifications?</label>
                            <textarea class="form-control" name="qualifications" id="shsQualifications" rows="4">{{ old('qualifications') ?? $instruction['Senior High']->qualifications }}</textarea>
                        </div>
                        <div class="mb-3">
                            <label for="shsDocuments" class="form-label">Documents to prepare:</label>
                            <textarea class="form-control" name="documents" id="shsDocuments" rows="4">{{ old('documents') ?? $instruction['Senior High']->requireddocuments }}</textarea>
                        </div>
                        <div class="mb-3">
                            <label for="shsProcess" class="form-label">Application Process:</label>
                            <textarea class="form-control" name="process" id="shsProcess" rows="4">{{ old('process') ?? $instruction['Senior High']->applicationprocess }}</textarea>
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

    <!-- Junior High School (JHS) Application Instructions Modal -->
    <div class="modal fade" id="jhsInstructionModal" tabindex="-1" aria-labelledby="jhsInstructionModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="jhsInstructionModalLabel">Junior High Application Instructions</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('updateapplicationinstructions', ['level' => 'Junior High']) }}"
                    method="post">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="jhsApplicants" class="form-label">Who can apply?</label>
                            <textarea class="form-control" name="applicants" id="jhsApplicants" rows="4">{{ old('applicants') ?? $instruction['Junior High']->applicants }}</textarea>
                        </div>
                        <div class="mb-3">
                            <label for="jhsQualifications" class="form-label">What are the qualifications?</label>
                            <textarea class="form-control" name="qualifications" id="jhsQualifications" rows="4">{{ old('qualifications') ?? $instruction['Junior High']->qualifications }}</textarea>
                        </div>
                        <div class="mb-3">
                            <label for="jhsDocuments" class="form-label">Documents to prepare:</label>
                            <textarea class="form-control" name="documents" id="jhsDocuments" rows="4">{{ old('documents') ?? $instruction['Junior High']->requireddocuments }}</textarea>
                        </div>
                        <div class="mb-3">
                            <label for="jhsProcess" class="form-label">Application Process:</label>
                            <textarea class="form-control" name="process" id="jhsProcess" rows="4">{{ old('process') ?? $instruction['Junior High']->applicationprocess }}</textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <!-- Elementary Instruction Modal -->
    <div class="modal fade" id="elemInstructionModal" tabindex="-1" aria-labelledby="elemInstructionModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title" id="elemInstructionModalLabel">Elementary Application Instructions</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('updateapplicationinstructions', ['level' => 'Elementary']) }}"
                    method="post">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="elemApplicants" class="form-label">Who can apply?</label>
                            <textarea class="form-control" name="applicants" id="elemApplicants" rows="4">{{ old('applicants') ?? $instruction['Elementary']->applicants }}</textarea>
                        </div>
                        <div class="mb-3">
                            <label for="elemQualifications" class="form-label">What are the qualifications?</label>
                            <textarea class="form-control" name="qualifications" id="elemQualifications" rows="4">{{ old('qualifications') ?? $instruction['Elementary']->qualifications }}</textarea>
                        </div>
                        <div class="mb-3">
                            <label for="elemDocuments" class="form-label">Documents to prepare:</label>
                            <textarea class="form-control" name="documents" id="elemDocuments" rows="4">{{ old('documents') ?? $instruction['Elementary']->requireddocuments }}</textarea>
                        </div>
                        <div class="mb-3">
                            <label for="elemProcess" class="form-label">Application Process:</label>
                            <textarea class="form-control" name="process" id="elemProcess" rows="4">{{ old('process') ?? $instruction['Elementary']->applicationprocess }}</textarea>
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

    <script>
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
            // Initialize CKEditor for College level fields
            initializeEditor('#collegeApplicants');
            initializeEditor('#collegeQualifications');
            initializeEditor('#collegeDocuments');
            initializeEditor('#collegeProcess');

            // Initialize CKEditor for Senior High School fields
            initializeEditor('#shsApplicants');
            initializeEditor('#shsQualifications');
            initializeEditor('#shsDocuments');
            initializeEditor('#shsProcess');

            // Initialize CKEditor for Junior High School fields
            initializeEditor('#jhsApplicants');
            initializeEditor('#jhsQualifications');
            initializeEditor('#jhsDocuments');
            initializeEditor('#jhsProcess');

            // Initialize CKEditor for Elementary fields
            initializeEditor('#elemApplicants');
            initializeEditor('#elemQualifications');
            initializeEditor('#elemDocuments');
            initializeEditor('#elemProcess');
        });

        function initializeEditor(selector) {
            ClassicEditor
                .create(document.querySelector(selector), {
                    plugins: [Essentials, Bold, Italic, Font, Paragraph, List, Link],
                    toolbar: [
                        'undo', 'redo', '|', 'bold', 'italic', '|',
                        'fontSize', 'fontFamily', 'fontColor', 'fontBackgroundColor', '|',
                        'bulletedList', 'numberedList', '|',
                        'link' // Make sure 'link' is in the toolbar
                    ],
                    link: {
                        addTargetToExternalLinks: true // Optionally add target=_blank for external links
                    }
                })
                .then(editor => {
                    document.querySelector('form').onsubmit = function() {
                        document.querySelector(selector).value = editor.getData();
                    };
                })
                .catch(error => {
                    console.error(error);
                });
        }
    </script>
    <script>
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
    </script>
</body>

</html>
