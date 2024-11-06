<!DOCTYPE html>
<html lang="en">

<head>
    <title>Change Password</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body style="background-color: #eaebea;">
    <!-- PAGE HEADER -->
    <div class="header">
        <div class="ctnlogo">
            <img src="{{ asset('images/logo.png') }}" id="headerlogo" alt="logo.png">
        </div>
        <div class="headertitle">
            <span style="color: #2e7c55;">Tzu Chi Philippines</span>
            <span style="color: #1a5319;">Educational Assistance Program</span>
        </div>
    </div>

    <!-- MAIN CONTENT -->
    <div class="container d-flex justify-content-center align-items-center"
        style="min-height: 70vh; padding-top: 20px; padding-bottom: 40px;">
        <div class="col-md-6">
            @if (session('success'))
                <div class="alert alert-success" role="alert">
                    {{ session('success') }}
                </div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger" role="alert">
                    {{ session('error') }}
                </div>
            @endif

            <form class="card p-4 shadow-lg mx-auto" method="POST"
                action="{{ route('submitchangepass', [$usertype, $casecode]) }}">
                @csrf
                <span class="text-center mb-4 fw-bold" style="font-family: Arial; font-size: 32px">Change
                    Password</span>

                <div class="form-group mb-3">
                    <label for="currentpass" class="form-label">Current Password</label>
                    <input type="password" class="form-control" id="currentpass" name="currentpass" required
                        placeholder="Enter current password">
                </div>

                <div class="form-group mb-3">
                    <label for="newpass" class="form-label">New Password</label>
                    <input type="password" class="form-control" id="newpass" name="newpass" required
                        placeholder="Enter new password" oninput="validatePasswords()">
                </div>

                <div class="form-group mb-4">
                    <label for="confirmnewpass" class="form-label">Confirm New Password</label>
                    <input type="password" class="form-control" id="confirmnewpass" name="confirmnewpass" required
                        placeholder="Re-enter new password" oninput="validatePasswords()">
                    <div class="row">
                        <small id="passwordMismatchWarning" class="text-danger d-none">Passwords do not match.</small>
                    </div>
                    <div class="row">
                        <small id="passwordLengthWarning" class="text-danger d-none">Passwords must be at least 8
                            characters
                            long.</small>
                    </div>
                </div>

                <button type="submit" class="btn btn-success w-100 mb-3" id="submitBtn" disabled>Submit</button>

                <div class="row d-flex justify-content-center">
                    <a id="btnregister" href="{{ route('exitchangepass', [$usertype, $casecode]) }}">Go back</a>
                </div>
            </form>

            <script>
                function validatePasswords() {
                    const newPassword = document.getElementById('newpass').value;
                    const confirmPassword = document.getElementById('confirmnewpass').value;
                    const mismatchWarning = document.getElementById('passwordMismatchWarning');
                    const lengthWarning = document.getElementById('passwordLengthWarning');
                    const submitButton = document.getElementById('submitBtn');

                    let isValid = true;

                    // Check if both fields have input
                    if (newPassword && confirmPassword) {
                        // Check password length
                        if (newPassword.length < 8 || confirmPassword.length < 8) {
                            lengthWarning.classList.remove('d-none');
                            isValid = false;
                        } else {
                            lengthWarning.classList.add('d-none');
                        }

                        // Check if passwords match
                        if (newPassword !== confirmPassword) {
                            mismatchWarning.classList.remove('d-none');
                            isValid = false;
                        } else {
                            mismatchWarning.classList.add('d-none');
                        }
                    } else {
                        // Hide warnings if one or both fields are empty
                        mismatchWarning.classList.add('d-none');
                        lengthWarning.classList.add('d-none');
                        isValid = false; // Keep submit button disabled until both fields have input
                    }

                    // Enable or disable the submit button based on validation
                    submitButton.disabled = !isValid;
                }
            </script>
        </div>
    </div>

</body>

</html>
