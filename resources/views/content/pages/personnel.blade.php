@php
    $configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('title', 'Staff Management')

@section('vendor-style')
    @vite([
      'resources/assets/vendor/libs/select2/select2.scss',
    'resources/assets/vendor/libs/datatables-bs5/datatables.bootstrap5.scss',
    'resources/assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.scss',
    'resources/assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.scss',
    'resources/assets/vendor/libs/sweetalert2/sweetalert2.scss',
    'resources/assets/vendor/libs/flatpickr/flatpickr.scss',
    ])
@endsection

@section('vendor-script')
    @vite([

      'resources/assets/vendor/libs/jquery/jquery.js',
    'resources/assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js',
    'resources/assets/vendor/libs/select2/select2.js',
    'resources/assets/vendor/libs/sweetalert2/sweetalert2.js',
    'resources/assets/vendor/libs/flatpickr/flatpickr.js',
    ])
@endsection

@section('page-script')
    @vite([
    'resources/assets/js/pages-personnel.js',
    'resources/assets/js/users-table-functions.js',
    ])
@endsection

@section('content')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><span class="fw-bold">Directories</span></li>
            <li class="breadcrumb-item active">Personnel</li>
        </ol>
    </nav>
    {{-- 1. Summary Stat Cards --}}
    <div class="row g-4 mb-4">
        <div class="col-sm-6 col-xl-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-start justify-content-between">
                        <div class="content-left">
                            <span>Total Personnel</span>
                            <div class="d-flex align-items-end mt-2">
                                <h3 class="mb-0 me-2">{{ $stats['total'] }}</h3>
                            </div>
                            <small>All Personnel</small>
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
                            <span>Hired</span>
                            <div class="d-flex align-items-end mt-2">
                                <h3 class="mb-0 me-2">{{ $stats['hired'] }}</h3>
                            </div>
                            <small>Currently Deployed</small>
                        </div>
                        {{-- ICON UPDATED --}}
                        <span class="badge bg-label-success rounded p-2"><i class="icon-base ti tabler-user-check"></i></span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-start justify-content-between">
                        <div class="content-left">
                            <span>Floating</span>
                            <div class="d-flex align-items-end mt-2">
                                <h3 class="mb-0 me-2">{{ $stats['floating'] }}</h3>
                            </div>
                            <small>Awaiting Deployment</small>
                        </div>
                        {{-- ICON UPDATED --}}
                        <span class="badge bg-label-warning rounded p-2"><i class="icon-base ti tabler-user-search"></i></span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-start justify-content-between">
                        <div class="content-left">
                            <span>Suspended</span>
                            <div class="d-flex align-items-end mt-2">
                                <h3 class="mb-0 me-2 text-danger">{{ $stats['suspended'] }}</h3>
                            </div>
                            <small>Suspension</small>
                        </div>
                        <span class="badge bg-label-danger rounded p-2"><i class="icon-base ti tabler-user-exclamation"></i></span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- 2. Main Data Table Card --}}
    <div class="card p-4">
        <div class="card-header border-bottom">
            <h5 class="card-title">Personnel Directory</h5>
            <div class="d-flex justify-content-between align-items-center row py-3 gap-3 gap-md-0">
                <div class="col-md-4">
                    <select id="role_filter" class="select2 form-control">
                        <option value="">Filter by Role</option>
                        @foreach($roles as $role)
                            <option value="{{ $role->name }}">{{ ucwords($role->name) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <select id="status_filter" class="select2 form-control">
                        <option value="">Filter by Status</option>
                        <option value="hired">Hired</option>
                        <option value="floating">Floating</option>
                        <option value="on_leave">On Leave</option>
                        <option value="resigned">Resigned</option>
                        <option value="preventive_suspension">Preventive Suspension</option>
                    </select>
                </div>
                <div class="col-md-4 text-end">
                    @can(config("permit.add personnel.name"))
                        <button class="btn btn-primary" type="button" data-bs-toggle="modal" data-bs-target="#users_modal">
                            {{-- ICON UPDATED --}}
                            <i class="icon-base ti tabler-plus"></i><span class="d-none d-sm-inline-block"> Add Personnel</span>
                        </button>
                    @endcan
                </div>
            </div>
        </div>
        <div class="card-datatable table-responsive">
            <table id="personnel_table" class="table border-top users-datatable" data-context="personnel">
                <thead>
                <tr>
                    <th>Name</th>
                    <th>Role</th>
                    <th>Detachment</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
                </thead>
            </table>
        </div>
    </div>
    @include('content.modals.add-edit-user-modal', ['context' => 'personnel', 'roles' => $roles, 'detachments' => $detachments])
@endsection
