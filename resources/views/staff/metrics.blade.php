<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Performance Metrics</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
    @include('partials._pageheader')
    <div class="ctnmain">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-10">
                    <h1 class="mb-4 fw-bold">Performance Metrics</h1>
                </div>
                <div class="col-md-2">
                    <a href="{{ route('showevalresults') }}" class="btn btn-success btn-block">Go back</a>
                </div>
            </div>
            <div class="card mb-3">
                <div class="card-body">
                    <h2 class="h4 card-title">Accuracy</h2>
                    <p class="card-text"><strong>{{ $data['accuracy'] }}</strong></p>
                </div>
            </div>

            <div class="card mb-3">
                <div class="card-body">
                    <h2 class="h4 card-title">Classification Report</h2>
                    <pre class="bg-light p-3">{{ $data['classification_report'] }}</pre>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <h2 class="h4 card-title">Confusion Matrix</h2>
                    <table class="table table-bordered">
                        <thead class="thead-dark">
                            <tr>
                                <th class="text-right align-middle">Predicted</th>
                                @foreach ($data['confusion_matrix'][0] as $value)
                                    <th rowspan="2" class="text-center align-middle">{{ $loop->index }}</th>
                                @endforeach
                            </tr>
                            <tr>
                                <th class="text-left align-middle">Actual</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data['confusion_matrix'] as $row)
                                <tr>
                                    <th class="text-center align-middle">{{ $loop->index }}</th>
                                    @foreach ($row as $cell)
                                        <td class="text-center align-middle">{{ $cell }}</td>
                                    @endforeach
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Include Bootstrap JS and Popper.js via CDN -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="{{ asset('js/headercontrol.js') }}"></script>
</body>

</html>
