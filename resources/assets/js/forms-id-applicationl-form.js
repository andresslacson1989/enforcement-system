'use strict';

$(function () {
  $('.select2').select2();
  $.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN': $('input[name="_token"]').val()
    }
  });

  // --- Form Validation Setup ---
  const form = document.getElementById('id_application_form');

  // This is the validation engine, configured for your form fields.
  const fv = FormValidation.formValidation(form, {
    fields: {
      emergency_contact_name: {
        validators: {
          notEmpty: {
            message: 'The emergency contact name is required.'
          }
        }
      },
      emergency_contact_relation: {
        validators: {
          notEmpty: {
            message: 'The emergency contact relation is required.'
          }
        }
      },
      emergency_contact_number: {
        validators: {
          notEmpty: {
            message: 'The emergency contact number is required.'
          }
        }
      },
      emergency_contact_address: {
        validators: {
          notEmpty: {
            message: 'The emergency contact address is required.'
          }
        }
      }
    },
    plugins: {
      trigger: new FormValidation.plugins.Trigger(),
      bootstrap5: new FormValidation.plugins.Bootstrap5({
        eleValidClass: '', // We don't need to show green checkmarks
        rowSelector: '.mb-3' // This is the class of the parent div for each field
      }),
      submitButton: new FormValidation.plugins.SubmitButton(),
      // This will automatically focus on the first invalid field
      autoFocus: new FormValidation.plugins.AutoFocus()
    }
  });

  // --- AJAX Submission on Valid Form ---
  // This event is triggered by the FormValidation library ONLY when all fields are valid.
  fv.on('core.form.valid', function () {
    const submitBtn = $('#submitBtn');
    const formElement = $(form); // Use the form element we already have

    // Disable the button and show a "loading" state
    submitBtn.prop('disabled', true).text('Submitting...');

    // Submit the form data via AJAX
    $.ajax({
      url: formElement.attr('action'),
      method: 'POST',
      data: formElement.serialize(),
      dataType: 'json',
      success: function (response) {
        Swal.fire({
          icon: 'success',
          title: 'Success!',
          text: response.text // Display the success message from the server
        }).then(() => {
          // Redirect or clear the form after success
          window.location.href = '/form-library'; // Redirect to the library
        });
      },
      error: function (xhr) {
        // For any server errors, show a generic error message
        Swal.fire({
          icon: 'error',
          title: 'An error occurred!',
          text: 'Something went wrong on the server. Please try again later.'
        });
      },
      complete: function () {
        // Re-enable the button
        submitBtn.prop('disabled', false).text('Submit Application');
      }
    });
  });
});
