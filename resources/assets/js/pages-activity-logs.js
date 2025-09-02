'use strict';

$(function () {
  // Initialize Select2 and Flatpickr on the filter inputs
  const select2 = $('.select2');
  if (select2.length) {
    select2.select2();
  }

  const startDateInput = $('input[name="start_date"]');
  const endDateInput = $('input[name="end_date"]');

  if (startDateInput.length) {
    startDateInput.flatpickr({
      monthSelectorType: 'static'
    });
  }
  if (endDateInput.length) {
    endDateInput.flatpickr({
      monthSelectorType: 'static'
    });
  }

  // --- DataTable Initialization ---
  const table = $('#activity-logs-table').DataTable({
    // Use the default DataTables DOM structure
    dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6 d-flex justify-content-center justify-content-md-end"f>><"row"<"col-sm-12"tr>><"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>',
    responsive: true,
    // Hide the 'Model' column by default, as it's only for filtering
    columnDefs: [
      {
        targets: 4, // The 5th column (index 4) is the Model column
        visible: false
      }
    ],
    order: [[0, 'desc']] // Order by date descending by default
  });

  // --- Custom Filtering Logic ---

  // Custom filtering function for the date range
  $.fn.dataTable.ext.search.push(function (settings, data, dataIndex) {
    const startDate = startDateInput.val();
    const endDate = endDateInput.val();
    // Parse the date from the first column using moment.js for reliability
    const date = moment(data[0], 'MMMM DD, YYYY HH:mm').format('YYYY-MM-DD');

    if (
      (startDate === '' && endDate === '') ||
      (startDate === '' && date <= endDate) ||
      (startDate <= date && endDate === '') ||
      (startDate <= date && date <= endDate)
    ) {
      return true;
    }
    return false;
  });

  // --- Filter Event Listeners ---

  // General search filter
  $('input[name="search"]').on('keyup change', function () {
    table.search(this.value).draw();
  });

  // Model filter
  $('select[name="model"]').on('change', function () {
    const selectedModel = $(this).val();
    // Search in the hidden 'Model' column (index 4) for an exact match
    table
      .column(4)
      .search(selectedModel ? '^' + selectedModel + '$' : '', true, false)
      .draw();
  });

  // Date range filter
  startDateInput.add(endDateInput).on('change', function () {
    table.draw();
  });

  // Clear filters button
  $('#clear-filters-btn').on('click', function () {
    // Reset form inputs
    $('#activity-log-filters')[0].reset();
    $('.select2').val(null).trigger('change');
    // Clear DataTable searches and redraw
    table.search('').columns().search('').draw();
  });

  // --- Modal Logic for "More Details" ---
  $('#logDetailsModal').on('show.bs.modal', function (event) {
    const button = $(event.relatedTarget); // Button that triggered the modal
    const details = button.data('details'); // Extract info from data-* attributes
    const modalBody = $(this).find('#log-details-content');
    modalBody.html(details); // Set the raw HTML content
  });
});
