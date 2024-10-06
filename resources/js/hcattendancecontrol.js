function toggleconfirmationdialog() {
    var container = document.getElementById('confirmdialog');

    if (container.style.display === 'none') {
        container.style.display = 'flex';
    }
    else {
        container.style.display = 'none';
    }
}

function toggleexitdialog() {
    var container = document.getElementById('exitdialog');

    if (container.style.display === 'none') {
        container.style.display = 'flex';
    }
    else {
        container.style.display = 'none';
    }
}

function toggleviewdialog() {
    var container = document.getElementById('savedialog');

    if (container.style.display === 'none') {
        container.style.display = 'flex';
    }
    else {
        container.style.display = 'none';
    }
}