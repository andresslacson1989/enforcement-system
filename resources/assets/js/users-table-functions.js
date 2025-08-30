// In: resources/assets/js/pages-staffs.js
$(function () {
  'use strict';

  const users_modal = $('#users_modal');
  const users_form = $('#users_form');
  const body = $('body');

  // Handle Filters
  $('#role_filter, #status_filter').on('change', () => dt_users.ajax.reload());

  // Prepare Modal for ADD action
  $('button[data-bs-target="#staff_modal"]').on('click', function () {
    users_form[0].reset();
    users_form.find('.is-invalid').removeClass('is-invalid');
    users_form.find('.error-text').text('');
    users_form.find('input[name="_method"]').remove();
    $('.selectpicker').selectpicker('val', ''); // Clear selectpickers
    $('#modal_title').text('Add');
    $('#modal_button').text('Add');
    users_form.attr('action', '/staffs/store');
  });

  // Prepare Modal for EDIT action
  body.on('click', '.edit-user', function () {
    const userId = $(this).data('user-id');

    // Fetch user data using the new standard 'show' method
    $.get(`/staffs/${userId}`, function (data) {
      users_form.find('input[name="_method"]').remove(); // Clear previous method spoofing

      // Configure modal for editing
      $('#modal_title').text('Edit');
      $('#modal_button').text('Save Changes');
      users_form.attr('action', `/staffs/update/${userId}`);
      users_form.append('<input type="hidden" name="_method" value="PUT">');

      // Populate fields
      for (const key in data) {
        if (key === 'primary_role' && data[key]) {
          $('#role').selectpicker('val', data[key].name);
        } else {
          $(`#${key}`).val(data[key]);
        }
      }
      // Refresh selectpickers after setting value
      $('#gender, #status').selectpicker('refresh');

      users_modal.modal('show');
    }).fail(() => Swal.fire('Error!', 'Could not retrieve user data.', 'error'));
  });

  // Unified Form Submission (for both Add and Edit)
  users_form.on('submit', function (e) {
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
        dt_users.ajax.reload();
        Swal.fire({ icon: 'success', title: response.text || 'Success!', showConfirmButton: false, timer: 1500 });
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

  // ## Delete User Logic ##
  body.on('click', '.delete-user', function () {
    const userId = $(this).data('user-id');
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
        $.ajax({
          url: `/staffs/delete/${userId}`,
          method: 'POST', // Use POST with method spoofing
          data: {
            _method: 'DELETE',
            _token: $('input[name="_token"]').val()
          },
          success: function (response) {
            dt_users.ajax.reload();
            Swal.fire('Deleted!', response.text, 'success');
          },
          error: function () {
            Swal.fire('Error!', 'Could not delete the user.', 'error');
          }
        });
      }
    });
  });

  // ## Suspend User Logic ##
  body.on('click', '.suspend-user', function () {
    const userId = $(this).data('user-id');
    const today = new Date().toISOString().split('T')[0]; // Get today's date in YYYY-MM-DD format

    // 1. Define the HTML for our form inside SweetAlert
    const formHtml = `
      <form id="sweetalert-suspend-form" class="text-start">
        <input type="hidden" name="user_id" value="${userId}">

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
      html: formHtml,
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
  body.on('click', '.unsuspend-user', function () {
    const userId = $(this).data('user-id');

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
        $.ajax({
          url: `/staffs/unsuspend/`,
          method: 'POST',
          data: {
            _token: $('input[name="_token"]').val(),
            user_id: userId
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

  //## Change Roles
  $(document).on('click', '.change-role-btn', function () {
    const userId = $(this).data('userId');
    const userName = $(this).data('userName');
    const currentRoleId = $(this).data('currentRoleId');

    // Fetch the list of roles from your endpoint
    $.get('/get-roles/personnel-staff', function (roles) {
      let roleOptionsHtml = '';
      $.each(roles, function (index, role) {
        const isSelected = role.id == currentRoleId ? 'selected' : '';
        roleOptionsHtml += `<option value="${role.id}" ${isSelected}>${role.name}</option>`;
      });
      const selectHtml = `<select id="swal-role-select" class="form-select">${roleOptionsHtml}</select>`;

      // Display the SweetAlert with the dynamic options
      Swal.fire({
        title: `Change role for ${userName}`,
        html: selectHtml,
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
          const newRoleId = result.value;
          const csrfToken = $('meta[name="csrf-token"]').attr('content');

          // 5. Handle the submission with AJAX
          $.ajax({
            url: `/personnel/update-role/${userId}`,
            method: 'POST',
            data: {
              _token: csrfToken,
              _method: 'PATCH',
              role_id: newRoleId
            },
            success: function (response) {
              // On success, just reload the table - no page refresh!
              dt_users.ajax.reload();
              Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: response.text || "The user's role has been updated.",
                showConfirmButton: false,
                timer: 1500
              });
            },
            error: function (jqXHR) {
              const errorText = jqXHR.responseJSON?.message || 'Could not update the role.';
              Swal.fire('Error!', errorText, 'error');
            }
          });
        }
      });
    }).fail(function () {
      Swal.fire({ title: 'Error!', text: 'Could not load roles.', icon: 'error' });
    });
  });
});
