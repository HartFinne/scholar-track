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

    <div class="ctnmain">
        <!-- USER NAME CONTAINER -->
        <div class="group1">
            <h1>Welcome,</h1>
            <h1>Name</h1>
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
        <div class="group3">
            @foreach ($announcements as $announcement)
                <h1>{{ $announcement->title }}</h1>
                <h2>{{ $announcement->description }}</h2>
            @endforeach

        </div>
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
                <div class="form-group">
                    <label class="lbloption" for="recipients">Select Recipients:</label>
                    <select class="form-control" name="recipients[]" id="recipients" multiple="multiple">
                        <option value="all">All Users</option>
                        @foreach ($users as $user)
                            <option value="{{ $user->id }}">{{ $user->caseCode }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="groupB2">
                    <label class="lbloption">Subject</label>
                    <input type="text" name="title" id="insubject" placeholder="enter subject" required>
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
            $('.form-control').select2();
        });
    </script>
</body>

</html>
