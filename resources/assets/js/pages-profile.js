'use strict';

$(function () {
  // Get the hash from the current URL (e.g., '#notifications')
  var hash = window.location.hash;

  if (hash) {
    // Find the tab button that has a data-bs-target matching the hash
    var tabToActivate = $('button[data-bs-target="' + hash + '"]');

    // If a matching tab button is found, use Bootstrap's jQuery plugin to show it
    if (tabToActivate.length) {
      tabToActivate.tab('show');
    }
  }

  // --- Profile Photo Upload Logic ---
  const changePhotoButton = $('#change-photo-btn');
  const photoInput = $('#profile-photo-input');
  const profilePicImg = $('#profile-pic-img');

  if (changePhotoButton.length) {
    // 1. Trigger the hidden file input when the camera button is clicked
    changePhotoButton.on('click', function () {
      photoInput.click();
    });

    // 2. Handle the file selection and upload
    photoInput.on('change', function () {
      const file = this.files[0];
      if (!file) {
        return;
      }

      // 3. Use FormData to prepare the file for upload
      const formData = new FormData();
      formData.append('photo', file);
      // We also need to add the CSRF token for Laravel to accept the POST request
      formData.append('_token', $('meta[name="csrf-token"]').attr('content'));
      formData.append('user_id', changePhotoButton.data('user-id'));
      // Show a loading state with SweetAlert
      Swal.fire({
        title: 'Uploading...',
        text: 'Please wait while your new profile picture is being uploaded.',
        allowOutsideClick: false,
        didOpen: () => {
          Swal.showLoading();
        }
      });

      // 4. Send the file to the server using jQuery AJAX
      $.ajax({
        url: '/user/profile-photo/',
        method: 'POST',
        data: formData,
        processData: false, // Important for FormData
        contentType: false, // Important for FormData
        success: function (response) {
          // 5. On success, update the image source and show a success message
          profilePicImg.attr('src', response.profile_photo_url);
          // Also update the navbar profile picture if it exists
          //  $('.navbar-dropdown .avatar img').attr('src', response.profile_photo_url);

          Swal.fire({
            icon: 'success',
            title: 'Success!',
            text: 'Profile picture updated successfully.'
          });
        },
        error: function (xhr) {
          // 6. On error, display the validation message or a generic error
          const errorMessage = xhr.responseJSON?.message || 'An unexpected error occurred.';
          Swal.fire({
            icon: 'error',
            title: 'Upload Failed',
            text: errorMessage
          });
        }
      });
    });
  }

  // --- Initialize DataTables ---
  $('#forms_table').DataTable({});

  // --- Trigger ready event for other scripts ---
  $(document).trigger('datatable:ready');
});
