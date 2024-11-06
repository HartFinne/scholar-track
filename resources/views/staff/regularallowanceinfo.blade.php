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
    <x-alert />

    <div class="ctnmain">
        <div class="container mt-5">
            <div class="col-md-1 mb-2" style="margin-left: auto;">
                <a href="{{ route('allowancerequests-regular') }}" class="btn btn-success w-100">Go back</a>
            </div>
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
                    <h4 class="mb-0"><strong>REGULAR ALLOWANCES REQUEST</strong></h4>
                </div>
                <div class="card-body">
                    <form method="POST" action="#">
                        <div class="mb-3 row">
                            <label class="col-sm-4 col-form-label"><strong>Update Request Status</strong></label>
                            <div class="col-sm-8">
                                <select name="status" class="form-select">
                                    <option value="Pending">Pending</option>
                                    <option value="Accepted">Accepted</option>
                                    <option value="Rejected">Rejected</option>
                                    <option value="Completed">Completed</option>
                                </select>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label class="col-sm-4 col-form-label"><strong>Set Release Date</strong></label>
                            <div class="col-sm-8">
                                <input type="date" name="releasedate" class="form-control" min="2024-11-03">
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
                            <p class="form-control-plaintext">: March 10, 2023</p>
                        </div>
                    </div>

                    <!-- SCHOLAR INFO -->
                    <div class="row mb-3">
                        <label class="col-sm-4 col-form-label">Area</label>
                        <div class="col-sm-8">
                            <p class="form-control-plaintext">: Sample Area</p>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-4 col-form-label">Name</label>
                        <div class="col-sm-8">
                            <p class="form-control-plaintext">: Doe, John A.</p>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-4 col-form-label">School</label>
                        <div class="col-sm-8">
                            <p class="form-control-plaintext">: Sample University</p>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-4 col-form-label">Year Level</label>
                        <div class="col-sm-8">
                            <p class="form-control-plaintext">: Third Year</p>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-4 col-form-label">Course</label>
                        <div class="col-sm-8">
                            <p class="form-control-plaintext">: Computer Science</p>
                        </div>
                    </div>

                    <!-- REQUEST INFO -->
                    <div class="row mb-3">
                        <label class="col-sm-4 col-form-label">Class Schedule</label>
                        <div class="col-sm-8">
                            <a href="#" target="_blank">View File</a>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <table>
                            <thead>
                                <tr>
                                    <th>TIME</th>
                                    <th>MON</th>
                                    <th>TUE</th>
                                    <th>WED</th>
                                    <th>THU</th>
                                    <th>FRI</th>
                                    <th>SAT</th>
                                    <th>SUN</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>8:00 AM - 9:00 AM</td>
                                    <td>Math</td>
                                    <td>Science</td>
                                    <td>History</td>
                                    <td>English</td>
                                    <td>PE</td>
                                    <td>--</td>
                                    <td>--</td>
                                </tr>
                                <tr>
                                    <td>9:00 AM - 10:00 AM</td>
                                    <td>--</td>
                                    <td>--</td>
                                    <td>--</td>
                                    <td>--</td>
                                    <td>--</td>
                                    <td>--</td>
                                    <td>--</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Travel Itinerary -->
                    <label class="col-sm-4 col-form-label">Travel Itinerary</label>
                    <div class="row mb-3">
                        <table>
                            <thead>
                                <tr>
                                    <th>From</th>
                                    <th>To</th>
                                    <th>Estimated Travel Time</th>
                                    <th>Type of Vehicle</th>
                                    <th>Student Fare Rate</th>
                                    <th>Total Costs per day</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>City A</td>
                                    <td>City B</td>
                                    <td>1 hour</td>
                                    <td>Bus</td>
                                    <td>$2.00</td>
                                    <td>$2.00</td>
                                </tr>
                                <tr>
                                    <td>City B</td>
                                    <td>City C</td>
                                    <td>30 mins</td>
                                    <td>Train</td>
                                    <td>$1.50</td>
                                    <td>$1.50</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Lodging Info -->
                    <label class="col-sm-4 col-form-label">Lodging Info</label>
                    <div class="row mb-3">
                        <label class="col-sm-4 col-form-label">Owner Name</label>
                        <div class="col-sm-8">
                            <p class="form-control-plaintext">: John Doe</p>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-4 col-form-label">Contact number</label>
                        <div class="col-sm-8">
                            <p class="form-control-plaintext">: 123-456-7890</p>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-4 col-form-label">Monthly rent</label>
                        <div class="col-sm-8">
                            <p class="form-control-plaintext">: $300</p>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-4 col-form-label">Type of lodging</label>
                        <div class="col-sm-8">
                            <p class="form-control-plaintext">: Apartment</p>
                        </div>
                    </div>

                    <!-- OJT Travel Itinerary Info -->
                    <label class="col-sm-4 col-form-label">OJT Travel Itinerary Info</label>
                    <div class="row mb-3">
                        <label class="col-sm-4 col-form-label">Photocopy of Endorsement</label>
                        <div class="col-sm-8">
                            <a href="#" target="_blank">View File</a>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-4 col-form-label">Letter of Acceptance</label>
                        <div class="col-sm-8">
                            <a href="#" target="_blank">View File</a>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <table>
                            <thead>
                                <tr>
                                    <th>From</th>
                                    <th>To</th>
                                    <th>Estimated Travel Time</th>
                                    <th>Type of Vehicle</th>
                                    <th>Student Fare Rate</th>
                                    <th>Total Costs per day</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>City D</td>
                                    <td>City E</td>
                                    <td>45 mins</td>
                                    <td>Taxi</td>
                                    <td>$5.00</td>
                                    <td>$5.00</td>
                                </tr>
                                <tr>
                                    <td>City E</td>
                                    <td>City F</td>
                                    <td>1.5 hours</td>
                                    <td>Bus</td>
                                    <td>$3.50</td>
                                    <td>$3.50</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="{{ asset('js/headercontrol.js') }}"></script>
</body>

</html>
