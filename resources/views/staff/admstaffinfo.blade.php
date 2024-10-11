<!DOCTYPE html>
<html lang="en">

<head>
    <title>User Information</title>
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

    <div class="ctnmain">
        <span class="pagetitle">Staff Account Information</span>

        <!-- User Info Table -->
        <table class="table table-bordered" id="tbluserinfo">
            <tbody>
                <tr>
                    <th class="text-right">Name:</th>
                    <td>{{ $user->name }}</td>
                </tr>
                <tr>
                    <th class="text-right">Email:</th>
                    <td>{{ $user->email }}</td>
                </tr>
                <tr>
                    <th class="text-right">Mobile Number:</th>
                    <td>{{ $user->mobileno ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <th class="text-right">Role:</th>
                    <td>{{ $user->role }}</td>
                </tr>
                <tr>
                    <th class="text-right">Area:</th>
                    <td>{{ $user->area }}</td>
                </tr>
                <tr>
                    <th class="text-right">Status:</th>
                    <td>{{ $user->status }}</td>
                </tr>
            </tbody>
        </table>

        <!-- Back Button -->
        <a href="{{ route('users-staff') }}" class="btn btn-primary">Back to List</a>
    </div>

    <script src="{{ asset('js/headercontrol.js') }}"></script>
</body>

</html>
