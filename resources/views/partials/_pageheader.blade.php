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
        <a href="{{ route('qualification') }}" class="sbarmainopt">Scholarship Criteria</a>
        <button id="btncriteria" class="sbarmainopt" onclick="togglesubopt3()">Application<i
                class="fas fa-caret-right"></i></button>
        <div class="ctnsuboptions" id="subopt3" style="display: none;">
            <a href="{{ route('applicants') }}" class="sbarsubopt">Applicants</a>
            <a href="{{ route('applicationforms') }}" class="sbarsubopt">Application Forms</a>
        </div>
        <a id="evaluate-link" class="sbarmainopt" href='{{ route('evaluatescholars') }}'>Scholars
            Evaluation</a>
        <a id="btnhc" class="sbarmainopt" href='{{ route('generatescholarshipreport') }}' target="_blank">Generate
            Report</a>
        <a id="btnhc" class="sbarmainopt" href='{{ route('appointments') }}'>Appointments</a>
    </div>
</div>

<!-- PROFILE MENU -->
<div id="ctnprofilemenu">
    <a id="linkprofile" href="{{ route('account-sw') }}"><i class="fas fa-user"></i>Account</a>
    <a id="linksignout" href="{{ route('logout-sw') }}"><i class="fa-solid fa-right-from-bracket"></i>Sign out</a>
</div>

<div class="modal fade" id="loadingModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-success">
            <div class="modal-body text-center p-4">
                <div class="text-success mb-3">
                    <i class="fas fa-check-circle fa-3x"></i>
                </div>
                <p style="font-size: 1.25em; color: #28a745; font-weight: 500;">
                    Processing your request...
                </p>
                <p class="text-muted">
                    The system is analyzing and updating scholars' data. This may take a moment. Thank you for your
                    patience!
                </p>
                <div class="spinner-border text-success mt-3" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>
        </div>
    </div>
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
</script>
