@php
    use App\Models\User;
    $configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('title', 'Home')

<!-- Vendor Styles -->
@section('vendor-style')
    @vite([
    'resources/assets/vendor/libs/select2/select2.scss',
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
      'resources/assets/vendor/libs/select2/select2.js',
    'resources/assets/vendor/libs/sweetalert2/sweetalert2.js',
    'resources/assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js',
    'resources/assets/vendor/libs/moment/moment.js',
    'resources/assets/vendor/libs/flatpickr/flatpickr.js',
    ])
@endsection

<!-- Page Scripts -->
@section('page-script')
    @vite([
    'resources/assets/js/pages-detachments.js',
    'resources/assets/js/users-table-functions.js',
    'resources/assets/js/extended-ui-sweetalert2.js',
    ])
@endsection

@section('content')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><span class="fw-bold">Directories</span></li>
            <li class="breadcrumb-item active">Detachments</li>
        </ol>
    </nav>
    <hr>
    <div class="row g-4 mb-4">
        <div class="col-sm-6 col-xl-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-start justify-content-between">
                        <div class="content-left">
                            <span>Total Detachments</span>
                            <div class="d-flex align-items-end mt-2">
                                <h3 class="mb-0 me-2">{{ $stats['total'] ?? ''}}</h3>
                            </div>
                            <small>All Detachments</small>
                        </div>
                        {{-- ICON UPDATED --}}
                        <span class="badge bg-label-primary rounded p-2"><i class="icon-base ti tabler-users-group"></i></span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-start justify-content-between">
                        <div class="content-left">
                            <span>Large Detachments</span>
                            <div class="d-flex align-items-end mt-2">
                                <h3 class="mb-0 me-2">{{ $stats['large_detachment'] ?? ''}}</h3>
                            </div>
                            <small>61 + Personnel</small>
                        </div>
                        {{-- ICON UPDATED --}}
                        <span class="badge bg-label-primary rounded p-2"><i class="icon-base ti tabler-users-group"></i></span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-start justify-content-between">
                        <div class="content-left">
                            <span>Medium Detachment</span>
                            <div class="d-flex align-items-end mt-2">
                                <h3 class="mb-0 me-2">{{ $stats['medium_detachment'] ?? '' }}</h3>
                            </div>
                            <small>21-60 Personnel</small>
                        </div>
                        {{-- ICON UPDATED --}}
                        <span class="badge bg-label-primary rounded p-2"><i class="icon-base ti tabler-users-group"></i></span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-start justify-content-between">
                        <div class="content-left">
                            <span>Small Team</span>
                            <div class="d-flex align-items-end mt-2">
                                <h3 class="mb-0 me-2">{{ $stats['small_team'] ?? '' }}</h3>
                            </div>
                            <small>3-20 Personnel</small>
                        </div>
                        {{-- ICON UPDATED --}}
                        <span class="badge bg-label-primary rounded p-2"><i class="icon-base ti tabler-users"></i></span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-start justify-content-between">
                        <div class="content-left">
                            <span>Single Post</span>
                            <div class="d-flex align-items-end mt-2">
                                <h3 class="mb-0 me-2 text-danger">{{ $stats['single_post'] ?? '' }}</h3>
                            </div>
                            <small>1-2 Personnel</small>
                        </div>
                        <span class="badge bg-label-primary rounded p-2"><i class="icon-base ti tabler-user"></i></span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-start justify-content-between">
                        <div class="content-left">
                            <span>Empty</span>
                            <div class="d-flex align-items-end mt-2">
                                <h3 class="mb-0 me-2">{{ $stats['empty'] ?? '' }}</h3>
                            </div>
                            <small>No Personnel</small>
                        </div>
                        {{-- ICON UPDATED --}}
                        <span class="badge bg-label-primary rounded p-2"><i class="icon-base ti tabler-users"></i></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row g-6">
        <div class="col-12 position-relative">
            <div class="card">
                <div class="card-header border-bottom">
                    <h5 class="card-title">Staff Directory</h5>
                    <div class="d-flex justify-content-between align-items-center row py-3 gap-3 gap-md-0">
                        <div class="col-md-4 position-relative">
                            <select id="status_filter" class="form-select select2">
                                <option value="">Filter by Category</option>
                                <option value="hired">Hired</option>
                                <option value="floating">Floating</option>
                                <option value="on_leave">On Leave</option>
                                <option value="resigned">Resigned</option>
                                <option value="preventive_suspension">Preventive Suspension</option>
                            </select>
                        </div>
                        <div class="col-md-4 text-end">
                            @can(config("permit.add detachment.name"))
                                <button class="btn btn-primary" type="button" id="add_detachment" data-bs-toggle="modal" data-bs-target="#add_detachment_modal">
                                    <i class="icon-base ti tabler-plus"></i> Add Detachment
                                </button>
                            @endcan
                        </div>
                    </div>
                </div>
                <div class="card-datatable table-responsive">
                    <table class="table table-sm" id="detachment_table">
                        <thead>
                        <tr>
                            <th>Name</th>
                            <th>Assigned Officer</th>
                            <th>Personnel</th>
                            <th>City</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @include('content.modals.add-detachment-modal')
@endsection
