<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Application Form</title>
    <link rel="stylesheet" href="{{ asset('css/appformview.css') }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
</head>

<body>
    <div class="ctn-main">
        <div class="appform-view">
            <div class="page1">
                <div class="header">
                    <img src="/imgs/logo.png" alt="Logo">
                    <p><strong>佛教慈濟慈善事業基金會菲律濱分會<br>Buddhist Compassion Relief Tzu Chi Foundation, Philippines</strong></p>
                </div>
                <div class="app-title">
                    <p>Educational Assistance Program</p>
                    <p><strong>APPLICATION FORM</strong></p>
                    <p id="schoolYear">S.Y. 2024-2025</p>
                </div>
                <div class="personal-info">
                    <div class="psec1">
                        <p class="sec-title">PERSONAL INFORMATION</p>
                        <div class="psec1-info">
                            <p>Status: <span><strong>New</strong></span></p>
                            <p>Case Code: <span id="caseCode"><strong>--</strong></span></p>
                            <p>Form No.: <span id="formNo"><strong>--</strong></span></p>
                        </div>
                    </div>
                    <div class="psec2">
                        <div class="table1">
                            <table class="table table-bordered" id="table">
                                <tr>
                                    <td colspan="2">
                                        <span class="flabel">Name: (Last Name, First Name, Middle Name)</span><br>
                                        <span class="fvalue" id="fullName">--</span>
                                    </td>
                                    <td colspan="2">
                                        <span class="flabel">Chinese Name</span><br>
                                        <span class="fvalue" id="cName">--</span>
                                    </td>                               
                                    <td>
                                        <span class="flabel">Gender</span><br>
                                        <span class="fvalue" id="gender">--</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2">
                                        <span class="flabel">Home Address (House #/Unit #/Floor/Bldg. Name/Street Name)</span><br>
                                        <span class="fvalue" id="resAddress">--</span>
                                    </td>
                                    <td>
                                        <span class="flabel">Barangay</span><br>
                                        <span class="fvalue" id="brgy">--</span>
                                    </td>
                                    <td>
                                        <span class="flabel">City</span><br>
                                        <span class="fvalue" id="city">--</span>
                                    </td>
                                    <td>
                                        <span class="flabel">Age</span><br>
                                        <span class="fvalue" id="age">--</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <span class="flabel">Email address</span><br>
                                        <span class="fvalue" id="email">--</span>
                                    </td>
                                    <td>
                                        <span class="flabel">Occupation and Income</span><br>
                                        <span class="fvalue" id="occupation">--</span>,
                                        <span class="fvalue" id="income">--</span>
                                    </td>
                                    <td colspan="2">
                                        <span class="flabel">Cellphone No./Landline</span><br>
                                        <span class="fvalue" id="phoneNum">--</span>
                                    </td>
                                    <td>
                                        <span class="flabel">Birthdate</span><br>
                                        <span class="fvalue" id="birthDate">--</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <span class="flabel">Facebook name</span><br>
                                        <span class="fvalue" id="fbName">--</span>
                                    </td>
                                    <td colspan="4">
                                        <span class="flabel">Are you a member of any indigenous group?</span><br>
                                        <span class="fvalue" id="indigenous">--</span>                                                               
                                    </td>
                                </tr>
                            </table>
                            
                        </div>
                        <img src="imgs/profilepic.png" alt="">
                    </div>               
                </div>
    
                <div class="educbg">
                    <div class="esec1">
                        <p class="sec-title">EDUCATIONAL BACKGROUND</p>
                    </div>
                    <div class="educbg-info">
                        <div class="college">
                            <p>COLLEGE</p>
                            <div class="esec2">
                                <div class="elabel">Name of University: <span id="university">Polytechnic University of the Philippines</span></div>                                      
                                <div class="elabel">College Department:<span id="collegeDept">--</span></div>                             
                                <div class="elabel">Incoming Year Level:<span id="yrLevel">--</span></div>                                      
                                <div class="elabel">Course:<span id="course">--</span></div>                                       
                                <div class="elabel">General Average Last Sem:<span id="gwa">--</span></div>                          
                            </div>
                        </div>
                        <div class="college">
                            <p>ELEMENTARY/HIGH SCHOOL</p>
                            <div class="esec2">
                                <div class="elabel">Name of Elementary/High School: <span id="school">--</span></div>                   
                                
                                <div class="group">
                                    <div class="groupa">
                                        <div class="elabel">Incoming Year Level:<span id="yrLevel">--</span></div>
                                        <div class="elabel">Section:<span id="section">--</span></div> 
                                        <div class="elabel">General Average:<span id="gwa">--</span></div>
                                    </div>
                                    <div class="groupb">
                                        <div class="elabel">Chinese Subject</div> 
                                        <div class="elabel">General Average:<span id="gwa">--</span></div>           
                                        <div class="elabel">Conduct:<span id="conduct">--</span></div>
                                    </div>
                                </div>
                                <div class="elabel">Conduct:<span id="conduct">--</span></div>
                                <div class="elabel">Strand:<span id="gwa">--</span></div>
                            </div>
                        </div>
                    </div>
                    
                    
                </div>
    
                <div class="fam-info">
                    <p class="sec-title fam">FAMILY INFORMATION</p>
                    <div class="table1">
                        <table class="table table-bordered" id="table">
                            <thead>
                                <tr>
                                    <th style="width: 150px;">Name <br><span>(Last Name, First Name)</span></th>
                                    <th>Age</th>
                                    <th>Sex</th>
                                    <th>Birtdate <br><span></span></th>
                                    <th>Relationship</th>
                                    <th>Religion</th>
                                    <th>Educational Attainment</th>
                                    <th>School/ Occupation</th>
                                    <th>Company</th>
                                    <th>Income</th>
                                </tr>
                            </thead>
                            <tbody id="familyTableBody">
                                <tr>
                                    <td id="fName">Last Name, First Name</td>
                                    <td id="fAge">--</td>
                                    <td id="fSex">--</td>
                                    <td id="fBirthdate">MM/DD/YYYY</td>
                                    <td id="fRelationship">--</td>
                                    <td id="fReligion">--</td>
                                    <td id="fAttainment">--</td>
                                    <td id="fSchoolOcc">--</td>
                                    <td id="fCompany">--</td>
                                    <td id="fIncome">--</td>                           
                                </tr>  
                                                    
                            </tbody>
                        </table>
                    </div>
                </div>
    
                <div class="other-info">
                    <p>Grant/Assistance from other Government and Non-Government scholarships, School Discount (How much per sem?)</p>
                    <span id="grant">Lorem ipsum dolor sit amet consectetur adipisicing elit. Debitis quisquam commodi fugit 
                        perferendis delectus nam in quia ipsam architecto ea, qui iste maiores illo nisi inventore eos aperiam. Quo, exercitationem?</span>
                    <p>Talents & Skills/ Honor and Recognition/ Extracurricular/Community Involvement/Employment</p>
                    <span id="talents">--</span>
                    <p>What are your expectations from Tzu Chi Foundation?</p>
                    <span id="expectations">--</span>
                </div>
    
                <div class="signature">
                    <p>I hereby attest that the information I have provided is true and correct. I also consents Tzu Chi Foundation to obtain and retain my personal information for the purpose of this application.</p>
                    <div>
                        <p id="nameDate">--</p>
                        <p class="sign">Applicant's Signature over Printed Name and Date</p>
                    </div>
                </div>
            </div>
    
            <div class="page2">
                <div class="header">
                    <img src="/imgs/logo.png" alt="Logo">
                    <p><strong>佛教慈濟慈善事業基金會菲律濱分會<br>Buddhist Compassion Relief Tzu Chi Foundation, Philippines</strong></p>
                </div>
                <h2>Educational Assistance Application Form</h2>
                <div class="sketchome">
                    <p class="sec-title">SKETCH OF HOME ADDRESS</p>
                    <div class="sketchimg"><img src="" alt=""></div>
                </div>
                <div class="instructions">
                    <ol>
                        <li>
                            The objective of Tzu Chi scholarship program is to provide financial assistance to deserving students through tuition fee support,   
                            monthly living allowance, as well as additional assistance for other school needs, should it be deemed necessary. College students 
                            are only authorized to enroll in partner schools and authorized courses.
                        </li>
                        <li>
                            Students with a failing grade on any subject, with <strong>general weighted average 82% both English and Chinese</strong> or with a grade on
                            <strong>Conduct below B</strong> or with scholarship grant from other foundations or organizations will not be accepted.
                        </li>
                        <li>Please fill up the Scholarship Application form completely and Draw Sketch of home address use the back page if necessary. Any
                            misleading information may lead to disqualification.
                        </li>
                        <li>
                            Submit the following: <span id="reqs">Photocopy of Report Card or copy of grade form last school year, Registration form, Accomplished Application   
                                Form, Two (2) 1x1 ID Pictures, Autobiography, Family Picture, Copies of Utility Bills, Detailed Sketch of Home Address, Certificate  
                                of indigence from the Barangay, Pictures of House (Inside and outside), Payslip or Income Tax Return of Both Parents (if working).</span> 
                            <strong>Deadline of Submission is on</strong> <span id="deadline">DEADLINE</span>.
                        </li>
                        <li>
                            Late applicants will not be entertained.
                        </li>
                        <li>
                            All applications are subject for home visit and approval.
                        </li>
                        <li>
                            The applicants will be notified on the acceptance or rejection of the application.
                        </li>
                        <li>
                            For inquiries, you may contact the Educational Assistance Program Staff with number 09953066694.
                        </li>
                    </ol>
                </div>
                <div class="fillstaff">
                    <div class="heading">
                        <p>To be filled up by TZU CHI FOUNDATION</p>
                        <p>Case Details</p>
                    </div>
                </div>
                <div class="table3">
                    <table class="table table-bordered" id="table">
                        <tr>
                            <td>
                                <span class="slabel">Nature of Needs</span><br>
                                <span class="svalue" id="natureOfneeds">
                                <input type="radio" id="financial" name="needs">
                                    <label for="financial">Financial</label><br>
                                    <input type="radio" id="medical" name="needs">
                                    <label for="medical">Medical</label><br>
                                    <input type="radio" id="food" name="needs">
                                    <label for="food">Food</label><br>                                    
                                    <input type="radio" id="material" name="needs">
                                    <label for="material">Material</label><br>                                    
                                    <input type="radio" id="educ" name="needs">
                                    <label for="educ"> Education</label><br>                                                                       
                                    <input type="radio" id="others" name="needs">
                                    <label for="others"> Others</label><br>
                                </span>
                            </td>
                            <td style="width: 600px;">
                                <span class="slabel"><strong>Problem Statement</strong></span><br>
                                <textarea name="probStatement" id="" rows="5" cols="6" placeholder="Type here..." required></textarea>
                            </td>                               
                        </tr>
                    </table>
                </div>
                <div class="group">
                    <div class="groupa">
                        <div class="slabel">Received By: <input type="text" name="receivedBy" required></div>
                        <div class="slabel">Date Received: <input type="date" name="dateReceived" required></div> 
                        <div class="slabel">Assigned District: <input type="text" name="assignedDistrict" required></div>
                        <div class="slabel">Assigned Volunteer: <input type="text" name="assignedVol" required></div>
                    </div>
                    <div class="groupb">
                        <p class="note">Note: Important information are essential to be able to assess and evaluate students' situation. 
                            Also please note significant details for home visitation purposes. You may use the back page if necessary.</p>
                        
                        <div class="groupb1">
                            <div class="group1">
                                <div class="slabel">Case Referred By: <input type="text" name="referredBy" required></div> 
                                <div class="slabel">Referral Contact no.: <input type="tel" name="refContactNo" required></div>           
                                <div class="slabel">Relationship with Beneficiary: <input type="text" name="relationship" required></div>
                            </div>
                            <div class="group2">
                                <div class="slabel">Applicant's Signature: <span></span></div>           
                                <div class="slabel">Date Reported: <input type="date" name="dateReported" required></div>
                            </div>
                        </div>
                    </div>
                </div>
    
            </div>
    
        </div>
    </div>
</body>
</html>