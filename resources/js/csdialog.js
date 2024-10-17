function showDialog(dialogId, csid) {
    // Show the confirmation dialog
    const dialog = document.getElementById(dialogId);
    dialog.classList.remove('hidden');

    // Add an event listener to the Yes button to submit the form
    const yesBtn = document.getElementById('yesBtn');
    yesBtn.onclick = function () {
        // Submit the cancel form for the specific registration
        document.getElementById(`cancel-form-${csid}`).submit();
    };
}

function closeDialog(dialogId) {
    // Hide the confirmation dialog
    const dialog = document.getElementById(dialogId);
    dialog.classList.add('hidden');
}


// Function to handle cancel action and show second dialog
function showCancelDialog() {
    closeDialog('confirmDialog');
    showDialog('cancelDialog');
}
