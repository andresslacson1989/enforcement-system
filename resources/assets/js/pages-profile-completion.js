'use strict';

$(function () {
  // Initialize Flatpickr for date fields
  $('.flatpickr-date').flatpickr({
    monthSelectorType: 'static'
  });

  // AJAX form submission with validation
  $('#profile_completion_form').on('submit', function (e) {
    e.preventDefault();

    const form = $(this);
    const submitBtn = form.find('button[type="submit"]');

    // Disable button and clear previous errors
    submitBtn.prop('disabled', true).text('Saving...');
    form.find('.is-invalid').removeClass('is-invalid');
    form.find('.error-text').text('');

    $.ajax({
      url: form.attr('action'),
      method: 'POST',
      data: form.serialize(),
      dataType: 'json',
      success: function (response) {
        Swal.fire({
          icon: 'success',
          title: 'Success!',
          text: response.text,
          showConfirmButton: false,
          timer: 1500
        }).then(() => {
          // Redirect to the URL provided by the server
          window.location.href = response.redirect_url;
        });
      },
      error: function (xhr) {
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
          Swal.fire({ icon: 'error', title: 'An error occurred!', text: 'Something went wrong. Please try again later.' });
        }
        // Re-enable button on any error
        submitBtn.prop('disabled', false).text('Save and Continue');
      }
    });
  });
});