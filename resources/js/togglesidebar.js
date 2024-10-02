function showsidebar() {
    document.getElementById('sidebar').style.display = 'block';
    document.getElementById('btn-showmenu').style.display = 'none';
}

function hidesidebar() {
    document.getElementById('sidebar').style.display = 'none';
    document.getElementById('btn-showmenu').style.display = 'block';
}

document.addEventListener('click', function (event) {
    var button = document.getElementById('btn-showmenu');
    var sidebar = document.getElementById('sidebar');

    if (!sidebar.contains(event.target) && !button.contains(event.target)) {
        document.getElementById('sidebar').style.display = 'none';
        document.getElementById('btn-showmenu').style.display = 'block';
    }
});

function togglesubopt1() {
    var subopt1 = document.getElementById('subopt1');
    var button = document.getElementById('btnscholarship');
    var icon = document.querySelector('#btnscholarship i');

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
    var button = document.getElementById('btncs');
    var icon = document.querySelector('#btncs i');

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
    var button = document.getElementById('btnrequests');
    var icon = document.querySelector('#btnrequests i');

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