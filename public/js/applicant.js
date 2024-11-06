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

// Track sibling count
let siblingCount = 0;

document.getElementById('addSibling').addEventListener('click', function (event) {
   event.preventDefault();

   var originalDiv = document.querySelector('.siblingsinfo');

   var clone = originalDiv.cloneNode(true);
   clone.style.display = 'block';

   // Clear values and set required attributes for the new clone
   clone.querySelectorAll('input, select').forEach(input => {
      input.value = '';
      input.required = true;
   });

   clone.querySelector('[name="srelationship"]').value = 'Sibling';

   // Ensure no duplicate "Remove" button in the clone
   if (!clone.querySelector('.removeSibling')) {
      var removeButton = document.createElement('button');
      removeButton.textContent = 'Remove';
      removeButton.type = 'button';
      removeButton.classList.add('removeSibling');
      removeButton.onclick = function () {
         clone.remove();
         updateSiblingCount(-1); // Decrease the sibling count when removed
      };
      clone.appendChild(removeButton);
   }

   // Append the clone to the container
   document.getElementById('siblings-container').appendChild(clone);

   // Update sibling count
   updateSiblingCount(1);
});

// Update the sibling count and hidden field
function updateSiblingCount(change) {
   siblingCount += change;
   document.getElementById('siblingCount').value = siblingCount;

   // If no siblings, remove required attributes from all inputs in the hidden template
   if (siblingCount === 0) {
      document.querySelector('.siblingsinfo').querySelectorAll('input, select').forEach(input => {
         input.required = false;
      });
   }
}

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

