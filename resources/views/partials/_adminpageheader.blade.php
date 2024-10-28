<div class="pageheader">
    <button id="btnsidebar" onclick="togglesidebar()"><i class="fas fa-bars"></i></button>
    <button id="btnprofilemenu" onclick="toggleprofilemenu()"><i class="fas fa-user"></i></button>
</div>

<!-- SIDE BAR -->
<div id="ctnsidebar">
    <button id="btnclosesidebar" onclick="togglesidebar()"><i class="fas fa-xmark"></i></button>
    <div id="ctnoptions">
        <a href="{{ route('dashboard') }}" class="sbarmainopt">Dashboard</a>
        <a href="{{ route('users-scholar') }}" class="sbarmainopt">Scholars</a>
        <a href="{{ route('users-staff') }}" class="sbarmainopt">Staff</a>
        <a href="{{ route('users-applicant') }}" class="sbarmainopt">Applicants</a>
        {{-- <a href="{{ route('admfeatures') }}">System Features</a> --}}
    </div>
</div>

<!-- PROFILE MENU -->
<div id="ctnprofilemenu">
    <a id="linkprofile" href="{{ route('account-sa') }}"><i class="fas fa-user"></i>Account</a>
    <a id="linksignout" href="{{ route('logout-sw') }}"><i class="fa-solid fa-right-from-bracket"></i>Sign out</a>
</div>
