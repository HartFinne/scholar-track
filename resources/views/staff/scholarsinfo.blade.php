<!DOCTYPE html>
<html lang="en">

<head>
    <title>Profile</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="{{ asset('css/scholar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/partial.css') }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
</head>

<body>
    <!-- Include Sidebar -->
    @include('partials._pageheader')

    <!-- MAIN CONTENT -->
    <div class="ctnmain">
        <div class="text-center profile">
            <h1 class="title">Scholar's Profile</h1>
            <a href="{{ route('scholars-college') }}" class="btngoback">&lt Go back</a>
        </div>
        <div class="profile-view">
            <div class="profile-info">
                <h4>BASIC INFORMATION</h4>
                <div class="info">
                    <div class="label">Case Code</div>
                    <div class="value">: <span>{{ $data->caseCode }}</span></div>

                    <div class="label">Assigned Area</div>
                    <div class="value">: <span>{{ $data->addressInfo->scArea }}</span></div>

                    <div class="label">Name</div>
                    <div class="value">: <span>{{ $data->basicInfo->scLastname }},
                            {{ $data->basicInfo->scFirstname }} {{ $data->basicInfo->scMiddlename }}</span></div>

                    <div class="label">Date of Birth</div>
                    <div class="value">:
                        <span>{{ \Carbon\Carbon::parse($data->basicInfo->scDateOfBirth)->format('m/d/Y') }}</span>
                    </div>

                    <div class="label">Sex</div>
                    <div class="value">: <span>{{ $data->basicInfo->scSex }}</span></div>

                    <div class="label">Start of Scholarship</div>
                    <div class="value">: <span>Di ko pa nagagawa mga lodi</span></div>

                    <div class="label">T-Shirt Size</div>
                    <div class="value">: <span>{{ $data->clothingSize->scTShirtSize }}</span></div>

                    <div class="label">Shoe Size</div>
                    <div class="value">: <span>{{ $data->clothingSize->scShoesSize }}</span></div>

                    <div class="label">Slippers Size</div>
                    <div class="value">: <span>{{ $data->clothingSize->scSlipperSize }}</span></div>

                    <div class="label">Pants Size</div>
                    <div class="value">: <span>{{ $data->clothingSize->scPantsSize }}</span></div>

                    <div class="label">Jogging Pants Size</div>
                    <div class="value">: <span>{{ $data->clothingSize->scJoggingPantSize }}</span></div>

                    {{-- change --}}
                    <div class="label">Email Address</div>
                    <div class="value">: <span>{{ $data->scEmail }}</span></div>

                    {{-- change --}}
                    <div class="label">Contact Number</div>
                    <div class="value">: <span>{{ $data->scPhoneNum }}</span></div>
                </div>
                <div class="profile-info">
                    <h4>ACCOUNT INFORMATION</h4>
                    <div class="info">
                        <div class="label">Account Number</div>
                        <div class="value">: <span>wala eto sa database na ginawa ko pasensya na</span></div>

                        <div class="label">Card Number</div>
                        <div class="value">: <span>wala eto sa database na ginawa ko pasensya na</span></div>
                    </div>
                </div>
                <div class="profile-info">
                    <h4>ADDRESS INFORMATION</h4>
                    <div class="info">
                        {{-- change --}}
                        <div class="label">Home Address</div>
                        <div class="value">: <span>{{ $data->addressInfo->scResidential }}</span></div>

                        <div class="label">Area Code</div>
                        <div class="value">: <span>what do you mean area code bro?</span></div>

                        <div class="label">Permanent Address</div>
                        <div class="value">: <span>{{ $data->addressInfo->scResidential }}</span></div>
                    </div>
                </div>
                <div class="profile-info">
                    <h4>EMERGENCY CONTACT</h4>
                    <div class="info">
                        {{-- change --}}
                        <div class="label">Name</div>
                        <div class="value">: <span>{{ $data->basicInfo->scGuardianName }}</span></div>

                        <div class="label">Relation</div>
                        <div class="value">: <span>{{ $data->basicInfo->scRelationToGuardian }}</span></div>

                        <div class="label">Email Address</div>
                        <div class="value">: <span>{{ $data->basicInfo->scGuardianEmailAddress }}</span></div>

                        <div class="label">Contact Number</div>
                        <div class="value">: <span>{{ $data->basicInfo->scGuardianPhoneNumber }}</span></div>
                    </div>
                </div>
                <div class="profile-info">
                    <h4>EDUCATIONAL BACKGROUND</h4>
                    <div class="info">
                        <div class="label">School Level</div>
                        <div class="value">: <span>{{ $data->education->scSchoolLevel }}</span></div>

                        <div class="label">School Name</div>
                        <div class="value">: <span>{{ $data->education->scSchoolName }}</span></div>

                        <div class="label">Grade/Year Level</div>
                        <div class="value">: <span>{{ $data->education->scYearLevel }}</span></div>

                        <div class="label">Course/Strand</div>
                        <div class="value">: <span>{{ $data->education->scCourseStrand }}</span></div>
                    </div>
                </div>
            </div>
            <script src="{{ asset('js/headercontrol.js') }}"></script>
        </div>
    </div>
</body>

</html>
