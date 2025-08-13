/**
 * app-academy-course Script
 */

'use strict';

$(function () {
  const form = $('#first-month-performance-evaluation-form');
  $('.selectpicker').selectpicker();
  form.on('submit', function (e) {
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
        // NOTE: The line for setting the meeting_date_input has been removed.
        const url = form.attr('action');
        const formData = form.serialize();
        $.ajax({
          type: 'POST',
          url: url,
          data: formData,
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          success: function (response) {
            Swal.fire('Success!', response.message, 'success');
            location.reload();  //form[0].reset();
          },
          error: function (response) {
            let errors = response.responseJSON.errors;
            let errorMessage = 'Validation Error:\n';
            $.each(errors, function (key, value) {
              errorMessage += '- ' + value[0] + '\n';
            });
            Swal.fire('Error!', errorMessage, 'error');
          }
        });
      }
    });
  });

  //fetch employee data
  // Listen for the 'change' event on your employee dropdown
  $('#employee').on('change', function () {
    const userId = $(this).val();

    // If the selected value is empty, do nothing or clear the fields
    if (!userId || userId.trim() === '') {
      $('#employee_number').val('');
      $('#job_title').val('');
      $('#deployment').val('');
      // Decide if you want to clear the supervisor field or leave it
      // $('#supervisor').val('');
      return;
    }

    // --- AJAX Request ---
    $.ajax({
      // The URL points to your Laravel route, fetching the selected user
      url: '/user/' + userId, // Make sure this route exists in your web.php
      type: 'GET',
      dataType: 'json',
      success: function (response) {
        // console.log(response);
        // This function runs on a successful response from the server.
        // We populate the input fields using their IDs.
        if (response) {
          $('#employee_number').val(response.employee_number || '');
          $('#job_title').val(response.job_title || '');
        }
      },
      error: function (xhr, status, error) {
        // This function runs if the server returns an error.
        // You can handle errors here, e.g., show a notification.
        console.error('An error occurred: ' + error);
        // Optionally clear the fields on error
        $('#employee_number').val('');
        $('#job_title').val('');
        $('#deployment').val('');
      }
    });
  });

  //compute overall performance
  // Define the numerical values for each rating
  const ratingScores = {
    poor: 1,
    fair: 2,
    good: 3,
    excellent: 4
  };

  // Find the table and listen for changes on any radio button inside it
  $('#performance-criteria-table').on('change', 'input[type="radio"]', function () {
    let totalScore = 0;
    let ratingCount = 0;

    // Find all checked radio buttons within the criteria table
    $('#performance-criteria-table input[type="radio"]:checked').each(function () {
      const ratingValue = $(this).val();
      if (ratingScores[ratingValue]) {
        totalScore += ratingScores[ratingValue];
        ratingCount++;
      }
    });

    if (ratingCount > 0) {
      const averageScore = totalScore / ratingCount;
      let overallRating = '';

      // Determine the final rating based on the average score
      if (averageScore <= 1.5) {
        overallRating = 'poor';
      } else if (averageScore <= 2.5) {
        overallRating = 'fair';
      } else if (averageScore <= 3.5) {
        overallRating = 'good';
      } else {
        overallRating = 'excellent';
      }

      // Check the corresponding radio button in the "Overall Standing" section
      $('#overall_standing_' + overallRating).prop('checked', true);
    }
  });
});
