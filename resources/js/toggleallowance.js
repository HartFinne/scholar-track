function toggleforms() {
    var button = document.getElementById('btnmanageform');
    var icon = document.querySelector('#btnmanageform i');
    var container = document.getElementById('ctnmanageform');

    if (container.style.display === 'none') {
        container.style.display = 'flex';
        button.style.backgroundColor = '#2e7c55';
        button.style.color = '#fff';
        button.style.transform = 'scale(1.003)';
        icon.classList.remove('fa-caret-right');
        icon.classList.add('fa-caret-down');
    }
    else {
        container.style.display = 'none';
        button.style.backgroundColor = '#d6efd8';
        button.style.color = '#2e7c55';
        button.style.transform = 'scale(1)';
        icon.classList.remove('fa-caret-down');
        icon.classList.add('fa-caret-right');
    }
}

function togglerequestlist() {
    var button = document.getElementById('btnshowlist');
    var icon = document.querySelector('#btnshowlist i');
    var container = document.getElementById('ctnfiltertable');

    if (container.style.display === 'none') {
        container.style.display = 'flex';
        button.style.backgroundColor = '#2e7c55';
        button.style.color = '#fff';
        button.style.transform = 'scale(1.003)';
        icon.classList.remove('fa-caret-right');
        icon.classList.add('fa-caret-down');
    }
    else {
        container.style.display = 'none';
        button.style.backgroundColor = '#d6efd8';
        button.style.color = '#2e7c55';
        button.style.transform = 'scale(1)';
        icon.classList.remove('fa-caret-down');
        icon.classList.add('fa-caret-right');
    }
}