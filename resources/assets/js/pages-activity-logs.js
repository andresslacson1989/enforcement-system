/**
 * app-academy-course Script
 */

'use strict';

$(function () {
  $('.select2').select2({
    dropdownParent: $('#users_modal .modal-body')
  });

  // Get the modal element by its ID
  const logDetailsModal = document.getElementById('logDetailsModal');

  // Listen for the event that fires just before the modal is shown
  logDetailsModal.addEventListener('show.bs.modal', function (event) {
    // Get the button that triggered the modal
    const button = event.relatedTarget;

    // Extract the full log message from the button's data-details attribute
    const logDetails = button.getAttribute('data-details');

    // Find the <pre> tag inside the modal where we'll display the content
    const modalContent = logDetailsModal.querySelector('#log-details-content');

    // Update the modal's content with the log details
    modalContent.innerHTML = logDetails;
  });
});
