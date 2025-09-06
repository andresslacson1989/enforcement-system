'use strict';

$(function () {
  $('.select2').select2();

  const form1 = $('#remarks_form');

  if (form1.length) {
    form1.on('submit', function (event) {
      event.preventDefault();

      Swal.fire({
        title: 'Are you sure?',
        text: 'This action will submit the form.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, submit it!',
        cancelButtonText: 'Cancel'
      }).then(result => {
        if (result.isConfirmed) {
          // Check if the user clicked the "confirm" button.
          if (result.isConfirmed) {
            const url = form1.attr('action');
            const type = form1.attr('method');
            const post_data = form1.serialize();
            const form_name = form1.attr('id');

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
        }
      });
    });
  }

  //exp date init flatpickr
  var exp_date = $('.exp_date');

  exp_date.flatpickr({
    minDate: 'today',
    altInput: true,
    altFormat: 'F j, Y',
    dateFormat: 'Y-m-d',
    static: true
  });

  //store and update form
  const form2 = $('#requirement-transmittal-form');
  if (form2.length) {
    form2.on('submit', function (event) {
      event.preventDefault();
      const form = $(this);
      const method = form.attr('method');
      const formData = form.serialize();
      const url = form.attr('action');

      // Display the SweetAlert confirmation dialog.
      Swal.fire({
        title: 'Are you sure?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, submit it!'
      }).then(result => {
        // If the user clicks the "Confirm" button...
        if (result.isConfirmed) {
          Swal.fire({
            title: 'Please Wait!',
            text: 'Processing your request...',
            allowOutsideClick: false,
            showConfirmButton: false, // This hides the "OK" button
            willOpen: () => {
              Swal.showLoading(); // 2. Show the spinner
            }
          });
          // Manually submit the form.
          $.ajax({
            url: url,
            method: method,
            data: formData,
            dataType: 'json',
            headers: {
              'X-CSRF-TOKEN': $('input[name="_token"]').val()
            },
            success: function (data) {
              // Remove loading state from button
              Swal.fire({
                title: 'Success',
                text: 'Do you want to upload the training certificate?',
                icon: 'info',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes',
                allowOutsideClick: false,
                allowEscapeKey: false
              }).then(result => {
                // If the user clicks the "Confirm" button...
                if (result.isConfirmed) {
                  window.location.href = '/user/profile/' + data.employee_id + '#trainings';
                } else {
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
