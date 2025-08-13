@if($submission)
  <div class="card-header sticky-element bg-label-warning d-flex justify-content-sm-between align-items-sm-center flex-column flex-sm-row mb-5">
  <h5 class="card-title mb-sm-0 me-2">ESIAI {{ strtoupper($form_name) }}
    <br>
    <span class="mt-2 badge {{ $color = ($submission->status === 'pending') ? 'bg-warning' : (($submission->status === 'denied') ? 'bg-danger' : (($submission->status === 'approved') ? 'bg-green' : 'default'))  }}">
      {{ strtoupper($submission->status) }}
    </span>
    @if($submission->denial_reason)
      <span class="mt-2 badge bg-label-danger fw-bold">Reason: {{ $submission->denial_reason ?? '' }}</span>
    @endif
  </h5>
  <div class="action-btns">
    @if($submission->status == 'approved' || $submission->status == 'denied')
      @can('print '. $form_name)
        <a href="/form/{{ str_replace(' ', '-', strtolower($form_name)) }}/print/{{ $submission->id }}" class="btn btn-primary me-4" target="_blank">
          <span class="align-middle">Print</span>
        </a>
      @endcan
    @endif
    <a href="{{ route('pages-home') }}" class="btn btn-label-primary me-4">
      <span class="align-middle">Back</span>
    </a>
    @can('edit '. $form_name)
      @if($submission->status == 'approved' && \Illuminate\Support\Facades\Auth::user()->can('update '.strtolower($submission->name)) )
        <!-- Change the button to indicate an update -->
        <button type="submit" class="btn btn-primary">Update Form</button>
      @elseif($submission->status == 'pending')
        <button type="submit" class="btn btn-primary">Update Form</button>
      @endif
    @endcan
  </div>
</div>
@else
  <div class="card-header sticky-element bg-label-warning d-flex justify-content-sm-between align-items-sm-center flex-column flex-sm-row">
    <h5 class="card-title mb-sm-0 me-2">ESIAI {{ strtoupper($form_name) }}</h5>
    <div class="action-btns">
      <a href="{{ route('pages-home') }}" class="btn btn-label-primary me-4">
        <span class="align-middle"> Back</span>
      </a>
      <button type="submit" class="btn btn-primary">Submit Form</button>
    </div>
  </div>
@endif
