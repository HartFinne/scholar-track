// toggle sidebar
function togglesidebar() {
    var sidebar = document.getElementById('ctnsidebar');

    if (sidebar.style.display === 'flex') {
        sidebar.style.display = 'none';
    }
    else {
        sidebar.style.display = 'flex';
    }
}

// function closesidebar() {
//     document.getElementById('ctnsidebar').style.display = 'none';
// }

// toggle profile menu
function toggleprofilemenu() {
    var menu = document.getElementById('ctnprofilemenu');
    var button = document.getElementById('btnprofilemenu');

    if (menu.style.display === 'flex') {
        menu.style.display = 'none';
        button.style.background = '#1a5319';
        button.style.color = '#fff';
    }
    else {
        menu.style.display = 'flex';
        button.style.background = '#8eb79c';
        button.style.color = '#1a5319';
    }
}

function togglesubopt1() {
    var subopt1 = document.getElementById('subopt1');
    var button = document.getElementById('btnscholars');
    var icon = document.querySelector('#btnscholars i');

    if (subopt1.style.display === 'none') {
        subopt1.style.display = 'flex';
        button.classList.add('active');
        icon.classList.remove('fa-caret-right');
        icon.classList.add('fa-caret-down');
    } else {
        subopt1.style.display = 'none';
        button.classList.remove('active');
        icon.classList.remove('fa-caret-down');
        icon.classList.add('fa-caret-right');
    }
}

function togglesubopt2() {
    var subopt2 = document.getElementById('subopt2');
    var button = document.getElementById('btnrequests');
    var icon = document.querySelector('#btnrequests i');

    if (subopt2.style.display === 'none') {
        subopt2.style.display = 'flex';
        button.classList.add('active');
        icon.classList.remove('fa-caret-right');
        icon.classList.add('fa-caret-down');
    } else {
        subopt2.style.display = 'none';
        button.classList.remove('active');
        icon.classList.remove('fa-caret-down');
        icon.classList.add('fa-caret-right');
    }
}

function togglesubopt3() {
    var subopt3 = document.getElementById('subopt3');
    var button = document.getElementById('btncriteria');
    var icon = document.querySelector('#btncriteria i');

    if (subopt3.style.display === 'none') {
        subopt3.style.display = 'flex';
        button.classList.add('active');
        icon.classList.remove('fa-caret-right');
        icon.classList.add('fa-caret-down');
    } else {
        subopt3.style.display = 'none';
        button.classList.remove('active');
        icon.classList.remove('fa-caret-down');
        icon.classList.add('fa-caret-right');
    }
}

document.addEventListener('click', function (event) {
    var btnsidebar = document.getElementById('btnsidebar');
    var sidebar = document.getElementById('ctnsidebar');
    var btnprofilemenu = document.getElementById('btnprofilemenu');
    var profilemenu = document.getElementById('ctnprofilemenu');

    // New condition: hide both sidebar and profile menu if both are open (display is 'flex')
    if (sidebar.style.display === 'flex' && profilemenu.style.display === 'flex') {
        if (!sidebar.contains(event.target) && !btnsidebar.contains(event.target) &&
            !profilemenu.contains(event.target) && !btnprofilemenu.contains(event.target)) {
            sidebar.style.display = 'none';
            profilemenu.style.display = 'none';
            btnprofilemenu.style.background = '#1a5319';
            btnprofilemenu.style.color = '#fff';
        }
    }
    // Second condition: hide the sidebar if it's open and the click is outside the sidebar
    else if (sidebar.style.display === 'flex') {
        if (!sidebar.contains(event.target) && !btnsidebar.contains(event.target)) {
            sidebar.style.display = 'none';
        }
    }
    // Third condition: hide the profile menu if it's open and the click is outside the profile menu
    else if (profilemenu.style.display === 'flex') {
        if (!profilemenu.contains(event.target) && !btnprofilemenu.contains(event.target)) {
            profilemenu.style.display = 'none';
            btnprofilemenu.style.background = '#1a5319';
            btnprofilemenu.style.color = '#fff';
        }
    }
});
