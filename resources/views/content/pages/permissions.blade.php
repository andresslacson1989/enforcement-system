@php
  $configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('title', 'Roles')
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
  'resources/assets/js/pages-permissions.js',
  'resources/assets/js/extended-ui-sweetalert2.js',
  ])
@endsection

@section('content')
  <nav aria-label="breadcrumb">
    <ol class="breadcrumb">
      <li class="breadcrumb-item">
        <span class="fw-bold">Access</span>
      </li>
      <li class="breadcrumb-item active">Permissions</li>
    </ol>
  </nav>
  @hasrole('root')
  @php
    // get roles, first role
     echo auth()->user()->getRoleNames()[0]
  @endphp
  @endhasrole

  <div class="row">
    <div class="col-md-4">
      <form id="permissions_form" method="post" action="{{ url('/form/permissions') }}">
        <div class="card">
          <div class="card-header">
            <h5 class="card-title">Add Permissions</h5>
          </div>
          <div class="card-body">
            <div class="row">
              <div class="col-12 mb-4">
                <label for="name" class="form-label">Permission Name</label>
                <input
                  type="text"
                  class="form-control"
                  placeholder="Name"
                  aria-label="Name"
                  aria-describedby="permission_name"
                  id="permission_name"
                  name="permission_name"
                  required
                />
              </div>
              <div class="col-12">
                <div class="maxLength-wrapper">
                  <label for="permission_group" class="form-label">Set To Group</label>
                  <select id="permission_group"
                          name="permission_group"
                          class="selectpicker w-100"
                          data-style="btn-default">
                    @foreach($permissions as $key => $item)
                      @if($key == 'Not Used')
                        @continue;
                      @endif
                      <option value="{{ $key }}">{{ ucwords($key) }}</option>
                    @endforeach
                  </select>
                </div>
              </div>
            </div>
          </div>
          <div class="card-footer">
            <div class="row">
              <div class="d-grid gap-2">
                <button type="submit" class="btn btn-primary ">Add Permission</button>
              </div>
            </div>
          </div>
        </div>
      </form>
    </div>

    <div class="col-md-8">
      <div class="row">
        <div class="card">
          <div class="card-body">
            <h5 class="card-title">List of Permission Groups</h5>
            <label for="permissions" class="form-label">Set Permissions</label>
            @php $i=0; @endphp
            @foreach($permissions as $key => $item)
              <div class="accordion mt-1" id="accordionExample{{$i}}">
                <div class="accordion-item mb-1 accordion-border-solid-light">
                  <h2 class="accordion-header text-body fw-bold" id="heading{{$i}}">
                    <button type="button"
                            class="accordion-button collapsed bg-light-subtle text-body"
                            data-bs-toggle="collapse"
                            data-bs-target="#accordion{{$i}}"
                            aria-expanded="false"
                            aria-controls="accordion{{$i}}"><span class="badge badge-center bg-primary me-2"> {{ count($item) }}</span> {{ $key }} Permission
                    </button>
                  </h2>
                  <div id="accordion{{$i}}"
                       class="accordion-collapse collapse"
                       aria-labelledby="heading{{$i}}"
                       data-bs-parent="#accordionExample{{$i}}">
                    <div class="accordion-body">
                      @foreach($item as $data)
                        <div class="col-md">
                          <div class="list-group mt-2 mb-2">
                            <a href="javascript:void(0);" permission_name="{{ $data['name'] }}" permission_group="{{ $key }}" class="list-group-item list-group-item-action text-body fw-bold permission">{{ ucwords($data['name'] )}}</a>
                          </div>
                        </div>
                      @endforeach
                    </div>
                  </div>
                </div>
              </div>
              @php $i++ @endphp
            @endforeach
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Modals -->
  <div class="modal fade" id="update_permission_modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <form id="update_roles_permission_form" method="post" action="{{ url('/form/update-permission') }}">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel4">Permission</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="row">
              <div class="col-12 mb-4">
                <label for="name" class="form-label">Permission Name</label>
                <input
                  type="text"
                  class="form-control"
                  placeholder="Name"
                  aria-label="Name"
                  aria-describedby="permission_name"
                  id="update_permission_name"
                  name="update_permission_name"
                  required
                />
              </div>
              <div class="col-12">
                <div class="maxLength-wrapper">
                  <label for="update_permission_group" class="form-label">Set To Group</label>
                  <select id="update_permission_group"
                          name="update_permission_group"
                          class="selectpicker w-100"
                          data-style="btn-default">
                    @foreach($permissions as $key => $item)
                      @if($key == 'Not Used')
                        @continue;
                      @endif
                      <option value="{{ $key }}">{{ ucwords($key) }}</option>
                    @endforeach
                  </select>
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary" id="update_role_permissions">Save changes</button>
          </div>
        </div>
      </form>
    </div>
  </div>
@endsection



