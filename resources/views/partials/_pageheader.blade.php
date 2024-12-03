<div class="pageheader">
    <button id="btnsidebar" onclick="togglesidebar()"><i class="fas fa-bars"></i></button>
    <button id="btnprofilemenu" onclick="toggleprofilemenu()"><i class="fas fa-user"></i></button>
</div>

<!-- SIDE BAR -->
<div id="ctnsidebar">
    <button id="btnclosesidebar" onclick="togglesidebar()"><i class="fas fa-xmark"></i></button>
    <div id="ctnoptions">
        <button id="btnhome" class="sbarmainopt"
            onclick="window.location.href='{{ route('home-sw') }}';">Home</button>
        <button id="btnscholars" class="sbarmainopt" onclick="togglesubopt1()">Scholars<i
                class="fas fa-caret-right"></i></button>
        <div class="ctnsuboptions" id="subopt1" style="display: none;">
            <a href="{{ route('scholars-overview') }}" class="sbarsubopt">Overview</a>
            <a href="{{ route('scholarshiprenewal') }}" class="sbarsubopt">Renewal</a>
            <a href="{{ route('lte') }}" class="sbarsubopt">Letter of Explanation</a>
            <a href="{{ route('penalty') }}" class="sbarsubopt">Penalty</a>
        </div>
        <button id="btnrequests" class="sbarmainopt" onclick="togglesubopt2()">Requests<i
                class="fas fa-caret-right"></i></button>
        <div class="ctnsuboptions" id="subopt2" style="display: none;">
            <a href="{{ route('allowancerequests-regular') }}" class="sbarsubopt">Regular Allowance</a>
            <a href="{{ route('allowancerequests-special') }}" class="sbarsubopt">Special Allowance</a>
        </div>
        <button id="btncs" class="sbarmainopt"
            onclick="window.location.href='{{ route('communityservice') }}';">Community
            Service</button>
        <button id="btnhc" class="sbarmainopt"
            onclick="window.location.href='{{ route('humanitiesclass') }}';">Humanities Class</button>
        <button id="btnappointments" class="sbarmainopt"
            onclick="window.location.href='{{ route('appointments') }}';">Appointments</button>
        <button id="btnapplicants" class="sbarmainopt"
            onclick="window.location.href='{{ route('applicants') }}';">Applicants</button>
        <button id="" class="sbarmainopt"
            onclick="window.location.href='{{ route('showreports') }}';">Scholarship Report</button>
        {{-- <button id="btnreport" class="sbarmainopt"
            onclick="window.location.href='{{ route('generatescholarshipreport') }}';" target="_blank">Scholarship
            Report</button> --}}
        <button id="btnsettings" class="sbarmainopt"
            onclick="window.location.href='{{ route('qualification') }}';">Scholarship Settings</button>

    </div>
</div>

<!-- PROFILE MENU -->
<div id="ctnprofilemenu">
    <a id="linkprofile" href="{{ route('account-sw') }}"><i class="fas fa-user"></i>Account</a>
    <a id="linksignout" href="{{ route('logout-sw') }}"><i class="fa-solid fa-right-from-bracket"></i>Sign out</a>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const evaluateLink = document.querySelector("#evaluate-link");

        if (evaluateLink) {
            evaluateLink.addEventListener("click", function(event) {
                const loadingModal = new bootstrap.Modal(document.getElementById('loadingModal'));
                loadingModal.show();
            });
        }
    });

    // Save the scroll position before navigating away
    window.addEventListener('beforeunload', () => {
        // Only save the scroll position if the user is refreshing or coming back to the same page
        if (document.referrer === window.location.href) {
            sessionStorage.setItem('scrollPosition', window.scrollY);
        } else {
            sessionStorage.removeItem('scrollPosition'); // Clear it for other pages
        }
    });

    // Restore the scroll position on page load
    window.addEventListener('load', () => {
        const scrollPosition = sessionStorage.getItem('scrollPosition');
        if (scrollPosition) {
            window.scrollTo(0, parseInt(scrollPosition));
            sessionStorage.removeItem('scrollPosition'); // Clear after use
        }
    });
</script>
