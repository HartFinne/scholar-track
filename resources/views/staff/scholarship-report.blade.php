<!DOCTYPE html>
<html lang="en">

<head>
    <title>Scholars Evaluation</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/table.css') }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body>
    @include('partials._pageheader')

    <div class="ctnmain">
        <div class="row align-items-center justify-content-between">
            <div class="col-auto">
                <span class="fw-bold text-success h2">Scholarship Reports</span>
            </div>
            <div class="col-auto">
                <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#reportModal">Generate Summary
                    Report</button>
            </div>
        </div>
        <div class="ctntable table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th class="text-center align-middle">#</th>
                        <th class="text-center align-middle">Report Name</th>
                        <th class="text-center align-middle">Date Generated</th>
                        <th class="text-center align-middle">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="text-center align-middle" colspan="4">No Reports Found.</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <div class="modal fade" id="reportModal" tabindex="-1" aria-labelledby="reportModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title" id="reportModalLabel">Generate Summary Report</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="post" action="{{ route('generateSSR') }}" id="summaryReportForm">
                        {{-- <div class="mb-3">
                            <label for="reportname" class="form-label">Report Name</label>
                            <small class="text-muted">(This will be used as file name)</small>
                            <input type="text" maxlength="200" name="reportname" id="reportname" class="form-control"
                                required>
                        </div> --}}
                        <div class="mb-3">
                            <label for="schoollevel" class="form-label">School Level</label>
                            <small class="text-muted">(Select who should be included in the report)</small>
                            <select name="schoollevel" id="schoollevel" class="form-select" required>
                                <option value="" selected hidden>Select school level</option>
                                <option value="All">All</option>
                                <option value="College">College</option>
                                <option value="Senior High">Senior High</option>
                                <option value="Junior High">Junior High</option>
                                <option value="Elementary">Elementary</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="startdate" class="form-label">Start Date</label>
                            <input type="date" name="startdate" id="startdate" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="enddate" class="form-label">End Date</label>
                            <input type="date" name="enddate" id="enddate" class="form-control" required>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" form="summaryReportForm" class="btn btn-success">Generate Report</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // document.addEventListener("DOMContentLoaded", function() {
        //     const evaluateLink = document.querySelector("#evaluate-link");

        //     if (evaluateLink) {
        //         evaluateLink.addEventListener("click", function(event) {
        //             const loadingModal = new bootstrap.Modal(document.getElementById('loadingModal'));
        //             loadingModal.show();
        //         });
        //     }
        // });
    </script>
    <script src="{{ asset('js/headercontrol.js') }}"></script>
</body>

</html>
