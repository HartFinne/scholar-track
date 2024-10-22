<!DOCTYPE html>

<html lang="en">

<head>
    <title>Account</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/account.css') }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body style="background-color: #d6efd8; color: #1a5319;">
    <!-- PAGE HEADER -->
    @include('partials._pageheader')

    <div class="mainctn">
        <div class="groupA">
            <span id="outusername">{{ $worker->name }}</span>
            <div class="groupA1">
                <form class="groupA2" style="width: 525px;" action="#">
                    <span class="subtitle">Account Information</span>
                    <div class="groupA3">
                        <span style="width: 150px;">Staff Name</span>
                        <input type="text" name="name" class="inacctinfo" value="{{ $worker->name }}" required>
                    </div>
                    <div class="groupA3">
                        <span style="width: 150px;">Email Address</span>
                        <input type="email" name="email" class="inacctinfo" value="{{ $worker->email }}" required>
                    </div>
                    <div class="groupA3">
                        <span style="width: 150px;">Mobile No.</span>
                        <input type="tel" name="contactno" class="inacctinfo" value="{{ $worker->mobileno }}"
                            placeholder="Please set your mobile number" required>
                    </div>
                    <div class="groupA3">
                        <span style="width: 150px;">Area</span>
                        <input type="text" name="area" class="inacctinfo" value="{{ $worker->area }}" required>
                    </div>
                    <div class="groupA3">
                        <span style="width: 150px;">Role</span>
                        <input type="text" name="role" class="inacctinfo" value="{{ $worker->role }}" readonly>
                    </div>
                    <button type="submit" id="btnupdateacct" class="button">Save</button>
                </form>
                <form class="groupA2" style="width: 525px;" action="#">
                    <span class="subtitle">Change your password?</span>
                    <div class="groupA3">
                        <span style="width: 300px;">New Password</span>
                        <input type="text" id="inname" class="inpassinfo" required>
                    </div>
                    <div class="groupA3">
                        <span style="width: 300px;">Confirm New Password</span>
                        <input type="text" id="incontactno" class="inpassinfo" required>
                    </div>
                    <div class="groupA3">
                        <span style="width: 300px">Current Password</span>
                        <input type="text" id="incontactno" class="inpassinfo" required>
                    </div>
                    <button type="submit" id="btnchangepass" class="button">Submit</button>
                </form>
            </div>
        </div>
    </div>
    <script src="{{ asset('js/headercontrol.js') }}"></script>
</body>

</html>
