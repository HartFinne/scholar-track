
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

window.onload = function() {
    var ctx = document.getElementById('myLineChart').getContext('2d');
    var myLineChart = new Chart(ctx, {
        type: 'line', // Specify chart type as 'line'
        data: {
            labels: json($chartData['labels']), // Pass labels from chartData
            datasets: [{
                label: 'GWA',
                data: json($chartData['grades']), // Pass grades from chartData
                fill: false, // Don't fill under the line
                borderColor: 'darkgreen', // Line color (changed to dark green)
                tension: 0.1, // Curve tension for smoothness
                pointBackgroundColor: 'darkgreen', // Color of the points
                pointBorderColor: 'darkgreen', // Border color of the points
            }]
        },
        options: {
            scales: {
                x: {
                    beginAtOne: false
                },
                y: {
                    beginAtZero: true
                }
            }
        }
    });
};

// Fucntion to add and remove sibling
document.getElementById('addSibling').addEventListener('click', function(event) {
    event.preventDefault();
    
    var originalDiv = document.querySelector('.siblingsinfo');
 
    var clone = originalDiv.cloneNode(true);
 
    clone.querySelectorAll('input').forEach(input => input.value = '');
 
    if (clone.querySelector('.removeSibling')) {
        clone.querySelector('.removeSibling').remove();
    }
 
    var removeButton = document.createElement('button');
    removeButton.textContent = 'Remove';
    removeButton.classList.add('removeSibling');
 
    clone.appendChild(removeButton);
 
    removeButton.addEventListener('click', function() {
        clone.remove();
    });
 
    document.getElementById('siblings-container').appendChild(clone);
 });


// Fucntion to add and remove destination
document.getElementById('addDestination').addEventListener('click', function(event) {
    event.preventDefault();
    
    var originalDiv = document.querySelector('.destination-info');
 
    var clone = originalDiv.cloneNode(true);
 
    clone.querySelectorAll('input').forEach(input => input.value = '');
 
    
    if (clone.querySelector('.removeDestination')) {
        clone.querySelector('.removeDestination').remove();
    }
 
    var removeButton = document.createElement('button');
    removeButton.textContent = 'Remove';
    removeButton.classList.add('removeDestination');
 
    clone.appendChild(removeButton);
 
    removeButton.addEventListener('click', function() {
        clone.remove();
    });
 
    document.getElementById('destination-container').appendChild(clone);
 });


 // Fucntion to add and remove destination (OJT)
document.getElementById('addDestination1').addEventListener('click', function(event) {
    event.preventDefault();
    
    var originalDiv = document.querySelector('.destination-info1');
 
    var clone = originalDiv.cloneNode(true);
 
    clone.querySelectorAll('input').forEach(input => input.value = '');
 
    if (clone.querySelector('.removeDestination')) {
        clone.querySelector('.removeDestination').remove();
    }
 
    var removeButton = document.createElement('button');
    removeButton.textContent = 'Remove';
    removeButton.classList.add('removeDestination');
 
    clone.appendChild(removeButton);
 
    removeButton.addEventListener('click', function() {
        clone.remove();
    });
   
    document.getElementById('destination-container1').appendChild(clone);
 });


 // Function to add a new row (class sched)
document.getElementById('addRowBtn').addEventListener('click', function(event) {
    event.preventDefault();
 
    var tableBody = document.getElementById('tableBody');
 
    var newRow = document.createElement('tr');
 
    newRow.innerHTML = `
        <td><input type="text" id="time" name="time" placeholder="ex: 7:00 AM - 8:00 AM" required></td>
        <td><input type="text" class="sub" id="mon" name="mon" placeholder="Course Code"></td>
        <td><input type="text" class="sub" id="tue" name="tue" placeholder="Course Code"></td>
        <td><input type="text" class="sub" id="wed" name="wed" placeholder="Course Code"></td>
        <td><input type="text" class="sub" id="thu" name="thu" placeholder="Course Code"></td>
        <td><input type="text" class="sub" id="fri" name="fri" placeholder="Course Code"></td>
        <td><input type="text" class="sub" id="sat" name="sat" placeholder="Course Code"></td>
        <td><input type="text" class="sub" id="sun" name="sun" placeholder="Course Code"></td>
        <td class="remove"><button class="removeRowBtn"><i class="fa-solid fa-xmark"></i></button></td>
    `;
 
    tableBody.appendChild(newRow);
 
    newRow.querySelector('.removeRowBtn').addEventListener('click', function() {
        newRow.remove();
    });
 });