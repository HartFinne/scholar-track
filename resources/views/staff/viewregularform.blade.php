<!DOCTYPE html>
<html lang="en">

<head>
    <title>View Request</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link href="{{ asset('css/regularform.css') }}" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
        integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
</head>

<body>
    <!-- PAGE HEADER -->
    @include('partials._pageheader')

    <!-- MAIN -->
    <div class="ctnmain">
        <div class="regular-header">
            <div class="col-md-1 mb-2" style="margin-left: auto;">
                <a href="{{ route('allowancerequests-regular') }}" class="btn btn-success w-100">Go back</a>
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
                </div>
            </div>
        </div>
        
        <div class="regular-form">
            <form action="" method="post">
                <div class="page1">
                    <div class="heading">
                        <p><strong>佛教慈濟慈善事業基金會菲律濱分會<br>
                            Buddhist Compassion Relief Tzu Chi Foundation, Philippines</strong></p>
                    </div>
                    <div class="subheader">
                        <p><strong>Educational Assistance Program</strong></p>
                    </div>
                    <div>
                        <p class="form-title"><strong>ALLOWANCE REQUEST FORM</strong></p>
                        <div class="subtitle">
                            <p>College Department</p>
                            <p>[Semester]<br>[School Year]</p>
                        </div>
                    </div>

                    <div class="table1">
                        <table class="table-bordered">
                            <tr>
                                <td class="gray">Name</td>
                                <td colspan="2">First Name Last Name</td>
                                <td class="gray">Date Submitted</td>
                                <td colspan="2">MM/DD/YYYY</td>
                            </tr>
                            <tr>
                                <td class="gray">School</td>
                                <td colspan="4">--</td>
                            </tr>
                            <tr>
                                <td class="gray">School Category</td>
                                <td colspan="2">--</td>
                                <td class="gray">Contact No.</td>
                                <td colspan="2">--</td>
                            </tr>
                            <tr>
                                <td class="gray">Year Level</td>
                                <td colspan="2">--</td>
                                <td class="gray">Course</td>
                                <td colspan="2">--</td>
                            </tr>
                            <tr>
                                <td class="gray">Home Address</td>
                                <td colspan="2">--</td>
                                <td class="gray">Boarding House Address (If applicable)</td>
                                <td colspan="2">--</td>
                            </tr>
                            <tr>
                                <td class="gray">Start of Semester</td>
                                <td colspan="2">--</td>
                                <td class="gray">End of Semester</td>
                                <td colspan="2">--</td>
                            </tr>
                            <tr>
                                <td class="gray">Start of OJT</td>
                                <td colspan="2">--</td>
                                <td class="gray">End of OJT</td>
                                <td colspan="2">--</td>
                            </tr>
                        </table>
                    </div>
                    <div class="section">
                        <p class="sec-title">CLASS SCHEDULE (REFERENCE ONLY)</p>
                        <p>* Attached the photocopy of the official class schedule from the Registrar (Registration Form)<br>
                            * Attached duly accomplished daily travel itinerary.</p>
                        <div class="table2">
                            <table class="table-bordered">
                                <thead>
                                    <tr class="text-center">
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
                                    <tr class="text-center">
                                        <td>--</td>
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
                    </div>
                    <hr>
                    <div class="section">
                        <p class="sec-title">TO BE FILLED BY TC-CHARITY STAFF</p>
                        <p><em>*Important note: The number of school days per month is 20 regardless 
                            of the scholars' class schedule for the computation of transportation and food allowances. </em>
                        </p>
                        <div class="table3">
                            <table class="table-bordered">
                                <tr class="text-center">
                                    <th colspan="3" class="table-heading">MONTHLY LIVING ALLOWANCES (MLA)</th>
                                </tr>
                                <tr class="text-center">
                                    <th class="table-subheading">ALLOWANCE DESCRIPTION</th>
                                    <th class="table-subheading">COMPUTATION</th>
                                    <th class="table-subheading">TOTAL</th>
                                </tr>
                                <tr>
                                    <td>Transportation</td>
                                    <td><input type="text" name="transpoComputation" placeholder="type here..."></td>
                                    <td><input type="text" name="transpoTotal" placeholder="type here..."></td>
                                </tr>
                                <tr>
                                    <td>Meal</td>
                                    <td><input type="text" name="mealComputation" placeholder="type here..."></td>
                                    <td><input type="text" name="mealTotal" placeholder="type here..."></td>
                                </tr>
                                <tr>
                                    <td>Lodging</td>
                                    <td><input type="text" name="lodgingComputation" placeholder="type here..."></td>
                                    <td><input type="text" name="lodgingTotal" placeholder="type here..."></td>
                                </tr>
                                <tr>
                                    <td>Mobile Data</td>
                                    <td><input type="text" name="mobileComputation" placeholder="type here..."></td>
                                    <td><input type="text" name="mobileTotal" placeholder="type here..."></td>
                                </tr>
                                <tr class="text-center">
                                    <th colspan="3" class="table-heading">SPECIAL ALLOWANCES (SA)</th>
                                </tr>
                                <tr>
                                    <td>Book</td>
                                    <td><input type="text" name="bookComputation" placeholder="type here..."></td>
                                    <td><input type="text" name="bookTotal" placeholder="type here..."></td>
                                </tr>
                                <tr>
                                    <td>Thesis</td>
                                    <td><input type="text" name="thesisComputation" placeholder="type here..."></td>
                                    <td><input type="text" name="thesisTotal" placeholder="type here..."></td>
                                </tr>
                                <tr>
                                    <td>Project</td>
                                    <td><input type="text" name="projectComputation" placeholder="type here..."></td>
                                    <td><input type="text" name="projectTotal" placeholder="type here..."></td>
                                </tr>
                                <tr>
                                    <td>Uniform</td>
                                    <td><input type="text" name="uniformComputation" placeholder="type here..."></td>
                                    <td><input type="text" name="uniformTotal" placeholder="type here..."></td>
                                </tr>
                                <tr>
                                    <td>Graduation Fee</td>
                                    <td><input type="text" name="gradComputation" placeholder="type here..."></td>
                                    <td><input type="text" name="gradTotal" placeholder="type here..."></td>
                                </tr>
                                <tr>
                                    <td>Field Trips, Trainings, and Seminars</td>
                                    <td><input type="text" name="fieldtripComputation" placeholder="type here..."></td>
                                    <td><input type="text" name="fieldtripTotal" placeholder="type here..."></td>
                                </tr>
                                <tr>
                                    <td>Summer Tuition and Allowance</td>
                                    <td><input type="text" name="summerComputation" placeholder="type here..."></td>
                                    <td><input type="text" name="summerTotal" placeholder="type here..."></td>
                                </tr>
                                <tr>
                                    <td>On-the-Job or Practicum Allowance</td>
                                    <td><input type="text" name="ojtComputation" placeholder="type here..."></td>
                                    <td><input type="text" name="ojtTotal" placeholder="type here..."></td>
                                </tr>
                                <tr>
                                    <td colspan="2" style="text-align: center;">TOTAL</td>
                                    <td><input type="text" name="total" placeholder="type here..."></td>
                                </tr>
                            </table>
                        </div>
                        <div class="reminder">
                            <p style="text-decoration: underline;">Reminder:</p>
                            <p>1. Students who are on OJT must attach a photocopy of the endorsement 
                                as well as the letter of acceptance from the company. </p>
                        </div>
                        <div class="table4">
                            <table class="table-bordered">
                                <tr>
                                    <td>Requested and submitted by:</td>
                                    <td>Noted by:</td>
                                </tr>
                                <tr>
                                    <td style="text-align: center;">[Scholar Name]</td>
                                    <td style="text-align: center;">[Staff Name]</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="row mb-2">
                    <button type="submit" class="col-md-1 btn btn-success mx-auto">Save</button>
                </div>        
            </form>
            

            <div class="page2">
                <div class="heading">
                    <p><strong>佛教慈濟慈善事業基金會菲律濱分會<br>
                        Buddhist Compassion Relief Tzu Chi Foundation, Philippines</strong></p>
                </div>
                <p class="sec-title" style="text-align: center;">DAILY TRAVEL ITINERARY FORM (FROM HOUSE TO SCHOOL AND VICE VERSA)</p>
                <div class="table5">
                    <table class="table-bordered">
                        <tr class="header">
                            <td colspan="2">Destination (Vice Versa)</td>
                            <td rowspan="2">Estimated Travel Time</td>
                            <td rowspan="2">Type of Vehicle</td>
                            <td rowspan="2">Student Fare Rate</td>
                        </tr>
                        <tr class="header">
                            <td style="width: 150px;">From</td>
                            <td style="width: 150px;">To</td>
                        </tr>
                        <tr>
                            <td>--</td>
                            <td>--</td>
                            <td>--</td>
                            <td>--</td>
                            <td>--</td>
                        </tr>
                        <tr>
                            <td colspan="4" style="text-align: center;">Total Costs per day</td>
                            <td>--</td>
                        </tr>
                    </table>
                </div>

                <p class="sec-title" style="text-align: center;">LODGING INFORMATION</p>
                <div class="table6">
                    <table class="table-bordered">
                        <tr>
                            <td style="width: 200px;">Name of owner/landlady/landlord</td>
                            <td>--</td>
                        </tr>
                        <tr>
                            <td>Contact number</td>
                            <td>--</td>
                        </tr>
                        <tr>
                            <td>Monthly rent</td>
                            <td>--</td>
                        </tr>
                        <tr>
                            <td>Type of lodging</td>
                            <td>--</td>
                        </tr>
                    </table>
                </div>

                <p class="sec-title" style="text-align: center;">OJT DAILY TRAVEL ITINERARY FORM (FROM HOUSE TO ASSIGNED COMPANY AND VICE VERSA)</p>
                <div class="table7">
                    <table class="table-bordered">
                        <tr class="header">
                            <td colspan="2">Destination (Vice Versa)</td>
                            <td rowspan="2">Estimated Travel Time</td>
                            <td rowspan="2">Type of Vehicle</td>
                            <td rowspan="2">Student Fare Rate</td>
                        </tr class="header">
                        <tr class="header">
                            <td style="width: 150px;">From</td>
                            <td style="width: 150px;">To</td>
                        </tr>
                        <tr>
                            <td>--</td>
                            <td>--</td>
                            <td>--</td>
                            <td>--</td>
                            <td>--</td>
                        </tr>
                        <tr>
                            <td colspan="4" style="text-align: center;">Total Costs per day</td>
                            <td>--</td>
                        </tr>
                    </table>
                </div>
                <p><em>I HEREBY CERTIFY that the information provided in this form is complete, true, 
                    and correct to the best of my knowledge.</em></p>
                <div class="signature">
                    <div class="group1">
                        <p>Submitted by:</p>
                        <div class="sign">
                            <p class="name">[Scholar Name]</p>
                            <p>Scholar</p>
                        </div>
                    </div>
                    <div class="group2">
                        <p>Noted by:</p>
                        <div class="sign">
                            <p class="name">[Staff Name]</p>
                            <p>Assigned Tzu Chi staff</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="file-info bg-light">
                <div class="info">
                    <div class="label">Registration Form</div>
                    <div class="value">: <span>file</span></div>
                    
                    <div class="label">Photocopy of Endorsement</div>
                    <div class="value">: <span>file</span></div>
                    
                    <div class="label">Letter of Acceptance</div>
                    <div class="value">: <span>file</span></div>
                </div>
            </div>
        </div>
        
    </div>
    
    <script src="{{ asset('js/headercontrol.js') }}"></script>
</body>

</html>