<!DOCTYPE html>

<html lang="en">

<head>
    <title>User | Scholars</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="{{ asset('css/style.css') }}" rel="stylesheet" />
    <link href="{{ asset('css/table.css') }}" rel="stylesheet" />
    <link href="{{ asset('css/admstaff.css') }}" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        window.onerror = function(message, source, lineno, colno, error) {
            alert('Error: ' + message + '\nSource: ' + source + '\nLine: ' + lineno);
        };
    </script>
</head>

<body>
    <!-- PAGE HEADER -->
    @include('partials._adminpageheader')


    <div class="ctnmain">
        <span class="pagetitle">Manage Scholar Accounts</span>
        <div class="groupA">
            <form action="#" class="searchbar">
                <input type="search" placeholder="Search" id="insearch" required>
                <button type="submit" id="btnsearch">
                    <i class="fas fa-magnifying-glass"></i>
                </button>
            </form>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert" id="success-alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert" id="error-alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="ctntable table-responsive">
            <table class="table table-bordered" id="tblpenalty">
                <thead>
                    <tr>
                        <th class="text-center align-middle">#</th>
                        <th class="text-center align-middle">Case Code</th>
                        <th class="text-center align-middle">Email</th>
                        <th class="text-center align-middle">Status</th>
                        <th class="text-center align-middle">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($scholarAccounts as $index => $scholar)
                        <tr>
                            <td class="text-center align-middle">{{ $index + 1 }}</td>
                            <td class="text-center align-middle">{{ $scholar->caseCode }}</td>
                            <td class="text-center align-middle">{{ $scholar->scEmail }}</td>
                            <td class="text-center align-middle">{{ $scholar->scStatus }}</td>
                            <td class="text-center align-middle">
                                <!-- Activate/Deactivate buttons -->
                                @if ($scholar->scStatus == 'Inactive')
                                    <form method="POST" action="{{ route('scholar.activate', $scholar->id) }}"
                                        style="display:inline;">
                                        @csrf
                                        <button type="submit" class="btn btn-success">Activate</button>
                                    </form>
                                @else
                                    <form method="POST" action="{{ route('scholar.deactivate', $scholar->id) }}"
                                        style="display:inline;">
                                        @csrf
                                        <button type="submit" class="btn btn-danger">Deactivate</button>
                                    </form>
                                @endif
                                <!-- View button -->
                                <a href="{{ route('scholar.view', $scholar->id) }}" class="btn btn-primary">View</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <script src="{{ asset('js/headercontrol.js') }}"></script>
</body>

</html>
