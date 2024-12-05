<div class="ctn-navbar">
    <div class="logo">
        <img src="{{ asset('images/logo.png') }}" alt="Logo">
        <h6 class="fw-bold">Tzu Chi Philippines<br>Educational Assistance Program</h6>
    </div>
    <button id="showprofmenu" onclick="showprofilemenu()"><i class="fas fa-user"></i></button>
</div>

<div class="ctn-profilemenu" id="profilemenu" style="display: none;">
    <a href="{{ route('manageprofile') }}"><i class="fa-solid fa-user"></i>Profile</a><br>
    <a href="{{ route('changepassword', ['scholar', auth()->user()->caseCode]) }}"><i class="fa-solid fa-key"></i>Change
        Password</a><br>
    <!-- <span><i class="fa-solid fa-language"></i>Language</span>
    <button class="toggle-btn active">English</button>
    <button class="toggle-btn">Tagalog</button><br> -->

    <span><i class="fa-solid fa-bell"></i>Notification</span>
    <button class="toggle-btn notification-btn" data-preference="sms" id="sms-btn"
        @if (auth()->user()->notification_preference === 'sms') class="active" style='background-color: #4CAF50; color: white;' @endif>SMS</button>
    <button class="toggle-btn notification-btn" data-preference="email" id="email-btn"
        @if (auth()->user()->notification_preference === 'email') class="active" style='background-color: #4CAF50; color: white;' @endif>Email</button>

    <hr>
    <a href="#" id="btn-signout"
        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
        <i class="fa-solid fa-right-from-bracket"></i>Sign out
    </a>

    <!-- Logout form -->
    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
    </form>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<!-- Add this script at the bottom of your page -->
<script>
    document.querySelectorAll('.notification-btn').forEach(button => {
        button.addEventListener('click', function() {
            const preference = this.getAttribute('data-preference');

            // Make an AJAX request to update the user's preference
            fetch('{{ route('update.notification.preference') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        notification_preference: preference
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Remove the active state and reset styles for both buttons
                        document.getElementById('sms-btn').classList.remove('active');
                        document.getElementById('sms-btn').style.backgroundColor =
                            '#f0f0f0'; // Reset to default
                        document.getElementById('sms-btn').style.color =
                            'black'; // Reset text color to black

                        document.getElementById('email-btn').classList.remove('active');
                        document.getElementById('email-btn').style.backgroundColor =
                            '#f0f0f0'; // Reset to default
                        document.getElementById('email-btn').style.color =
                            'black'; // Reset text color to black

                        // Add the active state and style for the clicked button
                        this.classList.add('active');
                        this.style.backgroundColor = '#4CAF50'; // Active button background
                        this.style.color = 'white'; // Active text color
                    } else {
                        alert('Failed to update notification preference');
                    }
                })
                .catch(error => console.error('Error:', error));
        });
    });
</script>
