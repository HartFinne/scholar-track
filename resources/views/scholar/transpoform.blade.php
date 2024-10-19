<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transportation Request Form</title>
    <link href="https://cdn.lineicons.com/4.0/lineicons.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/spform.css') }}">
    <link rel="stylesheet" href="{{ asset('css/partial.css') }}">
</head>

<body>

    <!-- Include Sidebar -->
    @include('partials._sidebar')

    <!-- Include Navbar -->
    @include('partials._navbar')

    <!-- TRANSPORTATION REIMBURSEMENT FORM -->
    <div class="ctn-main">
        <a href="" class="goback">&lt Go back</a>

        <div class="form">
            <h2 class="title text-center">Transportation Reimbursement Request Form </h2>
            <form action="#" method="post" enctype="multipart/form-data">
                <div class="row">
                    <div class="form-group">
                        <label for="area">Area</label>
                        <input type="text" id="area" name="area" value="MINDONG" disabled>
                    </div>
                    <div class="form-group">
                        <label for="course">Course</label>
                        <input type="text" id="course" name="course" value="BACHELOR OF SCIENCE IN INFORMATION TECHNOLOGY" disabled>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group">
                        <label for="lastName">Last Name</label>
                        <input type="text" id="lastName" name="lastName" value="DELA CRUZ" disabled>
                    </div>
                    <div class="form-group">
                        <label for="firstName">First Name</label>
                        <input type="text" id="firstName" name="firstName" value="JUAN" disabled>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group">
                        <label for="school">School</label>
                        <input type="text" id="school" name="school" value="POLYTECHNIC UNIVERSITY OF THE PHILIPPINES" disabled>
                    </div>
                    <div class="form-group">
                        <label for="yearLevel">Year Level</label>
                        <input type="text" id="yearLevel" name="yearLevel" value="1ST YEAR" disabled>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group">
                        <label for="totalPrice">Total Price</label>
                        <input type="number" id="totalPrice" name="totalPrice" required>
                    </div>
                    <div class="form-group">
                        <label for="purpose">Purpose/Activity</label>
                        <input type="text" id="purpose" name="purpose" required>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group">
                        <label for="assignedStaff">Name of the Assigned Staff or Volunteer</label>
                        <input type="text" id="assignedStaff" name="assignedStaff" required>
                    </div>
                    <div class="form-group">
                        <label for="reimbursementForm">Transportation Reimbursement Form</label>
                        <input type="file" id="reimbursementForm" name="reimbursementForm" required>
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
