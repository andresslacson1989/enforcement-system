@php use Illuminate\Support\Facades\Auth; @endphp
@if($submission)
    <div class="card-header sticky-element bg-label-warning d-flex justify-content-sm-between align-items-sm-center flex-column flex-sm-row mb-5">
        <h5 class="card-title mb-sm-0 me-2">ESIAI {{ strtoupper($form_name) }}
            <br>
            <span class="mt-2 badge {{ $submission->status_color }}">
        {{ strtoupper($submission->status) }}
    </span>
        </h5>
        <div class="d-flex justify-content-between">
            @if(in_array($submission->status, ['approved', 'submitted', 'processed']))
                @can(config("permit.print $form_name.name"))
                    <a href="/forms/print/{{ str_replace(' ', '-', strtolower($form_name)) }}/{{ $submission->id }}" class="btn btn-primary me-4" target="_blank">
                        <span class="align-middle">Print</span>
                    </a>
                @endcan
            @endif
            @if($submission->status == 'pending')
                @can(config("permit.approve $form_name.name"))
                    <a href="/form/{{ str_replace(' ', '-', strtolower($form_name)) }}/approve/{{ $submission->id }}" id="approve_button" class="btn btn-label-info me-4 mb-2" target="_blank">
                        <span class="align-middle">Approve</span>
                    </a>
                @endcan
            @endif
            <a href="{{ route('form-library') }}" class="btn btn-label-primary me-4 mb-2">
                <span class="align-middle">Back</span>
            </a>
            @can(config("permit.edit $form_name.name"))
                @if(($submission->status == 'submitted' || $submission->status == 'pending')&& Auth::user()->can(config("permit.edit ".strtolower($submission->name).".name")) )
                    <!-- Change the button to indicate an update -->
                    <button type="submit" class="btn btn-primary mb-2">Update Form</button>
                @endif
            @endcan
            @can("permit.update processed form.name")
                @if(($submission->status == 'processed' ))
                    <!-- Change the button to indicate an update -->
                    <button type="submit" class="btn btn-primary mb-2">Update Form</button>
                @endif
            @endcan


        </div>
    </div>
@else
    <div class="card-header sticky-element bg-label-warning d-flex justify-content-sm-between align-items-sm-center flex-column flex-sm-row">
        <h5 class="card-title mb-sm-0 me-2">ESIAI {{ strtoupper($form_name) }}</h5>
        <div class="action-btns">
            <a href="{{ route('form-library') }}" class="btn btn-label-primary me-4">
                <span class="align-middle"> Back</span>
            </a>
            <button type="submit" class="btn btn-primary">Submit Form</button>
        </div>
    </div>
@endif
