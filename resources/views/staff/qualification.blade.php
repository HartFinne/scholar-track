<!DOCTYPE html>
<html lang="en">

<head>
    <title>Scholarship Criteria | College</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/table.css') }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
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

    <div class="ctnmain">
        <!-- COLLEGE-->
        <h2 class="mb-4">Scholarship Criteria</h2>
        <div class="row">
            @if (session('importsuccess'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('importsuccess') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if (session('importerror'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('importerror') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
        </div>
        <fieldset class="row">
            <legend><strong>Import Scholar Emails</strong></legend>
            <span class="col-12 mb-2">
                Please upload the Excel file containing scholar emails for registration verification.
                Ensure the file contains only email addresses, and they must be placed in the first column of the sheet.
            </span>
            <form action="{{ route('importemails') }}" method="POST" enctype="multipart/form-data" class="col-12">
                @csrf <!-- Ensure CSRF protection for Laravel applications -->
                <div class="input-group">
                    <input type="file" class="form-control" id="file" name="file" required
                        aria-describedby="fileHelp">
                    <button class="btn btn-success" type="submit">Upload</button>
                </div>
                <small id="fileHelp" class="form-text text-muted">
                    File must be in .xlsx or .xls format.
                </small>
            </form>
        </fieldset>
        <div class="row" id="confirmmsg1">
            @if (session('critsuccess'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('critsuccess') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if (session('criterror'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('criterror') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
        </div>

        <div class="row">
            <fieldset class="col-12">
                <form method="POST" action="{{ route('updatecriteria') }}">
                    @csrf
                    <div class="row">
                        <div class="col-md-11">
                            <legend><strong>Requirements</strong></legend>
                        </div>
                        <div class="col-md-1">
                            <button type="submit" class="btn btn-success" style="width: 100%">Save</button>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="cshours" class="form-label">Required CS Hours</label>
                                <input type="text" class="form-control" id="cshours" name="cshours"
                                    value="{{ $criteria->cshours ?? '' }}" placeholder="Not Set" required>
                            </div>
                            <div class="mb-3">
                                <label for="cgwa" class="form-label">College GWA</label>
                                <input type="text" class="form-control" id="cgwa" name="cgwa"
                                    value="{{ $criteria->cgwa ?? '' }}" placeholder="Not Set" required>
                            </div>
                            <div class="mb-3">
                                <label for="shsgwa" class="form-label">Senior High GWA</label>
                                <input type="text" class="form-control" id="shsgwa" name="shsgwa"
                                    value="{{ $criteria->shsgwa ?? '' }}" placeholder="Not Set" required>
                            </div>
                            <div class="mb-3">
                                <label for="jhsgwa" class="form-label">Junior High GWA</label>
                                <input type="text" class="form-control" id="jhsgwa" name="jhsgwa"
                                    value="{{ $criteria->jhsgwa ?? '' }}" placeholder="Not Set" required>
                            </div>
                            <div class="mb-3">
                                <label for="elemgwa" class="form-label">Elementary GWA</label>
                                <input type="text" class="form-control" id="elemgwa" name="elemgwa"
                                    value="{{ $criteria->elemgwa ?? '' }}" placeholder="Not Set" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="fincome" class="form-label">Father's Income</label>
                                <input type="text" class="form-control" id="fincome" name="fincome"
                                    value="{{ $criteria->fincome ?? '' }}" placeholder="Not Set" required>
                            </div>
                            <div class="mb-3">
                                <label for="mincome" class="form-label">Mother's Income</label>
                                <input type="text" class="form-control" id="mincome" name="mincome"
                                    value="{{ $criteria->mincome ?? '' }}" placeholder="Not Set" required>
                            </div>
                            <div class="mb-3">
                                <label for="sincome" class="form-label">Siblings' Income</label>
                                <input type="text" class="form-control" id="sincome" name="sincome"
                                    value="{{ $criteria->sincome ?? '' }}" placeholder="Not Set" required>
                            </div>
                            <div class="mb-3">
                                <label for="aincome" class="form-label">Applicant's Income</label>
                                <input type="text" class="form-control" id="aincome" name="aincome"
                                    value="{{ $criteria->aincome ?? '' }}" placeholder="Not Set" required>
                            </div>
                        </div>
                    </div>
                </form>
            </fieldset>
        </div>
        <div class="row" id="confirmmsg2">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert" id="success-alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert" id="error-alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
        </div>
        <div class="row">
            <fieldset class="col-12 col-md-4">
                <legend><strong>Institutions</strong></legend>
                <form class="mb-3" method="POST" action="{{ route('addinstitution') }}">
                    @csrf
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" placeholder="Enter Institution" name="institute"
                            required>
                        <button class="btn btn-success" type="submit" id="btnaddinsti">Add</button>
                    </div>
                </form>
                <div class="table-responsive">
                    <table class="table table-bordered" id="tblinstitutions">
                        <thead>
                            <tr>
                                <th class="text-center" style="width: 75%;">Institution</th>
                                <th class="text-center" style="width: 25%;">Action</th>
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
        </div>
        </fieldset>

        <fieldset class="col-12 col-md-4">
            <legend><strong>Courses</strong></legend>
            <form class="mb-3" method="POST" action="{{ route('addcourse', 'College') }}">
                @csrf
                <div class="input-group mb-3">
                    <input type="text" class="form-control" placeholder="Enter Course" name="course" required>
                    <button class="btn btn-success" type="submit" id="btnaddcourse">Add</button>
                </div>
            </form>
            <div class="table-responsive">
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
    </div>
    </fieldset>

    <fieldset class="col-12 col-md-4">
        <legend><strong>Strand</strong></legend>
        <form class="mb-3" method="POST" action="{{ route('addcourse', 'Senior High') }}">
            @csrf
            <div class="input-group mb-3">
                <input type="text" class="form-control" placeholder="Enter Strand" name="strand" required>
                <button class="btn btn-success" type="submit" id="btnaddcourse">Add</button>
            </div>
        </form>
        <div class="table-responsive">
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
        </div>
    </fieldset>
    </div>
    </div>

    <script src="{{ asset('js/headercontrol.js') }}"></script>
    <script src="{{ asset('js/criteriacontrol.js') }}"></script>
</body>

</html>
