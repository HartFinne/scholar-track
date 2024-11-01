// instructions accordion
let boxes = document.querySelectorAll('.box-container .box');

boxes.forEach(box => {

   let heading = box.querySelector('.heading');
   let icon = box.querySelector('.heading i');

   heading.onclick = () => {

      for (var i = 0; i < boxes.length; i++) {
         if (boxes[i] != box) {
            boxes[i].classList.remove('active');
         } else {
            box.classList.toggle('active');
         }
      }

      if (icon.classList.contains('fa-angle-down')) {
         icon.classList.replace('fa-angle-down', 'fa-chevron-right');
      } else {
         document.querySelectorAll('.box-container .box .heading .fa-angle-down').forEach(minus => {
            minus.classList.replace('fa-angle-down', 'fa-chevron-right');
         });
         icon.classList.replace('fa-chevron-right', 'fa-angle-down');
      }

   };

});

// Function to enable input field
function toggleInput() {
   var indigenousCheck = document.getElementById("indigenousCheck");
   var indigenousInput = document.getElementById("indigenousInput");
   var noCheck = document.getElementById("noCheck");

   if (indigenousCheck.checked) {
      indigenousInput.disabled = false;
      noCheck.checked = false;
   } else {
      indigenousInput.disabled = true;
   }
}

function disableInput() {
   var noCheck = document.getElementById("noCheck");
   var indigenousCheck = document.getElementById("indigenousCheck");
   var indigenousInput = document.getElementById("indigenousInput");

   if (noCheck.checked) {
      indigenousInput.disabled = true;
      indigenousCheck.checked = false;
   }
}


// Function to add and remove sibling
document.getElementById('addSibling').addEventListener('click', function (event) {
   event.preventDefault();

   // Select the first sibling template
   var originalDiv = document.querySelector('.siblingsinfo');
   var clone = originalDiv.cloneNode(true);

   // Clear values for each input in the cloned element
   clone.querySelectorAll('input, select').forEach(input => {
       if (input.type === 'text' || input.type === 'date') {
           input.value = ''; // Clear text and date inputs
       } else if (input.tagName.toLowerCase() === 'select') {
           input.selectedIndex = 0; // Reset dropdown to first option
       }
   });

   // Add a remove button for each cloned sibling entry
   var removeButton = document.createElement('button');
   removeButton.textContent = 'Remove';
   removeButton.type = 'button';
   removeButton.classList.add('removeSibling');
   removeButton.onclick = function () { clone.remove(); };
   clone.appendChild(removeButton);

   // Append the cloned sibling entry to the siblings container
   document.getElementById('siblings-container').appendChild(clone);
});


// Function to toggle profile
function showprofilemenu() {
   const profileMenu = document.getElementById('profilemenu');
   if (profileMenu.style.display === 'none' || profileMenu.style.display === '') {
      profileMenu.style.display = 'block';
   } else {
      profileMenu.style.display = 'none';
   }
}

window.addEventListener('click', function (event) {
   const profileMenu = document.getElementById('profilemenu');
   const button = document.getElementById('showprofmenu');

   // Check if both elements exist before calling contains
   if (profileMenu && button && !profileMenu.contains(event.target) && !button.contains(event.target)) {
      profileMenu.style.display = 'none';
   }
});

