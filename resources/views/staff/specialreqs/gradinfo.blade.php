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

    <div class="ctnmain">
        <a href="{{ route('allowancerequests-special') }}" class="link-success">&lt Go back</a>
        <div class="container mt-5">
            <div class="row" id="confirmmsg">
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert" id="success-alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert" id="error-alert">
                        {!! session('error') !!}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
            </div>
            <div class="card p-3 bg-light">
                <div class="border bg-success text-white p-3">
                    <h4 class="mb-0"><strong>GRADUATION ALLOWANCE REQUEST</strong></h4>
                </div>
                <div class="card-body">
                    <form method="POST"
                        action="{{ route('updatespecreq', ['requesttype' => 'GAR', 'id' => $request->id]) }}">
                        @csrf
                        <div class="mb-3 row">
                            <label class="col-sm-4 col-form-label"><strong>Update Request Status</strong></label>
                            <div class="col-sm-8">
                                <select name="status" class="form-select">
                                    <option value="Pending" {{ $request->status == 'Pending' ? 'selected' : '' }}>
                                        Pending</option>
                                    <option value="Accepted" {{ $request->status == 'Accepted' ? 'selected' : '' }}>
                                        Accepted</option>
                                    <option value="Rejected" {{ $request->status == 'Rejected' ? 'selected' : '' }}>
                                        Rejected</option>
                                    <option value="Completed" {{ $request->status == 'Completed' ? 'selected' : '' }}>
                                        Completed</option>
                                </select>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label class="col-sm-4 col-form-label"><strong>Set Release Date</strong></label>
                            <div class="col-sm-8">
                                <input type="date" name="releasedate" class="form-control"
                                    value="{{ $request->releasedate ? $request->releasedate : '' }}"
                                    min="{{ date('Y-m-d') }}">
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <div class="col-md-10"></div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-success w-100">Save Update</button>
                            </div>
                        </div>
                    </form>
                    <div class="row mb-3">
                        <label class="col-sm-4 col-form-label">Date of Request</label>
                        <div class="col-sm-8">
                            <p class="form-control-plaintext">: {{ $request->created_at->format('F d, Y') }}</p>
                        </div>
                    </div>
                    {{-- SCHOLAR INFO --}}
                    <div class="row mb-3">
                        <label class="col-sm-4 col-form-label">Area</label>
                        <div class="col-sm-8">
                            <p class="form-control-plaintext">: {{ $scholar->scholarshipinfo->area }}</p>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-4 col-form-label">Name</label>
                        <div class="col-sm-8">
                            <p class="form-control-plaintext">: {{ $scholar->basicInfo->scLastname }},
                                {{ $scholar->basicInfo->scFirstname }}, {{ $scholar->basicInfo->scMiddlename }}</p>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-4 col-form-label">School</label>
                        <div class="col-sm-8">
                            <p class="form-control-plaintext">: {{ $scholar->education->scSchoolName }}</p>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-4 col-form-label">Year Level</label>
                        <div class="col-sm-8">
                            <p class="form-control-plaintext">: {{ $scholar->education->scYearGrade }}</p>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-4 col-form-label">Course</label>
                        <div class="col-sm-8">
                            <p class="form-control-plaintext">: {{ $scholar->education->scCourseStrandSec }}</p>
                        </div>
                    </div>
                    {{-- REQUEST INFO --}}
                    <div class="row mb-3">
                        <label class="col-sm-4 col-form-label">Total Price</label>
                        <div class="col-sm-8">
                            <p class="form-control-plaintext">: {{ $request->totalprice }}</p>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-4 col-form-label">Official List of Graduates</label>
                        <div class="col-sm-8">:
                            <a href="{{ asset('storage/' . $request->listofgraduates) }}" target="_blank"
                                rel="noopener noreferrer" class="link-success">Click here to view</a>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-4 col-form-label">Receipt or Acknowledgement Receipt</label>
                        <div class="col-sm-8">:
                            <a href="{{ asset('storage/' . $request->acknowledgement) }}" target="_blank"
                                rel="noopener noreferrer" class="link-success">Click here to view</a>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-4 col-form-label">Liquidation Form</label>
                        <div class="col-sm-8">:
                            <a href="{{ asset('storage/' . $request->liquidation) }}" target="_blank"
                                rel="noopener noreferrer" class="link-success">Click here to view</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="{{ asset('js/headercontrol.js') }}"></script>
</body>

</html>
