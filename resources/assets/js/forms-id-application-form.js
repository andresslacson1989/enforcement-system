'use strict';

$(function () {
  $('.select2').select2();
  $.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN': $('input[name="_token"]').val()
    }
  });

  // This is the jQuery validation pattern, same as in users-table-functions.js
  $('#id_application_form').on('submit', function (e) {
    e.preventDefault();

    const form = $(this);
    const submitBtn = $('#submitBtn');

    // Disable the button and show a "loading" state
    submitBtn.prop('disabled', true).text('Submitting...');

    // Clear previous errors before submitting
    form.find('.is-invalid').removeClass('is-invalid');
    form.find('.error-text').text('');

    // Submit the form data via AJAX
    $.ajax({
      url: form.attr('action'),
      method: 'POST',
      data: form.serialize(),
      dataType: 'json',
      success: function (response) {
        Swal.fire({
          icon: 'success',
          title: 'Success!',
          text: response.text
        }).then(() => {
          window.location.href = '/form-library'; // Redirect to the library
        });
      },
      error: function (xhr) {
        // This is the Laravel validation error handling
        if (xhr.status === 422) {
          const errors = xhr.responseJSON.errors;
          // Loop through errors and display them next to the correct input
          for (const key in errors) {
            const field = $(`[name="${key}"]`);
            field.addClass('is-invalid');
            field.siblings('.error-text').text(errors[key][0]);
          }
        } else {
          // For all other server errors
          Swal.fire({
            icon: 'error',
            title: 'An error occurred!',
            text: 'Something went wrong on the server. Please try again later.'
          });
        }
      },
      complete: function () {
        submitBtn.prop('disabled', false).text('Submit Application');
      }
    });
  });
});
