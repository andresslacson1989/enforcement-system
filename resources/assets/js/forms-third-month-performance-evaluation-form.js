/**
 * app-academy-course Script
 */

'use strict';

$(function () {
  const $form = $('#third-month-performance-evaluation-form');
  $('.select2').select2({
    dropdownParent: $('#users_modal .modal-body')
  });

  // Initialize flatpickr for all date inputs on this form
  const $date_inputs = $('.flatpickr-date');
  if ($date_inputs.length) {
    $date_inputs.flatpickr({
      altInput: true,
      altFormat: 'F j, Y',
      dateFormat: 'Y-m-d'
    });
  }

  $form.on('submit', function (e) {
    e.preventDefault(); // Prevent the default form submission

    // Show a simple confirmation SweetAlert
    Swal.fire({
      title: 'Are you sure?',
      text: 'This action will submit the evaluation.',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: 'Yes, submit it!',
      cancelButtonText: 'Cancel'
    }).then(result => {
      // Check if the user clicked the "confirm" button.
      if (result.isConfirmed) {
        // If confirmed, proceed with the AJAX form submission.
        const url = $form.attr('action');
        const form_data = $form.serialize();
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
          type: 'POST',
          url: url,
          data: form_data,
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
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
              if (data.message === 'Success') {
                window.location.href = '/form/view/third-month-performance-evaluation-form/' + data.form_id;
              }
            });
          },
          error: function (xhr, status, error) {
            console.log(xhr, status, error);
            Swal.fire({
              title: 'Error',
              text: xhr.responseJSON.message,
              icon: 'error',
              customClass: {
                confirmButton: 'btn btn-primary'
              },
              buttonsStyling: false
            });
          }
        });
      }
    });
  });

  //fetch employee data
  // Listen for the 'change' event on your employee dropdown
  $('#employee_id').on('change', function() {
    const user_id = $(this).val();
    Swal.fire({
      title: 'Processing...',
      text: 'Please wait while we save your data.',
      allowOutsideClick: false, // Prevents closing by clicking outside
      didOpen: () => {
        Swal.showLoading(); // Display the loading spinner
      }
    });
    // If the selected value is empty, do nothing or clear the fields
    if (!user_id || user_id.trim() === '') {
      $('#employee_number').val('');
      $('#detachment_id').val('');
      // Decide if you want to clear the supervisor field or leave it
      // $('#supervisor').val('');
      return;
    }

    // --- AJAX Request ---
    $.ajax({
      // The URL points to your Laravel route, fetching the selected user
      url: '/user/' + user_id, // Make sure this route exists in your web.php
      type: 'GET',
      dataType: 'json',
      success: function (response) {
        // console.log(response);
        // This function runs on a successful response from the server.
        // We populate the input fields using their IDs.
        if (response) {
          console.log(response.employee_number);
          $('#employee_number').val(response.employee_number || '');
          $('#detachment_id').val(response.detachment_id);
          $('#detachment_name').val(response.detachment.name || '');

          Swal.close();
        }
      },
      error: function (xhr, status, error) {
        // This function runs if the server returns an error.
        // You can handle errors here, e.g., show a notification.
        console.error('An error occurred: ' + error);
        // Optionally clear the fields on error
        $('#employee_number').val('');
        $('#job_title').val('');
        $('#detachment_id').val('');
      }
    });
    Swal.close();
  });

  //compute overall performance
  // Define the numerical values for each rating
  const rating_scores = {
    poor: 1,
    fair: 2,
    good: 3,
    excellent: 4
  };

  // Find the table and listen for changes on any radio button inside it
  $('.performance-criteria-table').on('change', 'input[type="radio"]', function() {
    let total_score = 0;
    let rating_count = 0;

    // Find all checked radio buttons within the criteria table
    $('.performance-criteria-table input[type="radio"]:checked').each(function() {
      const rating_value = $(this).val();
      if (rating_scores[rating_value]) {
        total_score += rating_scores[rating_value];
        rating_count++;
      }
    });

    if (rating_count > 0) {
      const average_score = total_score / rating_count;
      let overall_rating = '';

      // Determine the final rating based on the average score
      if (average_score <= 1.5) {
        overall_rating = 'poor';
      } else if (average_score <= 2.5) {
        overall_rating = 'fair';
      } else if (average_score <= 3.5) {
        overall_rating = 'good';
      } else {
        overall_rating = 'excellent';
      }

      // Check the corresponding radio button in the "Overall Standing" section
      $('#overall_standing_' + overall_rating).prop('checked', true);
    }
  });

  //Approve the form
  $('#approve_button').on('click', function (event) {
    event.preventDefault();
    const url = $(this).attr('href');
    // --- AJAX Request ---
    // Display the SweetAlert confirmation dialog.
    Swal.fire({
      title: 'Are you sure?',
      text: 'You will not be able to update the form once approved.',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Yes, approve it!'
    }).then(result => {
      // If the user clicks the "Confirm" button...
      if (result.isConfirmed) {
        // Manually submit the form.
        $.ajax({
          url: url,
          method: 'PATCH',
          dataType: 'json',
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          success: function (data) {
            console.log(data);
            // Remove loading state from button
            Swal.fire({
              title: data.message,
              text: data.text,
              icon: data.icon,
              customClass: {
                confirmButton: 'btn btn-primary'
              },
              buttonsStyling: false
            }).then(function () {
              if (data.message === 'Success') {
                location.reload();
              }
            });
          },
          error: function (xhr, status, error) {
            console.log(xhr, status, error);
            Swal.fire({
              title: xhr.responseJSON.message,
              text: xhr.responseJSON.text,
              icon: 'error',
              customClass: {
                confirmButton: 'btn btn-primary'
              },
              buttonsStyling: false
            });
          }
        });
      }
    });
  });

  //Edit
  $('#third_month_performance_evaluation_form_edit').on('submit', function (e) {
    e.preventDefault(); // Prevent the default form submission

    // Show a simple confirmation SweetAlert
    Swal.fire({
      title: 'Are you sure?',
      text: 'This action will update the evaluation.',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: 'Yes, update it!',
      cancelButtonText: 'Cancel'
    }).then(result => {
      // Check if the user clicked the "confirm" button.
      if (result.isConfirmed) {
        // If confirmed, proceed with the AJAX form submission.
        const $form_edit = $(this);
        const url = $form_edit.attr('action');
        const form_data = $form_edit.serialize();
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
          type: 'PUT', // Explicitly set the method to PUT for updates
          url: url,
          data: form_data,
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
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
          error: function (xhr, status, error) {
            console.log(xhr, status, error);
            Swal.fire({
              title: xhr.responseJSON.message,
              text: xhr.responseJSON.text,
              icon: 'error',
              customClass: {
                confirmButton: 'btn btn-primary'
              },
              buttonsStyling: false
            });
          }
        });
      }
    });
  });
});
