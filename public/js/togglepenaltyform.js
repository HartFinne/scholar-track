function togglepenaltyform() {
    var container = document.getElementById('ctnpenaltyform');

    if (container.style.display === 'flex') {
        container.style.display = 'none';
    }
    else {
        container.style.display = 'flex';
    }
}