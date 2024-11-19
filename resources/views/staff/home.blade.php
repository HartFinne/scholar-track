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
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
</head>

<body>
    <!-- PAGE HEADER -->
    @include('partials._pageheader')
    <x-alert />

    <div class="ctnmain">
        <!-- USER NAME CONTAINER -->
        <div class="row d-flex align-items-center text-success fw-bold h2">
            <div class="col-auto">Welcome,</div>
            <div class="col-auto">{{ $worker->name }}</div>
        </div>

        <!-- SEARCH BAR AND CREATE ANNOUNCEMENT BUTTON CONTAINER -->
        <div class="row">
            <div class="col-md-3 mb-3">
                <form id="formsearch">
                    <input type="search" class="form-control border border-success" placeholder="Search">
                </form>
            </div>
            <div class="col-md-6">
            </div>
            <div class="col-md-3">
                <button type="button" id="btnCreateAnnouncement" class="btn btn-success w-100" data-bs-toggle="modal"
                    data-bs-target="#announcementModal">
                    Create new announcement
                </button>
            </div>
        </div>

        <!-- ANNOUNCEMENTS CONTAINER -->
        @foreach ($announcements as $announcement)
            <div class="card col-md-6 mx-auto mt-4 mb-4 border border-success rounded">
                <div class="card-header bg-success d-flex justify-content-between align-items-center">
                    <span class="fw-bold" style="font-size: 18px; color: #fff">{{ $announcement->title }}</span>
                    <div class="d-flex">
                        <button class="btn btn-light me-1" data-bs-toggle="modal" data-bs-target="#updateModal">
                            <i class="fa-solid fa-pen-to-square"></i>
                        </button>
                        <a href="{{ route('deleteannouncement', $announcement->announcementID) }}"
                            class="btn btn-danger">
                            <i class="fa-solid fa-trash"></i>
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <p style="white-space: pre-wrap;">{{ $announcement->description }}</p>
                </div>
            </div>

            <!-- Update Modal -->
            <div class="modal fade" id="updateModal" tabindex="-1" aria-labelledby="updateModalLabel"
                aria-hidden="true">
                <div class="modal-dialog modal-lg"> <!-- Add modal-lg or modal-xl here -->
                    <div class="modal-content">
                        <div class="modal-header bg-success text-white">
                            <h5 class="modal-title" id="updateModalLabel">Update Announcement</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form action="{{ route('updateannouncement', $announcement->announcementID) }}"
                                method="POST">
                                @csrf
                                <div class="mb-3">
                                    <label for="title" class="form-label">Title</label>
                                    <input type="text" class="form-control" id="title" name="title"
                                        value="{{ $announcement->title }}" required>
                                </div>
                                <div class="mb-3">
                                    <label for="description" class="form-label">Description</label>
                                    <textarea class="form-control" id="description" name="description" rows="10" required
                                        style="resize: none; height: max-content;">{{ $announcement->description }}</textarea>
                                </div>
                                <button type="submit" class="btn btn-success">Save</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- ANNOUNCEMENT FORM CONTAINER -->
    <div class="modal fade" id="announcementModal" tabindex="-1" aria-labelledby="announcementModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg"> <!-- Using modal-lg to give enough space for the form -->
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title" id="announcementModalLabel">New Announcement</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('home-sw.post') }}" method="post">
                        @csrf
                        <!-- Multiple selection of recipients with Select2 -->
                        <div class="row mb-2">
                            <label class="col-md-3 col-form-label fw-bold">Select Recipients:</label>
                            <div class="col-md-9">
                                <select class="form-control" name="recipients[]" id="recipients" multiple="multiple"
                                    style="width: 100%">
                                    <option value="all">All Users</option>
                                    @foreach ($users as $user)
                                        <option value="{{ $user->caseCode }}">{{ $user->caseCode }} |
                                            {{ $user->basicInfo->scLastname }}, {{ $user->basicInfo->scFirstname }}
                                            {{ $user->basicInfo->scMiddlename }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <label class="col-md-3 col-form-label fw-bold" for="title">Subject</label>
                            <div class="col-md-9">
                                <input type="text" name="title" id="title" class="form-control"
                                    placeholder="Enter subject" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Message</label>
                            <textarea class="form-control" id="inmessage" name="description" placeholder="Type here..." required></textarea>
                        </div>
                        <div class="mb-3 text-end">
                            <button type="submit" class="btn btn-success">Post</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script src="{{ asset('js/headercontrol.js') }}"></script>
    <script src="{{ asset('js/toggleannouncementform.js') }}"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            // Initialize Select2 when the modal is opened
            $('#announcementModal').on('shown.bs.modal', function() {
                $('#recipients').select2({
                    dropdownParent: $(
                        '#announcementModal'
                    ) // Ensures dropdown is appended to the modal and shows correctly
                });
            });

            // Re-initialize when modal is closed to reset the form and clear selection
            $('#announcementModal').on('hidden.bs.modal', function() {
                $('#recipients').select2('destroy').select2({
                    dropdownParent: $('#announcementModal')
                });
            });
        });
    </script>
</body>

</html>
