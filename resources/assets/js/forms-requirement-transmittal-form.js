'use strict';

$(function () {
  $('.select2').select2();
  $.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN': $('input[name="_token"]').val()
    }
  });

  // Approve
  $('#approval_form').on('submit', function (e) {
    e.preventDefault(); // Prevent the default form submission

    // Get the status of the two checkboxes
    const $complete_requirements = $('#complete_requirements');
    const $qualified_for_loan = $('#qualified_for_loan');
    const form = $('#approval_form');

    // The core function that handles the form submission via fetch
    const submitForm = async => {
      // Get the boolean values for the checkboxes
      const complete_requirements_bool = $('#complete_requirements').is(':checked');
      const qualified_for_loan_bool = $('#qualified_for_loan').is(':checked');

      // Convert the boolean values to integers (1 or 0)
      const complete_requirements = complete_requirements_bool ? 1 : 0;
      const qualified_for_loan = qualified_for_loan_bool ? 1 : 0;

      const method = form.attr('method');
      const url = form.attr('action');
      const id = $('#form_id').val();
      const form_type = $('#form_type').val();

      // Prepare the data object
      const post_data = {
        complete_requirements,
        qualified_for_loan,
        id,
        form_type
      };
      Swal.fire({
        title: 'Please Wait!',
        text: 'Processing your request...',
        allowOutsideClick: false,
        showConfirmButton: false, // This hides the "OK" button
        willOpen: () => {
          Swal.showLoading(); // 2. Show the spinner
        }
      });
      // Use the provided AJAX logic
      $.ajax({
        url: url,
        method: method,
        data: post_data, // <-- The prepared data object is used here
        success: function (data) {
          if (data.message === 'Success') {
            Swal.fire({
              title: data.message,
              text: data.text,
              icon: 'success',
              customClass: {
                confirmButton: 'btn btn-primary'
              },
              buttonsStyling: false
            }).then(function () {
              location.reload();
            });
          } else {
            Swal.fire({
              title: data.message,
              text: data.text,
              icon: 'error',
              customClass: {
                confirmButton: 'btn btn-primary'
              },
              buttonsStyling: false
            });
          }
        },
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
        }
      });
    };

    // This is the approval flow
    Swal.fire({
      title: 'Are your sure?',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Yes, update it!',
      showLoaderOnConfirm: true,
      preConfirm: () => {
        return submitForm();
      },
      allowOutsideClick: () => !Swal.isLoading()
    });
  });

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
  $('#requirement_transmittal_form').on('submit', function (event) {
    event.preventDefault();
    const form = $(this);
    const method = form.attr('method');
    const formData = form.serialize();

    const url = form.attr('action');
    // Add a loading state to the submit button
    const submitButton = form.find('button[type="submit"]');
    submitButton.prop('disabled', true).text('Submitting...');

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
          success: function (data) {
            console.log(data);
            // Remove loading state from button
            submitButton.prop('disabled', false).text('Submit Form');
            Swal.fire({
              title: data.message,
              text: data.text,
              icon: data.icon,
              customClass: {
                confirmButton: 'btn btn-primary'
              },
              buttonsStyling: false
            }).then(function () {
              window.location.href = '/form/view/requirement-transmittal-form/' + data.form_id;
            });
          },
          error: function (xhr, status, error) {
            console.log(xhr, status, error);
            submitButton.prop('disabled', false).text('Submit Form');
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
      } else {
        submitButton.prop('disabled', false).text('Submit Form');
      }
    });
  });
});
