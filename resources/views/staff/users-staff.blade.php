<!DOCTYPE html>

<html lang="en">

<head>
    <title>User | Staff</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
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
    <x-alert />

    <div class="ctnmain">
        <span class="text-success fw-bold h2">Manage Staff Accounts</span>
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
                        <th class="text-center align-middle">Name</th>
                        <th class="text-center align-middle">Email</th>
                        <th class="text-center align-middle">Status</th>
                        <th class="text-center align-middle">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($staffAccounts as $index => $staff)
                        <tr>
                            <td class="text-center align-middle">{{ $index + 1 }}</td>
                            <td class="text-center align-middle">{{ $staff->name }}</td>
                            <td class="text-center align-middle">{{ $staff->email }}</td>
                            <td class="text-center align-middle">{{ $staff->status }}</td>
                            <td class="text-center align-middle">
                                <!-- Activate/Deactivate buttons -->
                                @if ($staff->status == 'Inactive')
                                    <form method="POST" action="{{ route('staff.activate', $staff->id) }}"
                                        style="display:inline;">
                                        @csrf
                                        <button type="submit" class="btn btn-success">Activate</button>
                                    </form>
                                @else
                                    <form method="POST" action="{{ route('staff.deactivate', $staff->id) }}"
                                        style="display:inline;">
                                        @csrf
                                        <button type="submit" class="btn btn-danger">Deactivate</button>
                                    </form>
                                @endif
                                <!-- View button -->
                                <a href="{{ route('staff.view', $staff->id) }}" class="btn btn-primary">View</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="d-flex justify-content-center mt-3">
            {{ $staffAccounts->links('pagination::bootstrap-4') }}
        </div>
    </div>

    <!-- Account Creation Form -->
    <form method="POST" action="{{ route('staccount.create') }}" id="formcreateacct">
        @csrf <!-- Add CSRF token for security -->
        <div class="formheader">
            <span id="formtitle">Create New Account</span>
            <button type="button" id="btncloseform" onclick="toggleform()"><i class="fas fa-xmark"></i></button>
        </div>
        <div class="formcontent">
            <span id="reminder">
                The system will automatically send an email containing the user's account
                credentials to the provided email address upon successful account creation.
                Please verify that the email address is correct and active to ensure receipt
                of this information.
            </span>
            <div class="groupC">
                <span class="formlabel">Staff Name</span>
                <input type="text" name="name" class="forminput" id="name">
            </div>
            <div class="groupC">
                <span class="formlabel">Email Address</span>
                <input type="email" name="email" class="forminput" id="email">
            </div>
            <div class="groupC">
                <span class="formlabel">Role</span>
                <select name="role" id="role" class="forminput">
                    <option value="" disabled selected>Select a Role</option>
                    <option value="Social Worker">Social Worker</option>
                    <option value="System Admin">System Admin</option>
                </select>
            </div>
            <div class="groupC">
                <span class="formlabel">Area</span>
                <select name="area" id="area" class="forminput" required>
                    <option value="" disabled selected>Select an Area</option>
                    <option value="Mindong">Mindong</option>
                    <option value="Minxi">Minxi</option>
                    <option value="Minzhong">Minzhong</option>
                    <option value="Bicol">Bicol</option>
                    <option value="Davao">Davao</option>
                    <option value="Iloilo">Iloilo</option>
                    <option value="Palo">Palo</option>
                    <option value="Zamboanga">Zamboanga</option>
                    <option value="Not Applicable">Not Applicable</option>
                </select>
            </div>
            <button type="submit" id="btncreate">Create</button>
        </div>
    </form>

    <script src="{{ asset('js/headercontrol.js') }}"></script>
    <script src="{{ asset('js/crud.js') }}"></script>
    <script>
        window.setTimeout(function() {
            // Hide the success alert if it exists
            const successAlert = document.getElementById('success-alert');
            if (successAlert) {
                successAlert.classList.remove('show');
                successAlert.classList.add('fade');
                successAlert.style.display = 'none';
            }

            // Hide the error alert if it exists
            const errorAlert = document.getElementById('error-alert');
            if (errorAlert) {
                errorAlert.classList.remove('show');
                errorAlert.classList.add('fade');
                errorAlert.style.display = 'none';
            }
        }, 3000);
    </script>
</body>

</html>
