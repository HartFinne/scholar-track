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

<body>
    <!-- PAGE HEADER -->
    @include('partials._pageheader')
    <x-alert />
    <div class="mainctn">
        <div class="container">
            <div class="text-center mb-4">
                <h3 id="outusername" class="fw-bold">{{ $worker->name }}</h3>
            </div>
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <!-- Account Information Form -->
                    <form action="{{ route('updateStaffAccount') }}" method="post" class="card p-4 shadow-sm">
                        @csrf
                        <h5 class="mb-3 text-success">Account Information</h5>
                        <div class="mb-3">
                            <label for="name" class="form-label">Staff Name</label>
                            <input type="text" id="name" name="name"
                                class="form-control border-success shadow-sm" value="{{ $worker->name }}" required
                                maxlength="255">
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email Address</label>
                            <input type="email" id="email" name="email"
                                class="form-control border-success shadow-sm" value="{{ $worker->email }}" required
                                maxlength="255">
                        </div>
                        <div class="mb-3">
                            <label for="contactno" class="form-label">Mobile No.</label>
                            <input type="tel" id="contactno" name="contactno"
                                class="form-control border-success shadow-sm" value="{{ $worker->mobileno }}"
                                placeholder="Please set your mobile number" required maxlength="12" minlength="11"
                                pattern="^(09\d{9}|63\d{10})$">
                        </div>
                        <div class="mb-3">
                            <label for="area" class="form-label">Area</label>
                            <input type="text" id="area" name="area"
                                class="form-control border-success shadow-sm" value="{{ $worker->area }}" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="role" class="form-label">Role</label>
                            <input type="text" id="role" name="role"
                                class="form-control border-success shadow-sm" value="{{ $worker->role }}" readonly>
                        </div>
                        <button type="submit" class="btn btn-success w-100">Save Changes</button>
                    </form>
                </div>

                <div class="col-md-6">
                    <form action="{{ route('changepass-staff') }}" method="post"
                        class="card p-4 shadow-sm mt-4 mt-md-0">
                        @csrf
                        <h5 class="mb-3 text-danger">Change Your Password</h5>

                        <!-- New Password -->
                        <div class="mb-3">
                            <label for="newpassword" class="form-label">New Password</label>
                            <input type="password" id="newpassword" name="newpassword"
                                class="form-control border-success shadow-sm" required>
                            <!-- Password Requirements -->
                            <div id="password-requirements" class="mt-2 small">
                                <ul>
                                    <li class="mb-1 text-danger" id="uppercase">At least 1 uppercase letter</li>
                                    <li class="mb-1 text-danger" id="lowercase">At least 1 lowercase letter</li>
                                    <li class="mb-1 text-danger" id="number">At least 1 number</li>
                                    <li class="mb-1 text-danger" id="special">At least 1 special character</li>
                                    <li class="mb-1 text-danger" id="length">Minimum 8 characters</li>
                                </ul>
                            </div>
                        </div>

                        <!-- Confirm Password -->
                        <div class="mb-3">
                            <label for="confirmpassword" class="form-label">Confirm New Password</label>
                            <input type="password" id="confirmpassword" name="confirmpassword"
                                class="form-control border-success shadow-sm" required>
                            <div id="password-match-collapse" class="collapse text-danger mt-2 small">
                                Passwords do not match!
                            </div>
                        </div>

                        <!-- Current Password -->
                        <div class="mb-3">
                            <label for="currentpassword" class="form-label">Current Password</label>
                            <input type="password" id="currentpassword" name="currentpassword"
                                class="form-control border-success shadow-sm" required>
                        </div>

                        <button type="submit" class="btn btn-danger w-100">Save Password</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const newPassword = document.getElementById('newpassword');
            const confirmPassword = document.getElementById('confirmpassword');
            const passwordRequirements = {
                uppercase: document.getElementById('uppercase'),
                lowercase: document.getElementById('lowercase'),
                number: document.getElementById('number'),
                special: document.getElementById('special'),
                length: document.getElementById('length'),
            };
            const passwordMatchCollapse = document.getElementById('password-match-collapse');

            // Regex patterns for validation
            const patterns = {
                uppercase: /[A-Z]/,
                lowercase: /[a-z]/,
                number: /\d/,
                special: /[!@#$%^&*(),.?":{}|<>]/,
                length: /^.{8,}$/,
            };

            // Validate password requirements
            newPassword.addEventListener('input', function() {
                const value = newPassword.value;

                for (const [key, regex] of Object.entries(patterns)) {
                    if (regex.test(value)) {
                        passwordRequirements[key].classList.remove('text-danger');
                        passwordRequirements[key].classList.add('text-success');
                    } else {
                        passwordRequirements[key].classList.remove('text-success');
                        passwordRequirements[key].classList.add('text-danger');
                    }
                }

                // Trigger match validation to handle edge cases (e.g., when editing newPassword after confirming)
                validatePasswordMatch();
            });

            // Validate password match
            confirmPassword.addEventListener('input', function() {
                validatePasswordMatch();
            });

            // Function to validate password match
            function validatePasswordMatch() {
                if (newPassword.value === confirmPassword.value && newPassword.value !== '') {
                    passwordMatchCollapse.classList.remove('show');
                } else {
                    passwordMatchCollapse.classList.add('show');
                }
            }
        });
    </script>
    <script src="{{ asset('js/headercontrol.js') }}"></script>
</body>

</html>
