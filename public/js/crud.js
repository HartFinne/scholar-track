function toggleform() {
    var form = document.getElementById('formcreateacct');

    if (form.style.display === 'flex') {
        form.style.display = 'none';
    } else {
        form.style.display = 'flex';
    }
}