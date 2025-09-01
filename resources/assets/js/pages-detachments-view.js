$(function () {
  'use strict';

  $('.select2').select2({
    placeholder: 'Choose an option'
  });
  $('.select2-personnel').select2({
    dropdownParent: $('#add_personnel_modal'),
    placeholder: 'Choose an option'
  });
  $('.select2-edit-detachment').select2({
    dropdownParent: $('#edit_detachment_modal'),
    placeholder: 'Choose an option'
  });

  const users_table = $('#users_table');

  // --- MODAL LOGIC ---
  $('#edit_detachment_modal').on('shown.bs.modal', function () {
    // Find the first visible input, textarea, or select element and focus it
    $(this).find('input:visible, textarea:visible, select:visible').first().focus();
  });

  // When the Edit button is clicked, populate the modal with current data from the page
  $('#edit_button').on('click', function () {
    // Get data from the display fields and populate the form
    $('#edit_detachment_form #name').val($('#name_display').text().trim());
    $('#edit_detachment_form #category').val($('#category_display').text().trim());
    $('#edit_detachment_form #street').val($('#street_display').text().trim());
    $('#edit_detachment_form #city').val($('#city_display').text().trim());
    $('#edit_detachment_form #province').val($('#province_display').text().trim());
    $('#edit_detachment_form #zip_code').val($('#zip_code_display').text().trim());
    $('#edit_detachment_form #hours_per_shift').val($('#hours_per_shift_display').text().trim());
    $('#edit_detachment_form #max_hrs_duty').val($('#max_hrs_duty_display').text().trim());
    $('#edit_detachment_form #max_ot').val($('#max_ot_display').text().trim());

    // Populate all pay rates
    $('.rate-field').each(function () {
      const id = $(this).attr('id').replace('_display', '');
      $('#edit_detachment_form #' + id).val($(this).text().trim().replace(/,/g, ''));
    });

    // Populate benefits and deductions
    $('#edit_detachment_form #cash_bond').val($('#cash_bond_display').text().trim().replace(/,/g, ''));
    $('#edit_detachment_form #sil').val($('#sil_display').text().trim().replace(/,/g, ''));
    $('#edit_detachment_form #ecola').val($('#ecola_display').text().trim().replace(/,/g, ''));
    $('#edit_detachment_form #retirement_pay').val($('#retirement_pay_display').text().trim());
    $('#edit_detachment_form #thirteenth_month_pay').val($('#thirteenth_month_pay_display').text().trim());
    $('#edit_detachment_form #assigned_officer').selectpicker(
      'val',
      $('#assigned_officer_display').attr('data-officer-id')
    );
  });

  // Edit Detachment submission via AJAX
  $('#edit_detachment_form').on('submit', function (event) {
    event.preventDefault(); // Prevent the default browser form submission
    const form = $(this);
    const url = form.attr('action');
    const method = form.attr('method');
    const formData = $(this).serialize(); // Serialize form data for submission
    Swal.fire({
      title: 'Please Wait!',
      text: 'Processing your request...',
      allowOutsideClick: false,
      showConfirmButton: false, // This hides the "OK" button
      willOpen: () => {
        Swal.showLoading(); // 2. Show the spinner
      }
    });
    // --- DATA SAVING ---
    $.ajax({
      url: url, // Example URL
      type: method, // Form uses POST, but @method('PUT') handles the update
      data: formData,
      success: function (data) {
        if (data.message === 'Success') {
          Swal.fire({
            title: data.message,
            text: data.text,
            customClass: {
              confirmButton: 'btn btn-primary'
            },
            buttonsStyling: false
          }).then(() => {
            window.location.reload();
          });
        } else {
          Swal.fire({
            title: data.message,
            text: data.text,
            icon: date.icon,
            customClass: {
              confirmButton: 'btn btn-primary'
            },
            buttonsStyling: false
          });
        }

        // Hide the modal
        $('#edit_detachment_modal').modal('hide');
      },
      error: function (xhr, status, error) {
        console.log(xhr, error);
        Swal.fire({
          title: error,
          text: xhr.responseJSON.message,
          icon: 'error',
          customClass: {
            confirmButton: 'btn btn-primary'
          },
          buttonsStyling: false
        });
      }
    });
  });

  // Add Personnel via AJAX
  $('#add_personnel_form').on('submit', function (event) {
    event.preventDefault(); // Prevent the default browser form submission
    const form = $(this);
    const url = form.attr('action');
    const method = form.attr('method');
    const formData = form.serialize(); // Correctly captures the array of user_ids[]
    const submitButton = form.find('button[type="submit"]');

    // Disable button to prevent multiple clicks
    submitButton.prop('disabled', true).text('Adding...');
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
      url: url,
      type: method,
      data: formData,
      dataType: 'json',
      success: function (data) {
        Swal.fire({
          title: data.message,
          text: data.text,
          icon: data.icon,
          customClass: {
            confirmButton: 'btn btn-primary'
          },
          buttonsStyling: false
        }).then(function () {
          location.reload();
        });
      },
      // Error callback function
      error: function (xhr, status, error) {
        console.log(xhr, error);
        Swal.fire({
          title: error,
          text: xhr.responseJSON.message,
          icon: 'error',
          customClass: {
            confirmButton: 'btn btn-primary'
          },
          buttonsStyling: false
        });
      },
      complete: function () {
        // Re-enable the button and reset its text
        submitButton.prop('disabled', false).text('Add to Roster');
      }
    });
  });

  //Approve Detachment
  $('#approve_button').on('click', function (event) {
    event.preventDefault();
    const id = $(this).data('detachment-id');

    // Show a simple confirmation SweetAlert
    Swal.fire({
      title: 'Are you sure?',
      text: 'This action will approve the addition of the detachment.',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: 'Yes, approve it!',
      cancelButtonText: 'Cancel'
    }).then(result => {
      // Check if the user clicked the "confirm" button.
      if (result.isConfirmed) {
        // If confirmed, proceed with the AJAX form submission.

        $.ajax({
          url: '/detachments/approve/' + id,
          method: 'patch',
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          data: {
            id
          },
          dataType: 'json', // Expect a JSON response from the server
          // Success callback function
          success: function (data) {
            Swal.fire({
              title: data.message,
              text: data.text,
              icon: data.icon,
              customClass: {
                confirmButton: 'btn btn-primary'
              },
              buttonsStyling: false
            }).then(function () {
              location.reload();
            });
          },

          // Error callback function
          error: function (xhr, status, error) {
            console.log(xhr, error);
            Swal.fire({
              title: error,
              text: xhr.responseJSON.error,
              icon: 'error',
              customClass: {
                confirmButton: 'btn btn-primary'
              },
              buttonsStyling: false
            });
          },

          // This function is always called, regardless of success or error
          complete: function () {
            // Re-enable the submit button
            submitButton.text('Submit');
          }
        });
      }
    });
  });

  // Initialize DataTables
  if (users_table.length) {
    const detachment_id = users_table.data('detachment-id');
    window.dt_users = users_table.DataTable({
      ajax: {
        url: '/personnel/detachment-personnel-table',
        data: function (d) {
          d.role_filter = $('#role_filter').val();
          d.status_filter = $('#status_filter').val();
          d.detachment_id = detachment_id;
        }
      },
      processing: true,
      serverSide: true,
      columns: [
        { data: 'name', name: 'name' },
        { data: 'role', name: 'role' },
        { data: 'status', name: 'status' },
        { data: 'action', orderable: false, searchable: false }
      ],
      language: { searchPlaceholder: 'Search Detachment...' }
    });
    $(document).trigger('datatable:ready', [dt_users]);
  }
});
