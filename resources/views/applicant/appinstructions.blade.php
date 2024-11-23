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
            <a href="{{ route('mainhome') }}" class="btn-back" style="text-decoration: none"><strong><i
                        class="fa-solid fa-chevron-left"></i> Go
                    back</strong></a>
        </a>
    </div>

    <div class="eap-details">
        <p>The Tzu Chi Foundation opened its program to poor but deserving College, High School, and Elementary School
            Students.
            It aims to take part in the efforts to eradicate the growing number of out-of-school children and youth in
            the country.
            The Foundation never expects repayment from its scholars, but hopes that they value education, become good
            citizens, and contribute to nation-building.
            Scholars even receive a monthly school allowance to cover some of the costs of education.<br><br>

            Each education level has specific requirements and instructions to guide you through the application
            process.
            To know more about how to apply for a scholarship, please select the appropriate education level you are in:
        </p>
    </div>
    <div class="box-container">
        {{-- COLLEGE --}}
        <div class="box">
            <div class="heading">
                <h4>College Scholarship</h4> <i class="fa-solid fa-chevron-right"></i>
            </div>
            <div class="content">
                <h5>Who can apply?</h5>
                {!! $instruction['College']->applicants !!}
                <h5>Applicants must be enrolled in one of these approved courses:</h5>
                <div class="row">
                    <ul>
                        @foreach ($courses as $course)
                            <li>{{ $course->coursename }}</li>
                        @endforeach
                    </ul>
                </div>

                <h5>What are the qualifications?</h5>
                {!! $instruction['College']->qualifications !!}

                <h5>Partner State University and Colleges:</h5>
                <div class="row">
                    <ul>
                        @foreach ($institutions['College'] as $school)
                            <li>{{ $school->schoolname }}</li>
                        @endforeach
                    </ul>
                </div>
                {{-- <div class="universities">
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
                </div> --}}

                <h5>Documents to prepare prior to filing your Scholarships Application Form:</h5>
                {!! $instruction['College']->requireddocuments !!}

                <h5>Application Process:</h5>
                @php
                    $applicationprocess = $instruction['College']->applicationprocess;
                    $applicationprocess = Str::replace(
                        'Application Form',
                        '<a href="' . route('form-college') . '" id="appformlink">Application Form</a>',
                        $applicationprocess,
                    );
                    $applicationprocess = Str::replace('<p>', '<div class="row">', $applicationprocess);
                    $applicationprocess = Str::replace('</p>', '</div>', $applicationprocess);
                @endphp
                {!! $applicationprocess !!}
            </div>
        </div>

        {{-- SENIOR HIGH --}}
        <div class="box">
            <div class="heading">
                <h4>Senior High Scholarship</h4> <i class="fa-solid fa-chevron-right"></i>
            </div>
            <div class="content">
                <h5>Who can apply?</h5>
                {!! $instruction['Senior High']->applicants !!}
                <h5>Applicants must be enrolled in one of these approved courses:</h5>
                <div class="row">
                    <ul>
                        @foreach ($strands as $strand)
                            <li>{{ $strand->coursename }}</li>
                        @endforeach
                    </ul>
                </div>

                <h5>What are the qualifications?</h5>
                {!! $instruction['Senior High']->qualifications !!}

                <h5>Partner State University and Colleges:</h5>
                <div class="row">
                    <ul>
                        @foreach ($institutions['Senior High'] as $school)
                            <li>{{ $school->schoolname }}</li>
                        @endforeach
                    </ul>
                </div>
                {{-- <div class="universities">
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
                </div> --}}

                <h5>Documents to prepare prior to filing your Scholarships Application Form:</h5>
                {!! $instruction['Senior High']->requireddocuments !!}

                <h5>Application Process:</h5>
                @php
                    $applicationprocess = $instruction['Senior High']->applicationprocess;
                    $applicationprocess = Str::replace(
                        'Application Form',
                        '<a href="' .
                            route('form-hselem', ['level' => 'Senior High']) .
                            '" id="appformlink">Application Form</a>',
                        $applicationprocess,
                    );
                    $applicationprocess = Str::replace('<p>', '<div class="row">', $applicationprocess);
                    $applicationprocess = Str::replace('</p>', '</div>', $applicationprocess);
                @endphp
                {!! $applicationprocess !!}
            </div>
        </div>

        {{-- JUNIOR HIGH --}}
        <div class="box">
            <div class="heading">
                <h4>Junior High Scholarship</h4> <i class="fa-solid fa-chevron-right"></i>
            </div>
            <div class="content">
                <h5>Who can apply?</h5>
                {!! $instruction['Junior High']->applicants !!}

                <h5>What are the qualifications?</h5>
                {!! $instruction['Junior High']->qualifications !!}

                <h5>Partner State University and Colleges:</h5>
                <div class="row">
                    <ul>
                        @foreach ($institutions['Junior High'] as $school)
                            <li>{{ $school->schoolname }}</li>
                        @endforeach
                    </ul>
                </div>
                {{-- <div class="universities">
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
                </div> --}}

                <h5>Documents to prepare prior to filing your Scholarships Application Form:</h5>
                {!! $instruction['Junior High']->requireddocuments !!}

                <h5>Application Process:</h5>
                @php
                    $applicationprocess = $instruction['Junior High']->applicationprocess;
                    $applicationprocess = Str::replace(
                        'Application Form',
                        '<a href="' .
                            route('form-hselem', ['level' => 'Junior High']) .
                            '" id="appformlink">Application Form</a>',
                        $applicationprocess,
                    );
                    $applicationprocess = Str::replace('<p>', '<div class="row">', $applicationprocess);
                    $applicationprocess = Str::replace('</p>', '</div>', $applicationprocess);
                @endphp
                {!! $applicationprocess !!}
            </div>
        </div>


        {{-- ELEMENTARY --}}
        <div class="box">
            <div class="heading">
                <h4>Elementary Scholarship</h4> <i class="fa-solid fa-chevron-right"></i>
            </div>
            <div class="content">
                <h5>Who can apply?</h5>
                {!! $instruction['Elementary']->applicants !!}

                <h5>What are the qualifications?</h5>
                {!! $instruction['Elementary']->qualifications !!}

                <h5>Partner State University and Colleges:</h5>
                <div class="row">
                    <ul>
                        @foreach ($institutions['Elementary'] as $school)
                            <li>{{ $school->schoolname }}</li>
                        @endforeach
                    </ul>
                </div>
                {{-- <div class="universities">
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
                </div> --}}

                <h5>Documents to prepare prior to filing your Scholarships Application Form:</h5>
                {!! $instruction['Elementary']->requireddocuments !!}

                <h5>Application Process:</h5>
                @php
                    $applicationprocess = $instruction['Elementary']->applicationprocess;
                    $applicationprocess = Str::replace(
                        'Application Form',
                        '<a href="' .
                            route('form-hselem', ['level' => 'Elementary']) .
                            '" id="appformlink">Application Form</a>',
                        $applicationprocess,
                    );
                    $applicationprocess = Str::replace('<p>', '<div class="row">', $applicationprocess);
                    $applicationprocess = Str::replace('</p>', '</div>', $applicationprocess);
                @endphp
                {!! $applicationprocess !!}
            </div>
        </div>

    </div>

    <p class="applicant-login">Already an Applicant? Click <a href="{{ route('login-applicant') }}">here</a> to login
        to your account.</p>

    <footer>
        <p>For further inquiries, please call: <strong>8761-6427</strong> or <strong>0908-764-4245</strong>.</p>
        <p>You can also send us a message on our Facebook Account at <a href="https://www.facebook.com/scholars.tzuchi/"
                target="_blank">Tzu Chi Scholars</a>
            or send us an e-mail at <a href="mailto:social.services@tzuchi.org.ph">social.services@tzuchi.org.ph</a></p>
    </footer>

    <script src="{{ asset('js/applicant.js') }}"></script>
</body>

</html>
