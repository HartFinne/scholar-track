@php
    use Illuminate\Support\Facades\DB;
    use Illuminate\Support\Facades\Auth;

    $educbg = null;
    $user = Auth::user();

    if ($user) {
        $educbg = DB::table('sc_education')
            ->where('caseCode', $user->caseCode)
            ->first();
    }
@endphp

<button id="btn-showmenu" onclick="showsidebar()"><i class="fas fa-bars"></i></button>
<div class="ctn-sidebar" id="sidebar">
    <div class="ctn-options">
        <button id="btn-closemenu" onclick="hidesidebar()"><i class="fas fa-xmark"></i></button>
        <div class="menuoptions">
            <!-- Home -->
            <button type="button" onclick="window.location.href='{{ route('schome') }}';">Home</button>
            <!-- Scholarship -->
            <button type="button" onclick="togglesubopt1()" id="btnscholarship">Scholarship<i
                    class="fa-solid fa-caret-right"></i></button>
            <div class="subopt" id="subopt1" style="display: none;">
                <a href="{{ route('overview') }}">Overview</a>
                <a href="{{ route('gradesub') }}">Grades Submission</a>
                <a href="{{ route('sclte') }}">Letter of Explanation</a>
            </div>
            <!-- HC -->
            <button type="button" onclick="window.location.href='{{ route('schumanities') }}';">Humanities
                Class</button>
            <!-- CS -->
            @if ($educbg->scSchoolLevel == 'College')
                <button type="button" onclick="togglesubopt2()" id="btncs">Community
                    Service<i class="fa-solid fa-caret-right"></i></button>
            @endif
            <div class="subopt" id="subopt2" style="display: none;">
                <a href="{{ route('csdashboard') }}">Dashboard</a>
                <a href="{{ route('csactivities') }}">Activities</a>
                <a href="{{ route('csattendance') }}">Attendance</a>
            </div>
            <!-- Allowance Requests -->
            @if ($educbg->scSchoolLevel == 'College')
                <button type="button" onclick="togglesubopt3()" id="btnrequests">Allowance Requests<i
                        class="fa-solid fa-caret-right"></i></button>
                <div class="subopt" id="subopt3" style="display: none;">
                    <a href="{{ route('scregular') }}">Regular Allowance</a>
                    <a href="{{ route('scspecial') }}">Special Allowance</a>
                </div>
            @else
                <button type="button" onclick="window.location.href='{{ route('scspecial') }}';">Special
                    Allowance</button>
            @endif
            <!-- Appointment System -->
            <button type="button" onclick="window.location.href='{{ route('appointment') }}';">Appointment
                System</button>
        </div>
    </div>
</div>

<script>
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
