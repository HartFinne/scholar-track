<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EAP Application System</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/appinstructions.css') }}">
</head>
<body>
    <div class="header">
        <div class="logo">
            <img src="{{ asset('images/logo.png') }}" alt="" class="logo">
            <div class="text">
                <h2>TZU CHI PHILIPPINES</h2>
                <h3>Educational Assistance Program</h3>
            </div>
        </div>
        <a href="">
            <button class="btn-back"><strong><i class="fa-solid fa-chevron-left"></i> Go back</strong></button>
        </a>
    </div>

    <div class="eap-details">
        <p>The Tzu Chi Foundation opened its program to poor but deserving College, High School, and Elementary School Students. 
            It aims to take part in the efforts to eradicate the growing number of out-of-school children and youth in the country. 
            The Foundation never expects repayment from its scholars, but hopes that they value education, become good citizens, and contribute to nation-building. 
            Scholars even receive a monthly school allowance to cover some of the costs of education.<br><br>

            Each education level has specific requirements and instructions to guide you through the application process. 
            To know more about how to apply for a scholarship, please select the appropriate education level you are in:</p>
    </div>
    
    <div class="box-container">
        <div class="box">
           <div class="heading"><h4>College Scholarship</h4> <i class="fa-solid fa-chevron-right"></i></div>
           <div class="content">
                <h5>Who can apply?</h5>
                <p>The scholarship application is open to all <b>incoming 2nd year college ONLY</b> and must be taking up a course that is in the following:</p>
                <div class="college-courses">
                    <div class="course1">
                        <ul>
                            <li>Bachelor of Science in Information Technology</li>
                            <li>Engineering Courses (Architecture, Mechanical, Electrical, Electronics, etc.)</li>
                            <li>Bachelor of Science in Social Work</li>
                            <li>Bachelor of Science in Accountancy</li>
                            <li>Bachelor of Science in Education</li>
                            <li>Bachelor of Arts in Journalism</li>
                            <li>Bachelor of Science in Agriculture</li>
                        </ul>
                    </div>

                    <div class="course2">
                        <ul>
                            <li>Bachelor of Science in Physical Therapy</li>
                            <li>Bachelor of Science in Medical Technology</li>
                            <li> Bachelor of Science in Radiologic Technology</li>
                            <li>Bachelor of Science in Business</li>
                            <li>Bachelor of Science in Business
                                Administration, Major in the following:
                                <ul>
                                    <li>Financial Management</li>
                                    <li>Management</li>
                                    <li>Economics</li>
                                </ul>
                            </li>
                            
                        </ul>
                    </div>
                </div>

                <h5>What are the qualifications?</h5>
                <ul>
                    <li>Must belong to an INDIGENT or ECONOMICALLY CHALLENGED family.</li>
                    <li>Metro Manila Residents ONLY</li>
                    <li>Must have a GWA of <span id="crequiredGwa">2.25 (82%)</span> and above with no failing grades in any subject.</li>
                    <li>With Good Moral Character.</li>
                    <li>Must be enrolled in partner State University, Colleges, or partner Chinese Schools.</li>
                </ul>

                <h5>Partner State University and Colleges:</h5>
                <div class="universities">
                    <div class="uni">
                        <img src="{{ asset('images/plm.png') }}" alt="Pamantasan ng Lungsad ng Maynila">
                        <p>Pamantasan ng Lungsad ng Maynila</p>
                    </div>
                    <div class="uni">
                        <img src="{{ asset('images/pnu.png') }}" alt="Philippine Normal University">
                        <p>Philippine Normal University</p>
                    </div>
                    <div class="uni">
                        <img src="{{ asset('images/pup.png') }}" alt="Polytechnic University of the Philippines">
                        <p>Polytechnic University of the Philippines</p>
                    </div>
                    <div class="uni">
                        <img src="{{ asset('images/tup.png') }}" alt="Technological University of the Philippines">
                        <p>Technological University of the Philippines</p>
                    </div>
                    <div class="uni">
                        <img src="{{ asset('images/udm.png') }}" alt="Universidad De Manila">
                        <p>Universidad De Manila</p>
                    </div>
                    <div class="uni">
                        <img src="{{ asset('images/up.png') }}" alt="University of the Philippines">
                        <p>University of the Philippines</p>
                    </div>
                </div>

                <h5>Documents to prepare prior to filing your Scholarships Application Form:</h5>
                <ul>
                    <li>Scanned copy of latest Report Card</li>
                    <li>Scanned copy of latest Registration Form</li>
                    <li>1x1 inch ID Picture (Format: JPG or JPEG)</li>
                    <li>Autobiography</li>
                    <li>Family Picture (Format: JPG or JPEG)</li>
                    <li>Picture of the inside and outside of the house (Format: JPG or JPEG)</li>
                    <li>Scanned copy of latest Utility Bills</li>
                    <li>Scanned copy of latest ITR/ Official Pay Slip of parent/s (if applicable)</li>
                    <li>Scanned copy of Barangay Certificate of Indigency</li>
                    <li>Scanned copy of Detailed Sketch of Home Address. <span class="sketchinfo">Please draw your exact location 
                        legibly and indicate name of landmarks. Eq. name of street, church, sari-sari store, etc.</span></li>
                </ul>

                <h5>Application Process:</h5>
                <ul class="app-process">
                    <li><strong>STEP 01:</strong> Fill out the <a href="{{ route('form-college') }}">Application Form.</a></li>
                    <li><strong>STEP 02:</strong> Submit the complete required documents.</li>
                    <li><strong>STEP 03:</strong> Initial Interview</li>
                    <li><strong>STEP 04:</strong> Panel Interview</li>
                    <li><strong>STEP 05:</strong> Virtual Home Visit</li>
                    <li><strong>STEP 06:</strong> Education Committee Deliberation</li>
                    <li><strong>STEP 07:</strong> Education Committee Decision</li>
                </ul>
           </div>
        </div>
     
        <div class="box">
           <div class="heading"><h4>High School Scholarship</h4> <i class="fa-solid fa-chevron-right"></i></div>
           <div class="content">
                <h5>Who can apply?</h5>

                <h5>What are the qualifications?</h5>
                <ul>
                    <li>Must belong to an INDIGENT or ECONOMICALLY CHALLENGED family.</li>
                    <li>Must have a GWA of <span id="hrequiredGwa">82%</span> and above with no failing grades in any subject.</li>
                    <li>With Good Moral Character.</li>
                    <li>Must be enrolled in partner State University, Colleges, or partner Chinese Schools.</li>
                </ul>

                <h5>Partner Schools:</h5>
                <div class="universities">
                    <div class="uni">
                        <img src="{{ asset('images/plm.png') }}" alt="Pamantasan ng Lungsad ng Maynila">
                        <p>Pamantasan ng Lungsad ng Maynila</p>
                    </div>
                    
                </div>

                <h5>Documents to prepare prior to filing your Scholarships Application Form:</h5>
                <ul>
                    <li>Scanned copy of latest Report Card</li>
                    <li>Scanned copy of latest Registration Form</li>
                    <li>1x1 inch ID Picture (Format: JPG or JPEG)</li>
                    <li>Autobiography</li>
                    <li>Family Picture (Format: JPG or JPEG)</li>
                    <li>Picture of the inside and outside of the house (Format: JPG or JPEG)</li>
                    <li>Scanned copy of latest Utility Bills</li>
                    <li>Scanned copy latest ITR/ Official Pay Slip of parent/s (if applicable)</li>
                    <li>Barangay Certificate of Indigency</li>
                    <li>Scanned copy of Detailed Sketch of Home Address. <span class="sketchinfo">Please draw your exact location 
                        legibly and indicate name of landmarks. Eq. name of street, church, sari-sari store, etc.</span></li>
                </ul>

                <h5>Application Process:</h5>
                <ul class="app-process">
                    <li><strong>STEP 01:</strong> Fill out the <a href="{{ route('form-hselem') }}">Application Form.</a></li>
                    <li><strong>STEP 02:</strong> Submit the complete required documents.</li>
                    <li><strong>STEP 03:</strong> Initial Interview</li>
                    <li><strong>STEP 04:</strong> Panel Interview</li>
                    <li><strong>STEP 05:</strong> Virtual Home Visit</li>
                    <li><strong>STEP 06:</strong> Education Committee Deliberation</li>
                    <li><strong>STEP 07:</strong> Education Committee Decision</li>
                </ul>
            </div>
        </div>
     
        <div class="box">
           <div class="heading"><h4>Elementary Scholarship</h4> <i class="fa-solid fa-chevron-right"></i></div>
            <div class="content">
                <h5>Who can apply?</h5>

                <h5>What are the qualifications?</h5>
                <ul>
                    <li>Must belong to an INDIGENT or ECONOMICALLY CHALLENGED family.</li>
                    <li>Must have a <span id="erequiredGwa">82%</span> and above with no failing grades in any subject.</li>
                    <li>With Good Moral Character.</li>
                    <li>Must be enrolled in partner State University, Colleges, or partner Chinese Schools.</li>
                </ul>

                <h5>Partner Schools:</h5>
                <div class="universities">
                    <div class="uni">
                        <img src="{{ asset('images/plm.png') }}" alt="Pamantasan ng Lungsad ng Maynila">
                        <p>Pamantasan ng Lungsad ng Maynila</p>
                    </div>
                    
                </div>

                <h5>Documents to prepare prior to filing your Scholarships Application Form:</h5>
                <ul>
                    <li>Scanned copy of latest Report Card</li>
                    <li>Scanned copy of latest Registration Form</li>
                    <li>1x1 inch ID Picture (Format: JPG or JPEG)</li>
                    <li>Autobiography</li>
                    <li>Family Picture (Format: JPG or JPEG)</li>
                    <li>Picture of the inside and outside of the house (Format: JPG or JPEG)</li>
                    <li>Scanned copy of latest Utility Bills</li>
                    <li>Scanned copy latest ITR/ Official Pay Slip of parent/s (if applicable)</li>
                    <li>Barangay Certificate of Indigency</li>
                    <li>Scanned copy of Detailed Sketch of Home Address. <span class="sketchinfo">Please draw your exact location 
                        legibly and indicate name of landmarks. Eq. name of street, church, sari-sari store, etc.</span></li>
                </ul>

                <h5>Application Process:</h5>
                <ul class="app-process">
                    <li><strong>STEP 01:</strong> Fill out the <a href="{{ route('form-hselem') }}">Application Form.</a></li>
                    <li><strong>STEP 02:</strong> Submit the complete required documents.</li>
                    <li><strong>STEP 03:</strong> Initial Interview</li>
                    <li><strong>STEP 04:</strong> Panel Interview</li>
                    <li><strong>STEP 05:</strong> Virtual Home Visit</li>
                    <li><strong>STEP 06:</strong> Education Committee Deliberation</li>
                    <li><strong>STEP 07:</strong> Education Committee Decision</li>
                </ul>
            </div>
        </div>
    </div>

    <p class="applicant-login">Already an Applicant? Click <a href="">here</a> to login to your account.</p>

    <footer>
        <p>For further inquiries, please call: <strong>8761-6427</strong> or <strong>0908-764-4245</strong>.</p>
        <p>You can also send us a message on our Facebook Account at <a href="https://www.facebook.com/scholars.tzuchi/" target="_blank">Tzu Chi Scholars</a>
           or send us an e-mail at <a href="mailto:social.services@tzuchi.org.ph">social.services@tzuchi.org.ph</a></p>
    </footer>

    <script src="{{ asset('js/applicant.js') }}"></script>
</body>
</html>