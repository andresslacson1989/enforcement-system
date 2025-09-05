'use strict';
$(function () {
  // --- Global AJAX Setup for CSRF Token ---
  // This is the standard and most reliable way to ensure all AJAX requests
  // include the CSRF token, preventing mismatch errors.
  $.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
  });

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

  // --- File Explorer & Upload Logic ---
  // This section is wrapped in a check to ensure it only runs on the profile page.
  if ($('#file_manager_app').length) {
    let current_view_mode = 'list';
    let current_category_filter = 'all';
    let current_search_term = '';
    let current_page_number = 1;
    const user_id_for_files = $('#upload_file_form input[name="user_id"]').val();

    // --- Main AJAX Fetch Function (defined at the top of the scope) ---
    function fetchFiles(page_number = 1) {
      current_page_number = page_number;
      const display_area = $('#file_display_area');
      const pagination_links = $('#file_pagination_links');

      // Show a loading spinner or overlay
      display_area.html(
        '<div class="d-flex justify-content-center mt-5"><div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div></div>'
      );
      pagination_links.empty();
      $('#no_files_in_category_message, #no_search_results_message').hide();

      $.ajax({
        url: `/user/${user_id_for_files}/files`,
        data: {
          page: current_page_number,
          category: current_category_filter,
          search: current_search_term,
          view_mode: current_view_mode
        },
        success: function (response) {
          display_area.html(response.html);
          pagination_links.html(response.pagination);

          // Update category counts
          for (const category in response.counts) {
            $(`#file_category_list a[data-category="${category}"] .badge`).text(response.counts[category]);
          }

          // Check if the returned HTML is empty or just whitespace
          if (!response.html.trim()) {
            if (current_search_term) {
              $('#no_search_results_message').show();
            } else {
              $('#no_files_in_category_message').show();
            }
          }
        },
        error: function () {
          display_area.html('<p class="text-center text-danger">Could not load files. Please try again.</p>');
        }
      });
    }

    // --- Event Handlers ---

    // Category selection
    $('#file_category_list a').on('click', function (e) {
      e.preventDefault();
      $('#file_category_list a').removeClass('active');
      $(this).addClass('active');
      $('#file_search_input').val('');
      current_search_term = '';
      current_category_filter = $(this).data('category');
      fetchFiles(1); // Fetch page 1 of the new category
    });

    // View mode toggle
    $('#file_view_grid_btn').on('click', function () {
      current_view_mode = 'grid';
      $(this).addClass('active');
      $('#file_view_list_btn').removeClass('active');
      fetchFiles(current_page_number);
    });

    $('#file_view_list_btn').on('click', function () {
      current_view_mode = 'list';
      $(this).addClass('active');
      $('#file_view_grid_btn').removeClass('active');
      fetchFiles(current_page_number);
    });

    // Live search (with debounce to avoid excessive requests)
    let search_timeout;
    $('#file_search_input').on('keyup', function () {
      clearTimeout(search_timeout);
      current_search_term = $(this).val().toLowerCase();
      search_timeout = setTimeout(function () {
        fetchFiles(1); // Always go to page 1 for a new search
      }, 300); // 300ms delay
    });

    // Pagination links (using event delegation)
    $(document).on('click', '#file_pagination_links a', function (e) {
      e.preventDefault();
      const page_number = $(this).attr('href').split('page=')[1];
      fetchFiles(page_number);
    });

    // --- Event Delegation for Dynamic Content ---
    // We attach the listeners to a static parent container, '#file_manager_app'.
    // This ensures that events are captured for elements loaded via AJAX.
    const file_manager_app = $('#file_manager_app');
    file_manager_app.on('click', '.delete-file-btn', function (e) {
      e.preventDefault();
      const file_id_to_delete = $(this).data('file-id');

      Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, delete it!',
        customClass: {
          confirmButton: 'btn btn-danger me-3',
          cancelButton: 'btn btn-label-secondary'
        },
        buttonsStyling: false
      }).then(function (result) {
        if (result.isConfirmed) {
          $.ajax({
            url: `/user-files/${file_id_to_delete}`,
            method: 'POST', // Use POST with method spoofing for DELETE
            data: { _method: 'DELETE' },
            success: function (response) {
              Swal.fire('Deleted!', response.message, 'success');
              fetchFiles(current_page_number);
            },
            error: function (xhr) {
              const error_message = xhr.responseJSON?.message || 'Could not delete the file.';
              Swal.fire('Error!', error_message, 'error');
            }
          });
        }
      });
    });

    // --- File Preview Logic (using event delegation) ---
    file_manager_app.on('click', '.file-card-item, .file-list-item', function (e) {
      // Stop if the click was on a dropdown or a button inside it
      if ($(e.target).closest('.dropdown').length) {
        return;
      }

      const file_url_for_preview = $(this).data('file-url');
      const file_title_for_preview = $(this).data('file-title');
      const mime_type_for_preview = $(this).data('mime-type');
      const preview_container = $('#file_preview_container');
      const preview_modal = $('#filePreviewModal');

      preview_container.html(
        '<div class="d-flex justify-content-center align-items-center h-100"><div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div></div>'
      );
      preview_modal.find('.modal-title').text(file_title_for_preview);
      $('#download_file_btn').attr('href', file_url_for_preview);

      if (mime_type_for_preview.startsWith('image/')) {
        preview_container.html(
          `<img src="${file_url_for_preview}" class="img-fluid" style="max-height: 100%; object-fit: contain;">`
        );
      } else if (mime_type_for_preview === 'application/pdf') {
        preview_container.html(
          `<embed src="${file_url_for_preview}" type="application/pdf" width="100%" height="100%" />`
        );
      } else {
        preview_container.html(`
          <div class="d-flex flex-column justify-content-center align-items-center h-100">
            <i class="ti tabler-file-alert ti-lg mb-3"></i>
            <h5>No Preview Available</h5>
            <p class="text-muted">You can download the file to view it.</p>
          </div>
        `);
      }

      preview_modal.modal('show');
    });

    // --- Upload File Logic (Reverted to simpler version) ---
    const upload_file_form = $('#upload_file_form');
    upload_file_form.on('submit', function (e) {
      e.preventDefault();
      const form_data = new FormData(this);
      // The CSRF token is now handled globally by $.ajaxSetup,
      // so we don't need to append it here manually.

      // Show a loading state
      Swal.fire({
        title: 'Uploading...',
        text: 'Please wait while your file is being uploaded.',
        allowOutsideClick: false,
        didOpen: () => {
          Swal.showLoading();
        }
      });

      $.ajax({
        url: '/user/upload-file',
        method: 'POST',
        data: form_data,
        processData: false,
        contentType: false,
        success: function (response) {
          $('#uploadFileModal').modal('hide');
          upload_file_form[0].reset();
          fetchFiles(1); // Immediately refresh the file list to show the new file
          Swal.fire({
            icon: 'success',
            title: 'Success!',
            text: response.message,
            timer: 1500, // Auto-close the alert after 1.5 seconds
            showConfirmButton: false
          });
        },
        error: function (xhr) {
          const error_message = xhr.responseJSON?.message || 'An unexpected error occurred.';
          Swal.fire('Upload Failed', error_message, 'error');
        }
      });
    });

    // --- Initial Render ---
    fetchFiles(1);
  }
});
