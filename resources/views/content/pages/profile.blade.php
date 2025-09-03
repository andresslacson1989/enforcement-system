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
@section('page-style')
    <style>
        .profile-details i {
            width: 1.2rem; /* Align icons nicely */
        }
    </style>
@endsection

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
                            <div class="profile-pic-container position-relative d-inline-block">
                                <img src="{{ $user->profile_photo_url }}" alt="Profile Picture" class="img img-thumbnail rounded-3 w-px-160" id="profile-pic-img" />
                                @if(Auth::id() == $user->id || Auth::user()->can(config("permit.edit personnel.name")))
                                    <button class="btn btn-icon btn-label-gray position-absolute bottom-0 end-0" id="change-photo-btn" data-custom-tooltip="tooltip" data-user-id="{{ $user->id }}" custom-tooltip-title="Change Photo">
                                        <i class="ti icon-lg tabler-camera"></i>
                                    </button>
                                @endif
                            </div>
                            <input type="file" id="profile-photo-input" name="photo" class="d-none" accept="image/png, image/jpeg, image/jpg">
                            <h4 class="card-title mt-3"> {{ $user->name ?? '' }} </h4>
                            <p class="text-muted mb-1">Employee No.: #{{ $user->employee_number ?? '' }} </p>
                            <h5>
                                <span class="badge bg-primary"> {{ ucwords(Role::findById($user->primary_role_id)->name ?? '') }} </span>
                                <span class="badge bg-success"> {{ ucwords($user->status ?? '') }} </span>
                            </h5>
                            <hr>
                            <div class="text-start profile-details">
                                <p><i class="ti tabler-mail me-2"></i><strong>Email:</strong> {{ $user->email ?? 'N/A' }}</p>
                                <p><i class="ti tabler-phone me-2"></i><strong>Phone:</strong> {{ $user->phone_number ?? 'N/A' }}</p>
                                <p><i class="ti tabler-brand-telegram me-2"></i><strong>Telegram:</strong> {{ $user->telegram_chat_id ?? 'N/A' }}</p>
                                <hr>
                                <p><i class="ti tabler-calendar me-2"></i><strong>Birthdate:</strong> {{ $user->birthdate ? $user->birthdate->format('M d, Y') : 'N/A' }}</p>
                                <p><i class="ti tabler-gender-male me-2"></i><strong>Gender:</strong> {{ ucfirst($user->gender ?? 'N/A') }}</p>
                                <hr>
                                <p><i class="ti tabler-map-pin me-2"></i><strong>Address:</strong><br>
                                    <span class="ms-4">{{ $user->street ?? 'N/A' }}, {{ $user->city ?? '' }}</span><br>
                                    <span class="ms-4">{{ $user->province ?? '' }}, {{ $user->zip_code ?? '' }}</span>
                                </p>
                                <hr>
                                <h6 class="text-muted">Government IDs</h6>
                                <p><i class="ti tabler-id me-2"></i><strong>SSS:</strong> {{ $user->sss_number ?? 'N/A' }}</p>
                                <p><i class="ti tabler-id me-2"></i><strong>PhilHealth:</strong> {{ $user->philhealth_number ?? 'N/A' }}</p>
                                <p><i class="ti tabler-id me-2"></i><strong>Pag-IBIG:</strong> {{ $user->pagibig_number ?? 'N/A' }}</p>
                            </div>
                            {{-- A user can edit their own profile, or an admin with permission can edit any profile --}}
                            @if(Auth::id() == $user->id || Auth::user()->can(config("permit.edit personnel.name")))
                                <a href="#" class="btn btn-dark mt-3 w-100 edit-user" data-user-id="{{ $user->id }}">Edit Profile</a>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-lg-8">

                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-white">
                            <h5 class="card-title mb-0"><i class="fas fa-building me-2"></i>Current Detachment</h5>
                        </div>
                        <div class="card-body">
                            <h4> {{ $user->detachment?->name ?? 'N/A' }}</h4>
                            <p class="text-muted"> {{ ucwords($user->detachment?->street ?? '') }} {{ ucwords($user->detachment?->city ?? '') }} {{ ucwords($user->detachment?->province ?? '') }} {{ $user->detachment?->zip_code ?? '' }}</p>
                            <hr>
                            <p><strong>Assigned Officer:</strong> {{ $user->detachment?->assignedOfficer?->name ?? 'N/A' }} </p>
                            <p><strong>Contact Number:</strong> {{ $user->detachment?->phone_number ?? 'N/A' }} </p>
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
                                    <li class="nav-item " role="presentation">
                                        <button class="nav-link" id="telegram-tab" data-bs-toggle="tab" data-bs-target="#telegram" type="button" role="tab" aria-controls="telegram" aria-selected="false">
                                            Telegram
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
                                                    $submitted_form = $item->submittable;
                                                @endphp
                                                <tr>
                                                    <td>
                                                        <a href="/form/view/{{ strtolower(str_replace(' ', '-', $submitted_form->name)) }}/{{ $submitted_form->id }}"> {{ $submitted_form->name }}</a>
                                                    </td>
                                                    <td>
                                                        @can(config("permit.view any personnel profile.name"))
                                                            <a href="{{ route('user-profile', $submitted_form->submittedBy->id) }}">{{ $submitted_form->submittedBy->name ?? ''}}</a>
                                                        @else
                                                            {{ $submitted_form->submittedBy->name ?? ''}}
                                                        @endcan
                                                    </td>
                                                    <td>
                                                        @can(config("permit.view any personnel profile.name"))
                                                            <a href="{{ route('user-profile', $submitted_form->employee->id) }}">{{ $submitted_form->employee->name ?? ''}}</a>
                                                        @else
                                                            {{ $submitted_form->employee->name ?? ''}}
                                                        @endcan
                                                    </td>
                                                    <td class="text-nowrap">
                                                        <span>{{ $submitted_form->created_at->format('M d, Y H:i') }}</span> <br>
                                                        <small class="text-muted">{{ $submitted_form->created_at->diffForHumans() }}</small>
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
                                <!-- Telegram Tab -->
                                <div class="tab-pane" id="telegram" role="tabpanel" aria-labelledby="telegram-tab">
                                    <div class="text-center">
                                        @if ($user->telegram_chat_id)
                                            <div class="alert alert-success" role="alert">
                                                <h5 class="alert-heading"><i class="ti ti-circle-check me-2"></i>Account Linked!</h5>
                                                <p class="mb-0">Your Telegram account is successfully linked to the system.</p>
                                            </div>
                                        @else
                                            <div id="telegram-linking-section">
                                                @if($telegram_linking_url)
                                                    <h5>Link Your Telegram Account</h5>
                                                    <p class="text-muted">Click the button below to link your Telegram account and receive notifications directly.</p>
                                                    <a href="{{ $telegram_linking_url }}" target="_blank" class="btn btn-primary my-3">
                                                        <i class="ti tabler-brand-telegram me-2"></i> Link with Telegram
                                                    </a>
                                                    <small class="text-muted d-block mt-2">This link is valid for 10 minutes.</small>
                                                @else
                                                    <p class="text-muted">Could not generate a linking URL at this time. Please try again later.</p>
                                                @endif
                                            </div>
                                        @endif
                                    </div>
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
