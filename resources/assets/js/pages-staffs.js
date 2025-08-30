/**
 * app-academy-course Script
 */

'use strict';

//Roles DataTable
// Initialize DataTables
// var table = $('#roles_table').DataTable({
//   processing: true, // Show processing indicator
//   serverSide: true, // Enable server-side processing
//   ajax: "/table/roles", // The AJAX endpoint defined in routes/web.php
//   type: 'POST',
//   columns: [
//     // These 'data' properties must match the keys in your JSON response from the controller
//     { data: 'id', name: 'id' },
//     { data: 'name', name: 'name' },
//     { data: 'permissions', name: 'permissions' },
//     { data: 'actions', name: 'actions', orderable: false, searchable: false } // Custom 'actions' column
//   ],
//
// });

$(function () {
  $('.selectpicker').selectpicker();

  // $(".select2").select2({
  //   placeholder: 'Select an option', // Your desired placeholder text
  // });

  //Roles Form
  $('#roles_form').on('submit', function (e) {
    e.preventDefault();
    const form = $(this);
    const method = form.attr('method');
    const formData = form.serialize();
    console.log(formData);
    const url = form.attr('action');

    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });

    $.ajax({
      url: url,
      method: method,
      data: formData,
      dataType: 'json',
      success: function (data) {
        if (data.message === 'Success') {
          Swal.fire({
            title: data.message,
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
          title: 'Error',
          text: 'Invalid input details',
          icon: 'error',
          customClass: {
            confirmButton: 'btn btn-primary'
          },
          buttonsStyling: false
        });
      }
    });
  });

  //Update Role Permissions Form
  $('#roles_permission_form').on('submit', function (e) {
    e.preventDefault();
    const form = $(this);
    const method = form.attr('method');
    const formData = form.serialize();
    const url = form.attr('action');

    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });

    $.ajax({
      url: url,
      method: method,
      data: formData,
      dataType: 'json',
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
          title: 'Error',
          text: 'Invalid input details',
          icon: 'error',
          customClass: {
            confirmButton: 'btn btn-primary'
          },
          buttonsStyling: false
        });
      }
    });
  });

  //See Permissions
  $('.see_permissions').on('click', function (e) {
    e.preventDefault();
    const form = $(this);
    const method = 'POST';
    const url = '/see-permissions';
    const id = form.attr('role_id');
    const name = form.attr('name');
    const description = form.attr('role_description');

    //append role_id to modal
    $("#role_id").val(id);

    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });

    $.ajax({
      url: url,
      method: method,
      data: {
        id
      },
      dataType: 'json',
      success: function (data) {
        $('#permission_modal').modal('toggle');
        //Append Role Name
        $('#role_name').html(name);

        //append description
        $("#role_description").val(description)

        //uncheck all checkboxes
        $('input[type="checkbox"]').prop('checked', false);

        //collapse all accordions
        $('.accordion .collapse.show').collapse('hide');

        // Use $.each() to loop through the array
        $.each(data, function (index, item) {
          $('input[type="checkbox"]').each(function () {
            const checkboxValue = $(this).val();
            if (item.name === checkboxValue) {
              $(this).prop('checked', true);
            }
          });
        });
      },
      error: function (xhr, status, error) {
        console.log(xhr, error);
        Swal.fire({
          title: 'Error',
          text: xhr.statusText,
          icon: 'error',
          customClass: {
            confirmButton: 'btn btn-primary'
          },
          buttonsStyling: false
        });
      }
    });
  });
});
  //max length
  const textareaMaxLength = document.getElementById('description').getAttribute('maxlength');
  const textareaInfo = document.getElementById('textarea-maxlength-info');
  const textareaElement = document.getElementById('description');
  window.Helpers.maxLengthCount(textareaElement, textareaInfo, textareaMaxLength);
  textareaElement.addEventListener('input', function () {
    window.Helpers.maxLengthCount(textareaElement, textareaInfo, textareaMaxLength);
  });

  const textareaMaxLength2 = document.getElementById('role_description').getAttribute('maxlength');
  const textareaInfo2 = document.getElementById('textarea-maxlength-info2');
  const textareaElement2 = document.getElementById('role_description');

  window.Helpers.maxLengthCount(textareaElement2, textareaInfo2, textareaMaxLength2);

  textareaElement2.addEventListener('input', function () {
    window.Helpers.maxLengthCount(textareaElement2, textareaInfo2, textareaMaxLength2);
  });
