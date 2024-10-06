<!DOCTYPE html>

<html lang="en">

<head>
    <title>Scholarship Criteria | Elementary</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/table.css') }}">
    <link rel="stylesheet" href="{{ asset('css/qualification.css') }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body>
    <!-- PAGE HEADER -->
    @include('partials._pageheader')

    <div class="ctnmain">
        <span class="pagetitle">Scholarship Criteria</span>
        <div class="groupA">
            <div class="groupA1">
                <div class="groupA2">
                    <span class="subtitle">Elementary Requirements</span>
                    <div class="groupA3">
                        <button type="button" id="btneditreq" class="btncontrol"
                            onclick="togglereqform()">Edit</button>
                    </div>
                </div>
                <form class="groupA4" onsubmit="saveupdate(event)" action="#">
                    <span class="instruction">Set minimum requirements for each field.</span>
                    <div class="groupA5">
                        <span class="label">Average Grade</span>
                        <input type="text" class="reqinput" required disabled>
                    </div>
                    <div class="groupA5">
                        <span class="label">Father's Income</span>
                        <input type="text" class="reqinput" required disabled>
                    </div>
                    <div class="groupA5">
                        <span class="label">Mother's Income</span>
                        <input type="text" class="reqinput" required disabled>
                    </div>
                    <div class="groupA5">
                        <span class="label">Siblings' Income</span>
                        <input type="text" class="reqinput" required disabled>
                    </div>
                    <div class="groupA5">
                        <span class="label">Applicant's Income</span>
                        <input type="text" class="reqinput" required disabled>
                    </div>
                    <button type="submit" id="btnsavereq" class="btncontrol" disabled>Save</button>
                </form>
            </div>

            <div class="groupA1">
                <div class="groupA2">
                    <span class="subtitle">Schools</span>
                    <form class="groupA3" action="#">
                        <input type="text" id="ininstitute" class="addinput" placeholder="Enter Institution"
                            required>
                        <button type="submit" id="btnaddinsti" class="btncontrol">Add</button>
                    </form>
                </div>
                <div class="ctntable table-responsive">
                    <table class="table table-bordered" id="tblinstitutions">
                        <thead>
                            <tr>
                                <th class="text-center align-middle">Institution</th>
                                <th class="text-center align-middle">Action</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/headercontrol.js') }}"></script>
    <script src="{{ asset('js/criteriacontrol.js') }}"></script>
</body>

</html>
