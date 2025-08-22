@php
  use App\Models\User;
  $configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('title', 'Home')

<!-- Vendor Styles -->
@section('vendor-style')
  @vite([
  'resources/assets/vendor/libs/bootstrap-select/bootstrap-select.scss',
  'resources/assets/vendor/libs/animate-css/animate.scss"',
  'resources/assets/vendor/libs/sweetalert2/sweetalert2.scss',

  'resources/assets/vendor/libs/datatables-bs5/datatables.bootstrap5.scss',
  'resources/assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.scss',
  'resources/assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.scss',
  'resources/assets/vendor/libs/datatables-rowgroup-bs5/rowgroup.bootstrap5.scss',
  ])
@endsection
<!-- Page Styles -->
{{--@section('page-style')--}}
{{--  @vite([--}}
{{--  //'resources/assets/vendor/scss/pages/wizard-ex-checkout.scss'--}}
{{--  ])--}}
{{--@endsection--}}

<!-- Vendor Scripts -->
@section('vendor-script')
  @vite([
  'resources/assets/vendor/libs/jquery/jquery.js',
  'resources/assets/vendor/libs/bootstrap-select/bootstrap-select.js',
  'resources/assets/vendor/libs/sweetalert2/sweetalert2.js',
  'resources/assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js',
  'resources/assets/vendor/libs/moment/moment.js',
  'resources/assets/vendor/libs/flatpickr/flatpickr.js',
  ])
@endsection

<!-- Page Scripts -->
@section('page-script')
  @vite([
  'resources/assets/js/pages-detachments-view.js',
  'resources/assets/js/extended-ui-sweetalert2.js',
  ])
@endsection

@section('content')
  <nav aria-label="breadcrumb">
    <ol class="breadcrumb">
      <li class="breadcrumb-item">
        <span class="fw-bold">Detachments</span>
      </li>
      <li class="breadcrumb-item active">View</li>
    </ol>
  </nav>
  <hr>
  <div class="row g-6 mb-4">
    <div class="container my-4 my-md-5">
     <div class="col">
       <div class="btn-group">
         @can('add detachment')
           <button class="btn btn-label-primary" type="button" id="add_detachment" data-bs-toggle="modal" data-bs-target="#add_detachment_modal">
             <i class="icon-base ti tabler-plus"></i> Add Detachment
           </button>
         @endcan
       </div>
     </div>
    </div>
  </div>
  <div class="row g-6">
    {{-- The main container now holds the detachment ID for easy access in JS --}}
    <div class="container my-4 my-md-5" data-detachment-id="{{ $detachment->id }}">
      <!-- Header Section -->
      <div class="card shadow-sm mb-4">
        <div class="card-body p-4">
          <div class="d-flex justify-content-end">
            @if($detachment->status == 'pending')<span class="badge bg-label-warning">{{ ucwords($detachment->status) }}</span> @endif
          </div>
          <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center">
            <div>
              {{-- Data is now rendered directly by the server --}}
              <h1 id="name_display" class="h3 fw-bold mb-1">{{ $detachment->name }} </h1>
              <p id="category_display" class="text-muted mb-0">{{ ucwords($detachment->category) }}</p>
            </div>
            <div class="mt-3 mt-md-0">
              <!-- Edit button now triggers the modal -->
              @can('approve detachment')
                @if($detachment->status == 'pending')
                <button type="button" id="approve_button" class="btn btn-label-primary" data-detachment-id="{{ $detachment->id }}" ><i class="fas fa-pencil-alt me-1"></i> Approve</button>
                @endif
              @endcan
              <button id="edit_button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#edit_detachment_modal"><i class="fas fa-pencil-alt me-1"></i> Edit Details</button>
            </div>
          </div>
        </div>
      </div>

      <!-- Tab Navigation & Content -->
      <div class="card shadow-sm">
        <div class="card-header">
          <ul class="nav nav-tabs card-header-tabs" id="detachmentTabs" role="tablist">
            <li class="nav-item" role="presentation">
              <button class="nav-link active" id="general-tab" data-bs-toggle="tab" data-bs-target="#general" type="button" role="tab" aria-controls="general" aria-selected="true">General Info</button>
            </li>
            <li class="nav-item" role="presentation">
              <button class="nav-link" id="location-tab" data-bs-toggle="tab" data-bs-target="#location" type="button" role="tab" aria-controls="location" aria-selected="false">Location</button>
            </li>
            <li class="nav-item" role="presentation">
              <button class="nav-link" id="duty-pay-tab" data-bs-toggle="tab" data-bs-target="#duty-pay" type="button" role="tab" aria-controls="duty-pay" aria-selected="false">Duty & Pay Rates</button>
            </li>
            <li class="nav-item" role="presentation">
              <button class="nav-link" id="benefits-tab" data-bs-toggle="tab" data-bs-target="#benefits" type="button" role="tab" aria-controls="benefits" aria-selected="false">Benefits & Deductions</button>
            </li>
            <li class="nav-item" role="presentation">
              <button class="nav-link" id="personnel-tab" data-bs-toggle="tab" data-bs-target="#personnel" type="button" role="tab" aria-controls="personnel" aria-selected="false">Personnel Roster</button>
            </li>
          </ul>
        </div>
        <div class="card-body p-4 tab-content" id="detachmentTabContent">
          <!-- Tab 1: General Info -->
          <div class="tab-pane fade show active" id="general" role="tabpanel" aria-labelledby="general-tab">
            <h2 class="h5 fw-semibold mb-3">General Information</h2>
            <div class="row g-3">
              <div class="col-md-6">
                <label class="form-label text-muted">Assigned Officer</label>
                <div id="assigned_officer_display" class="fs-6 display-field" data-officer-id="{{ $detachment->assigned_officer ?? ''}}">{{ User::find($detachment->assigned_officer)->name ?? 'N/A' }}</div>
              </div>
            </div>
          </div>
          <!-- Tab 2: Location -->
          <div class="tab-pane fade" id="location" role="tabpanel" aria-labelledby="location-tab">
            <h2 class="h5 fw-semibold mb-3">Location Details</h2>
            <div class="row g-3">
              <div class="col-12">
                <label class="form-label text-muted">Street</label>
                <div id="street_display" class="fs-6 display-field">{{ $detachment->street }}</div>
              </div>
              <div class="col-md-6">
                <label class="form-label text-muted">City</label>
                <div id="city_display" class="fs-6 display-field">{{ $detachment->city }}</div>
              </div>
              <div class="col-md-6">
                <label class="form-label text-muted">Province</label>
                <div id="province_display" class="fs-6 display-field">{{ $detachment->province }}</div>
              </div>
              <div class="col-md-6">
                <label class="form-label text-muted">Zip Code</label>
                <div id="zip_code_display" class="fs-6 display-field">{{ $detachment->zip_code }}</div>
              </div>
            </div>
          </div>
          <!-- Tab 3: Duty & Pay Rates -->
          <div class="tab-pane fade" id="duty-pay" role="tabpanel" aria-labelledby="duty-pay-tab">
            <h2 class="h5 fw-semibold mb-3">Duty & Shift Configuration</h2>
            <div class="row g-3 mb-4">
              <div class="col-md-4">
                <label class="form-label text-muted">Hours per Shift</label>
                <div id="hours_per_shift_display" class="fs-6 display-field">{{ $detachment->hours_per_shift }}</div>
              </div>
              <div class="col-md-4">
                <label class="form-label text-muted">Max Hours on Duty</label>
                <div id="max_hrs_duty_display" class="fs-6 display-field">{{ $detachment->max_hrs_duty }}</div>
              </div>
              <div class="col-md-4">
                <label class="form-label text-muted">Max Overtime (hrs)</label>
                <div id="max_ot_display" class="fs-6 display-field">{{ $detachment->max_ot }}</div>
              </div>
            </div>
            <hr>
            <h2 class="h5 fw-semibold my-3">Pay Rate Configuration</h2>
            <div class="row g-3">
              <div class="col-md-3"><label class="form-label text-muted">Hourly Rate</label><div id="hr_rate_display" class="fs-6 rate-field display-field">{{ number_format($detachment->hr_rate, 2) }}</div></div>
              <div class="col-md-3"><label class="form-label text-muted">OT Rate</label><div id="ot_rate_display" class="fs-6 rate-field display-field">{{ number_format($detachment->ot_rate, 2) }}</div></div>
              <div class="col-md-3"><label class="form-label text-muted">Night Diff. Rate</label><div id="nd_rate_display" class="fs-6 rate-field display-field">{{ number_format($detachment->nd_rate, 2) }}</div></div>
              <div class="col-md-3"><label class="form-label text-muted">Rest Day Rate</label><div id="rdd_rate_display" class="fs-6 rate-field display-field">{{ number_format($detachment->rdd_rate, 2) }}</div></div>
              <div class="col-md-3"><label class="form-label text-muted">Rest Day OT Rate</label><div id="rdd_ot_rate_display" class="fs-6 rate-field display-field">{{ number_format($detachment->rdd_ot_rate, 2) }}</div></div>
              <div class="col-md-3"><label class="form-label text-muted">Holiday Rate</label><div id="hol_rate_display" class="fs-6 rate-field display-field">{{ number_format($detachment->hol_rate, 2) }}</div></div>
              <div class="col-md-3"><label class="form-label text-muted">Holiday OT Rate</label><div id="hol_ot_rate_display" class="fs-6 rate-field display-field">{{ number_format($detachment->hol_ot_rate, 2) }}</div></div>
              <div class="col-md-3"><label class="form-label text-muted">Special Hol. Rate</label><div id="sh_rate_display" class="fs-6 rate-field display-field">{{ number_format($detachment->sh_rate, 2) }}</div></div>
              <div class="col-md-3"><label class="form-label text-muted">Special Hol. OT Rate</label><div id="sh_ot_rate_display" class="fs-6 rate-field display-field">{{ number_format($detachment->sh_ot_rate, 2) }}</div></div>
              <div class="col-md-3"><label class="form-label text-muted">RD + Holiday Rate</label><div id="rd_hol_rate_display" class="fs-6 rate-field display-field">{{ number_format($detachment->rd_hol_rate, 2) }}</div></div>
              <div class="col-md-3"><label class="form-label text-muted">RD + Holiday OT Rate</label><div id="rd_hol_ot_rate_display" class="fs-6 rate-field display-field">{{ number_format($detachment->rd_hol_ot_rate, 2) }}</div></div>
              <div class="col-md-3"><label class="form-label text-muted">RD + Special Hol. Rate</label><div id="rd_sh_rate_display" class="fs-6 rate-field display-field">{{ number_format($detachment->rd_sh_rate, 2) }}</div></div>
              <div class="col-md-3"><label class="form-label text-muted">RD + Special Hol. OT Rate</label><div id="rd_sh_ot_rate_display" class="fs-6 rate-field display-field">{{ number_format($detachment->rd_sh_ot_rate, 2) }}</div></div>
            </div>
          </div>
          <!-- Tab 4: Benefits & Deductions -->
          <div class="tab-pane fade" id="benefits" role="tabpanel" aria-labelledby="benefits-tab">
            <h2 class="h5 fw-semibold mb-3">Benefits & Deductions</h2>
            <div class="row g-3">
              <div class="col-md-4">
                <label class="form-label text-muted">Cash Bond</label>
                <div id="cash_bond_display" class="fs-6 rate-field display-field">{{ number_format($detachment->cash_bond, 2) }}</div>
              </div>
              <div class="col-md-4">
                <label class="form-label text-muted">Service Incentive Leave (SIL)</label>
                <div id="sil_display" class="fs-6 rate-field display-field">{{ number_format($detachment->sil, 2) }}</div>
              </div>
              <div class="col-md-4">
                <label class="form-label text-muted">ECOLA</label>
                <div id="ecola_display" class="fs-6 rate-field display-field">{{ number_format($detachment->ecola, 2) }}</div>
              </div>
              <div class="col-md-4">
                <label class="form-label text-muted">Retirement Pay</label>
                <div id="retirement_pay_display" class="fs-6 display-field">{{ number_format($detachment->retirement_pay, 2) }}</div>
              </div>
              <div class="col-md-4">
                <label class="form-label text-muted">13th Month Pay</label>
                <div id="thirteenth_month_pay_display" class="fs-6 display-field">{{ number_format($detachment->thirteenth_month_pay, 2)  }}</div>
              </div>
            </div>
          </div>
          <!-- Tab 5: Personnel Roster -->
          <div class="tab-pane fade" id="personnel" role="tabpanel" aria-labelledby="personnel-tab">

            {{-- Flex container to hold the heading and button --}}
            <div class="d-flex justify-content-between align-items-center mb-3">
              <h2 class="h5 fw-semibold mb-0">Assigned Personnel</h2>
              <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#add_personnel_modal">
                <i class="fas fa-plus"></i> Add Personnel
              </button>
            </div>

            <div class="table-responsive">
              <table class="table table-hover align-middle">
                <thead class="table-light">
                <tr>
                  <th scope="col">Name</th>
                  <th scope="col">Position</th>
                  <th scope="col">Shift</th>
                  <th scope="col">Contact</th>
                  <th scope="col">Status</th>
                  <th scope="col">Action</th>
                </tr>
                </thead>
                <tbody id="personnel-roster-body">
                @forelse ($detachment->users as $person)
                  @if($detachment->assigned_officer != $person->id)
                    @continue;
                  @endif
                  <tr class="bg-label-success fw-bold">
                    <td>{{ $person->name }}</td>
                    <td>@foreach($person->getRoleNames() as $role) {!!  ucwords($role) .' <br>' !!}  @endforeach</td>
                    <td>{{ $person->shift }}</td>
                    <td>{{ $person->phone_number }}</td>
                    <td>
            <span class="badge {{ $person->status === 'hired' ? 'bg-success' : 'bg-secondary' }}">
              {{ ucwords($person->status) }}
            </span>
                    </td>
                  </tr>
                @empty

                @endforelse
                @forelse ($detachment->users as $person)
                  @if($detachment->assigned_officer == $person->id)
                    @continue;
                  @endif
                  <tr>
                    <td>{{ $person->name }}</td>
                    <td>@foreach($person->getRoleNames() as $role) {!!  ucwords($role) .' <br>' !!}  @endforeach</td>
                    <td>{{ $person->shift }}</td>
                    <td>{{ $person->phone_number }}</td>
                    <td>
            <span class="badge {{ $person->status === 'hired' ? 'bg-success' : 'bg-secondary' }}">
              {{ ucwords($person->status) }}
            </span>
                    </td>
                  </tr>
                @empty
                  <tr><td colspan="6" class="dt-empty text-center">No matching records found</td></tr>
                @endforelse
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  @include('content.modals.edit-detachment-modal')
  @include('content.modals.add-detachment-modal')
  @include('content.modals.add-personnel-modal')
@endsection
