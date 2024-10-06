function toggleevenform() {
    var container = document.getElementById('ctneventform');

    if (container.style.display === 'none') {
        container.style.display = 'flex';
    }
    else {
        container.style.display = 'none';
    }
}