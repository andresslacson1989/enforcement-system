'use strict';

$(function () {
  const $requisition_form = $('#personnel_requisition_form');

  // Initialize standard Select2 elements
  const $client_select = $('#client_id'); // This is the correct ID from the blade file
  const $education_select = $('#education');

  if ($client_select.length) {
    $client_select.select2({
      placeholder: 'Select a client...'
    });
  }

  if ($education_select.length) {
    $education_select.select2({
      placeholder: 'Select an option...'
    });
  }

  // Initialize AJAX-powered Select2 for skills/tags
  const $skills_experience_select = $('#skills_experience');
  if ($skills_experience_select.length) {
    $skills_experience_select.select2({
      tags: true, // Allows creating new tags on the fly
      placeholder: 'Select or type skills...',
      tokenSeparators: [','],
      ajax: {
        url: '/tags/search', // Your endpoint for searching tags
        dataType: 'json',
        delay: 250, // Wait 250ms after typing before searching
        processResults: function (data) {
          return data; // The controller already formats the data correctly
        },
        cache: true
      }
    });
  }

  // Handle form submission with AJAX
  if ($requisition_form.length) {
    $requisition_form.on('submit', function (e) {
      e.preventDefault();
      const form = $(this);
      const url = form.attr('action');
      const method = form.attr('method');
      const form_data = form.serialize();
      const $submit_button = form.find('button[type="submit"]');

      // Disable button and show loading text
      $submit_button.prop('disabled', true).text('Submitting...');

      $.ajax({
        url: url,
        method: method,
        data: form_data,
        success: function (response) {
          Swal.fire({
            icon: 'success',
            title: 'Success!',
            text: response.text,
            customClass: {
              confirmButton: 'btn btn-primary'
            }
          }).then(() => {
            // Redirect to the form library or another appropriate page
            window.location.href = '/form-library';
          });
        },
        error: function (xhr) {
          const error_data = xhr.responseJSON;
          Swal.fire({
            icon: 'error',
            title: 'Submission Failed',
            text: error_data.message || 'An unexpected error occurred.'
          });
        },
        complete: function () {
          // Re-enable the button
          $submit_button.prop('disabled', false).text('Submit Requisition');
        }
      });
    });
  }
});