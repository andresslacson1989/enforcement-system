@php
    use App\Models\User;use Spatie\Permission\Models\Role;$configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('title', 'Staffs')
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
    'resources/assets/vendor/libs/@form-validation/popular.js',
    'resources/assets/vendor/libs/@form-validation/bootstrap5.js',
    'resources/assets/vendor/libs/@form-validation/auto-focus.js'
    ])
@endsection

<!-- Page Scripts -->
@section('page-script')
    @vite([
    'resources/assets/js/pages-profile.js',
    'resources/assets/js/users-table-functions.js',
    ])
@endsection

@section('content')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><span class="fw-bold">Main</span></li>
            <li class="breadcrumb-item active">Profile</li>
        </ol>
    </nav>
    <hr>
    <div class="row g-6">
        <div class="container my-4 my-md-5">
            <div class="row">

                <div class="col-lg-4 mb-4">
                    <div class="card text-center profile-card shadow-sm">
                        <div class="card-header"></div>
                        <div class="card-body">
                            <img src="{{ $user->profile_photo_url }}" alt="Profile Picture" class="profile-pic">

                            <h4 class="card-title mt-3"> {{ $user->name ?? '' }} </h4>
                            <p class="text-muted mb-1">Employee No.: #{{ $user->employee_number?? '' }} </p>

                            <h5>
                                <span class="badge bg-primary"> {{ ucwords(Role::findById($user->primary_role_id)->name ?? '') }} </span>
                                <span class="badge bg-success"> {{ ucwords($user->status ?? '') }} </span>
                            </h5>

                            <hr>

                            <div class="text-start">
                                <p><strong><i class="fas fa-envelope me-2"></i>Email:</strong> {{ $user->email ?? '' }} </p>
                                <p><strong><i class="fas fa-phone me-2"></i>Phone:</strong> {{ $user->phone_number ?? '' }} </p>
                                <p><strong><i class="fas fa-phone me-2"></i>Street:</strong> {{ $user->street ?? '' }} </p>
                                <p><strong><i class="fas fa-phone me-2"></i>City:</strong> {{ $user->city ?? '' }} </p>
                                <p><strong><i class="fas fa-phone me-2"></i>Province:</strong> {{ $user->province ?? '' }} </p>
                                <p><strong><i class="fas fa-phone me-2"></i>Zip Code:</strong> {{ $user->zip_code ?? '' }} </p>
                                <p><strong><i class="fas fa-phone me-2"></i>Gender:</strong> {{ ucfirst($user->gender ?? '')  }} </p>
                                <hr>
                                <p><strong><i class="fas fa-phone me-2"></i>Telegram:</strong> {{ $user->telegram_chat_id  ?? '' }} </p>
                            </div>
                            @can(config("permit.edit personnel.name"))
                                <a href="#" class="btn btn-dark mt-3 w-100 edit-user" data-user-id="{{ $user->id }}">Edit Profile</a>
                            @endcan
                        </div>
                    </div>
                </div>

                <div class="col-lg-8">

                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-white">
                            <h5 class="card-title mb-0"><i class="fas fa-building me-2"></i>Current Detachment</h5>
                        </div>
                        <div class="card-body">
                            <h4> {{ $detachment->name ?? 'N/A' }}</h4>
                            <p class="text-muted"> {{ ucwords($detachment->street ?? '') }} {{ ucwords($detachment->city ?? '') }} {{ ucwords($detachment->province ?? '') }} {{ $detachment->zip_code ?? '' }}</p>
                            <hr>
                            <p><strong>Assigned Officer:</strong> {{ $detachment->assignedOfficer->name ?? '' }} </p>
                            <p><strong>Contact Number:</strong> {{ $detachment->phone_number ?? '' }} </p>
                        </div>
                    </div>
                    <!-- Tab Navigation & Content -->
                    <div class="col-md-12">
                        <div class="card shadow-sm">
                            <div class="card-header">
                                <ul class="nav nav-tabs card-header-tabs" id="detachmentTabs" role="tablist">
                                    <li class="nav-item " role="presentation">
                                        <button class="nav-link active" id="documents-tab" data-bs-toggle="tab" data-bs-target="#documents" type="button" role="tab" aria-controls="documents" aria-selected="false">
                                            Forms
                                        </button>
                                    </li>
                                    <li class="nav-item " role="presentation">
                                        <button class="nav-link" id="notifications-tab" data-bs-toggle="tab" data-bs-target="#notifications" type="button" role="tab" aria-controls="notifications" aria-selected="false">
                                            Notifications
                                        </button>
                                    </li>
                                </ul>
                            </div>
                            <div class="card-body p-4 tab-content" id="detachmentTabContent">
                                <!-- Forms -->
                                <div class="tab-pane show active" id="documents" role="tabpanel" aria-labelledby="documents-tab">
                                    @if($forms->count() > 0)
                                        <table class="table-sm table-borderless table-flush-spacing" id="forms_table">
                                            <thead>
                                            <tr>
                                                <th>Name</th>
                                                <th class="text-nowrap">Submitted By</th>
                                                <th class="text-nowrap">Submitted For</th>
                                                <th>Date</th>
                                            </tr>
                                            </thead>
                                            @foreach ($forms as $item)
                                                @php
                                                    $form = $item->submittable;
                                                @endphp
                                                <tr>
                                                    <td>
                                                        <a href="/form/view/{{ strtolower(str_replace(' ', '-', $form->name)) }}/{{ $form->id }}"> {{ $form->name }}</a>
                                                    </td>
                                                    <td>
                                                        {{ $form->submittedBy->name ?? ''}}
                                                    </td>
                                                    <td>
                                                        {{ $form->employee->name ?? ''}}
                                                    </td>
                                                    <td class="text-nowrap">
                                                        <span>{{ $form->created_at->format('M d, Y H:i') }}</span> <br>
                                                        <small class="text-muted">{{ $form->created_at->diffForHumans() }}</small>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </table>
                                    @else
                                        <ul class="list-group list-group-flush">
                                            <li class="list-group-item text-center text-muted">No Forms.</li>
                                        </ul>
                                    @endif
                                </div>
                                <!-- Notifications -->
                                <div class="tab-pane show" id="notifications" role="tabpanel" aria-labelledby="notifications-tab">
                                    <ul class="list-group list-group-flush">
                                        @forelse ($notifications as $notification)
                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                <a href="{{ $notification->link }}"> {{ $notification->body }}</a>
                                                <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                                            </li>
                                        @empty
                                            <li class="list-group-item text-center text-muted">No notifications.</li>
                                        @endforelse
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @can(config("permit.edit personnel.name"))
        @include('content.modals.edit-profile-modal')
    @endcan
@endsection



