@php
    use App\Models\User;use Illuminate\Support\Facades\Auth;
    $configData = Helper::appClasses();

    //dd(Auth::user()->getAllPermissions()->pluck('name')->toArray());
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
    'resources/assets/vendor/libs/flatpickr/flatpickr.scss',
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
    'resources/assets/js/pages-detachments-view.js',
    'resources/assets/js/users-table-functions.js',
    'resources/assets/js/extended-ui-sweetalert2.js',
    ])
@endsection

@section('content')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><span class="fw-bold">Directories</span></li>
            <li class="breadcrumb-item"><a href="{{ route('detachments') }}"><span class="fw-bold">Detachments</span></a></li>
            <li class="breadcrumb-item active">{{ $detachment->name }} Profile</li>
        </ol>
    </nav>
    <hr>
    <div class="row g-6">
        <div class="container my-4 my-md-5" data-detachment-id="{{ $detachment->id }}">
            <div class="row">
                <!-- Header Section -->
                <div class="col-md-12">
                    <div class="card shadow-sm mb-4">
                        <div class="card-body">
                            <div class="d-flex justify-content-end">
                                @if($detachment->status == 'pending')
                                    <span class="badge bg-label-warning">{{ ucwords($detachment->status) }}</span>
                                @endif
                            </div>
                            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center">
                                <div>
                                    {{-- Data is now rendered directly by the server --}}
                                    <h1 id="name_display" class="h3 fw-bold mb-1">{{ $detachment->name }} </h1>
                                    <p id="category_display" class="text-muted mb-0">{{ ucwords($detachment->category) }}</p>
                                </div>
                                <div class="mt-3 mt-md-0">
                                    <!-- Edit button now triggers the modal -->
                                    @can(config("permit.approve detachment.name"))
                                        @if($detachment->status == 'pending')
                                            <button type="button" id="approve_button" class="btn btn-label-primary" data-detachment-id="{{ $detachment->id }}"><i class="fas fa-pencil-alt me-1"></i> Approve</button>
                                        @endif
                                    @endcan
                                    @can(config("permit.edit detachment.name"))
                                        <button id="edit_button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#edit_detachment_modal"><i class="fas fa-pencil-alt me-1"></i> Edit Details</button>
                                    @endcan
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">General Info</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12 d-flex justify-content-center">
                                    <img src="{{ $detachment->assignedOfficer->profile_photo_url }}" alt="User Image" class="rounded-3 w-px-150 mb-3">
                                </div>
                            </div>
                            <div id="assigned_officer_display" class="fs-6 display-field" data-officer-id="{{ $detachment->assigned_officer ?? ''}}">
                                <a href="{{ route('user-profile', $detachment->assigned_officer ?? 'N/A') }}" class="list-group-item list-group-item-action d-flex align-items-center">
                                    <div class="w-100">
                                        <div class="d-flex justify-content-between">
                                            <div class="user-info">
                                                <label class="form-label text-muted">Assigned Officer</label>
                                                <h6 class="mb-1">{{ $detachment->assignedOfficer->name }}'</h6>
                                                <small class="text-muted">{{  $detachment->assignedOfficer->phone_number }}</small> <br>
                                                <small class="text-muted">#{{  $detachment->assignedOfficer->employee_number }}</small>
                                                <div class="user-status">
                                                    <span class="badge badge-dot bg-success"></span>
                                                    <small>Online</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <hr>
                            <h2 class="h5 fw-semibold mb-3">Location Details</h2>
                            <div class="row g-3">
                                <div class="col-12">
                                    <label class="form-label text-muted">Street</label>
                                    <div id="street_display" class="fs-6 display-field">{{ $detachment->street }}</div>
                                </div>
                                <div class="col-md-12">
                                    <label class="form-label text-muted">City</label>
                                    <div id="city_display" class="fs-6 display-field">{{ $detachment->city }}</div>
                                </div>
                                <div class="col-md-12">
                                    <label class="form-label text-muted">Province</label>
                                    <div id="province_display" class="fs-6 display-field">{{ $detachment->province }}</div>
                                </div>
                                <div class="col-md-12">
                                    <label class="form-label text-muted">Zip Code</label>
                                    <div id="zip_code_display" class="fs-6 display-field">{{ $detachment->zip_code }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Tab Navigation & Content -->
                <div class="col-md-9">
                    <div class="card shadow-sm">
                        <div class="card-header">
                            <ul class="nav nav-tabs card-header-tabs" id="detachmentTabs" role="tablist">
                                @can(config("permit.view own detachment personnel.name"))
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link active" id="personnel-tab" data-bs-toggle="tab" data-bs-target="#personnel" type="button" role="tab" aria-controls="personnel" aria-selected="false">Personnel Roster</button>
                                    </li>
                                @endcan
                                <li class="nav-item " role="presentation">
                                    <button class="nav-link @if(! Auth::user()->can(config("permit.view personnel.name"))) active @endif" id="duty-pay-tab" data-bs-toggle="tab" data-bs-target="#duty-pay" type="button" role="tab"
                                            aria-controls="duty-pay"
                                            aria-selected="false">
                                        Duty & Pay Rates
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="benefits-tab" data-bs-toggle="tab" data-bs-target="#benefits" type="button" role="tab" aria-controls="benefits" aria-selected="false">Benefits & Deductions</button>
                                </li>
                            </ul>
                        </div>
                        <div class="card-body p-4 tab-content" id="detachmentTabContent">
                            <!-- Tab Personnel Roster -->
                            @can(config("permit.view own detachment personnel.name"))
                                <div class="tab-pane show active" id="personnel" role="tabpanel" aria-labelledby="personnel-tab">
                                    <div class="row g-3">
                                        <h5 class="card-title">Personnel Directory</h5>
                                        <div class="d-flex justify-content-between align-items-center row py-3 gap-3 gap-md-0">
                                            <div class="col-md-4">
                                                <select id="role_filter" class="select2">
                                                    <option value="">Filter by Role</option>
                                                    @foreach($roles as $role)
                                                        <option value="{{ $role->name }}">{{ ucwords($role->name) }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-4">
                                                <select id="status_filter" class="select2">
                                                    <option value="">Filter by Status</option>
                                                    <option value="hired">Hired</option>
                                                    <option value="floating">Floating</option>
                                                    <option value="on_leave">On Leave</option>
                                                    <option value="resigned">Resigned</option>
                                                    <option value="preventive_suspension">Preventive Suspension</option>
                                                </select>
                                            </div>
                                            <div class="col-md-4 text-end">
                                                @can(config("permit.add personnel to detachment.name"))
                                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#add_personnel_modal">
                                                        <i class="fas fa-plus"></i> Add Personnel
                                                    </button>
                                                @endcan
                                            </div>
                                        </div>
                                        <div class="card-datatable table-responsive">
                                            <table id="detachment_personnel_table" class="table border-top users-datatable" data-detachment-id="{{ $detachment->id }}">
                                                <thead>
                                                <tr>
                                                    <th>Name</th>
                                                    <th>Role</th>
                                                    <th>Status</th>
                                                    <th>Actions</th>
                                                </tr>
                                                </thead>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            @endcan
                            <!-- Tab Duty & Pay Rates -->
                            <div class="tab-pane @if(! Auth::user()->can(config("permit.view personnel.name"))) show active @endif" id="duty-pay" role="tabpanel" aria-labelledby="duty-pay-tab">
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
                                    <div class="col-md-3"><label class="form-label text-muted">Hourly Rate</label>
                                        <div id="hr_rate_display" class="fs-6 rate-field display-field">{{ number_format($detachment->hr_rate, 2) }}</div>
                                    </div>
                                    <div class="col-md-3"><label class="form-label text-muted">OT Rate</label>
                                        <div id="ot_rate_display" class="fs-6 rate-field display-field">{{ number_format($detachment->ot_rate, 2) }}</div>
                                    </div>
                                    <div class="col-md-3"><label class="form-label text-muted">Night Diff. Rate</label>
                                        <div id="nd_rate_display" class="fs-6 rate-field display-field">{{ number_format($detachment->nd_rate, 2) }}</div>
                                    </div>
                                    <div class="col-md-3"><label class="form-label text-muted">Rest Day Rate</label>
                                        <div id="rdd_rate_display" class="fs-6 rate-field display-field">{{ number_format($detachment->rdd_rate, 2) }}</div>
                                    </div>
                                    <div class="col-md-3"><label class="form-label text-muted">Rest Day OT Rate</label>
                                        <div id="rdd_ot_rate_display" class="fs-6 rate-field display-field">{{ number_format($detachment->rdd_ot_rate, 2) }}</div>
                                    </div>
                                    <div class="col-md-3"><label class="form-label text-muted">Holiday Rate</label>
                                        <div id="hol_rate_display" class="fs-6 rate-field display-field">{{ number_format($detachment->hol_rate, 2) }}</div>
                                    </div>
                                    <div class="col-md-3"><label class="form-label text-muted">Holiday OT Rate</label>
                                        <div id="hol_ot_rate_display" class="fs-6 rate-field display-field">{{ number_format($detachment->hol_ot_rate, 2) }}</div>
                                    </div>
                                    <div class="col-md-3"><label class="form-label text-muted">Special Hol. Rate</label>
                                        <div id="sh_rate_display" class="fs-6 rate-field display-field">{{ number_format($detachment->sh_rate, 2) }}</div>
                                    </div>
                                    <div class="col-md-3"><label class="form-label text-muted">Special Hol. OT Rate</label>
                                        <div id="sh_ot_rate_display" class="fs-6 rate-field display-field">{{ number_format($detachment->sh_ot_rate, 2) }}</div>
                                    </div>
                                    <div class="col-md-3"><label class="form-label text-muted">RD + Holiday Rate</label>
                                        <div id="rd_hol_rate_display" class="fs-6 rate-field display-field">{{ number_format($detachment->rd_hol_rate, 2) }}</div>
                                    </div>
                                    <div class="col-md-3"><label class="form-label text-muted">RD + Holiday OT Rate</label>
                                        <div id="rd_hol_ot_rate_display" class="fs-6 rate-field display-field">{{ number_format($detachment->rd_hol_ot_rate, 2) }}</div>
                                    </div>
                                    <div class="col-md-3"><label class="form-label text-muted">RD + Special Hol. Rate</label>
                                        <div id="rd_sh_rate_display" class="fs-6 rate-field display-field">{{ number_format($detachment->rd_sh_rate, 2) }}</div>
                                    </div>
                                    <div class="col-md-3"><label class="form-label text-muted">RD + Special Hol. OT Rate</label>
                                        <div id="rd_sh_ot_rate_display" class="fs-6 rate-field display-field">{{ number_format($detachment->rd_sh_ot_rate, 2) }}</div>
                                    </div>
                                </div>
                            </div>
                            <!-- Tab Benefits & Deductions -->
                            <div class="tab-pane" id="benefits" role="tabpanel" aria-labelledby="benefits-tab">
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
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('content.modals.add-edit-user-modal', ['context' => 'detachment-view', 'roles' => $roles, 'detachment_id' => $detachment->id])
    @can(config("permit.edit detachment.name"))
        @include('content.modals.edit-detachment-modal')
    @endcan
    @can(config("permit.add detachment.name"))
        @include('content.modals.add-detachment-modal')
    @endcan
    @can(config("permit.add personnel to detachment.name"))
        @include('content.modals.add-personnel-modal')
    @endcan
@endsection
