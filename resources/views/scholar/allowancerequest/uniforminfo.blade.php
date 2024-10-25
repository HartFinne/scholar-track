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
    <link rel="stylesheet" href="{{ asset('css/partial.css') }}">
</head>

<body>
    <!-- Include Sidebar -->
    @include('partials._sidebar')

    <!-- Include Navbar -->
    @include('partials._navbar')

    <div class="ctn-main">
        <a href="{{ route('scspecial') }}" class="goback">&lt Go back</a>

        <div class="request-view">
            <div class="request-status">
                <h6 class="request-stat">{{ $request->status }}</h6>
            </div>

            <div class="request-info">
                <h5>UNIFORM ALLOWANCE REQUEST</h5>
                <div class="info">
                    <div class="label">Date of Request:</div>
                    <div class="value"><span>{{ $request->created_at->format('F d, Y') }}</span></div>

                    <div class="label">Date of Release:</div>
                    <div class="value">
                        <span>{{ $request->releasedate ? $request->releasedate->format('F d, Y') : '--' }}</span>
                    </div>

                    <div class="label">Area:</div>
                    <div class="value"><span>{{ $scholar->scholarshipinfo->area }}</span></div>

                    <div class="label">Name:</div>
                    <div class="value"><span>{{ $scholar->basicInfo->scLastname }},
                            {{ $scholar->basicInfo->scFirstname }}, {{ $scholar->basicInfo->scMiddlename }}</span></div>

                    <div class="label">School:</div>
                    <div class="value"><span>{{ $scholar->education->scSchoolName }}</span></div>

                    <div class="label">Year Level:</div>
                    <div class="value"><span>{{ $scholar->education->scYearGrade }}</span></div>

                    <div class="label">Course:</div>
                    <div class="value"><span>{{ $scholar->education->scCourseStrandSec }}</span></div>

                    <div class="label">Uniform for:</div>
                    <div class="value"><span>{{ $request->uniformtype }}</span></div>

                    <div class="label">Total Price:</div>
                    <div class="value"><span>{{ $request->totalprice }}</span></div>

                    <div class="label">Certificate of Enrollment or OJT Certificate/Instruction:</div>
                    <div class="value">
                        <span>
                            <a href="{{ asset('storage/' . $request->certificate) }}" target="_blank"
                                rel="noopener noreferrer" class="link-success" role="button">Click here to
                                view</a>
                        </span>
                    </div>

                    <div class="label">Receipt or Acknowledgement Receipt:</div>
                    <div class="value">
                        <span>
                            <a href="{{ asset('storage/' . $request->acknowledgement) }}" target="_blank"
                                rel="noopener noreferrer" class="link-success" role="button">Click here to
                                view</a>
                        </span>
                    </div>

                    <div class="label">Picture of the Uniform:</div>
                    <div class="value">
                        <span>
                            <a href="{{ asset('storage/' . $request->uniformpic) }}" target="_blank"
                                rel="noopener noreferrer" class="link-success" role="button">Click here to
                                view</a>
                        </span>
                    </div>

                    <div class="label">Liquidation Form:</div>
                    <div class="value">
                        <span>
                            <a href="{{ asset('storage/' . $request->liquidation) }}" target="_blank"
                                rel="noopener noreferrer" class="link-success" role="button">Click here to
                                view</a>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="{{ asset('js/scholar.js') }}"></script>
</body>

</html>
