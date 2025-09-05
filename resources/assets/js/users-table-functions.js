// Listen for the custom event 'datatable:ready'
$(document).on('datatable:ready', function (e, dt_instance, table_id) {
  // Initialize Flatpickr for the birth_date input in the modal
  // We target the class '.flatpickr-date' within the modal's context.
  $('#users_modal .flatpickr-date').flatpickr({
    monthSelectorType: 'static',
    static: true
  });

  const $select2_modal = $('.select2-modal');
  $select2_modal.select2({
    dropdownParent: $('#users_modal .modal-body'),
    placeholder: 'Choose an option'
  });

  const $users_modal = $('#users_modal');
  const $users_form = $('#users_form');
  const $body = $('body');
  let dt_users; // Declare a local variable to hold the DataTable instance
  dt_users = dt_instance; // Get the DataTable instance from the event data
  // Handle Filters
  $('#role_filter, #status_filter').on('change', () => dt_users.ajax.reload());

  // Prepare Modal for ADD action
  $('button[data-bs-target="#users_modal"]').on('click', function () {
    resetModal('Add User', 'Add', '/staffs/store');
  });

  // Prepare Modal for EDIT action
  $body.on('click', '.edit-user', function () {
    const user_id = $(this).data('user-id');
    resetModal('Edit User', 'Save Changes', `/staffs/update/${user_id}`);
    // Fetch user data...
    $.get(`/staffs/${user_id}`, function (data) {
      $users_form.append('<input type="hidden" name="_method" value="PUT">');
      // Populate fields...
      for (const key in data) {
        const field = $(`[name="${key}"]`);
        if (field.length) {
          const type = field.attr('type');
          if (type === 'checkbox') {
            field.prop('checked', !!data[key]); // Use .prop() for booleans
          } else if (type === 'radio') {
            $(`[name="${key}"][value="${data[key]}"]`).prop('checked', true);
          } else {
            field.val(data[key]).trigger('change'); // For text, select, etc.
          }
        }
      }
      Swal.close();
      $users_modal.modal('show');
    }).fail(() => Swal.fire('Error!', 'Could not retrieve user data.', 'error'));
  });

  // Unified Form Submission (for both Add and Edit)
  $users_form.on('submit', function (e) {
    e.preventDefault();
    const form = $(this);
    const $submit_button = $(e.originalEvent.submitter);
    const submit_action = $submit_button.val(); // 'personal', 'address', 'all', etc.

    let data_to_submit;
    let success_message;

    // Determine which data to serialize based on the button clicked
    if (submit_action === 'all') {
      data_to_submit = form.serialize();
      success_message = 'All changes have been saved successfully!';
    } else {
      // Find the tab pane associated with the button and serialize its inputs
      const tab_pane = $submit_button.closest('.tab-pane');
      data_to_submit = tab_pane.find(':input').serialize();
      // We also need to manually add the _method and _token for partial updates
      data_to_submit += '&' + form.find('input[name="_method"], input[name="_token"]').serialize();
      success_message = `${$submit_button.text().replace('Save ', '')} updated successfully!`;
    }

    $.ajax({
      url: form.attr('action'),
      method: 'POST',
      data: data_to_submit,
      beforeSend: () => {
        // Clear all previous errors
        form.find('.error-text').text('');
        form.find('.is-invalid').removeClass('is-invalid');
        // Disable the clicked button
        $submit_button.prop('disabled', true).addClass('disabled');
      },
      success: data => {
        $users_modal.modal('hide');
        Swal.fire({
          title: 'Success!',
          text: success_message,
          icon: data.icon,
          showConfirmButton: false,
          timer: 1500
        });
        dt_users.ajax.reload();
      },
      error: jqXHR => {
        if (jqXHR.status === 422) {
          const errors = jqXHR.responseJSON.errors;
          let first_error_field = null;
          for (const key in errors) {
            const field = $(`[name="${key}"]`);
            field.addClass('is-invalid');
            // Use .closest() to find the parent container and then find the error-text div. This is more robust.
            field.closest('.col-md-6, .col-12').find('.error-text').text(errors[key][0]);
            if (!first_error_field) {
              first_error_field = field;
            }
          }
          // If an error field is found, switch to its tab and then focus it.
          if (first_error_field) {
            const tab_pane = first_error_field.closest('.tab-pane');
            if (tab_pane.length) {
              const tab_id = tab_pane.attr('id');
              // Use Bootstrap's API to programmatically show the correct tab
              const tab_trigger = new bootstrap.Tab($(`button[data-bs-target="#${tab_id}"]`)[0]);
              tab_trigger.show();
            }
            // Focus the field after the tab is shown
            first_error_field.focus();
          }
        } else {
          // Only show a generic error for non-validation issues (e.g., server errors)
          Swal.fire({
            icon: 'error',
            title: 'Request Failed',
            text: jqXHR.responseJSON?.message || 'An unknown error occurred.'
          });
        }
      },
      complete: () => $submit_button.prop('disabled', false).removeClass('disabled')
    });
  });

  // ## Delete User Logic ##
  $body.on('click', '.delete-user', function () {
    const user_id = $(this).data('user-id');
    Swal.fire({
      title: 'Are you sure?',
      text: "You won't be able to revert this!",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: 'Yes, delete it!',
      customClass: { confirmButton: 'btn btn-primary me-3', cancelButton: 'btn btn-label-secondary' },
      buttonsStyling: false
    }).then(function (result) {
      if (result.isConfirmed) {
        Swal.fire({
          title: 'Please Wait!',
          text: 'Processing your request...',
          allowOutsideClick: false,
          showConfirmButton: false, // This hides the "OK" button
          willOpen: () => {
            Swal.showLoading(); // 2. Show the spinner
          }
        });
        $.ajax({
          url: `/staffs/delete/${user_id}`,
          method: 'POST', // Use POST with method spoofing
          data: {
            _method: 'DELETE',
            _token: $('input[name="_token"]').val()
          },
          success: function (response) {
            Swal.fire('Deleted!', response.text, 'success');
            dt_users.ajax.reload();
          },
          error: function () {
            Swal.fire('Error!', 'Could not delete the user.', 'error');
          }
        });
      }
    });
  });

  // ## Remove User Logic ##
  $body.on('click', '.remove-user', function () {
    const user_id = $(this).data('user-id');
    const detachment_name = $(this).data('detachment-name');

    Swal.fire({
      title: 'Are you sure?',
      text: 'Remove this personnel from ' + detachment_name + '.',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: 'Yes, remove it!',
      customClass: { confirmButton: 'btn btn-primary me-3', cancelButton: 'btn btn-label-secondary' },
      buttonsStyling: false
    }).then(function (result) {
      if (result.isConfirmed) {
        Swal.fire({
          title: 'Please Wait!',
          text: 'Processing your request...',
          allowOutsideClick: false,
          showConfirmButton: false, // This hides the "OK" button
          willOpen: () => {
            Swal.showLoading(); // 2. Show the spinner
          }
        });
        $.ajax({
          url: `/staffs/remove/${user_id}`,
          method: 'POST', // Use POST with method spoofing
          data: {
            _method: 'PATCH',
            _token: $('input[name="_token"]').val()
          },
          success: function (response) {
            Swal.fire('Removed!', response.text, 'success');
            dt_users.ajax.reload();
          },
          error: function () {
            Swal.fire('Error!', 'Could not delete the user.', 'error');
          }
        });
      }
    });
  });

  // ## Suspend User Logic ##
  $body.on('click', '.suspend-user', function () {
    const user_id = $(this).data('user-id');
    const today = new Date().toISOString().split('T')[0]; // Get today's date in YYYY-MM-DD format

    // 1. Define the HTML for our form inside SweetAlert
    const form_html = `
      <form id="sweetalert-suspend-form" class="text-start">
        <input type="hidden" name="user_id" value="${user_id}">

        <label for="swal-type" class="form-label mt-2">Suspension Type</label>
        <select id="swal-type" name="type" class="form-select">
          <option value="preventive" selected>Preventive</option>
          <option value="disciplinary">Disciplinary</option>
        </select>

        <label for="swal-start-date" class="form-label mt-3">Start Date</label>
        <input type="date" id="swal-start-date" name="start_date" class="form-control" value="${today}" required>

        <label for="swal-end-date" class="form-label mt-3">End Date (Optional)</label>
        <input type="date" id="swal-end-date" name="end_date" class="form-control">

        <label for="swal-reason" class="form-label mt-3">Reason</label>
        <textarea id="swal-reason" name="reason" class="form-control" placeholder="Enter reason for suspension" required></textarea>
      </form>
    `;

    // 2. Configure SweetAlert to use our HTML
    Swal.fire({
      title: 'Suspend User',
      html: form_html,
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: 'Suspend User',
      customClass: { confirmButton: 'btn btn-danger', cancelButton: 'btn btn-label-secondary' },
      buttonsStyling: false,
      preConfirm: () => {
        // 3. Gather data from the form before submission
        const form = document.getElementById('sweetalert-suspend-form');
        if (!form.checkValidity()) {
          Swal.showValidationMessage('Please fill out all required fields.');
          return false;
        }
        return {
          user_id: form.user_id.value,
          type: form.type.value,
          reason: form.reason.value,
          start_date: form.start_date.value,
          end_date: form.end_date.value || null
        };
      }
    }).then(result => {
      if (result.isConfirmed) {
        Swal.fire({
          title: 'Please Wait!',
          text: 'Processing your request...',
          allowOutsideClick: false,
          showConfirmButton: false, // This hides the "OK" button
          willOpen: () => {
            Swal.showLoading(); // 2. Show the spinner
          }
        });
        // 4. Make the AJAX call with all the required data
        $.ajax({
          url: '/staffs/suspend/',
          method: 'POST',
          data: {
            _token: $('input[name="_token"]').val(),
            ...result.value // Use the data collected from the form
          },
          success: function (response) {
            dt_users.ajax.reload();
            Swal.fire('Suspended!', response.text || 'User has been suspended.', 'success');
          },
          error: function (jqXHR) {
            const errorText = jqXHR.responseJSON.message || 'Could not process the request.';
            Swal.fire('Error!', errorText, 'error');
          }
        });
      }
    });
  });

  // ## Unsuspend User Logic ##
  $body.on('click', '.unsuspend-user', function () {
    const user_id = $(this).data('user-id');

    Swal.fire({
      title: 'Unsuspend User?',
      text: "This will reactivate the user's account and allow them to log in again.",
      icon: 'info',
      showCancelButton: true,
      confirmButtonText: 'Yes, unsuspend user!',
      customClass: { confirmButton: 'btn btn-primary me-3', cancelButton: 'btn btn-label-secondary' },
      buttonsStyling: false
    }).then(function (result) {
      if (result.isConfirmed) {
        Swal.fire({
          title: 'Please Wait!',
          text: 'Processing your request...',
          allowOutsideClick: false,
          showConfirmButton: false, // This hides the "OK" button
          willOpen: () => {
            Swal.showLoading(); // 2. Show the spinner
          }
        });
        $.ajax({
          url: `/staffs/unsuspend/`,
          method: 'POST',
          data: {
            _token: $('input[name="_token"]').val(),
            user_id: user_id
          },
          success: function (response) {
            // Reload the DataTable to show the updated status
            dt_users.ajax.reload();
            Swal.fire('Unsuspended!', response.text || 'User has been reactivated.', 'success');
          },
          error: function () {
            Swal.fire('Error!', 'Could not unsuspend the user.', 'error');
          }
        });
      }
    });
  });

  //## Change Roles logic
  $body.on('click', '.change-role-btn', function () {
    const user_id = $(this).data('user-id'); // snake_case variable
    const user_name = $(this).data('user-name'); // snake_case variable
    const current_role_id = $(this).data('current-role-id'); // snake_case variable

    // Dynamically determine the role category based on the table's context
    const context = $(`#${table_id}`).data('context');
    const role_category = context === 'staff' ? 'staff' : 'personnel';
    const url = `/get-roles/${role_category}`;

    // Fetch the list of roles from your endpoint
    $.get(url, function (roles) {
      let role_options_html = '';
      $.each(roles, function (index, role) {
        const is_selected = role.id == current_role_id ? 'selected' : '';
        role_options_html += `<option value="${role.id}" ${is_selected}>${role.name}</option>`;
      });
      const select_html = `<select id="swal-role-select" class="form-select">${role_options_html}</select>`;

      // Display the SweetAlert with the dynamic options
      Swal.fire({
        title: `Change role for ${user_name}`,
        html: select_html,
        icon: 'info',
        showCancelButton: true,
        confirmButtonText: 'Save Changes',
        customClass: {
          confirmButton: 'btn btn-primary',
          cancelButton: 'btn btn-light ms-2'
        },
        buttonsStyling: false,
        preConfirm: () => {
          return $('#swal-role-select').val();
        }
      }).then(result => {
        // This is the updated part
        if (result.isConfirmed) {
          const new_role_id = result.value;
          const csrf_token = $('meta[name="csrf-token"]').attr('content');
          Swal.fire({
            title: 'Please Wait!',
            text: 'Processing your request...',
            allowOutsideClick: false,
            showConfirmButton: false, // This hides the "OK" button
            willOpen: () => {
              Swal.showLoading(); // 2. Show the spinner
            }
          });
          // 5. Handle the submission with AJAX
          $.ajax({
            url: `/personnel/update-role/${user_id}`,
            method: 'POST',
            data: {
              _token: csrf_token,
              _method: 'PATCH',
              role_id: new_role_id
            },
            success: function (response) {
              // On success, just reload the table - no page refresh!
              Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: response.text || "The user's role has been updated.",
                showConfirmButton: false,
                timer: 1500
              });
              dt_users.ajax.reload();
            },
            error: function (jqXHR) {
              const error_text = jqXHR.responseJSON?.message || 'Could not update the role.';
              Swal.fire('Error!', error_text, 'error');
            }
          });
        }
      });
    }).fail(function () {
      Swal.fire({ title: 'Error!', text: 'Could not load roles.', icon: 'error' });
    });
  });

  function resetModal(title, button_text, action) {
    $users_form[0].reset();
    $users_form.find('.is-invalid').removeClass('is-invalid');
    $users_form.find('.error-text').text('');
    $users_form.find('input[name="_method"]').remove();
    $select2_modal.val('').trigger('change'); // Clear select2

    $('#modal_title').text(title);
    $('#modal_button').text(button_text);
    $users_form.attr('action', action);
  }
});
