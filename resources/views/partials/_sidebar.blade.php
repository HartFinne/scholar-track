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
            <button type="button" onclick="togglesubopt2()" id="btncs">Community
                Service<i class="fa-solid fa-caret-right"></i></button>
            <div class="subopt" id="subopt2" style="display: none;">
                <a href="{{ route('csdashboard') }}">Dashboard</a>
                <a href="{{ route('csactivities') }}">Activities</a>
                <a href="{{ route('csattendance') }}">Attendance</a>
            </div>
            <!-- Allowance Requests -->
            <button type="button" onclick="togglesubopt3()" id="btnrequests">Allowance Requests<i
                    class="fa-solid fa-caret-right"></i></button>
            <div class="subopt" id="subopt3" style="display: none;">
                <a href="">Regular Allowance</a>
                <a href="">Special Allowance</a>
            </div>
        </div>
    </div>
</div>
