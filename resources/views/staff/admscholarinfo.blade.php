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
        <span class="text-success fw-bold h2">Scholar Account Information</span>

        <!-- User Info Table -->
        <table class="table table-bordered" id="tbluserinfo">
            <tbody>
                <tr>
                    <th class="text-right">Case Code:</th>
                    <td>{{ $user->caseCode }}</td>
                </tr>
                <tr>
                    <th class="text-right">Email:</th>
                    <td>{{ $user->scEmail }}</td>
                </tr>
                <tr>
                    <th class="text-right">Mobile Number:</th>
                    <td>{{ $user->scPhoneNum ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <th class="text-right">Status:</th>
                    <td>{{ $user->scStatus }}</td>
                </tr>
            </tbody>
        </table>

        <!-- Back Button -->
        <a href="{{ route('users-scholar') }}" class="btn btn-primary">Back to List</a>
    </div>

    <script src="{{ asset('js/headercontrol.js') }}"></script>
</body>

</html>
