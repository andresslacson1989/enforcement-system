/**
 * app-academy-course Script
 */

'use strict';

$(function () {
  $('.select2-detachment').select2({
    dropdownParent: $('#add_detachment_modal'),
    placeholder: 'Choose an option'
  });

  $('.select2').select2({
    placeholder: 'Choose an option'
  });

  $('#detachment_table').DataTable({
    processing: true,
    serverSide: true,
    ajax: '/detachments/table',
    columns: [
      { data: 'name', name: 'name' },
      { data: 'commander_name', name: 'commander_name' },
      { data: 'users_count', name: 'users_count' },
      { data: 'city', name: 'city' },
      { data: 'action', name: 'action', orderable: false, searchable: false }
    ],
    order: [],
    columnDefs: [
      {
        targets: [4],
        orderable: false,
        searchable: false
      }
    ],
    language: { searchPlaceholder: 'Search Detachment...' }
  });

  // Intercept the form submission event
  $('#add_detachment_form').on('submit', function (event) {
    // Prevent the default form submission which causes a page reload
    event.preventDefault();

    // Clear previous messages
    $('#form-messages').html('');
    $('.form-control').removeClass('is-invalid');
    $('.invalid-feedback').remove();

    // Get form data
    var formData = $(this).serialize();
    var formAction = $(this).attr('action');
    var formMethod = $(this).attr('method');

    // Disable the submit button to prevent multiple submissions
    var submitButton = $(this).find('button[type="submit"]');
    submitButton.text('Submitting...');
    Swal.fire({
      title: 'Please Wait!',
      text: 'Processing your request...',
      allowOutsideClick: false,
      showConfirmButton: false, // This hides the "OK" button
      willOpen: () => {
        Swal.showLoading(); // 2. Show the spinner
      }
    });
    // Perform the AJAX request
    $.ajax({
      url: formAction,
      method: formMethod,
      data: formData,
      dataType: 'json', // Expect a JSON response from the server
      // Success callback function
      success: function (data) {
        $('#add_detachment_form')[0].reset();
        Swal.fire({
          title: data.message,
          text: data.text,
          icon: data.icon,
          customClass: {
            confirmButton: 'btn btn-primary'
          },
          buttonsStyling: false
        }).then(function () {
          $('#add_detachment_modal').modal('hide');
          $('#detachment_table').DataTable().ajax.reload();
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
  });
});
