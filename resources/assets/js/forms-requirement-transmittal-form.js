'use strict';

$(function () {
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
    const submitForm = async status => {
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
        _token: $('input[name="_token"]').val(), // <-- The CSRF token is added here
        status,
        complete_requirements,
        qualified_for_loan,
        id,
        form_type
      };

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
        return submitForm('filed');
      },
      allowOutsideClick: () => !Swal.isLoading()
    });
  });

  // Get references to the checkboxes and the button
  const $completeRequirements = $('#complete_requirements');
  const $qualifiedForLoan = $('#qualified_for_loan');
  const $approveButton = $('#approve_button');
  const $deny_button = $('#deny_button');

  // Function to check if both checkboxes are checked
  /*  function checkCheckboxes() {
    if ($completeRequirements.is(':checked') && $qualifiedForLoan.is(':checked')) {
      $approveButton.prop('disabled', false); // Enable the button
      $deny_button.prop('disabled', true); // Disable
    } else {
      $approveButton.prop('disabled', true); // Disable the button
      $deny_button.prop('disabled', false); // Enable
    }
  }

  // Call the function on page load to set the initial state
  checkCheckboxes();

  // Listen for changes on both checkboxes
  $completeRequirements.on('change', checkCheckboxes);
  $qualifiedForLoan.on('change', checkCheckboxes);*/

  //exp date init flatpickr
  var exp_date = $('.exp_date');

  exp_date.flatpickr({
    minDate: 'today',
    altInput: true,
    altFormat: 'F j, Y',
    dateFormat: 'Y-m-d',
    static: true
  });

  $('#requirement_transmittal_form').on('submit', function (e) {
    e.preventDefault();
    const form = $(this);
    const method = form.attr('method');
    const formData = form.serialize();
    const url = form.attr('action');
    // Add a loading state to the submit button
    const submitButton = form.find('button[type="submit"]');
    submitButton.prop('disabled', true).text('Submitting...');

    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });

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
        // Manually submit the form.
        $.ajax({
          url: url,
          method: method,
          data: formData,
          dataType: 'json',
          success: function (data) {
            // Remove loading state from button
            submitButton.prop('disabled', false).text('Submit Form');
            if (data.message === 'Success') {
              Swal.fire({
                title: data.message,
                icon: 'success',
                customClass: {
                  confirmButton: 'btn btn-primary'
                },
                buttonsStyling: false
              }).then(function () {
                window.location.href = '/form/view/requirement-transmittal-form/' + data.form_id;
              });
            } else {
              Swal.fire({
                title: data.error,
                text: data.message,
                icon: 'error',
                customClass: {
                  confirmButton: 'btn btn-primary'
                },
                buttonsStyling: false
              });
            }
          },
          error: function (xhr, status, error) {
            // Remove loading state from button
            submitButton.prop('disabled', false).text('Submit Form');
            console.log(xhr, error);

            let errorMessage = 'An error occurred. Please try again.';
            // Handle Laravel validation errors
            if (xhr.responseJSON && xhr.responseJSON.errors) {
              ``;
              errorMessage = 'Please check the form for errors.';
              // You can loop through xhr.responseJSON.errors and display them
              // next to the corresponding input fields if you want more specific feedback.
              console.log('Validation errors:', xhr.responseJSON.errors);
            }

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
