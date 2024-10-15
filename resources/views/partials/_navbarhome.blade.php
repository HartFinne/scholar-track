<!-- NAVBAR -->
<div class="ctn-navbar">
    <div class="logo">
        <h4 class="fw-bold"></h4>
    </div>
    <button id="showprofmenu" onclick="showprofilemenu()"><i class="fas fa-user"></i></button>
</div>

<div class="ctn-profilemenu" id="profilemenu" style="display: none;">
    <a href="{{ route('manageprofile') }}"><i class="fa-solid fa-user"></i>Profile</a><br>
    <a href="{{ route('changepassword') }}"><i class="fa-solid fa-key"></i>Change Password</a><br>
    <span><i class="fa-solid fa-language"></i>Language</span>
    <button class="toggle-btn active">English</button>
    <button class="toggle-btn">Tagalog</button><br>
    <span><i class="fa-solid fa-bell"></i>Notification</span>
    <button class="toggle-btn active">SMS</button>
    <button class="toggle-btn">Email</button><br>
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
