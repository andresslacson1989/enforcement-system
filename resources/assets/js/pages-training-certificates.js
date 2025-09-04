'use strict';

$(function () {
  const $certificates_table = $('#training_certificates_table');

  // Initialize DataTable
  if ($certificates_table.length) {
    const dt_certificates = $certificates_table.DataTable({
      processing: true,
      serverSide: true,
      ajax: {
        url: '/training-certificates/table',
        data: function (d) {
          d.tag_filter = $('#tag_filter').val();
        }
      },
      columns: [
        { data: 'employee' },
        { data: 'name' },
        { data: 'training_center' },
        { data: 'date_of_training' },
        { data: 'tags', orderable: false, searchable: false },
        { data: 'action', orderable: false, searchable: false }
      ],
      order: [[3, 'desc']], // Order by date of training descending by default
      language: {
        searchPlaceholder: 'Search Certificate, Employee...'
      }
    });

    // Handle Tag Filter Change
    $('#tag_filter').on('change', function () {
      dt_certificates.draw(); // Redraw the table when the filter changes
    });
  }

  // Initialize Select2
  const $tag_filter_select = $('#tag_filter');
  if ($tag_filter_select.length) {
    $tag_filter_select.select2({
      placeholder: 'Filter by Tag',
      allowClear: true
    });
  }

  // --- View Training Certificate Tags via SweetAlert ---
  $certificates_table.on('click', '.view-tags-btn', function () {
    const $button = $(this);
    const certificate_name = $button.data('certificate-name');
    const tags = $button.data('tags');

    if (tags && tags.length > 0) {
      // Check if there are any tags
      const tags_html = tags.map(tag => `<span class="badge bg-label-secondary m-1">${tag}</span>`).join('');
      Swal.fire({
        title: `Tags for: ${certificate_name}`,
        html: `<div class="mt-3">${tags_html}</div>`,
        icon: 'info',
        customClass: {
          confirmButton: 'btn btn-primary'
        }
      });
    } else {
      Swal.fire({
        title: `Tags for: ${certificate_name}`,
        text: 'No tags have been assigned to this certificate.',
        icon: 'warning'
      });
    }
  });

  // --- View Certificate Image via Modal ---
  const $view_certificate_modal = $('#viewCertificateModal');
  let print_url = ''; // Variable to hold the URL for the print button

  // Use event delegation to handle clicks on the view button
  $certificates_table.on('click', '.view-certificate-btn', function () {
    const $button = $(this);
    const certificate_url = $button.data('certificate-url');
    const certificate_name = $button.data('certificate-name');
    const can_print = $button.data('can-print');

    // Set the image source and title in the modal
    $view_certificate_modal.find('#certificate_image_in_modal').attr('src', certificate_url);
    $view_certificate_modal.find('.modal-title').text(certificate_name);

    // Store the URL for the print button
    print_url = certificate_url;

    // Show or hide the print button based on permission
    if (can_print) {
      $('#print_certificate_btn').show();
    } else {
      $('#print_certificate_btn').hide();
    }
  });

  // Handle the print button click inside the modal
  $('#print_certificate_btn').on('click', function () {
    const print_window = window.open('', '_blank', 'height=600,width=800');
    print_window.document.write('<html><head><title>Print Certificate</title>');
    print_window.document.write(
      '<style>body { margin: 0; padding: 0; background-color: #fff !important; } img { max-width: 100%; } @media print { body { -webkit-print-color-adjust: exact; background-color: #fff !important; } }</style>'
    );
    print_window.document.write('</head><body>');
    print_window.document.write(`<img src="${print_url}" />`);
    print_window.document.write('</body></html>');
    print_window.document.close();

    // Use a timeout to ensure the image has loaded before printing
    setTimeout(function () {
      print_window.print();
    }, 500);

    // This event will fire after the user prints or cancels the print dialog.
    print_window.onafterprint = function () {
      print_window.close();
    };
  });
});
