<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Special Allowance Form | {{ $form->formcode }}</title>
    <link rel="stylesheet" href="{{ asset('css/special.css') }}">
    <link rel="stylesheet" href="{{ asset('css/partial.css') }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.tiny.cloud/1/u8od7gmihvz9en9414xjqlrif77eel97bab2vypiq386dycy/tinymce/7/tinymce.min.js"
        referrerpolicy="origin"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body>
    <!-- Include Sidebar -->
    @include('partials._sidebar')

    <!-- Include Navbar -->
    @include('partials._navbar')
    <x-alert />

    <!-- BOOK ALLOWANCE REQUEST -->
    <div class="ctn-main">
        <a href="{{ route('scspecial') }}" class="goback">&lt Go back</a>
        <div class="container bg-light rounded px-4 py-3 mb-4 mt-3">
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
                @forelse ($files as $file)
                    <a href="{{ asset('storage/' . $file->pathname) }}" class="col-12 text-center link link-success"
                        download>
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
                        <input type="text" readonly name="firstname" class="form-control"
                            value="{{ $user->basicInfo->scFirstname ?? 'Failed to load' }}">
                    </div>
                </div>
                <div class="col-md-5 mb-3">
                    <div class="row fw-bold">Last Name</div>
                    <div class="row">
                        <input type="text" readonly name="lastname" class="form-control"
                            value="{{ $user->basicInfo->scLastname ?? 'Failed to load' }}">
                    </div>
                </div>
                <div class="col-md-5 mb-3">
                    <div class="row fw-bold">Area</div>
                    <div class="row">
                        <input type="text" readonly name="area" class="form-control"
                            value="{{ $user->scholarshipinfo->area ?? 'Failed to load' }}">
                    </div>
                </div>
                <div class="col-md-5 mb-3">
                    <div class="row fw-bold">Year Level</div>
                    <div class="row">
                        <input type="text" readonly name="yearlevel" class="form-control"
                            value="{{ $user->education->scYearGrade ?? 'Failed to load' }}">
                    </div>
                </div>
                <div class="col-md-5 mb-3">
                    <div class="row fw-bold">Course</div>
                    <div class="row">
                        <input type="text" readonly name="course" class="form-control"
                            value="{{ $user->education->scCourseStrandSec ?? 'Failed to load' }}">
                    </div>
                </div>
                <div class="col-md-5 mb-3">
                    <div class="row fw-bold">School</div>
                    <div class="row">
                        <input type="text" readonly name="school" class="form-control"
                            value="{{ $user->education->scSchoolName ?? 'Failed to load' }}">
                    </div>
                </div>
            </div>
            <div class="row justify-content-evenly">
                @forelse ($fields as $field)
                    <div class="col-md-5 mb-3">
                        <div class="row fw-bold">{{ $field->fieldname }}</div>
                        <div class="row">
                            <input type="{{ $field->fieldtype }}" required name="{{ $field->fieldname }}"
                                class="form-control" {{ $field->fieldtype = 'number' ? 'step="0.01" min="1" ' : '' }}>
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
    <script src="{{ asset('js/scholar.js') }}"></script>
</body>

</html>
