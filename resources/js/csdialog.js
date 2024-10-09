// Function to show the dialog
function showDialog(dialogId) {
    document.getElementById(dialogId).classList.remove('hidden');
}

// Function to close the dialog
function closeDialog(dialogId) {
    document.getElementById(dialogId).classList.add('hidden');
}

// Function to handle cancel action and show second dialog
function showCancelDialog() {
    closeDialog('confirmDialog');
    showDialog('cancelDialog');
}
