function togglereqform() {
    document.querySelectorAll('.reqinput').forEach(input => input.disabled = false);
    document.getElementById('btneditreq').disabled = true;
    document.getElementById('btnsavereq').disabled = false;
}

function saveupdate(event) {
    event.preventDefault();

    document.querySelectorAll('.reqinput').forEach(input => input.disabled = true);
    document.getElementById('btnsavereq').disabled = true;
    document.getElementById('btneditreq').disabled = false;
}