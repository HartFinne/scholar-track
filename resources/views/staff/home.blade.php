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
        </div>
    </div>

    <!-- ANNOUNCEMENT FORM CONTAINER -->
    <div id="ctnannouncementform">
        <form>
            <div class="groupA" id="formheader">
                <span>New announcement</span>
                <button type="button" id="btnhideannouncement" onclick="hideannouncementform()">
                    <i class="fas fa-xmark"></i>
                </button>
            </div>
            <div class="groupB">
                <div class="groupB1">
                    <label class="lbloption">Recipient</label>
                    <div class="formoptions">
                        <label for="radall">
                            <input type="radio" id="radall" name="recipient" checked>
                            All
                        </label>
                        <label for="radscholars">
                            <input type="radio" id="radscholars" name="recipient">
                            Scholars
                        </label>
                        <label for="radstaff"><input type="radio" id="radstaff" name="recipient">
                            Staff</label>
                        <label for="radspecific">
                            <input type="radio" id="radspecific" name="recipient">
                            Specific Person
                        </label>
                        <div>
                            <input type="text" placeholder="enter name" id="inrcpname" required>
                        </div>
                    </div>
                </div>
                <div class="groupB2">
                    <label class="lbloption">Subject</label>
                    <input type="text" id="insubject" placeholder="enter subject" required>
                </div>
                <div class="groupB3">
                    <label class="lbloption">Message</label>
                    <textarea id="inmessage" placeholder="type here..." required></textarea>
                </div>
                <div class="groupB3">
                    <button type="submit" id="btnpost">Post</button>
                </div>
            </div>
        </form>
    </div>
    <script src="{{ asset('js/headercontrol.js') }}"></script>
    <script src="{{ asset('js/toggleannouncementform.js') }}"></script>
</body>

</html>
