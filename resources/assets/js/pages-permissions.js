/**
 * app-academy-course Script
 */

'use strict';

$(function() {

  $(".selectpicker").selectpicker();

  //Permission Form
  $("#permissions_form").on('submit', function(e) {
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
      success: function(data) {
        if(data.message === 'Success'){
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
        } else{
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
      error: function(xhr, status, error) {
        console.log(xhr, error)
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

    })
  })

  //See Permissions
  $('.permission').on('click', function (e) {
    e.preventDefault();
    const permission = $(this)
    const permission_name = permission.attr('permission_name');
    const permission_group = permission.attr('permission_group');
    const method = 'POST';

    $("#update_permission_modal").modal('toggle');
    $("#update_permission_name").val(permission_name);
    $("#update_permission_group").selectpicker('val', permission_group);

    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });

  });

  //update permission
  $("#update_roles_permission_form").on('submit', function(e) {
    e.preventDefault();
    const form = $(this);
    const method = form.attr('method');
    const formData = form.serialize();
    const url = form.attr('action');

    $.ajax({
      url: url,
      method: method,
      data: formData,
      dataType: 'json',
      success: function (data) {
        $('#update_permission_modal').modal('hide');
        if(data.message === 'Success'){
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
        } else{
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
          text: xhr.statusText,
          icon: 'error',
          customClass: {
            confirmButton: 'btn btn-primary'
          },
          buttonsStyling: false
        });
      }
    });
  })
})
