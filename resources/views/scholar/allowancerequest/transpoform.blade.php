<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transportation Request Form</title>
    <link href="https://cdn.lineicons.com/4.0/lineicons.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/spform.css') }}">
    <link rel="stylesheet" href="{{ asset('css/partial.css') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body>
    <!-- Include Sidebar -->
    @include('partials._sidebar')

    <!-- Include Navbar -->
    @include('partials._navbar')

    <!-- TRANSPORTATION REIMBURSEMENT FORM -->
    <div class="ctn-main">
        <a href="{{ route('scspecial') }}" class="goback">&lt Go back</a>
        <div class="row">
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
        <div class="form">
            <h2 class="title text-center">Transportation Reimbursement Request Form </h2>
            <form action="{{ route('reqallowancetranspo', $scholar->caseCode) }}" method="post"
                enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="form-group">
                        <label for="area">Area</label>
                        <input type="text" id="area" name="area"
                            value="{{ $scholar->scholarshipinfo->area }}" readonly>
                    </div>
                    <div class="form-group">
                        <label for="course">Course</label>
                        <input type="text" id="course" name="course"
                            value="{{ $scholar->education->scCourseStrandSec }}" readonly>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group">
                        <label for="lastName">Last Name</label>
                        <input type="text" id="lastName" name="lastName"
                            value="{{ $scholar->basicInfo->scLastname }}" readonly>
                    </div>
                    <div class="form-group">
                        <label for="firstName">First Name</label>
                        <input type="text" id="firstName" name="firstName"
                            value="{{ $scholar->basicInfo->scFirstname }}" readonly>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group">
                        <label for="school">School</label>
                        <input type="text" id="school" name="school"
                            value="{{ $scholar->education->scSchoolName }}" readonly>
                    </div>
                    <div class="form-group">
                        <label for="yearLevel">Year Level</label>
                        <input type="text" id="yearLevel" name="yearLevel"
                            value="{{ $scholar->education->scYearGrade }}" readonly>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group">
                        <label for="totalPrice">Total Price</label>
                        <input type="number" min="1" id="totalPrice" name="totalprice" required step="0.01">
                    </div>
                    <div class="form-group">
                        <label for="purpose">Purpose/Activity</label>
                        <input type="text" maxlength="100" id="purpose" name="purpose" required>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group">
                        <label for="assignedStaff">Name of the Assigned Staff or Volunteer</label>
                        <input type="text" id="assignedStaff" name="staffname" required>
                    </div>
                    <div class="form-group">
                        <label for="reimbursementForm">Transportation Reimbursement Form</label>
                        <input type="file" id="reimbursementForm" name="transpoform"
                            accept="application/pdf, application/msword, application/vnd.openxmlformats-officedocument.wordprocessingml.document, image/jpeg, image/png"
                            required>
                    </div>
                </div>

                <div class="text-center">
                    <button type="submit" class="submit-btn">Submit</button>
                </div>
            </form>
        </div>

    </div>
    <script src="{{ asset('js/scholar.js') }}"></script>
</body>

</html>
