<!DOCTYPE html>

<html lang="en">

<head>
    <title>User | Staff</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link href="{{ asset('css/style.css') }}" rel="stylesheet" />
    <link href="{{ asset('css/table.css') }}" rel="stylesheet" />
    <link href="{{ asset('css/admstaff.css') }}" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body>
    <!-- PAGE HEADER -->
    @include('partials._adminpageheader')

    <div class="ctnmain">
        <span class="pagetitle">Manage Staff Accounts</span>
        <div class="groupA">
            <form action="#" class="searchbar">
                <input type="search" placeholder="Search" id="insearch" required>
                <button type="submit" id="btnsearch">
                    <i class="fas fa-magnifying-glass"></i>
                </button>
            </form>
            <button id="btncreateacct" onclick="toggleform()">Create Account</button>
        </div>
        <div class="ctntable table-responsive">
            <table class="table table-bordered" id="tblpenalty">
                <thead>
                    <tr>
                        <th class="text-center align-middle">#</th>
                        <th class="text-center align-middle">Topic</th>
                        <th class="text-center align-middle">Date</th>
                        <th class="text-center align-middle">Number of Attendees</th>
                        <th class="text-center align-middle">Action</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>

    <form action="#file" id="formcreateacct">
        <diV class="formheader">
            <span id="formtitle">Create New Account</span>
            <button type="button" id="btncloseform" onclick="toggleform()"><i class="fas fa-xmark"></i></button>
        </diV>
        <div class="formcontent">
            <span id="reminder">
                The system will automatically send an email containing the user's account
                credentials to the provided email address upon successful account creation.
                Please verify that the email address is correct and active to ensure receipt
                of this information.
            </span>
            <div class="groupC">
                <span class="formlabel">Staff Name</span>
                <input type="text" class="forminput" id="inname" required>
            </div>
            <div class="groupC">
                <span class="formlabel">Email Address</span>
                <input type="email" class="forminput" id="inemail" required>
            </div>
            <div class="groupC">
                <span class="formlabel">Role</span>
                <select id="inrole" class="forminput" required>
                    <option value="" disabled selected>Select a Role</option>
                    <option value="Social Worker">Social Worker</option>
                    <option value="System Admin">System Admin</option>
                </select>
            </div>
            <div class="groupC">
                <span class="formlabel">Area</span>
                <select id="inarea" class="forminput" required>
                    <option value="" disabled selected>Select an Area</option>
                    <option value="Mindong">Mindong</option>
                    <option value="Minxi">Minxi</option>
                    <option value="Minzhong">Minzhong</option>
                    <option value="Not Applicable">Not Applicable</option>
                </select>
            </div>
            <button type="submit" id="btncreate">Create</button>
        </div>
    </form>

    <script src="{{ asset('js/headercontrol.js') }}"></script>
    <script src="{{ asset('js/crud.js') }}"></script>
</body>

</html>
