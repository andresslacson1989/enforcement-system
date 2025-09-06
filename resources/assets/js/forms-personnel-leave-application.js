'use strict';

$(function () {
  $('.select2').select2();
  // Initialize Flatpickr for the date inputs
  const start_date_input = $('#start_date');
  const end_date_input = $('#end_date');

  if (start_date_input.length) {
    start_date_input.flatpickr({
      altInput: true,
      altFormat: 'F j, Y',
      dateFormat: 'Y-m-d'
    });
  }

  if (end_date_input.length) {
    end_date_input.flatpickr({
      altInput: true,
      altFormat: 'F j, Y',
      dateFormat: 'Y-m-d'
    });
  }

  // Handle form submission with jQuery AJAX
  const form = $('#personnel-leave-application-form');

  if (form.length) {
    form.on('submit', function (e) {
      e.preventDefault(); // Prevent the default form submission

      // Show a simple confirmation SweetAlert
      Swal.fire({
        title: 'Are you sure?',
        text: 'This action will submit the form.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, submit it!',
        cancelButtonText: 'Cancel'
      }).then(result => {
        // Check if the user clicked the "confirm" button.
        if (result.isConfirmed) {
          const url = form.attr('action');
          const type = form.attr('method');
          const post_data = form.serialize();

          // Show a loading indicator
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
            type: type,
            url: url,
            data: post_data,
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
                  window.location.href = '/form/view/' + data.form_name + '/' + data.form_id;
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
  }
});
