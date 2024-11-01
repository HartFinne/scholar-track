<!DOCTYPE html>

<html lang="en">

<head>
    <title>Home | Staff</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link href="{{ asset('css/style.css') }}" rel="stylesheet" />
    <link href="{{ asset('css/sthome.css') }}" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
</head>

<body>
    <!-- PAGE HEADER -->
    @include('partials._pageheader')
    <x-alert />

    <div class="ctnmain">
        <!-- USER NAME CONTAINER -->
        <div class="group1">
            <h1>Welcome,</h1>
            <h1>{{ $worker->name }}</h1>
        </div>

        <!-- SEARCH BAR AND CREATE ANNOUNCEMENT BUTTON CONTAINER -->
        <div class="group2">
            <form id="formsearch">
                <input type="search" id="insearch" placeholder="Search">
            </form>
            <button type="button" id="btncreateannouncement" onclick="showannouncementform()">Create new
                announcement</button>
        </div>

        <!-- ANNOUNCEMENTS CONTAINER -->
        @foreach ($announcements as $announcement)
            <div class="card col-md-6 mx-auto mt-2 mb-2 border border-success rounded">
                <div class="card-header bg-success">
                    <span class="fw-bold" style="font-size: 18px; color: #fff">{{ $announcement->title }}</span>
                </div>
                <div class="card-body">
                    <p>{{ $announcement->description }}</p>
                </div>
            </div>
        @endforeach
    </div>

    <!-- ANNOUNCEMENT FORM CONTAINER -->
    <div id="ctnannouncementform">
        <form action="{{ route('home-sw.post') }}" method="post">
            @csrf
            <div class="groupA" id="formheader">
                <span>New announcement</span>
                <button type="button" id="btnhideannouncement" onclick="hideannouncementform()">
                    <i class="fas fa-xmark"></i>
                </button>
            </div>

            <div class="groupB">
                <!-- Multiple selection of recipients with Select2 -->
                <div class="row mb-2 align-items-center">
                    <label class="fw-bold col-md-3" for="recipients">Select Recipients:</label>
                    <div class="col-md-9">
                        <select class="form-control" name="recipients[]" id="recipients" multiple="multiple"
                            style="width: 100%">
                            <option value="all">All Users</option>
                            @foreach ($users as $user)
                                <option value="{{ $user->caseCode }}">{{ $user->caseCode }} |
                                    {{ $user->basicInfo->scLastname }}, {{ $user->basicInfo->scFirstname }}
                                    {{ $user->basicInfo->scMiddlename }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="row mb-2 align-items-center">
                    <label class="fw-bold col-md-3" for="title">Subject</label>
                    <div class="col-md-9">
                        <input type="text" name="title" id="title" class="form-control" style="width: 100%"
                            placeholder="Enter subject" required>
                    </div>
                </div>
                <div class="groupB3">
                    <label class="lbloption">Message</label>
                    <textarea id="inmessage" name="description" placeholder="type here..." required></textarea>
                </div>
                <div class="groupB3">
                    <button type="submit" id="btnpost">Post</button>
                </div>
            </div>
        </form>
    </div>
    <script src="{{ asset('js/headercontrol.js') }}"></script>
    <script src="{{ asset('js/toggleannouncementform.js') }}"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        // Initialize Select2 on the recipients dropdown
        $(document).ready(function() {
            $('#recipients').select2();
        });
    </script>
</body>

</html>
