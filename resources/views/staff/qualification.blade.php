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
    <script>
        window.onerror = function(message, source, lineno, colno, error) {
            alert('Error: ' + message + '\nSource: ' + source + '\nLine: ' + lineno);
        };
    </script>
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
        <div class="row" id="confirmmsg1">
            @if (session('critsuccess'))
                <div class="alert alert-success alert-dismissible fade show" role="alert" id="success-alert">
                    {{ session('critsuccess') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if (session('criterror'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert" id="error-alert">
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
                                <label for="collegeGwa" class="form-label">College GWA</label>
                                <input type="text" class="form-control" id="collegeGwa" name="collegeGwa"
                                    value="{{ $criteria->cgwa ?? '--' }}" required>
                            </div>
                            <div class="mb-3">
                                <label for="seniorHighGwa" class="form-label">Senior High GWA</label>
                                <input type="text" class="form-control" id="seniorHighGwa" name="seniorHighGwa"
                                    value="{{ $criteria->shsgwa ?? '--' }}" required>
                            </div>
                            <div class="mb-3">
                                <label for="juniorHighGwa" class="form-label">Junior High GWA</label>
                                <input type="text" class="form-control" id="juniorHighGwa" name="juniorHighGwa"
                                    value="{{ $criteria->jhsgwa ?? '--' }}" required>
                            </div>
                            <div class="mb-3">
                                <label for="elementaryGwa" class="form-label">Elementary GWA</label>
                                <input type="text" class="form-control" id="elementaryGwa" name="elementaryGwa"
                                    value="{{ $criteria->elemgwa ?? '--' }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="fatherIncome" class="form-label">Father's Income</label>
                                <input type="text" class="form-control" id="fatherIncome" name="fatherIncome"
                                    value="{{ $criteria->fincome ?? '--' }}" required>
                            </div>
                            <div class="mb-3">
                                <label for="motherIncome" class="form-label">Mother's Income</label>
                                <input type="text" class="form-control" id="motherIncome" name="motherIncome"
                                    value="{{ $criteria->mincome ?? '--' }}" required>
                            </div>
                            <div class="mb-3">
                                <label for="siblingsIncome" class="form-label">Siblings' Income</label>
                                <input type="text" class="form-control" id="siblingsIncome" name="siblingsIncome"
                                    value="{{ $criteria->sincome ?? '--' }}" required>
                            </div>
                            <div class="mb-3">
                                <label for="applicantIncome" class="form-label">Applicant's Income</label>
                                <input type="text" class="form-control" id="applicantIncome"
                                    name="applicantIncome" value="{{ $criteria->aincome ?? '--' }}" required>
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
