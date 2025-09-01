'use strict';

$(function () {
  $('.select2').select2({
    dropdownParent: $('#edit_profile_modal .modal-body')
  });

  $('#forms_table').DataTable({});

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

  const users_modal = $('#edit_profile_modal');
  const users_form = $('#edit_profile_form');
  const body = $('body');

  // Prepare Modal for EDIT action
  body.on('click', '.edit-user', function () {
    const userId = $(this).data('user-id');

    // Fetch user data using the new standard 'show' method
    $.get(`/staffs/${userId}`, function (data) {
      users_form.find('input[name="_method"]').remove(); // Clear previous method spoofing

      // Configure modal for editing
      users_form.attr('action', `/staffs/update/${userId}`);
      users_form.append('<input type="hidden" name="_method" value="PUT">');

      // Populate fields
      for (const key in data) {
        $(`#${key}`).val(data[key]).trigger('change');
      }

      users_modal.modal('show');
    }).fail(() => Swal.fire('Error!', 'Could not retrieve user data.', 'error'));
  });

  // Unified Form Submission (for both Add and Edit)
  users_form.on('submit', function (e) {
    Swal.fire({
      title: 'Please Wait!',
      text: 'Processing your request...',
      allowOutsideClick: false,
      showConfirmButton: false, // This hides the "OK" button
      willOpen: () => {
        Swal.showLoading(); // 2. Show the spinner
      }
    });
    e.preventDefault();
    const form = $(this);
    $.ajax({
      url: form.attr('action'),
      method: 'POST', // Always POST
      data: form.serialize(),
      beforeSend: () => {
        form.find('.error-text').text('');
        form.find('.is-invalid').removeClass('is-invalid');
      },
      success: response => {
        users_modal.modal('hide');
        Swal.fire({ icon: 'success', title: response.text || 'Success!', showConfirmButton: false, timer: 1500 });
        dt_users.ajax.reload();
      },
      error: jqXHR => {
        if (jqXHR.status === 422) {
          const errors = jqXHR.responseJSON.errors;
          for (const key in errors) {
            $(`#${key}`).addClass('is-invalid').siblings('.error-text').text(errors[key][0]);
          }
        } else {
          Swal.fire({ icon: 'error', title: 'Request Failed', text: 'An error occurred on the server.' });
        }
      }
    });
  });
});
