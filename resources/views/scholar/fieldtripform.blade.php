<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Field Trip, Training, Seminar Request Form</title>
    <link href="https://cdn.lineicons.com/4.0/lineicons.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ asset('css/spform.css') }}">
    <link rel="stylesheet" href="{{ asset('css/partial.css') }}">
</head>

<body>
    <!-- Include Sidebar -->
    @include('partials._sidebar')

    <!-- Include Navbar -->
    @include('partials._navbar')

    <!-- FIELD TRIP, TRAINING, SEMINAR REQUEST FORM -->
    <div class="ctn-main">
        <a href="" class="goback">&lt Go back</a>

        <div class="form">
            <h2 class="title text-center">Field Trip, Training, Seminar Request Form </h2>
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
                        <label for="event">Type of Event</label>
                        <select name="event" id="event" >
                            <option value="" selected disabled hidden>Select event</option>
                            <option value="Field Trip">Field Trip</option>
                            <option value="Training">Training</option>
                            <option value="Seminar">Seminar</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="eventLoc">Event Location</label>
                        <input type="text" id="eventLoc" name="eventLoc" required>
                    </div>
                    
                </div>
                <div class="row">
                    <div class="form-group">
                        <label for="totalPrice">Total Price<br>
                            <em>NOTE: Total Price from the Receipts</em></label>
                        <input type="text" id="totalPrice" name="totalPrice" required>
                    </div>
                    <div class="form-group">
                        <label for="memo">Official Memo from Subject Professor <span>(Document or Picture)</span></label>
                        <input type="file" id="memo" name="memo" required>
                    </div>
                </div> 
                <div class="row">
                    <div class="form-group">
                        <label for="waiver">Parent or Guardian Waiver <span>(Picture or Scanned copy)</span></label>
                        <input type="file" id="waiver" name="waiver" required>
                    </div>
                    <div class="form-group">
                        <label for="receipt">Receipt or Acknowledgement Receipt</label>
                        <input type="file" id="receipt" name="receipt" required>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group">
                        <label for="liquidation">Liquidation Form</label>
                        <input type="file" id="liquidation" name="liquidation" required>
                    </div>
                    <div class="form-group note">
                        <p class="fw-bold">Note:</p>
                        <ul>
                            <li>Kung maraming picture, I-compile sa iisang zip or pdf ang mga pictures.</li>
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