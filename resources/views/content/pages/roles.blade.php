@php
    $configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('title', 'Roles & Permissions')

@section('vendor-style')
    @vite(['resources/assets/vendor/libs/sweetalert2/sweetalert2.scss'])
@endsection

@section('vendor-script')
    @vite([
      'resources/assets/vendor/libs/sweetalert2/sweetalert2.js',
    ])
@endsection

@section('page-script')
    @vite([
        'resources/assets/js/pages-roles.js',
        'resources/assets/js/ui-popover.js',
    ])
@endsection

@section('content')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><span class="fw-bold">Management</span></li>
            <li class="breadcrumb-item"><span class="fw-bold">Access</span></li>
            <li class="breadcrumb-item active">Roles</li>
        </ol>
    </nav>
    <hr class="mt-0">

    <div class="row">
        {{-- Left Panel: Role List --}}
        <div class="col-lg-4 col-md-5">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <h5 class="mb-0">Roles</h5>
                    @can(config("permit.add role.name"))
                        <button class="btn btn-primary btn-sm" id="addNewRoleBtn">
                            <i class="icon-20px ti tabler-plus"></i> New Role
                        </button>
                    @endcan
                </div>
                <div class="list-group list-group-flush" id="roles-list">
                    @foreach($roles as $role)
                        <a href="#" class="list-group-item list-group-item-action role-item"
                           data-role-id="{{ $role->id }}"
                           data-role-name="{{ $role->name }}"
                           data-role-description="{{ $role->description }}"
                           data-role-permissions="{{ json_encode($role->permissions->pluck('name')) }}">

                            {{-- UPDATED STRUCTURE --}}
                            <div class="d-flex w-100 justify-content-between">
                                <h6 class="mb-1">{{ ucwords($role->name) }}</h6>
                                <span class="badge bg-label-primary rounded-pill">{{ $role->users_count }}   <i class="ti tabler-users align-content-center"></i> </span>
                            </div>
                            <small class="text-muted">{{ $role->permissions_count }} permissions assigned</small>

                        </a>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Right Panel: Details and Permissions --}}
        <div class="col-lg-8 col-md-7">
            <div class="card">
                <div class="card-body" id="details-panel">
                    {{-- Initial State --}}
                    <div id="initial-state" class="text-center">
                        <i class="icon-50px ti tabler-pencil-search mb-2"></i>
                        <h5>Select a Role</h5>
                        <p>Select a role from the list to view or edit its permissions.</p>
                    </div>

                    {{-- Editing/Creating State (Initially Hidden) --}}
                    <div id="editing-state" class="d-none">
                        <form id="roleForm">
                            @csrf
                            <input type="hidden" id="role_id" name="role_id">

                            <h5 id="form-title" class="mb-3">Edit Role</h5>

                            {{-- Role Name and Description --}}
                            <div class="row g-3">
                                <div class="col-12">
                                    <label for="role_name" class="form-label">Role Name</label>
                                    <input type="text" id="role_name" name="name" class="form-control" placeholder="Enter a role name" required>
                                </div>
                                <div class="col-12">
                                    <label for="role_description" class="form-label">Description</label>
                                    <input type="text" id="role_description" name="description" class="form-control" placeholder="A short description for the role" required>
                                </div>
                            </div>

                            <hr>

                            {{-- Permissions Section --}}
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <h5 class="mb-0">Permissions</h5>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="selectAll">
                                    <label class="form-check-label" for="selectAll">Select All</label>
                                </div>
                            </div>

                            <div class="permissions-container">
                                @foreach($permissions as $group => $permissionGroup)
                                    <div class="mb-3">
                                        <h6 class="bg-label-dark p-2 fw-bold">{{ $group }}</h6>
                                        <div class="row">
                                            @foreach($permissionGroup as $permission)
                                                <div class="col-md-6 col-lg-4">
                                                    <div class="form-check mb-2">
                                                        <input class="form-check-input permission-checkbox" type="checkbox" name="permissions[]" value="{{ $permission['name'] }}" id="perm_{{ $permission['id'] }}" />
                                                        <label class="form-check-label" for="perm_{{ $permission['id'] }}">
                                                            {{ ucwords($permission['name']) }}
                                                            <i class="icon-base ti tabler-info-circle icon-xs text-muted ms-1 mb-2" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ $permission['description'] }}"></i>
                                                        </label>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <div class="mt-4 d-flex justify-content-end">
                                <button type="submit" class="btn btn-primary">Save Changes</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
