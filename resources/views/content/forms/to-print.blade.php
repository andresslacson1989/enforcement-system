@extends('layouts/blankLayout')

@section('title', 'ESIAI FORM')

@section('content')
    @include('content.forms.forms-printable.'.$form_type.'-printable')
@endsection

<script>
    window.print();
    document.addEventListener('contextmenu', event => event.preventDefault());

    window.onafterprint = function() {
        //Use the Fetch API to make an AJAX POST request
        fetch('/forms/print-report/{{ $form_type }}/{{ $submission->id }}', {
            method: 'PUT',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        }).then(response => {
            if (!response.ok) {
                location.reload();
            }
            window.close();
        }).catch(error => {
            location.reload();
        });
    };

    window.addEventListener('afterprint', () => self.close);
    window.onfocus = function() {
        window.close();
    };
</script>
