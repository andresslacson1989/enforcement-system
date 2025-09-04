'use strict';
$(function () {
  // Get the hash from the current URL (e.g., '#notifications')
  var hash = window.location.hash;

  if (hash) {
    // Find the tab button that has a data-bs-target matching the hash
    var tab_to_activate = $('button[data-bs-target="' + hash + '"]');

    // If a matching tab button is found, use Bootstrap's jQuery plugin to show it
    if (tab_to_activate.length) {
      tab_to_activate.tab('show');
    }
  }

  // --- Profile Photo Upload Logic ---
  const $change_photo_button = $('#change-photo-btn');
  const $photo_input = $('#profile-photo-input');
  const $profile_pic_img = $('#profile-pic-img');

  if ($change_photo_button.length) {
    // 1. Trigger the hidden file input when the camera button is clicked
    $change_photo_button.on('click', function () {
      $photo_input.click();
    });

    // 2. Handle the file selection and upload
    $photo_input.on('change', function () {
      const file = this.files[0];
      if (!file) {
        return;
      }

      // 3. Use FormData to prepare the file for upload
      const form_data = new FormData();
      form_data.append('photo', file);
      // We also need to add the CSRF token for Laravel to accept the POST request
      form_data.append('_token', $('meta[name="csrf-token"]').attr('content'));
      form_data.append('user_id', $change_photo_button.data('user-id'));
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
        data: form_data,
        processData: false, // Important for FormData
        contentType: false, // Important for FormData
        success: function (response) {
          // 5. On success, update the image source and show a success message
          $profile_pic_img.attr('src', response.profile_photo_url);
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
          const error_message = xhr.responseJSON?.message || 'An unexpected error occurred.';
          Swal.fire({
            icon: 'error',
            title: 'Upload Failed',
            text: error_message
          });
        }
      });
    });
  }

  // --- Initialize DataTables ---
  $('#forms_table').DataTable();
  $('#training_certificates_table').DataTable();

  // --- Initialize Flatpickr for the training date input ---
  const $date_of_training_input = $('#date_of_training'); // snake_case variable
  if ($date_of_training_input.length) {
    $date_of_training_input.flatpickr({
      altInput: true,
      altFormat: 'F j, Y',
      dateFormat: 'Y-m-d',
      maxDate: 'today' // This disables all future dates
    });
  }

  // --- Initialize Select2 for Tags ---
  const $training_tags_select = $('#training_tags');
  if ($training_tags_select.length) {
    $training_tags_select.select2({
      tags: true, // Allows creating new tags
      tokenSeparators: [',', ' '],
      placeholder: 'Select or type tags...',
      ajax: {
        url: '/tags/search',
        dataType: 'json',
        delay: 250, // Wait 250ms after typing before searching
        processResults: function (data) {
          return data;
        },
        cache: true
      }
    });
  }

  // --- Add Training Certificate Logic ---
  const $add_training_form = $('#add_training_certificate_form'); // snake_case variable
  if ($add_training_form.length) {
    $add_training_form.on('submit', function (e) {
      e.preventDefault(); // snake_case variable
      const form_data = new FormData(this);
      form_data.append('_token', $('meta[name="csrf-token"]').attr('content'));

      $.ajax({
        url: '/training-certificates/store',
        method: 'POST',
        data: form_data,
        processData: false,
        contentType: false,
        success: function (response) {
          Swal.fire({
            icon: 'success',
            title: 'Success!',
            text: response.message
          }).then(() => {
            // Reload the page to show the new certificate in the table
            window.location.reload();
          });
        },
        error: function (xhr) {
          const errors = xhr.responseJSON.errors;
          let error_message = 'An unexpected error occurred.'; // let is correct here as it's reassigned
          if (errors) {
            // Format validation errors into a list
            error_message = Object.values(errors)
              .map(error => `<li>${error[0]}</li>`)
              .join('');
            error_message = `<ul>${error_message}</ul>`;
          }
          Swal.fire({
            icon: 'error',
            title: 'Submission Failed',
            html: error_message
          });
        }
      });
    });
  }

  // --- View Training Certificate Tags via SweetAlert ---
  // We use event delegation on the table body to ensure this works even if the table is dynamically updated.
  $('#training_certificates_table_body').on('click', '.view-tags-btn', function () {
    const $button = $(this);
    const certificate_name = $button.data('certificate-name');
    const tags = $button.data('tags');

    if (tags && tags.length > 0) {
      const tags_html = tags.map(tag => `<span class="badge bg-label-secondary m-1">${tag}</span>`).join('');
      Swal.fire({
        title: `Tags for: ${certificate_name}`,
        html: `<div class="mt-3">${tags_html}</div>`,
        icon: 'info',
        customClass: {
          confirmButton: 'btn btn-primary'
        }
      });
    } else {
      Swal.fire({
        title: `Tags for: ${certificate_name}`,
        text: 'No tags have been assigned to this certificate.',
        icon: 'warning'
      });
    }
  });

  // --- View Certificate Image via SweetAlert ---
  const $view_certificate_modal = $('#viewCertificateModal');
  let print_url = ''; // Variable to hold the URL for the print button

  // Use event delegation to handle clicks on the view button
  $('#training_certificates_table_body').on('click', '.view-certificate-btn', function () {
    const $button = $(this);
    const certificate_url = $button.data('certificate-url');
    const certificate_name = $button.data('certificate-name');
    const can_print = $button.data('can-print');
    const file_type = $button.data('file-type').toLowerCase();
    const $viewer_container = $('#certificate_viewer_container');
    const $modal_dialog = $view_certificate_modal.find('.modal-dialog');

    // Clear previous content
    $viewer_container.empty();

    // Conditionally render the viewer based on file type
    if (['jpg', 'jpeg', 'png', 'gif'].includes(file_type)) {
      // For images, ensure modal is not fullscreen
      // and show the print button if the user has permission
      $modal_dialog.removeClass('modal-fullscreen');
      $viewer_container.html(`<img src="${certificate_url}" class="img-fluid" alt="Training Certificate">`);
      if (can_print) {
        $('#print_certificate_btn').show();
      }
    } else if (file_type === 'pdf') {
      // For PDFs, make the modal fullscreen for better viewing
      // and hide our custom print button, as PDFs have their own.
      $modal_dialog.addClass('modal-fullscreen'); // Make the modal fullscreen
      $viewer_container.html(`<embed src="${certificate_url}" type="application/pdf" width="100%" height="100%" />`); // Set embed height to 100%
      $('#print_certificate_btn').hide();
    } else {
      $modal_dialog.removeClass('modal-fullscreen');
      $viewer_container.html('<p class="text-muted">Unsupported file type. Cannot display preview.</p>');
    }

    // Set the image source and title in the modal
    $view_certificate_modal.find('.modal-title').text(certificate_name);

    // Store the URL for the print button
    print_url = certificate_url;
  });

  // Handle the print button click inside the modal

  $('#print_certificate_btn').on('click', function () {
    const print_window = window.open('', '_blank', 'height=600,width=800');
    print_window.document.write('<html><head><title>Print Certificate</title>');
    print_window.document.write(
      '<style>body { margin: 0; padding: 0; background-color: #fff !important; } img { max-width: 100%; } @media print { body { -webkit-print-color-adjust: exact; background-color: #fff !important; } }</style>'
    );
    print_window.document.write('</head><body>');
    print_window.document.write(`<img src="${print_url}" />`);
    print_window.document.write('</body></html>');
    print_window.document.close();

    // Use a timeout to ensure the image has loaded before printing
    setTimeout(function () {
      print_window.print();
    }, 500);

    // This event will fire after the user prints or cancels the print dialog.
    print_window.onafterprint = function () {
      print_window.close();
    };
  });

  // --- Trigger ready event for other scripts ---
  $(document).trigger('datatable:ready');
});
