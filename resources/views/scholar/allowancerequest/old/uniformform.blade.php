<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Uniform Request Form</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="{{ asset('css/spform.css') }}">
    <link rel="stylesheet" href="{{ asset('css/partial.css') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body>
    <!-- Include Sidebar -->
    @include('partials._sidebar')

    <!-- Include Navbar -->
    @include('partials._navbar')
    <x-alert />

    <!-- UNIFORM REQUEST FORM -->
    <div class="ctn-main">
        <a href="{{ route('scspecial') }}" class="goback">&lt Go back</a>
        <div class="form">
            <h2 class="title text-center">Uniform Request Form </h2>
            <form action="{{ route('reqallowanceuniform', $scholar->caseCode) }}" method="post"
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
                        <input type="text" id="schoolname" name="school"
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
                        <p class="fw-bold">Uniform for?</p>
                        <div class="row-radio">
                            <input type="radio" id="school" name="uniformtype" value="School" required>
                            <label for="school">School</label>
                            <input type="radio" id="ojt" name="uniformtype" value="OJT" required>
                            <label for="ojt">OJT</label>
                        </div>

                    </div>
                    <div class="form-group">
                        <label for="totalPrice">Total Price<br>
                            <em>NOTE: Total Price from the Receipts</em></label>
                        <input type="number" min="1" id="totalPrice" name="totalprice" required step="0.01">
                    </div>
                </div>
                <div class="row">
                    <div class="form-group">
                        <label for="enOjtCert">Certificate of Enrollment or OJT Certificate/Instruction</label>
                        <input type="file" id="enOjtCert" name="certificate"
                            accept="application/pdf, application/msword, application/vnd.openxmlformats-officedocument.wordprocessingml.document, image/jpeg, image/png"
                            required>
                    </div>
                    <div class="form-group">
                        <label for="receipt">Receipt or Acknowledgement Receipt</label>
                        <input type="file" id="receipt" name="acknowledgement"
                            accept="application/pdf, application/msword, application/vnd.openxmlformats-officedocument.wordprocessingml.document, image/jpeg, image/png"
                            required>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group">
                        <label for="proofPic">Picture of the Uniform</label>
                        <input type="file" id="proofPic" name="uniformpic"
                            accept="application/pdf, application/msword, application/vnd.openxmlformats-officedocument.wordprocessingml.document, image/jpeg, image/png"
                            required>
                    </div>
                    <div class="form-group">
                        <label for="liquidation">Liquidation Form</label>
                        <input type="file" id="liquidation" name="liquidation"
                            accept="application/pdf, application/msword, application/vnd.openxmlformats-officedocument.wordprocessingml.document, image/jpeg, image/png"
                            required>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group note">
                        <p class="fw-bold">Note:</p>
                        <ul>
                            <li>Kung maraming picture, I-compile sa iisang pdf or docx ang mga pictures.</li>
                            <li>Siguraduhing malinaw at nakuhanan ang uniform ng maayos.</li>
                        </ul>
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
