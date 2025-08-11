@php
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
  'resources/assets/vendor/libs/moment/moment.js', 'resources/assets/vendor/libs/flatpickr/flatpickr.js',
  'resources/assets/vendor/libs/@form-validation/popular.js',
  'resources/assets/vendor/libs/@form-validation/bootstrap5.js',
  'resources/assets/vendor/libs/@form-validation/auto-focus.js'
  ])
@endsection

<!-- Page Scripts -->
@section('page-script')
  @vite([
  'resources/assets/js/pages-home.js',
  'resources/assets/js/extended-ui-sweetalert2.js',
  ])
@endsection

@section('content')
  <nav aria-label="breadcrumb">
    <ol class="breadcrumb">
      <li class="breadcrumb-item active">Home</li>
    </ol>
  </nav>
  <hr>
  <div class="row mb-4">
    <div class="col">
      <div class="btn-group">
        <button class="btn btn-label-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <i class="icon-base ti tabler-plus"></i> Add Item
        </button>

        <div class="row" id="notification-container"></div>
        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
          @can('fill requirement transmittal form')
          <li><a class="dropdown-item" href="/form/new/requirement-transmittal-form" id="requirement_transmittal_form">Requirement Transmittal Form</a></li>
          @endcan
          <li><a class="dropdown-item" href="/form/new/first-month-performance-evaluation-form" id="first_month_performance_evaluation_form">1st Month Performance Evaluation Form</a></li>
          <li><a class="dropdown-item" href="/form/new/third-month-performance-evaluation-form" id="third_month_performance_evaluation_form">3rd Month Performance Evaluation Form</a></li>
          <li><a class="dropdown-item" href="/form/new/sixth-month-performance-evaluation-form" id="sixth_month_performance_evaluation_form">6th Month Performance Evaluation Form</a></li>
          <li><a class="dropdown-item" href="/form/new/id-application-form" id="id_application_form">ID Application Form</a></li>
          <li><a class="dropdown-item" href="/form/new/personnel-requisition-form" id="personnel_application_form">Personnel Requisition Form</a></li>
          <li><a class="dropdown-item" href="/form/new/supply-requisition-form" id="supply_requisition_form">Supply Requisition Form</a></li>
          <li><a class="dropdown-item" href="/form/new/applicant-evaluation-form" id="applicant_evaluation_form">Applicant Evaluation Form</a></li>
          <li><a class="dropdown-item" href="/form/new/license-renewal-form" id="license_renewal_form">License Renewal Form</a></li>
          <li><a class="dropdown-item" href="/form/new/sss-accident-and-sickens-form" id="sss_accident_and_sickness_form">SSS Accident & Sickness Form</a></li>
          <li><a class="dropdown-item" href="/form/new/maternity-claim-form">Maternity Claim Form </a></li>
          <li><a class="dropdown-item" href="/form/new/benevolent-application-form" id="benevolent_application_form">Benevolent Application Form</a></li>
          <li><a class="dropdown-item" href="/form/new/guard-attendance" id="guard_attendance">Guard Attendance</a></li>
        </ul>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="row g-6">
      @foreach($forms as $item)
        <div class="col-md">
          <div class="card shadow-none bg-label-primary">
            <div class="card-body">
              <h5 class="card-title text-primary">{{ ucwords($item->name)}}</h5>
              <div class="me-12">
                <p class="text-nowrap mb-2"><i class="icon-base ti tabler-calendar me-2 align-bottom"></i>{{ \Carbon\Carbon::parse($item->created_at)->format('F d, Y') }}</p>
                <p class="text-nowrap mb-2"><i class="icon-base ti tabler-progress-alert me-2 align-top"></i> <span
                    class="badge {{ $color = ($item->status === 'pending') ? 'bg-label-warning' : (($item->status === 'denied') ? 'bg-label-danger' : (($item->status === 'approved') ? 'green' : 'default'))  }}">{{ ucfirst($item->status) }}</span>
                </p>
              </div>
              @can('view requirement transmittal form')
                <div class="row mt-4">
                  <div class="col">
                    <a href="/form/view/{{ str_replace(' ', '-', strtolower($item->name)) }}/{{ $item->id }}" class="btn btn-primary btn-sm view">View</a>
                  </div>
                </div>
              @endcan
            </div>
          </div>
        </div>
      @endforeach
    </div>
  </div>
@endsection
