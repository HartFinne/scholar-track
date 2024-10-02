
function showprofilemenu() {
    const profileMenu = document.getElementById('profilemenu');
        if (profileMenu.style.display === 'none' || profileMenu.style.display === '') {
            profileMenu.style.display = 'block';
        } else {
            profileMenu.style.display = 'none';
        }
}

window.addEventListener('click', function(event) {
    const profileMenu = document.getElementById('profilemenu');
    const button = document.getElementById('showprofmenu');

    if (!profileMenu.contains(event.target) && !button.contains(event.target)) {
        profileMenu.style.display = 'none';
    }
});
    