<!DOCTYPE html>
<html lang="en">

<head>
    <title>Application Forms</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/table.css') }}">
    <link rel="stylesheet" href="{{ asset('css/applicationforms.css') }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body>
    <!-- PAGE HEADER -->
    @include('partials._pageheader')

    <div class="ctnmain">
        <span class="pagetitle">Manage Application Forms</span>
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show text-center" role="alert" id="success-alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show text-center" role="alert" id="error-alert">
                {!! session('error') !!}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        <div class="ctntable table-responsive">
            <table class="table table-bordered" id="tblapplicationforms">
                <thead>
                    <tr>
                        <th class="text-center align-middle">Form</th>
                        <th class="text-center align-middle">Status</th>
                        <th class="text-center align-middle">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($forms as $form)
                        <tr>
                            <td class="text-center align-middle">{{ $form->formname }}</td>
                            <td class="text-center align-middle">{{ $form->status }}</td>
                            <td class="text-center align-middle">
                                <form action="{{ route('updateappformstatus', $form->formname) }}" method="POST">
                                    @csrf <!-- Ensure CSRF protection is enabled for the form -->
                                    @if ($form->status == 'Closed')
                                        <input type="hidden" name="status" value="Open">
                                        <button type="submit" class="btn btn-success">Open</button>
                                    @else
                                        <input type="hidden" name="status" value="Closed">
                                        <button type="submit" class="btn btn-danger">Close</button>
                                    @endif
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <script src="{{ asset('js/headercontrol.js') }}"></script>
    <script src="{{ asset('js/toggleapplicationforms.js') }}"></script>
</body>

</html>
