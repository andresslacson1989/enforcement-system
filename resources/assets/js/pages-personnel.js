$(function () {
  'use strict';
  $('.select2').select2();
  $('.select2-modal').select2({
    dropdownParent: $('#users_modal .modal-body')
  });

  const $users_table = $('.users-datatable'); // Use the common class
  let dt_users;

  // Initialize DataTables
  if ($users_table.length) {
    dt_users = $users_table.DataTable({
      ajax: {
        url: '/personnel/table',
        data: function (d) {
          d.role_filter = $('#role_filter').val();
          d.status_filter = $('#status_filter').val();
        }
      },
      processing: true,
      serverSide: true,
      columns: [
        { data: 'name', name: 'name' },
        { data: 'role', name: 'role' },
        { data: 'detachment', name: 'detachment' },
        { data: 'status', name: 'status' },
        { data: 'action', orderable: false, searchable: false }
      ],
      language: { searchPlaceholder: 'Search Personnel...' }
    });
  }
  $(document).trigger('datatable:ready', [dt_users, $users_table.attr('id')]);
});
