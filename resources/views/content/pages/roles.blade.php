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
  'resources/assets/js/pages-roles.js',
  'resources/assets/js/extended-ui-sweetalert2.js',
  ])
@endsection

@section('content')
  <nav aria-label="breadcrumb">
    <ol class="breadcrumb">
      <li class="breadcrumb-item">
        <span class="fw-bold">Access</span>
      </li>
      <li class="breadcrumb-item active">Roles</li>
    </ol>
  </nav>
  @hasrole('root')
  @php
    // get roles, first role
     echo auth()->user()->getRoleNames()[0]
  @endphp
  @endhasrole
  <div class="row">
    <div class="col-md-12 mb-5">
      <form id="roles_form" method="post" action="{{ url('/form/roles') }}">
        <div class="card">
          <div class="card-header">
            <h5 class="card-title">Add Roles</h5>
          </div>
          <div class="card-body">
            <div class="row">
              <div class="col-12 mb-4">
                <label for="name" class="form-label">Role Name</label>
                <input
                  type="text"
                  class="form-control"
                  placeholder="Guard"
                  aria-label="Name"
                  aria-describedby="name"
                  id="name"
                  name="name"
                  required
                />
              </div>
              <div class="col-12 mb-4">
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
                              <div class="form-check form-check-primary mt-4">
                                <input class="form-check-input" type="checkbox" value="{{ $data['name']  }}" id="{{$data['id']}}" name="permissions[]" />
                                <label class="form-check-label" for="{{ $data['id'] }}">
                                  {{ ucwords($data['name'] )}}</label>
                                <small class="fw-medium d-block text-gray">{{ $data['description'] ?? 'No Description' }}</small>
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
              <div class="col-12">
                <div class="maxLength-wrapper">
                  <label for="description" class="form-label">Role Description</label>
                  <textarea class="form-control autosize  maxLength-example"
                            id="description"
                            name="description"
                            maxlength="120"></textarea>
                  <small id="textarea-maxlength-info" class="text-muted"></small>
                </div>
              </div>
            </div>
          </div>
          <div class="card-footer">
            <div class="row">
              <div class="d-grid d-md-flex justify-content-md-end">
                <button type="submit" class="btn btn-primary">Add Role</button>
              </div>
            </div>
          </div>
        </div>
      </form>
    </div>
    <hr>
    <div class="col-md-12">
      <div class="row">
        <div class="col-12 mb-5">
          <div class="card-header">
            <h5 class="card-title">List of Roles</h5>
          </div>
        </div>
        @foreach($roles as $item)
          <div class="col-md-4 mb-5">
            <div class="card h-100">
              <div class="card-body">
                <h5 class="card-title">{{ ucfirst($item->name) }}</h5>
                <p class="card-text">{{ $item->description ?? 'No Description'}}</p>
                <button type="button" id="role_{{ $item->id }}" name="{{ ucwords($item->name) }}" role_id="{{ $item->id }}" role_description="{{ $item->description }}" class="btn btn-primary see_permissions">See Permissions</button>
              </div>
            </div>
          </div>
        @endforeach
      </div>
    </div>
  </div>

  <!-- Modals -->
  <div class="modal fade" id="permission_modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
      <form id="roles_permission_form" method="post" action="{{ url('/form/update-roles') }}">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel4"><span id="role_name"></span> Permissions</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="row">
              <div class="col mb-4">
                <input type="hidden" id="role_id" name="role_id" value="">
                <label for="permissions" class="form-label">Set Permissions</label>
                @php $i=0; @endphp
                @foreach($permissions as $key => $item)
                  <div class="accordion mt-1" id="role_permission{{$i}}">
                    <div class="accordion-item mb-1">
                      <h2 class="accordion-header" id="heading_permission{{$i}}">
                        <button type="button"
                                class="accordion-button collapsed bg-label-gray text-body"
                                data-bs-toggle="collapse"
                                data-bs-target="#accordion_permission{{$i}}"
                                aria-expanded="false"
                                aria-controls="accordion_permission{{$i}}">{{ $key }} Permission
                        </button>
                      </h2>
                      <div id="accordion_permission{{$i}}"
                           class="accordion-collapse collapse"
                           aria-labelledby="heading_permission{{$i}}"
                           data-bs-parent="#role_permission{{$i}}">
                        <div class="accordion-body">
                          @foreach($item as $data)
                            <div class="col-md">
                              <div class="form-check form-check-primary mt-4">
                                <input class="form-check-input" type="checkbox" value="{{ $data['name']  }}" id="role_permission_{{$data['id']}}" name="role_permissions[]" />
                                <label class="form-check-label" for="role_permission_{{ $data['id'] }}">
                                  {{ ucwords($data['name'] )}}</label>
                                <small class="fw-medium d-block text-gray">{{ $data['description'] ?? 'No Description' }}</small>
                              </div>
                            </div>
                          @endforeach
                        </div>
                      </div>
                    </div>
                  </div>
                  @php $i++ @endphp
                @endforeach
                <div class="maxLength-wrapper2 mt-4">
                  <label for="role_description" class="form-label">Role Description</label>
                  <textarea class="form-control autosize  maxLength-example2"
                            id="role_description"
                            name="role_description"
                            maxlength="120"></textarea>
                  <small id="textarea-maxlength-info2" class="text-muted"></small>
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
@endsection



