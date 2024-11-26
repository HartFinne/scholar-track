<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Request Form</title>
    <link href="https://cdn.lineicons.com/4.0/lineicons.css" rel="stylesheet" />
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

    <!-- BOOK REQUEST FORM -->
    <div class="ctn-main">
        <a href="{{ route('scspecial') }}" class="goback">&lt Go back</a>
        <div class="form">
            <h2 class="title text-center">Book Request Form </h2>
            <form action="{{ route('reqallowancebook', $scholar->caseCode) }}" method="post"
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
                        <label for="bookTitle">Title of the Book</label>
                        <input type="text" id="bookTitle" name="booktitle" maxlength="255" required>
                    </div>
                    <div class="form-group">
                        <label for="author">Author</label>
                        <input type="text" id="author" name="author" maxlength="255" required>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group">
                        <label for="price">Price</label>
                        <input type="number" id="price" name="price" min="1" required step="0.01">
                    </div>
                    <div class="form-group">
                        <label for="bookCert">Book Certification</label>
                        <input type="file" id="bookCert" name="certification"
                            accept="application/pdf, application/msword, application/vnd.openxmlformats-officedocument.wordprocessingml.document, image/jpeg, image/png"
                            required>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group">
                        <label for="receipt">Receipt or Acknowledgement Receipt</label>
                        <input type="file" id="receipt" name="acknowledgement"
                            accept="application/pdf, application/msword, application/vnd.openxmlformats-officedocument.wordprocessingml.document, image/jpeg, image/png"
                            required>
                    </div>
                    <div class="form-group">
                        <label for="proofPic">Proof of Purchase (Picture ng biniling libro)</label>
                        <input type="file" id="proofPic" name="purchaseproof"
                            accept="application/pdf, application/msword, application/vnd.openxmlformats-officedocument.wordprocessingml.document, image/jpeg, image/png"
                            required>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group">
                        <label for="liquidation">Liquidation Form</label>
                        <input type="file" id="liquidation" name="liquidation"
                            accept="application/pdf, application/msword, application/vnd.openxmlformats-officedocument.wordprocessingml.document, image/jpeg, image/png"
                            required>
                    </div>
                    <div class="form-group note">
                        <p class="fw-bold">Note:</p>
                        <ul>
                            <li>Kung maraming picture, I-compile sa iisang pdf or docx ang mga pictures.</li>
                            <li>Siguraduhing malinaw at nakuhanan ang Title at Author ng Libro.</li>
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
