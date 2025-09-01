@php
    use Illuminate\Support\Facades\Auth;$configData = Helper::appClasses();
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

<!-- Vendor Scripts -->
@section('vendor-script')
    @vite([
    'resources/assets/vendor/libs/jquery/jquery.js',
    'resources/assets/vendor/libs/select2/select2.js',
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
            <li class="breadcrumb-item"><span class="fw-bold">Main</span></li>
            <li class="breadcrumb-item active">Form Library</li>
        </ol>
    </nav>
    <hr>
    <div class="card shadow-sm">
        <div class="card-body">
            <div class="container mt-5">
                <div class="text-center mb-5">
                    <h1 class="display-5">Form Library</h1>
                    <p class="lead text-muted">Quickly find and access all company forms.</p>
                </div>

                <div class="mb-12">
                    <input type="text" id="formSearch" class="form-control form-control-lg h-px-50 text-center display-2" placeholder="Search for a form by name or keyword...">
                </div>

                <div class="row">
                    <div class="col-lg-3 mb-5">
                        <div class="card">
                            <div class="card-header">
                                <h5>Categories</h5>
                            </div>
                            <div class="list-group list-group-flush">
                                <a href="#" class="list-group-item list-group-item-action active" data-category="all">All Forms</a>
                                <a href="#" class="list-group-item list-group-item-action" data-category="onboarding">Onboarding</a>
                                <a href="#" class="list-group-item list-group-item-action" data-category="performance">Performance</a>
                                <a href="#" class="list-group-item list-group-item-action" data-category="requisition">Requisition</a>
                                <a href="#" class="list-group-item list-group-item-action" data-category="benefits">Benefits & Claims</a>
                                <a href="#" class="list-group-item list-group-item-action" data-category="personnel">Personnel</a>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-9">
                        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4" id="forms-grid">
                            {{-- ONBOARDING --}}
                            @can(config("permit.fill requirement transmittal form.name"))
                                <div class="col" data-category="onboarding">
                                    <div class="card h-100">
                                        <div class="card-body">
                                            <h5 class="card-title">Requirement Transmittal</h5>
                                            <p class="card-text small text-muted">For submitting employee documents during hiring.</p>
                                            <span class="badge bg-label-dark">Onboarding</span>
                                        </div>
                                        <div class="card-footer"><a href="{{ route('forms.create', 'requirement-transmittal-form') }}" class="btn btn-primary w-100">Fill Form</a></div>
                                    </div>
                                </div>
                            @endcan

                            {{-- PERFORMANCE --}}
                            @can(config("permit.fill first month performance evaluation form.name"))
                                <div class="col" data-category="performance">
                                    <div class="card h-100">
                                        <div class="card-body">
                                            <h5 class="card-title">1st Month Performance Evaluation</h5>
                                            <p class="card-text small text-muted">Initial performance review for new employees.</p>
                                            <span class="badge bg-success">Performance</span>
                                        </div>
                                        <div class="card-footer"><a href="{{ route('forms.create', 'first-month-performance-evaluation-form') }}" class="btn btn-primary w-100">Fill Form</a></div>
                                    </div>
                                </div>
                            @endcan
                            @can(config("permit.fill third month performance evaluation form.name"))
                                <div class="col" data-category="performance">
                                    <div class="card h-100">
                                        <div class="card-body">
                                            <h5 class="card-title">3rd Month Performance Evaluation</h5>
                                            <p class="card-text small text-muted">Mid-probationary performance review.</p>
                                            <span class="badge bg-success">Performance</span>
                                        </div>
                                        <div class="card-footer"><a href="{{ route('forms.create', 'third-month-performance-evaluation-form') }}" class="btn btn-primary w-100">Fill Form</a></div>
                                    </div>
                                </div>
                            @endcan
                            @can(config("permit.fill sixth month performance evaluation form.name"))
                                <div class="col" data-category="performance">
                                    <div class="card h-100">
                                        <div class="card-body">
                                            <h5 class="card-title">6th Month Performance Evaluation</h5>
                                            <p class="card-text small text-muted">Final performance review for regularization.</p>
                                            <span class="badge bg-success">Performance</span>
                                        </div>
                                        <div class="card-footer"><a href="{{ route('forms.create', 'sixth-month-performance-evaluation-form') }}" class="btn btn-primary w-100">Fill Form</a></div>
                                    </div>
                                </div>
                            @endcan

                            @can(config("permit.fill applicant application form.name"))
                                <div class="col" data-category="performance">
                                    <div class="card h-100">
                                        <div class="card-body">
                                            <h5 class="card-title">Applicant Evaluation</h5>
                                            <p class="card-text small text-muted">For interviewers to assess job applicants.</p>
                                            <span class="badge bg-success">Performance</span>
                                        </div>
                                        <div class="card-footer"><a href="{{ route('forms.create', 'applicant-evaluation-form') }}" class="btn btn-primary w-100">Fill Form</a></div>
                                    </div>
                                </div>
                            @endcan

                            @can(config("permit.fill personnel requisition form.name"))
                                {{-- REQUISITION --}}
                                <div class="col" data-category="requisition">
                                    <div class="card h-100">
                                        <div class="card-body">
                                            <h5 class="card-title">Personnel Requisition</h5>
                                            <p class="card-text small text-muted">Request to hire a new employee for a vacant position.</p>
                                            <span class="badge bg-warning">Requisition</span>
                                        </div>
                                        <div class="card-footer"><a href="{{ route('forms.create', 'personnel-requisition-form') }}" class="btn btn-primary w-100">Fill Form</a></div>
                                    </div>
                                </div>
                            @endcan
                            @can(config("permit.fill supply requisition form.name"))
                                <div class="col" data-category="requisition">
                                    <div class="card h-100">
                                        <div class="card-body">
                                            <h5 class="card-title">Supply Requisition</h5>
                                            <p class="card-text small text-muted">Request for office supplies or equipment.</p>
                                            <span class="badge bg-warning">Requisition</span>
                                        </div>
                                        <div class="card-footer"><a href="{{ route('forms.create', 'supply-requisition-form') }}" class="btn btn-primary w-100">Fill Form</a></div>
                                    </div>
                                </div>

                                {{-- BENEFITS & CLAIMS --}}
                            @endcan
                            @can(config("permit.fill SSS accident sickness form.name"))
                                <div class="col" data-category="benefits">
                                    <div class="card h-100">
                                        <div class="card-body">
                                            <h5 class="card-title">SSS Accident & Sickness</h5>
                                            <p class="card-text small text-muted">Claim form for SSS sickness or accident benefits.</p>
                                            <span class="badge bg-info">Benefits & Claims</span>
                                        </div>
                                        <div class="card-footer"><a href="{{ route('forms.create', 'sss-accident-and-sickness-form') }}" class="btn btn-primary w-100">Fill Form</a></div>
                                    </div>
                                </div>
                            @endcan
                            @can(config("permit.fill maternity claim form.name"))
                                <div class="col" data-category="benefits">
                                    <div class="card h-100">
                                        <div class="card-body">
                                            <h5 class="card-title">Maternity Claim</h5>
                                            <p class="card-text small text-muted">Application for SSS maternity leave benefits.</p>
                                            <span class="badge bg-info">Benefits & Claims</span>
                                        </div>
                                        <div class="card-footer"><a href="{{ route('forms.create', 'maternity-claim-form') }}" class="btn btn-primary w-100">Fill Form</a></div>
                                    </div>
                                </div>
                            @endcan
                            @can(config("permit.fill benevolent application form.name"))
                                <div class="col" data-category="benefits">
                                    <div class="card h-100">
                                        <div class="card-body">
                                            <h5 class="card-title">Benevolent Application</h5>
                                            <p class="card-text small text-muted">Application for financial assistance from the company.</p>
                                            <span class="badge bg-info">Benefits & Claims</span>
                                        </div>
                                        <div class="card-footer"><a href="{{ route('forms.create', 'benevolent-application-form') }}" class="btn btn-primary w-100">Fill Form</a></div>
                                    </div>
                                </div>

                                {{-- PERSONNEL --}}
                            @endcan
                            @can(config("permit.fill id application form.name"))
                                <div class="col" data-category="personnel">
                                    <div class="card h-100">
                                        <div class="card-body">
                                            <h5 class="card-title">ID Application</h5>
                                            <p class="card-text small text-muted">Request a new or replacement company ID.</p>
                                            <span class="badge bg-primary">Personnel</span>
                                        </div>
                                        <div class="card-footer"><a href="{{ route('forms.create', 'id-application-form') }}" class="btn btn-primary w-100">Fill Form</a></div>
                                    </div>
                                </div>
                            @endcan
                            @can(config("permit.fill license renewal form.name"))
                                <div class="col" data-category="personnel">
                                    <div class="card h-100">
                                        <div class="card-body">
                                            <h5 class="card-title">License Renewal</h5>
                                            <p class="card-text small text-muted">Form for renewing professional or security licenses.</p>
                                            <span class="badge bg-primary">Personnel</span>
                                        </div>
                                        <div class="card-footer"><a href="{{ route('forms.create', 'license-renewal-form') }}" class="btn btn-primary w-100">Fill Form</a></div>
                                    </div>
                                </div>
                            @endcan
                            @can(config("permit.fill attendance form.name"))
                                <div class="col" data-category="personnel">
                                    <div class="card h-100">
                                        <div class="card-body">
                                            <h5 class="card-title">Guard Attendance</h5>
                                            <p class="card-text small text-muted">Daily time record for security guard attendance.</p>
                                            <span class="badge bg-primary">Personnel</span>
                                        </div>
                                        <div class="card-footer"><a href="{{ route('forms.create', 'guard-attendance') }}" class="btn btn-primary w-100">Fill Form</a></div>
                                    </div>
                                </div>
                            @endcan
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
