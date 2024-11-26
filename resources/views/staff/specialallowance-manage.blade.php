<!DOCTYPE html>

<html lang="en">

<head>
    <title>Allowance Requests | Special</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/table.css') }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.tiny.cloud/1/u8od7gmihvz9en9414xjqlrif77eel97bab2vypiq386dycy/tinymce/7/tinymce.min.js"
        referrerpolicy="origin"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body>
    <!-- PAGE HEADER -->
    @include('partials._pageheader')
    <x-alert />

    <div class="ctnmain">
        {{-- MANAGE SPECIAL ALLOWANCE FORMS --}}
        <div class="row">
            <div class="d-flex justify-content-between align-items-center w-100">
                <span class="text-success fw-bold h2">Manage Special Allowance Forms</span>
                <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#createFormModal">
                    Create New Form
                </button>
            </div>
        </div>
        <div class="ctntable">
            <table class="table table-responsive">
                <thead>
                    <tr>
                        <th class="text-center align-middle">Form Name</th>
                        <th class="text-center align-middle">Requestor</th>
                        <th class="text-center align-middle">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($forms as $form)
                        <tr>
                            <td class="text-center align-middle">{{ $form->formname }}</td>
                            <td class="text-center align-middle">
                                @php
                                    $requestors = json_decode($form->requestor, true);
                                @endphp

                                @foreach ($requestors as $requestor)
                                    {{ $requestor }}<br>
                                @endforeach
                            </td>
                            <td class="text-center align-middle">
                                <button class="btn btn-sm btn-success" data-bs-toggle="modal"
                                    data-bs-target="#viewForm-{{ $form->csafid }}"><i class="fas fa-eye"></i></button>
                                <button class="btn btn-sm btn-warning" data-bs-toggle="modal"
                                    data-bs-target="#updateForm-{{ $form->csafid }}"><i
                                        class="fas fa-edit"></i></button>
                                <button class="btn btn-sm btn-danger" data-bs-toggle="modal"
                                    data-bs-target="#deleteForm-{{ $form->csafid }}"><i
                                        class="fas fa-trash"></i></button>
                            </td>
                        </tr>

                        <!-- View Modal -->
                        <div class="modal fade" id="viewForm-{{ $form->csafid }}" tabindex="-1"
                            aria-labelledby="viewFormLabel" aria-hidden="true">
                            <div class="modal-dialog modal-xl">
                                <div class="modal-content">
                                    <div class="modal-header bg-success text-white">
                                        <div class="row">
                                            <span class="modal-title h5 fw-bold" id="viewFormLabel">Preview</span>
                                        </div>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="container bg-light rounded px-4 py-3 mb-4">
                                            <div class="row text-center h4 fw-bold my-3">
                                                <span class="col-12 text-center h4 fw-bold">{{ $form->formname }}</span>
                                            </div>
                                            <div class="row h6 fw-bold">
                                                Please follow the given instructions carefully.
                                            </div>
                                            <div class="row">
                                                {!! $form->instruction !!}
                                            </div>
                                            <div class="row">
                                                <span class="col-12 text-center h6 fw-bold">Downloadable Files:</span>
                                                @php
                                                    $filesId = json_decode($form->downloadablefiles, true);

                                                    $downloadablefiles = DB::table('specialallowanceforms')
                                                        ->whereIn('id', $filesId)
                                                        ->get();
                                                @endphp

                                                @forelse ($downloadablefiles as $file)
                                                    <a href="{{ asset('storage/' . $file->pathname) }}"
                                                        class="col-12 text-center link link-success" download>
                                                        {{ $file->filename }}
                                                    </a>
                                                @empty
                                                    <span class="col-12 text-center h6 fw-bold">No Files Found</span>
                                                @endforelse
                                            </div>
                                        </div>
                                        <div class="container bg-light rounded px-4 py-3 mb-3">
                                            <div class="row text-center h4 fw-bold my-3">
                                                <span class="col-12 text-center h4 fw-bold">{{ $form->formname }}
                                                    Form</span>
                                            </div>
                                            <div class="row mb-3">
                                                Please complete the form below to submit your request. Ensure that all
                                                required files are prepared and ready for submission.
                                            </div>
                                            @php
                                                $fields = DB::table('special_allowance_forms_structure')
                                                    ->where('csafid', $form->csafid)
                                                    ->get();
                                            @endphp
                                            <div class="row justify-content-evenly">
                                                <div class="col-md-5 mb-3">
                                                    <div class="row fw-bold">First Name</div>
                                                    <div class="row">
                                                        <input type="text" readonly name="firstname"
                                                            class="form-control">
                                                    </div>
                                                </div>
                                                <div class="col-md-5 mb-3">
                                                    <div class="row fw-bold">Last Name</div>
                                                    <div class="row">
                                                        <input type="text" readonly name="lastname"
                                                            class="form-control">
                                                    </div>
                                                </div>
                                                <div class="col-md-5 mb-3">
                                                    <div class="row fw-bold">Area</div>
                                                    <div class="row">
                                                        <input type="text" readonly name="area"
                                                            class="form-control">
                                                    </div>
                                                </div>
                                                <div class="col-md-5 mb-3">
                                                    <div class="row fw-bold">Year Level</div>
                                                    <div class="row">
                                                        <input type="text" readonly name="yearlevel"
                                                            class="form-control">
                                                    </div>
                                                </div>
                                                <div class="col-md-5 mb-3">
                                                    <div class="row fw-bold">Course</div>
                                                    <div class="row">
                                                        <input type="text" readonly name="course"
                                                            class="form-control">
                                                    </div>
                                                </div>
                                                <div class="col-md-5 mb-3">
                                                    <div class="row fw-bold">School</div>
                                                    <div class="row">
                                                        <input type="text" readonly name="school"
                                                            class="form-control">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row justify-content-evenly">
                                                @forelse ($fields as $field)
                                                    <div class="col-md-5 mb-3">
                                                        <div class="row fw-bold">{{ $field->fieldname }}</div>
                                                        <div class="row">
                                                            <input type="{{ $field->fieldtype }}" readonly
                                                                name="{{ $field->fieldname }}" class="form-control">
                                                        </div>
                                                    </div>
                                                @empty
                                                    <div class="row">
                                                        <span class="col-12 text-center">No Fields Found.</span>
                                                    </div>
                                                @endforelse
                                            </div>
                                            <div class="row justify-content-center">
                                                <div class="col-auto">
                                                    <button type="submit" class="btn btn-success fw-bold">Submit
                                                        Request</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Update Modal -->
                        <div class="modal fade" id="updateForm-{{ $form->csafid }}" tabindex="-1"
                            aria-labelledby="updateFormModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-xl">
                                <div class="modal-content">
                                    <div class="modal-header bg-success text-white">
                                        <h5 class="modal-title fw-bold" id="updateFormModalLabel">Update Form</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body px-4 py-2">
                                        <form action="{{ route('updateSpecialAllowanceForm', $form->csafid) }}"
                                            method="post" id="updateForm">
                                            @csrf
                                            <div class="row mb-3">
                                                <div class="col-12">
                                                    <label for="newformname" class="fw-bold">Form Name</label>
                                                </div>
                                                <div class="col-12">
                                                    <input type="text" maxlength="200" class="form-control"
                                                        name="newformname" id="newformname"
                                                        value="{{ old('formname') ?? $form->formname }}" required>
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <div class="col-12">
                                                    <label for="newformcode" class="fw-bold">Form Code</label>
                                                </div>
                                                <div class="col-12">
                                                    <input type="text" maxlength="25" class="form-control"
                                                        name="newformcode" id="newformcode"
                                                        value="{{ old('formcode') ?? $form->formcode }}" required>
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <div class="col-12">
                                                    <label for="newrequestor" class="fw-bold">Requestor/s</label>
                                                </div>
                                                <div class="col-12 px-3">
                                                    <div class="form-check d-flex align-items-center">
                                                        <input type="checkbox" name="newrequestor[]"
                                                            id="newrequestorCollege" value="College"
                                                            style="cursor: pointer;"
                                                            {{ in_array('College', $requestors ?? []) ? 'checked' : '' }}>
                                                        <label for="newrequestorCollege" class="form-check-label ms-2"
                                                            style="cursor: pointer;">College</label>
                                                    </div>
                                                    <div class="form-check d-flex align-items-center">
                                                        <input type="checkbox" name="newrequestor[]"
                                                            id="newrequestorSH" value="Senior High"
                                                            style="cursor: pointer;"
                                                            {{ in_array('Senior High', $requestors ?? []) ? 'checked' : '' }}>
                                                        <label for="newrequestorSH" class="form-check-label ms-2"
                                                            style="cursor: pointer;">Senior High</label>
                                                    </div>
                                                    <div class="form-check d-flex align-items-center">
                                                        <input type="checkbox" name="newrequestor[]"
                                                            id="newrequestorJH" value="Junior High"
                                                            style="cursor: pointer;"
                                                            {{ in_array('Junior High', $requestors ?? []) ? 'checked' : '' }}>
                                                        <label for="newrequestorJH" class="form-check-label ms-2"
                                                            style="cursor: pointer;">Junior High</label>
                                                    </div>
                                                    <div class="form-check d-flex align-items-center">
                                                        <input type="checkbox" name="newrequestor[]"
                                                            id="newrequestorElem" value="Elementary"
                                                            style="cursor: pointer;"
                                                            {{ in_array('Elementary', $requestors ?? []) ? 'checked' : '' }}>
                                                        <label for="newrequestorElem" class="form-check-label ms-2"
                                                            style="cursor: pointer;">Elementary</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <div class="col-12">
                                                    <label for="newinstruction" class="fw-bold">Instruction</label>
                                                </div>
                                                <div class="col-12">
                                                    <textarea name="newinstruction" id="newinstruction" rows="10" class="form-control">{{ old('newinstruction') ?? $form->instruction }}</textarea>
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <div class="col-12">
                                                    <label for="newdownloadablefiles" class="fw-bold">Downloadable
                                                        Files</label>
                                                </div>
                                                <div class="col-12 px-3">
                                                    @forelse ($files as $file)
                                                        <div class="form-check d-flex align-items-center">
                                                            <input type="checkbox" name="newdownloadablefiles[]"
                                                                value="{{ $file->id }}"
                                                                id="newfile-{{ $file->id }}"
                                                                style="cursor: pointer"
                                                                {{ in_array($file->id, $filesId ?? []) ? 'checked' : '' }}>
                                                            <label for="newfile-{{ $file->id }}"
                                                                class="form-check-label"
                                                                style="cursor: pointer; margin-left: 5px;">{{ $file->filename }}</label>
                                                        </div>
                                                    @empty
                                                        <div class="text-center fw-bold">No Files Found.</div>
                                                    @endforelse
                                                </div>
                                            </div>
                                            {{-- <div class="row mb-3">
                                                <div
                                                    class="col-12 d-flex justify-content-between align-items-center mb-3">
                                                    <label for="newform" class="fw-bold">Update the form</label>
                                                    <button type="button" class="btn btn-sm btn-success"
                                                        onclick="addFieldToUpdateForm()">Add Field</button>
                                                </div>
                                                <div class="row">
                                                    <input type="number" name="newfieldcount" id="newfieldcount"
                                                        min="1" class="form-control" readonly>
                                                </div>
                                                <div class="col-12">
                                                    <div id="updateFields">
                                                    </div>
                                                </div>
                                            </div> --}}
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-light"
                                                    data-bs-dismiss="modal">Cancel</button>
                                                <button type="submit" class="btn btn-success" id="updateBtn">Save
                                                    changes</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Form Builder for Update Modal --}}
                        {{-- <script>
                            let updateFieldCount = 0;
                            let fields = @json($fields); // Pre-existing fields from the server

                            // Add pre-existing fields to the form
                            fields.forEach(field => {
                                addExistingFields(field.fieldname, field.fieldtype);
                            });

                            // Function to add existing fields to the form
                            function addExistingFields(fieldname, fieldtype) {
                                updateFieldCount++;
                                createFieldRow(fieldname, fieldtype, true);
                            }

                            // Function to add a new field to the form
                            function addFieldToUpdateForm() {
                                updateFieldCount++;
                                createFieldRow('', '', false);
                            }

                            // Function to create a field row
                            function createFieldRow(fieldname = '', fieldtype = '', isExisting = false) {
                                // Create a new row for the field
                                let fieldRow = document.createElement('div');
                                fieldRow.classList.add('row', 'mb-3', 'align-items-center');
                                fieldRow.classList.add('form-field');

                                // Create the column for the field name input
                                let updatefieldNameLblDiv = document.createElement('div');
                                updatefieldNameLblDiv.classList.add('col-md-2');
                                let updatefieldNameInpDiv = document.createElement('div');
                                updatefieldNameInpDiv.classList.add('col-md-3');

                                // Add a label for the field name
                                let nameLabel = document.createElement('label');
                                nameLabel.classList.add('fw-bold');
                                nameLabel.innerText = 'Field Name ';
                                updatefieldNameLblDiv.appendChild(nameLabel);

                                // Field name input
                                let updateNameInput = document.createElement('input');
                                updateNameInput.type = 'text';
                                updateNameInput.classList.add('form-control');
                                updateNameInput.maxLength = 255;
                                updateNameInput.placeholder = 'Enter field name';
                                updateNameInput.required = true;
                                updateNameInput.name = 'newfieldName[]'; // Make it an array
                                updateNameInput.value = isExisting ? fieldname : ''; // Set value if it's an existing field
                                updatefieldNameInpDiv.appendChild(updateNameInput);

                                // Create the column for the field type dropdown
                                let updatefieldTypeLblDiv = document.createElement('div');
                                updatefieldTypeLblDiv.classList.add('col-md-2');
                                let updatefieldTypeInpDiv = document.createElement('div');
                                updatefieldTypeInpDiv.classList.add('col-md-3');

                                // Add a label for the field type
                                let updateTypeLabel = document.createElement('label');
                                updateTypeLabel.classList.add('fw-bold');
                                updateTypeLabel.innerText = 'Field Type ';
                                updatefieldTypeLblDiv.appendChild(updateTypeLabel);

                                // Field type dropdown
                                let updateTypeSelect = document.createElement('select');
                                updateTypeSelect.classList.add('form-select');
                                updateTypeSelect.required = true;
                                updateTypeSelect.name = 'newfieldType[]'; // Make it an array

                                const newinputTypes = [
                                    'text', 'number', 'email', 'url', 'tel', 'date', 'time', 'week',
                                    'month', 'checkbox', 'radio', 'file',
                                ];

                                newinputTypes.forEach(type => {
                                    let newoption = document.createElement('option');
                                    newoption.value = type;
                                    newoption.innerText = type.charAt(0).toUpperCase() + type.slice(1);
                                    if (type === fieldtype) {
                                        newoption.selected = true;
                                    }
                                    updateTypeSelect.appendChild(newoption);
                                });

                                updatefieldTypeInpDiv.appendChild(updateTypeSelect);

                                // Create the column for the remove button
                                let updateRemoveButtonCol = document.createElement('div');
                                updateRemoveButtonCol.classList.add('col-md-2');

                                // Remove button
                                let updateRemoveButton = document.createElement('button');
                                updateRemoveButton.type = 'button';
                                updateRemoveButton.classList.add('btn', 'btn-danger', 'btn-sm');
                                updateRemoveButton.innerText = 'Remove';
                                updateRemoveButton.onclick = function() {
                                    updateRemoveField(fieldRow);
                                };
                                updateRemoveButtonCol.appendChild(updateRemoveButton);

                                // Append columns to the row
                                fieldRow.appendChild(updatefieldNameLblDiv);
                                fieldRow.appendChild(updatefieldNameInpDiv);
                                fieldRow.appendChild(updatefieldTypeLblDiv);
                                fieldRow.appendChild(updatefieldTypeInpDiv);
                                fieldRow.appendChild(updateRemoveButtonCol);

                                // Append the new row to the form
                                document.getElementById('updateFields').appendChild(fieldRow);
                                document.getElementById('newfieldcount').value = updateFieldCount;
                            }

                            // Function to remove a field row
                            function updateRemoveField(fieldRow) {
                                fieldRow.remove();
                            }

                            // Debug the form submission
                            document.getElementById('updateForm').addEventListener('submit', function(e) {
                                let fieldCount = document.querySelectorAll('input[name="newfieldName[]"]').length;
                                console.log('Field count:', fieldCount); // Debugging field count

                                document.querySelectorAll('input[name="newfieldName[]"]').forEach((fieldName, index) => {
                                    let fieldType = document.querySelectorAll('select[name="newfieldType[]"]')[index];
                                    console.log('Field Name:', fieldName.value); // Check values here
                                    console.log('Field Type:', fieldType.value); // Check values here
                                });
                            });
                        </script> --}}

                        <!-- Confirm Delete Modal -->
                        <div class="modal fade" id="deleteForm-{{ $form->csafid }}" tabindex="-1"
                            aria-labelledby="deleteFormLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header bg-danger text-white">
                                        <h5 class="modal-title" id="deleteFormLabel">Delete Form</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body px-4">
                                        <div class="row h6">
                                            Are you sure you want to delete this form?
                                        </div>
                                        <div class="row">
                                            <span class="col-12 text-center h5 fw-bold">{{ $form->formname }}</span>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <form action="{{ route('delSpecialAllowanceForm', $form->csafid) }}"
                                            method="POST">
                                            @csrf
                                            <button type="button" class="btn btn-secondary"
                                                data-bs-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn btn-danger">Delete</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{-- <script>
                            window.addEventListener('load', function() {
                                // Replace the placeholder {{ $form->id }} with the actual form ID in JavaScript
                                var formId = '{{ $form->csafid }}';
                                var modalId = '#updateForm-' + formId;

                                // Check if the modal exists, then show it
                                var modalElement = document.querySelector(modalId);
                                if (modalElement) {
                                    var modal = new bootstrap.Modal(modalElement);
                                    modal.show();
                                }
                            });
                        </script> --}}
                    @empty
                        <tr>
                            <td class="text-center align-middle" colspan="3">No Forms Found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{-- MANAGE DOWNLOADABLE FILES --}}
        <div class="row">
            <span class="text-success fw-bold h2">Manage Downloadable Files</span>
        </div>
        {{-- ADD DOWNLOADABLE FILE --}}
        <div class="row">
            <form method="POST" action="{{ route('addDownloadableFiles') }}" enctype="multipart/form-data">
                @csrf
                <div class="input-group mb-3">
                    <input type="text" maxlength="200" class="form-control" placeholder="Enter File Name"
                        name="filename" required>
                    <input type="file" class="form-control" accept=".pdf" name="file" required>
                    <button class="btn btn-success" type="submit" style="z-index: 1">Add</button>
                </div>
            </form>
        </div>
        {{-- VIEW/EDIT/DEL DOWNLOADABLE FILE --}}
        <div class="ctntable table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th class="text-center align-middle">File Name</th>
                        <th class="text-center align-middle">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($files as $file)
                        <tr>
                            <td class="text-center align-middle">{{ $file->filename }}</td>
                            <td class="text-center align-middle">
                                <!-- Button to open iframe modal -->
                                <button class="btn btn-sm btn-success" data-bs-toggle="modal"
                                    data-bs-target="#iframeModal-{{ $file->id }}">
                                    <i class="fas fa-eye"></i>
                                </button>

                                <!-- Button to open update modal -->
                                <button class="btn btn-sm btn-warning" data-bs-toggle="modal"
                                    data-bs-target="#updateModal-{{ $file->id }}">
                                    <i class="fas fa-edit"></i>
                                </button>

                                <!-- Delete button -->
                                <button class="btn btn-sm btn-danger" data-bs-toggle="modal"
                                    data-bs-target="#deleteFile-{{ $file->id }}"><i
                                        class="fas fa-trash"></i></button>
                            </td>
                        </tr>

                        <!-- View Modal -->
                        <div class="modal fade" id="iframeModal-{{ $file->id }}" tabindex="-1"
                            aria-labelledby="iframeModalLabel-{{ $file->id }}" aria-hidden="true">
                            <div class="modal-dialog modal-xl">
                                <div class="modal-content">
                                    <div class="modal-header bg-success text-white">
                                        <div class="row">
                                            <span class="modal-title h6"
                                                id="iframeModalLabel-{{ $file->id }}">Preview:</span>
                                            <span class="modal-title h5 fw-bold"
                                                id="iframeModalLabel-{{ $file->id }}">{{ $file->filename }}</span>
                                        </div>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <iframe src="{{ asset('storage/' . $file->pathname) }}#zoom=100"
                                            width="100%" height="500px">
                                            Your browser does not support iframes. Please download the PDF file
                                            <a href="{{ url('storage/' . $file->pathname) }}">here</a>.
                                        </iframe>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Update Modal -->
                        <div class="modal fade" id="updateModal-{{ $file->id }}" tabindex="-1"
                            aria-labelledby="updateModalLabel-{{ $file->id }}" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form method="POST" action="{{ route('updateDownloadableFile', $file->id) }}"
                                        enctype="multipart/form-data">
                                        @csrf
                                        <div class="modal-header bg-success text-white">
                                            <div class="row">
                                                <span class="modal-title h6"
                                                    id="updateModalLabel-{{ $file->id }}">Update File:</span>
                                                <span class="modal-title h5 fw-bold"
                                                    id="updateModalLabel-{{ $file->id }}">{{ $file->filename }}</span>
                                            </div>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label for="newfilename" class="form-label">New File Name</label>
                                                <input type="text" maxlength="200" class="form-control"
                                                    placeholder="Enter File Name" name="newfilename" required
                                                    value="{{ $file->filename }}">
                                            </div>
                                            <div class="mb-3">
                                                <label for="newfile" class="form-label">Upload New File</label>
                                                <input type="file" class="form-control" accept=".pdf"
                                                    name="newfile" required>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-bs-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn btn-success">Save</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- Confirm Delete Modal -->
                        <div class="modal fade" id="deleteFile-{{ $file->id }}" tabindex="-1"
                            aria-labelledby="deleteFileLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header bg-danger text-white">
                                        <h5 class="modal-title" id="deleteFileLabel">Delete File</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body px-4">
                                        <div class="row h6">
                                            Are you sure you want to delete this file?
                                        </div>
                                        <div class="row">
                                            <span class="col-12 text-center h5 fw-bold">{{ $file->filename }}</span>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <form action="{{ route('deleteDownloadableFile', $file->id) }}"
                                            method="POST">
                                            @csrf
                                            <button type="button" class="btn btn-secondary"
                                                data-bs-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn btn-danger">Delete</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <tr>
                            <td class="text-center align-middle" colspan="2">No Files Uploaded</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- CREATE NEW FORM MODAL --}}
    <div class="modal fade" id="createFormModal" tabindex="-1" aria-labelledby="createFormModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title fw-bolf" id="createFormModalLabel">Create New Form</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body px-4 py-2">
                    <form action="{{ route('createNewSpecialAllowanceForm') }}" method="post" id="createForm">
                        @csrf
                        <div class="row mb-3">
                            <div class="col-12">
                                <label for="formname" class="fw-bold">Form Name</label>
                            </div>
                            <div class="col-12">
                                <input type="text" maxlength="200" class="form-control" name="formname"
                                    id="formname" value="{{ old('formname') }}" required>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-12">
                                <label for="formcode" class="fw-bold">Form Code</label>
                            </div>
                            <div class="col-12">
                                <input type="text" maxlength="25" class="form-control" name="formcode"
                                    id="formcode" value="{{ old('formcode') }}" required>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-12">
                                <label for="requestor" class="fw-bold">Requestor/s</label>
                            </div>
                            <div class="col-12 px-3">
                                <div class="form-check d-flex align-items-center">
                                    <input type="checkbox" name="requestor[]" id="requestorCollege" value="College"
                                        style="cursor: pointer;">
                                    <label for="requestorCollege" class="form-check-label ms-2"
                                        style="cursor: pointer;">College</label>
                                </div>
                                <div class="form-check d-flex align-items-center">
                                    <input type="checkbox" name="requestor[]" id="requestorSH" value="Senior High"
                                        style="cursor: pointer;">
                                    <label for="requestorSH" class="form-check-label ms-2"
                                        style="cursor: pointer;">Senior High</label>
                                </div>
                                <div class="form-check d-flex align-items-center">
                                    <input type="checkbox" name="requestor[]" id="requestorJH" value="Junior High"
                                        style="cursor: pointer;">
                                    <label for="requestorJH" class="form-check-label ms-2"
                                        style="cursor: pointer;">Junior High</label>
                                </div>
                                <div class="form-check d-flex align-items-center">
                                    <input type="checkbox" name="requestor[]" id="requestorElem" value="Elementary"
                                        style="cursor: pointer;">
                                    <label for="requestorElem" class="form-check-label ms-2"
                                        style="cursor: pointer;">Elementary</label>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-12">
                                <label for="instruction" class="fw-bold">Instruction</label>
                            </div>
                            <div class="col-12">
                                <textarea name="instruction" id="instruction" rows="10" class="form-control">{{ old('instruction') }}</textarea>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-12">
                                <label for="downloadablefiles" class="fw-bold">Downloadable Files</label>
                            </div>
                            <div class="col-12 px-3">
                                @forelse ($files as $file)
                                    <div class="form-check d-flex align-items-center">
                                        <input type="checkbox" name="downloadablefiles[]"
                                            value="{{ $file->id }}" id="file-{{ $file->id }}"
                                            style="cursor: pointer">
                                        <label for="file-{{ $file->id }}" class="form-check-label"
                                            style="cursor: pointer; margin-left: 5px;">{{ $file->filename }}</label>
                                    </div>
                                @empty
                                    <div class="text-center fw-bold">No Files Found.</div>
                                @endforelse
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-12 d-flex justify-content-between align-items-center mb-3">
                                <label for="form" class="fw-bold">Create the form</label>
                                <button type="button" class="btn btn-sm btn-success" onclick="addField()">Add
                                    Field</button>
                            </div>
                            <div class="row">
                                <input type="number" name="fieldcount" id="fieldcount" min="1" hidden>
                            </div>
                            <div class="col-12">
                                <div id="formFields">
                                    <!-- Dynamically added fields will appear here -->
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-success" id="submitBtn">Save Form</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- ALERT MODAL --}}
    <div class="modal fade" id="validationModal" tabindex="-1" aria-labelledby="validationModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="validationModalLabel">Validation Errors</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <ul id="validationErrorsModal">
                        <!-- Error messages will be appended here -->
                    </ul>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    {{-- CONTROL SIDEBAR AND PROFILE MENU --}}
    <script src="{{ asset('js/headercontrol.js') }}"></script>
    {{-- RICH TEXT EDITOR --}}
    <script>
        tinymce.init({
            selector: '#instruction',
            toolbar: 'undo redo | formatselect | fontfamily fontsize forecolor | bold italic underline | numlist bullist | alignleft aligncenter alignright alignjustify | lineheight | link',
            menubar: false,
            // branding: false,
            plugins: [
                'link', // Enables the hyperlink plugin
                'lists', // Enables list functionality
            ],
        });
        tinymce.init({
            selector: '#newinstruction',
            toolbar: 'undo redo | formatselect | fontfamily fontsize forecolor | bold italic underline | numlist bullist | alignleft aligncenter alignright alignjustify | lineheight | link',
            menubar: false,
            // branding: false,
            plugins: [
                'link', // Enables the hyperlink plugin
                'lists', // Enables list functionality
            ],
        });
    </script>
    {{-- CREATE FORM BUILDER --}}
    <script>
        let fieldCount = 0;

        // Function to add a new field with field name and field type
        function addField() {
            fieldCount++;

            // Create a new row for the field (div.container for the row layout)
            let newFieldRow = document.createElement('div');
            newFieldRow.classList.add('row', 'mb-3', 'align-items-center');
            newFieldRow.classList.add('form-field');

            // Create the column for the field name input
            let fieldNameLblDiv = document.createElement('div');
            fieldNameLblDiv.classList.add('col-md-2');

            let fieldNameInpDiv = document.createElement('div');
            fieldNameInpDiv.classList.add('col-md-3');

            // Add a label for the field name
            let nameLabel = document.createElement('label');
            nameLabel.setAttribute('for', 'fieldName' + fieldCount);
            nameLabel.classList.add('fw-bold');
            nameLabel.innerText = 'Field Name ';

            let nameInput = document.createElement('input');
            nameInput.type = 'text';
            nameInput.id = 'fieldName' + fieldCount;
            nameInput.name = 'fieldName' + fieldCount;
            nameInput.classList.add('form-control');
            nameInput.maxLength = 255;
            nameInput.placeholder = 'Enter field name';
            nameInput.required = true;

            fieldNameLblDiv.appendChild(nameLabel);
            fieldNameInpDiv.appendChild(nameInput);

            // Create the column for the field type dropdown
            let fieldTypeLblDiv = document.createElement('div');
            fieldTypeLblDiv.classList.add('col-md-2');

            let fieldTypeInpDiv = document.createElement('div');
            fieldTypeInpDiv.classList.add('col-md-3');

            // Add a label for the field type
            let typeLabel = document.createElement('label');
            typeLabel.setAttribute('for', 'fieldType' + fieldCount);
            typeLabel.classList.add('fw-bold');
            typeLabel.innerText = 'Field Type ';

            // Add a dropdown for selecting field type
            let typeSelect = document.createElement('select');
            typeSelect.id = 'fieldType' + fieldCount;
            typeSelect.name = 'fieldType' + fieldCount;
            typeSelect.classList.add('form-select');
            typeSelect.required = true;

            // Add options for field types (all possible <input> types)
            const inputTypes = [
                'Select type', 'text', 'number', 'email', 'url', 'tel', 'date', 'time', 'week',
                'month', 'checkbox', 'radio', 'file',
            ];

            inputTypes.forEach(type => {
                let option = document.createElement('option');
                if (type == 'Select type') {
                    option.value = '';
                } else {
                    option.value = type;
                }
                option.innerText = type.charAt(0).toUpperCase() + type.slice(1); // Capitalize first letter
                typeSelect.appendChild(option);
            });

            fieldTypeLblDiv.appendChild(typeLabel);
            fieldTypeInpDiv.appendChild(typeSelect);

            // Create the column for the remove button
            let removeButtonCol = document.createElement('div');
            removeButtonCol.classList.add('col-md-2'); // Adjust to 4 columns for the remove button

            let removeButton = document.createElement('button');
            removeButton.type = 'button';
            removeButton.classList.add('btn', 'btn-danger', 'btn-sm');
            removeButton.innerText = 'Remove';
            removeButton.onclick = function() {
                removeCreateField(newFieldRow);
            };

            removeButtonCol.appendChild(removeButton);

            // Append columns to the row
            newFieldRow.appendChild(fieldNameLblDiv);
            newFieldRow.appendChild(fieldNameInpDiv);
            newFieldRow.appendChild(fieldTypeLblDiv);
            newFieldRow.appendChild(fieldTypeInpDiv);
            newFieldRow.appendChild(removeButtonCol);

            // Append the new row to the form
            document.getElementById('formFields').appendChild(newFieldRow);
            document.getElementById('fieldcount').value = fieldCount;
        }

        // Function to remove a field row
        function removeCreateField(fieldRow) {
            fieldRow.remove();
            document.getElementById('fieldcount').value = fieldCount;
        }
    </script>
    {{-- SAVE FORM DATA ON RELOAD --}}
    <script>
        // Save form data to localStorage on change
        document.getElementById('createForm').addEventListener('input', function() {
            localStorage.setItem('formData', JSON.stringify(getFormData()));
        });

        // Function to get form data
        function getFormData() {
            const form = document.getElementById('createForm');
            const formData = {};

            // Collect all input data
            Array.from(form.elements).forEach(element => {
                if (element.name) {
                    // Skip checkbox inputs
                    if (element.type === 'checkbox') {
                        // Handle checkbox array inputs like requestor[]
                        if (!formData[element.name]) {
                            formData[element.name] = [];
                        }
                        if (element.checked) {
                            formData[element.name].push(element.value);
                        }
                    } else if (element.type === 'textarea' || element.type === 'text' || element.type ===
                        'number') {
                        // Handle other input types (textarea, text, number)
                        formData[element.name] = element.value;
                    }
                }
            });

            return formData;
        }

        // Load saved form data from localStorage
        window.addEventListener('load', function() {
            const savedData = JSON.parse(localStorage.getItem('formData'));
            if (savedData) {
                populateForm(savedData);
            }
        });

        // Function to populate form with saved data
        function populateForm(savedData) {
            const form = document.getElementById('createForm');

            // Loop through saved data and populate form
            for (const name in savedData) {
                const element = form.elements[name];

                if (element) {
                    if (element.type === 'checkbox') {
                        // Handle checkbox inputs (array) like requestor[]
                        savedData[name].forEach(value => {
                            const checkbox = form.querySelector(`#${name}-${value}`); // Use ID to select checkboxes
                            if (checkbox) {
                                checkbox.checked = true;
                            }
                        });
                    } else {
                        // Populate text, textarea, and number fields
                        element.value = savedData[name];
                    }
                }
            }
        }

        // Clear localStorage on form submit
        document.getElementById('createForm').addEventListener('submit', function() {
            localStorage.removeItem('formData');
        });
    </script>
    {{-- VALIDATE REQUESTOR, DL FILES, AND FORM STRUCTURE --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const validationModal = new bootstrap.Modal(document.getElementById('validationModal'));
            const validationErrorsModalList = document.getElementById('validationErrorsModal');
            const submitBtn = document.getElementById('submitBtn');
            const createForm = document.getElementById('createForm');
            const requestorCheckboxes = document.querySelectorAll('input[name="requestor[]"]');
            const downloadableFilesCheckboxes = document.querySelectorAll('input[name="downloadablefiles[]"]');
            const fieldCountInput = document.getElementById('fieldcount');
            const formFieldsContainer = document.getElementById('formFields');
            const updateBtn = document.getElementById('updateBtn');
            const updateForm = document.getElementById('updateForm');
            const updateRequestorCheckboxes = document.querySelectorAll('input[name="newrequestor[]"]');
            const updatedownloadableFilesCheckboxes = document.querySelectorAll(
                'input[name="newdownloadablefiles[]"]');
            // Validation function
            function validateCreateForm(event) {
                event.preventDefault();

                let errorMessages = []; // Initialize error messages array

                // Check if at least one Requestor is selected
                const isRequestorChecked = Array.from(requestorCheckboxes).some(checkbox => checkbox.checked);
                if (!isRequestorChecked) {
                    errorMessages.push('Please select at least one Requestor.');
                }

                // Check if at least one Downloadable File is selected
                const isDownloadableFilesChecked = Array.from(downloadableFilesCheckboxes).some(checkbox => checkbox
                    .checked);
                if (!isDownloadableFilesChecked) {
                    errorMessages.push('Please select at least one Downloadable File.');
                }

                // Check if at least one field has been added
                if (parseInt(fieldCountInput.value, 10) <= 0 || formFieldsContainer.children.length === 0) {
                    errorMessages.push('Please add at least one form field.');
                }

                // If there are error messages, display them in the Bootstrap modal
                if (errorMessages.length > 0) {
                    // Clear previous errors
                    validationErrorsModalList.innerHTML = '';

                    // Append new error messages to the list
                    errorMessages.forEach(function(message) {
                        const listItem = document.createElement('li');
                        listItem.textContent = message;
                        validationErrorsModalList.appendChild(listItem);
                    });

                    // Show the modal
                    validationModal.show();
                    return false;
                }

                // If all validations pass, submit the form
                createForm.submit();
            }

            function validateUpdateForm(event) {
                event.preventDefault();

                let errorMessages = [];

                // Check if at least one Requestor is selected
                const isRequestorChecked = Array.from(updateRequestorCheckboxes).some(checkbox => checkbox.checked);
                if (!isRequestorChecked) {
                    errorMessages.push('Please select at least one Requestor.');
                }

                // Check if at least one Downloadable File is selected
                const isUpdateDownloadableFilesChecked = Array.from(updatedownloadableFilesCheckboxes).some(
                    checkbox => checkbox
                    .checked);
                if (!isUpdateDownloadableFilesChecked) {
                    errorMessages.push('Please select at least one Downloadable File.');
                }

                // If there are error messages, display them in the Bootstrap modal
                if (errorMessages.length > 0) {
                    // Clear previous errors
                    validationErrorsModalList.innerHTML = '';

                    // Append new error messages to the list
                    errorMessages.forEach(function(message) {
                        const listItem = document.createElement('li');
                        listItem.textContent = message;
                        validationErrorsModalList.appendChild(listItem);
                    });

                    // Show the modal
                    validationModal.show();
                    return false;
                }

                // If all validations pass, submit the form
                updateForm.submit();
            }

            // Add event listener to the submit button
            submitBtn.addEventListener('click', validateCreateForm);
            updateBtn.addEventListener('click', validateUpdateForm);
        });
    </script>

</body>

</html>
