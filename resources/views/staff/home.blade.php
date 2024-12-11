<!DOCTYPE html>

<html lang="en">

<head>
    <title>Home | Staff</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link href="{{ asset('css/style.css') }}" rel="stylesheet" />
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
                    <input type="search" class="form-control border border-success text-success" placeholder="Search"
                        id="searchInput">
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
        <div id="announcementsContainer">
            @foreach ($announcements as $announcement)
                <div class="card col-md-6 mx-auto mt-4 mb-4 border border-success rounded announcement">
                    <div class="card-header bg-success d-flex justify-content-between align-items-center">
                        <span class="fw-bold" style="font-size: 18px; color: #fff">{{ $announcement->title }}</span>
                        <div class="d-flex">
                            <button class="btn btn-light me-1" data-bs-toggle="modal" data-bs-target="#updateModal">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </button>
                            <button class="btn btn-danger" data-bs-toggle="modal"
                                data-bs-target="#deleteConfirmationModal"
                                data-announcement-id="{{ $announcement->announcementID }}">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <p style="white-space: pre-wrap;">{{ $announcement->description }}</p>
                    </div>
                </div>

                <!-- UPDATE ANNOUNCEMENT MODAL -->
                <div class="modal fade" id="updateModal" tabindex="-1" aria-labelledby="updateModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog modal-lg">
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
                                            style="resize: none; height: 40vh;">{{ $announcement->description }}</textarea>
                                    </div>
                                    <button type="submit" class="btn btn-success">Save</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- ANNOUNCEMENT FORM CONTAINER -->
    <div class="modal fade" id="announcementModal" tabindex="-1" aria-labelledby="announcementModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title" id="announcementModalLabel">New Announcement</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('home-sw.post') }}" method="post">
                        @csrf
                        <!-- Multiple selection of recipients with Select2 -->
                        <div class="row mb-3">
                            <label for="recipients" class="col-md-3 col-form-label fw-bold">Select Recipients:</label>
                            <div class="col-md-9">
                                <select name="recipients[]" id="recipients" multiple style="width: 100%;"
                                    class="custom-form-control">
                                    <option value="all">All Users</option>
                                    @foreach ($users as $user)
                                        <option value="{{ $user->caseCode }}">
                                            {{ $user->caseCode }} | {{ $user->basicInfo->scLastname }},
                                            {{ $user->basicInfo->scFirstname }} {{ $user->basicInfo->scMiddlename }}
                                        </option>
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
                            <textarea class="form-control" style="resize: none; height: 40vh" id="inmessage" name="description"
                                placeholder="Type here..." required></textarea>
                        </div>
                        <div class="mb-3 text-end">
                            <button type="submit" class="btn btn-success">Post</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- DELETE CONFIRMATION MODAL -->
    <div class="modal fade" id="deleteConfirmationModal" tabindex="-1"
        aria-labelledby="deleteConfirmationModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="deleteConfirmationModalLabel">Confirm Deletion</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete this announcement?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <a href="#" id="confirmDeleteBtn" class="btn btn-danger">Delete</a>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/headercontrol.js') }}"></script>
    <script src="{{ asset('js/toggleannouncementform.js') }}"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            // Initialize Select2 for recipients
            $('#recipients').select2({
                dropdownParent: $('#announcementModal'),
                placeholder: "Select recipients",
                allowClear: true
            });

            // Search function for announcements (title + description)
            $('#searchInput').on('keyup', function() {
                var searchTerm = $(this).val().toLowerCase();
                $('.announcement').each(function() {
                    var title = $(this).find('.card-header span').text().toLowerCase();
                    var description = $(this).find('.card-body p').text().toLowerCase();

                    // Check if either title or description contains the search term
                    if (title.indexOf(searchTerm) !== -1 || description.indexOf(searchTerm) !== -
                        1) {
                        $(this).show();
                    } else {
                        $(this).hide();
                    }
                });
            });

            // Handle clearing of the search input (when the 'X' button is clicked)
            $('#searchInput').on('input', function() {
                if ($(this).val() === '') {
                    // Reset search when input is cleared
                    $('.announcement').show(); // Show all announcements
                }
            });

            // Set up delete confirmation modal
            $('#deleteConfirmationModal').on('show.bs.modal', function(event) {
                var button = $(event.relatedTarget);
                var announcementId = button.data('announcement-id');
                var deleteUrl = '{{ route('deleteannouncement', ':id') }}';
                deleteUrl = deleteUrl.replace(':id', announcementId);
                $('#confirmDeleteBtn').attr('href', deleteUrl);
            });
        });
    </script>
</body>

</html>
