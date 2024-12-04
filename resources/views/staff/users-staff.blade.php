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
        <div class="row justify-content-between align-items-center my-3">
            <!-- Search bar -->
            <div class="col-md-3">
                <input type="search" placeholder="Search" class="form-control border-success" id="searchInput">
            </div>
            <div class="col-auto">
                <!-- Create New Account Button -->
                <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#createAccountModal">
                    Create New Account
                </button>
            </div>
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
                                        <button type="submit" class="btn btn-sm btn-success">Activate</button>
                                    </form>
                                @else
                                    <form method="POST" action="{{ route('staff.deactivate', $staff->id) }}"
                                        style="display:inline;">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-danger">Deactivate</button>
                                    </form>
                                @endif
                                <!-- View button -->
                                <a href="{{ route('staff.view', $staff->id) }}" class="btn btn-sm btn-primary">View</a>
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

    <!-- Create Account Modal -->
    <div class="modal fade" id="createAccountModal" tabindex="-1" aria-labelledby="createAccountModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <form method="POST" action="{{ route('staccount.create') }}" class="modal-content">
                @csrf <!-- Add CSRF token for security -->
                <div class="modal-header bg-success text-white fw-bold">
                    <h5 class="modal-title" id="createAccountModalLabel">Create New Account</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="name" class="form-label">Staff Name</label>
                        <input type="text" name="name" id="name" class="form-control border-success"
                            required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email Address</label>
                        <input type="email" name="email" id="email" class="form-control border-success"
                            required>
                    </div>
                    <div class="mb-3">
                        <label for="role" class="form-label">Role</label>
                        <select name="role" id="role" class="form-control border-success" required>
                            <option value="" disabled selected>Select a Role</option>
                            <option value="Social Worker">Social Worker</option>
                            <option value="System Admin">System Admin</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="area" class="form-label">Area</label>
                        <select name="area" id="area" class="form-control border-success" required>
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
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Create</button>
                </div>
            </form>
        </div>
    </div>

    <!-- JavaScript for Search Bar -->
    <script>
        document.getElementById('searchInput').addEventListener('input', function() {
            let filter = this.value.toLowerCase();
            let rows = document.querySelectorAll('#tblpenalty tbody tr');

            rows.forEach(row => {
                let cells = Array.from(row.getElementsByTagName('td'));
                let match = cells.some(cell => cell.textContent.toLowerCase().includes(filter));
                row.style.display = match ? '' : 'none';
            });
        });
    </script>


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
