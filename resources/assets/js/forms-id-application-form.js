'use strict';

$(function () {
  $('.select2').select2();
  $.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN': $('input[name="_token"]').val()
    }
  });

  // --- New Photo Upload Logic ---
  const changePhotoButton = $('#change-photo-btn');
  const photoInput = $('#profile-photo-input');
  const photoPreview = $('#profile-pic-preview');

  // 1. Trigger the hidden file input when the camera button is clicked
  changePhotoButton.on('click', function () {
    photoInput.click();
  });

  // 2. Handle the file selection and show a preview
  photoInput.on('change', function () {
    const file = this.files[0];
    if (file) {
      const reader = new FileReader();
      reader.onload = e => photoPreview.attr('src', e.target.result);
      reader.readAsDataURL(file);
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

    // Use FormData to handle both text fields and the file upload
    const formData = new FormData(form[0]);

    axios
      .post(form.attr('action'), formData)
      .then(response => {
        Swal.fire({
          icon: 'success',
          title: 'Success!',
          text: response.data.text
        }).then(() => {
          window.location.href = '/form/view/id-application-form/' + response.data.form_id; // Redirect to the view page
        });
      })
      .catch(error => {
        if (error.response && error.response.status === 422) {
          const errors = error.response.data.errors;
          // Loop through errors and display them next to the correct input
          for (const key in errors) {
            const field = $(`[name="${key}"]`);
            if (key === 'photo') {
              // Special handling for the photo error message
              $('.photo-error').text(errors[key][0]);
            } else {
              field.addClass('is-invalid');
              field.siblings('.error-text').text(errors[key][0]);
            }
          }
        } else if (error.response && error.response.data && error.response.data.text) {
          Swal.fire({
            icon: error.response.data.icon || 'error',
            title: error.response.data.message || 'An error occurred!',
            text: error.response.data.text
          });
        } else {
          Swal.fire({
            icon: 'error',
            title: 'An error occurred!',
            text: 'Something went wrong on the server. Please try again later.'
          });
        }
      })
      .finally(() => {
        submitBtn.prop('disabled', false).text('Submit Application');
      });
  });
});
