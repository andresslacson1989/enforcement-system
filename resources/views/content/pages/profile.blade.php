@php
  $configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('title', 'Staffs')
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
  'resources/assets/js/pages-staffs.js',
  'resources/assets/js/extended-ui-sweetalert2.js',
  ])
@endsection

@section('content')
  <nav aria-label="breadcrumb">
    <ol class="breadcrumb">
      <li class="breadcrumb-item active">Staffs</li>
    </ol>
  </nav>
  <hr>
  <div class="row g-6 mb-4">
    <div class="container my-4 my-md-5">
      <div class="col">
        <div class="btn-group">
          @can('add employee')
            <button class="btn btn-label-primary" type="button" id="add_staff" data-bs-toggle="modal" data-bs-target="#staff_modal">
              <i class="icon-base ti tabler-plus"></i> Add Staff
            </button>
          @endcan
        </div>
      </div>
    </div>
  </div>
  <div class="row g-6">
    <div class="container my-4 my-md-5">

      {{-- ## LIST OF USERS TABLE ## --}}
      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-body">
              <div class="row">
                <table id="staffs_table" class="table table-sm table-bordered">
                  <thead>
                  <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Action</th>
                  </tr>
                  </thead>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal -->
  @include('content.modals.add-staff-modal')
@endsection



