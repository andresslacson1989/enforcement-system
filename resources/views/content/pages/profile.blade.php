@php
    use App\Http\Classes\UserClass;use App\Models\User;use Carbon\Carbon;use Spatie\Permission\Models\Role;$configData = Helper::appClasses();
      $isNavbar = false;
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
    <style>
        @media (min-width: 1200px) {
            .modal-xl {
                --bs-modal-width: 1550px !important;
            }
        }
    </style>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><span class="fw-bold">Main</span></li>
            <li class="breadcrumb-item active">Profile</li>
        </ol>
    </nav>
    <hr>
    <div class="row g-6">
        <!-- Header -->
        <div class="col-12">
            <div class="card mb-6">
                <div class="user-profile-header-banner img-fluid">
                    <img src="{{ asset('assets/img/pages/profile-banner.png') }}" alt="Banner image" class="rounded-top w-100 h-px-200">
                </div>
                <div class="user-profile-header d-flex flex-column flex-lg-row text-sm-start text-center mb-5">
                    <div class="flex-shrink-0 mt-n2 mx-sm-0 mx-auto">
                        <div class="profile-pic-container position-relative d-inline-block ms-0 ms-sm-6"
                             style="width: 160px; height: 160px;">
                            <img src="{{ $user->profile_photo_url }}" alt="user image" class="d-block h-auto rounded user-profile-img img-fluid" id="profile-pic-img">
                            @if(Auth::id() == $user->id || Auth::user()->can(config("permit.edit personnel.name")))
                                <button class="btn btn-icon btn-label-dark bt-sm position-absolute bottom-0 end-0 mb-1 me-1"
                                        id="change-photo-btn" data-custom-tooltip="tooltip" data-user-id="{{ $user->id }}"
                                        custom-tooltip-title="Change Photo">
                                    <i class="ti tabler-camera"></i>
                                </button>
                            @endif
                            <input type="file" id="profile-photo-input" name="photo" class="d-none"
                                   accept="image/png, image/jpeg, image/jpg">
                        </div>
                    </div>
                    <div class="flex-grow-1 mt-3 mt-lg-5">
                        <div
                            class="d-flex align-items-md-end align-items-sm-start align-items-center justify-content-md-between justify-content-start mx-5 flex-md-row flex-column gap-4">
                            <div class="user-profile-info">
                                <h4 class="mb-2 mt-lg-6">{{ $user->name ?? '' }}</h4>
                                <ul
                                    class="list-inline mb-0 d-flex align-items-center flex-wrap justify-content-sm-start justify-content-center gap-4 my-2">
                                    @foreach($user->getRoleNames() as $role)
                                        <li class="list-inline-item d-flex gap-2 align-items-center"><i
                                                class="icon-base ti tabler-color-swatch icon-lg"></i><span
                                                class="fw-medium">{{ ucwords($role) }}</span></li>
                                    @endforeach
                                    <li class="list-inline-item d-flex gap-2 align-items-center"><i
                                            class="icon-base ti tabler-map-pin icon-lg"></i><span
                                            class="fw-medium">{{ $user->detachment?->name ?? 'Unassigned' }}</span></li>
                                    <li class="list-inline-item d-flex gap-2 align-items-center"><i
                                            class="icon-base ti tabler-calendar icon-lg"></i><span class="fw-medium"> Joined
                                            {{ $user->created_at->format('F Y') }}</span></li>
                                </ul>
                            </div>
                            @if(Auth::id() == $user->id || Auth::user()->can(config("permit.edit personnel.name")))
                                <a href="#" class="btn btn-primary mb-1 edit-user" data-user-id="{{ $user->id }}">
                                    <i class="ti tabler-edit me-1"></i>Edit Profile
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--/ Header -->
        <div class="row">
            <!-- Tab Navigation & Content -->
            <div class="col-md-12">
                <ul class="nav nav-pills flex-column flex-sm-row mb-4 gap-sm-1 gap-2" id="detachmentTabs" role="tablist">
                    <li class="nav-item " role="presentation">
                        <button class="nav-link active waves-effect waves-light active" id="about-me-tab" data-bs-toggle="tab" data-bs-target="#about-me" type="button" role="tab" aria-controls="about-me"
                                aria-selected="true">
                            About Me
                        </button>
                    </li>
                    <li class="nav-item " role="presentation">
                        <button class="nav-link" id="documents-tab" data-bs-toggle="tab" data-bs-target="#documents" type="button" role="tab" aria-controls="documents" aria-selected="false">
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
                    <li class="nav-item " role="presentation">
                        <button class="nav-link" id="trainings-tab" data-bs-toggle="tab" data-bs-target="#trainings" type="button" role="tab" aria-controls="trainings" aria-selected="false">
                            Trainings
                        </button>
                    </li>
                    <li class="nav-item " role="presentation">
                        <button class="nav-link" id="files-tab" data-bs-toggle="tab" data-bs-target="#files" type="button" role="tab" aria-controls="files" aria-selected="false">
                            Files
                        </button>
                    </li>
                </ul>
            </div>
            <div class="col-12">
                <div class="card">
                    <div class="card shadow-sm">
                        <div class="nav-align-top">
                            <div class="card-header">
                                <div class="card-body p-4 tab-content" id="detachmentTabContent">
                                    <!-- Forms -->
                                    <div class="tab-pane fade show active" id="about-me" role="tabpanel"
                                         aria-labelledby="about-me-tab">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <!-- About Card -->
                                                <div class="card card-body mb-4">
                                                    <h5 class="card-title text-uppercase text-body-secondary small mb-3">About</h5>
                                                    <ul class="list-unstyled">
                                                        <li class="d-flex align-items-center mb-3"><i class="ti ti-user ti-md me-2"></i><span class="fw-medium me-2">Full Name:</span> <span>{{ $user->name }}</span></li>
                                                        <li class="d-flex align-items-center mb-3"><i class="ti ti-check ti-md me-2"></i><span class="fw-medium me-2">Status:</span> <span class="badge bg-label-primary">{{ strtoupper($user->status ?? '') }}</span></li>
                                                        <li class="d-flex align-items-center mb-3"><i class="ti ti-crown ti-md me-2"></i><span class="fw-medium me-2">Role:</span> <span>{{ ucwords($user->primaryRole->name ?? 'N/A') }}</span></li>
                                                        <li class="d-flex align-items-center mb-3"><i class="ti ti-id-badge-2 ti-md me-2"></i><span class="fw-medium me-2">Employee No:</span> <span>#{{ $user->employee_number ?? '' }}</span></li>
                                                        <li class="d-flex align-items-center mb-3"><i class="ti ti-license ti-md me-2"></i><span class="fw-medium me-2">License No:</span> <span>{{ $user->license_number ?? 'N/A' }}</span></li>
                                                    </ul>
                                                </div>
                                                <!-- Contacts Card -->
                                                <div class="card card-body">
                                                    <h5 class="text-uppercase text-body-secondary small mb-3">Contacts</h5>
                                                    <ul class="list-unstyled">
                                                        <li class="d-flex align-items-center mb-3"><i class="ti ti-phone-call ti-md me-2"></i><span class="fw-medium me-2">Contact:</span><span>{{ $user->phone_number ?? 'N/A' }}</span></li>
                                                        <li class="d-flex align-items-center mb-3"><i class="ti ti-brand-telegram ti-md me-2"></i><span class="fw-medium me-2">Telegram:</span> <span>{{ $user->telegram_chat_id ?? 'N/A' }}</span></li>
                                                        <li class="d-flex align-items-center mb-3"><i class="ti ti-mail ti-md me-2"></i><span class="fw-medium me-2">Email:</span><span>{{ $user->email ?? 'N/A' }}</span></li>
                                                    </ul>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <!-- Personal Details Card -->
                                                <div class="card card-body mb-4">
                                                    <h5 class="text-uppercase text-body-secondary small mb-3">Personal Details</h5>
                                                    <ul class="list-unstyled">
                                                        <li class="d-flex align-items-center mb-3"><i class="ti ti-calendar-event ti-md me-2"></i><span class="fw-medium me-2">Birthdate:</span>
                                                            <span>{{ $user->birthdate ? $user->birthdate->format('M d, Y') : 'N/A' }}</span>
                                                        </li>
                                                        <li class="d-flex align-items-center mb-3"><i class="ti ti-gender-male ti-md me-2"></i><span class="fw-medium me-2">Gender:</span> <span>{{ ucfirst($user->gender ?? 'N/A') }}</span></li>
                                                        <li class="d-flex align-items-start mb-3"><i class="ti ti-map-pin ti-md me-2 mt-1"></i>
                                                            <div>
                                                                <span class="fw-medium me-2">Address:</span>
                                                                <span>{{ $user->street ?? 'N/A' }}, {{ $user->city ?? '' }}<br>{{ $user->province ?? '' }}, {{ $user->zip_code ?? '' }}</span>
                                                            </div>
                                                        </li>
                                                    </ul>
                                                </div>
                                                <!-- Government IDs Card -->
                                                <div class="card card-body">
                                                    <h5 class="text-uppercase text-body-secondary small mb-3">Government IDs</h5>
                                                    <ul class="list-unstyled">
                                                        <li class="d-flex align-items-center mb-3"><i class="ti ti-id ti-md me-2"></i><span class="fw-medium me-2">SSS:</span> <span>{{ $user->sss_number ?? 'N/A' }}</span></li>
                                                        <li class="d-flex align-items-center mb-3"><i class="ti ti-id ti-md me-2"></i><span class="fw-medium me-2">PhilHealth:</span> <span>{{ $user->philhealth_number ?? 'N/A' }}</span></li>
                                                        <li class="d-flex align-items-center mb-3"><i class="ti ti-id ti-md me-2"></i><span class="fw-medium me-2">Pag-IBIG:</span> <span>{{ $user->pagibig_number ?? 'N/A' }}</span></li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Forms -->
                                    <div class="tab-pane fade" id="documents" role="tabpanel" aria-labelledby="documents-tab">
                                        @php
                                            $statuses = [
                                                'submitted' => ['title' => 'Submitted', 'color' => 'secondary', 'icon' => 'ti-inbox'],
                                                'processing' => ['title' => 'Processing', 'color' => 'info', 'icon' => 'ti-settings'],
                                                // Consolidate 'processed' and 'approved' into one column for clarity
                                                'processed' => ['title' => 'Processed / Approved', 'color' => 'success', 'icon' => 'ti-circle-check'],
                                                'rejected' => ['title' => 'Rejected', 'color' => 'danger', 'icon' => 'ti-circle-x'],
                                                'for-rescheduling' => ['title' => 'For Rescheduling', 'color' => 'warning', 'icon' => 'ti-calendar-time'],
                                            ];
                                        @endphp
                                        <small class="text-info">*Forms submitted by or for you are listed here.</small>
                                        <div id="kanban-board-container" class="d-flex flex-nowrap overflow-auto py-3" data-user-id="{{ $user->id }}">
                                            @foreach($statuses as $status_key => $status_info)
                                                <!-- Kanban Column: {{ $status_info['title'] }} -->
                                                <div class="kanban-lane card me-4" style="min-width: 320px; width: 320px;">
                                                    <div class="card-header bg-label-{{ $status_info['color'] }} d-flex justify-content-between align-items-center">
                                                        <h6 class="card-title mb-0 text-{{ $status_info['color'] }}"><i class="ti {{ $status_info['icon'] }} me-2"></i>{{ $status_info['title'] }}</h6>
                                                        <span id="count-{{$status_key}}" class="badge bg-{{ $status_info['color'] }} rounded-pill">0</span>
                                                    </div>
                                                    <div id="lane-{{$status_key}}" class="card-body p-2 kanban-column" data-status="{{$status_key}}" style="max-height: 60vh; overflow-y: auto;">
                                                        {{-- Form cards will be injected here by JavaScript --}}
                                                        <div class="text-center text-muted p-5 kanban-loader">
                                                            <div class="spinner-border" role="status">
                                                                <span class="visually-hidden">Loading...</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                    <!-- Notifications -->
                                    <div class="tab-pane fade" id="notifications" role="tabpanel" aria-labelledby="notifications-tab">
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
                                    <div class="tab-pane fade" id="telegram" role="tabpanel" aria-labelledby="telegram-tab">
                                        <div class="text-center">
                                            @if(Auth::id() == $user->id)
                                                @if ($user->telegram_chat_id)
                                                    <div class="alert alert-success" role="alert"><i class="ti tabler-circle-check me-2 display-1"></i>
                                                        <h5 class="alert-heading">Account Linked!</h5>
                                                        <p class="mb-0">Your Telegram account is successfully linked to the system.</p>
                                                    </div>
                                                    <div class="row">
                                                        <h4>Telegram ID: {{ $user->telegram_chat_id }}</h4>
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
                                            @else
                                                @if ($user->telegram_chat_id)
                                                    <div class="alert alert-success" role="alert"><i class="ti tabler-circle-check me-2 display-1"></i>
                                                        <h5 class="alert-heading">Account Linked!</h5>
                                                        <p class="mb-0">The Telegram account is successfully linked to the system.</p>
                                                    </div>
                                                    <div class="row">
                                                        <h4>Telegram ID: {{ $user->telegram_chat_id }}</h4>
                                                    </div>
                                                @else
                                                    <div id="telegram-linking-section">
                                                        <div class="alert alert-warning" role="alert"><i class="ti tabler-circle-x me-2 display-1"></i>
                                                            <h5 class="alert-heading">Account Not Linked</h5>
                                                            <p class="mb-0">The Telegram account is not yet linked to the system.</p>
                                                        </div>
                                                    </div>
                                                @endif
                                            @endif
                                        </div>
                                    </div>
                                    <!-- Trainings Tab -->
                                    <div class="tab-pane fade" id="trainings" role="tabpanel" aria-labelledby="trainings-tab">
                                        @can(config("permit.add certificate.name"))
                                            <div class="card mb-7">
                                                <h5 class="card-header">Add New Training Certificate</h5>
                                                <div class="card-body">
                                                    <form id="add_training_certificate_form">
                                                        <input type="hidden" name="user_id" value="{{ $user->id }}">
                                                        <div class="row g-3">
                                                            <div class="col-md-6">
                                                                <label class="form-label" for="training_name">Training Name</label>
                                                                <input type="text" id="training_name" name="name" class="form-control" placeholder="e.g., CCTV Training Course" />
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label class="form-label" for="training_center">Training Center</label>
                                                                <input type="text" id="training_center" name="training_center" class="form-control" placeholder="e.g., ESIAI Training Center" />
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label class="form-label" for="date_of_training">Date of Training</label>
                                                                <input type="text" class="form-control flatpickr-date" id="date_of_training" name="date_of_training" placeholder="YYYY-MM-DD" />
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label for="certificate_file" class="form-label">Upload Certificate (Optional)</label>
                                                                <input class="form-control" type="file" id="certificate_file" name="certificate_file">
                                                            </div>
                                                            <div class="col-md-12">
                                                                <label for="training_tags" class="form-label">Tags</label>
                                                                <select id="training_tags" class="select2 form-select" name="tags[]" multiple>
                                                                    @foreach($all_tags as $tag)
                                                                        <option value="{{ $tag->name }}">{{ $tag->name }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="pt-7 d-flex justify-content-end">
                                                            <button type="submit" class="btn btn-primary">Add Certificate</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        @endcan
                                        <h5>Training Certificates</h5>
                                        <div class="table-responsive">
                                            <table class="table" id="training_certificates_table_body">
                                                <thead>
                                                <tr>
                                                    <th>Training Name</th>
                                                    <th>Training Center</th>
                                                    <th>Date</th>
                                                    <th>Tags</th>
                                                    <th>Certificate</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @forelse($user->trainingCertificates as $certificate)
                                                    <tr>
                                                        <td class="fw-bold">{{ $certificate->name }}</td>
                                                        <td>{{ $certificate->training_center }}</td>
                                                        <td>{{ Carbon::parse($certificate->date_of_training)->format('M d, Y') }}</td>
                                                        <td>
                                                            <button type="button" class="btn btn-sm btn-label-info view-tags-btn" data-certificate-name="{{ $certificate->name }}"
                                                                    data-tags="{{ json_encode($certificate->tags->pluck('name')) }}">View Tags
                                                            </button>
                                                        </td>
                                                        <td>
                                                            @if($certificate->certificate_path)
                                                                <button type="button" class="btn btn-sm btn-label-secondary view-certificate-btn"
                                                                        data-bs-toggle="modal" data-bs-target="#viewCertificateModal" data-certificate-url="{{ Storage::url($certificate->certificate_path) }}"
                                                                        data-certificate-name="{{ $certificate->name }}"
                                                                        data-file-type="{{ pathinfo($certificate->certificate_path, PATHINFO_EXTENSION) }}"
                                                                        data-can-print="{{ Auth::user()->can(config('permit.print certificate.name')) ? 'true' : 'false' }}">
                                                                    View
                                                                </button>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @empty

                                                @endforelse
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <!-- Files Tab -->
                                    <div class="tab-pane fade" id="files" role="tabpanel" aria-labelledby="files-tab">
                                        <div class="d-flex justify-content-between align-items-center mb-4">
                                            <h5 class="mb-0">User Files</h5>
                                            @can(config("permit.edit personnel.name"))
                                                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#uploadFileModal">
                                                    <i class="ti tabler-upload me-2"></i>Upload New File
                                                </button>
                                            @endcan
                                        </div>

                                        <!-- File Explorer Layout -->
                                        <div class="row" id="file_manager_app">
                                            <!-- Left Sidebar: Categories/Folders -->
                                            <div class="col-lg-2 col-md-4">
                                                <div class="card">
                                                    <div class="card-body">
                                                        <div class="list-group list-group-flush" id="file_category_list">
                                                            <a href="#" class="btn btn-label-dark text-nowrap d-inline-flex position-relative me-4 mb-1 active" data-category="all">
                                                                All Files
                                                                <span class="position-absolute top-0 start-100 translate-middle badge badge-center rounded-pill bg-primary text-white">0</span>
                                                            </a>
                                                            <a href="#" class="btn btn-label-info text-nowrap d-inline-flex position-relative me-4 mb-1" data-category="licenses">
                                                                Licenses
                                                                <span class="position-absolute top-0 start-100 translate-middle badge badge-center rounded-pill bg-info text-white">0</span>
                                                            </a>
                                                            <a href="#" class="btn btn-label-success text-nowrap d-inline-flex position-relative me-4 mb-1" data-category="documents">
                                                                Docs
                                                                <span class="position-absolute top-0 start-100 translate-middle badge badge-center rounded-pill bg-success text-white">0</span>
                                                            </a>
                                                            <a href="#" class="btn btn-label-warning text-nowrap d-inline-flex position-relative me-4 mb-1" data-category="images">
                                                                Images
                                                                <span class="position-absolute top-0 start-100 translate-middle badge badge-center rounded-pill bg-warning text-white">0</span>
                                                            </a>
                                                            <a href="#" class="btn btn-label-danger text-nowrap d-inline-flex position-relative me-4 mb-1" data-category="pdfs">
                                                                PDFs
                                                                <span class="position-absolute top-0 start-100 translate-middle badge badge-center rounded-pill bg-danger text-white">0</span>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Right Content Area: File Display -->
                                            <div class="col-lg-10 col-md-8">
                                                <!-- Header with Search and View Toggles -->
                                                <div class="d-flex justify-content-between align-items-center mb-3">
                                                    <div class="flex-grow-1 me-3">
                                                        <div class="input-group input-group-merge">
                                                            <span class="input-group-text"><i class="ti tabler-search"></i></span>
                                                            <input type="text" class="form-control" id="file_search_input" placeholder="Search files...">
                                                        </div>
                                                    </div>
                                                    <div class="btn-group" role="group" aria-label="View toggle">
                                                        <button type="button" class="btn btn-outline-secondary" id="file_view_grid_btn" data-bs-toggle="tooltip" title="Grid View"><i class="ti tabler-layout-grid"></i></button>
                                                        <button type="button" class="btn btn-outline-secondary active" id="file_view_list_btn" data-bs-toggle="tooltip" title="List View"><i class="ti tabler-list"></i></button>
                                                    </div>
                                                </div>

                                                <!-- File Display Area -->
                                                <div id="file_display_area">
                                                    {{-- This area will be populated by JavaScript --}}
                                                </div>
                                                <!-- Pagination Container -->
                                                <div class="d-flex justify-content-center mt-4">
                                                    <nav id="file_pagination_links"></nav>
                                                </div>

                                                <!-- "No Files" Message Placeholders -->
                                                <div id="no_files_in_category_message" class="text-center text-muted mt-5" style="display: none;">
                                                    <i class="ti tabler-folder-off tabler-lg mb-2"></i>
                                                    <h5>This category is empty</h5>
                                                    <p>Upload a file to get started.</p>
                                                </div>
                                                <div id="no_search_results_message" class="text-center text-muted mt-5" style="display: none;">
                                                    <i class="ti tabler-file-search tabler-lg mb-2"></i>
                                                    <h5>No files found</h5>
                                                    <p>Your search did not match any files.</p>
                                                </div>
                                            </div>
                                        </div>
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

    <!-- Upload File Modal -->
    <div class="modal fade" id="uploadFileModal" tabindex="-1" aria-labelledby="uploadFileModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="uploadFileModalLabel">Upload New File</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="upload_file_form">
                        <input type="hidden" name="user_id" value="{{ $user->id }}">
                        <div class="mb-3">
                            <label for="file_upload_input" class="form-label">File</label>
                            <input class="form-control" type="file" id="file_upload_input" name="files[]" required multiple>
                        </div>
                        <div class="mb-3">
                            <label for="file_category" class="form-label">Category</label>
                            <select class="form-control select2" id="file_category" name="category" required>
                                <option value="" selected disabled>Select a category...</option>
                                <option value="licenses">License</option>
                                <option value="documents">Document</option>
                                <option value="images">Image</option>
                                <option value="pdfs">PDF</option>
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" form="upload_file_form">Upload</button>
                </div>
            </div>
        </div>
    </div>

    <!-- File Preview Modal -->
    <div class="modal fade" id="filePreviewModal" tabindex="-1" aria-labelledby="filePreviewModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="filePreviewModalLabel">File Preview</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center p-0">
                    <div id="file_preview_container" style="height: 80vh;">
                        {{-- Preview content will be injected here by JavaScript --}}
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <a href="#" id="download_file_btn" class="btn btn-primary" download><i class="ti tabler-download me-2"></i>Download</a>
                    <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Hidden element to pass asset path to JS -->
    <div id="file_icon_path" data-path="{{ asset('assets/img/icons/misc/file-icon.png') }}" style="display: none;"></div>

    <!-- View Certificate Modal -->
    <div class="modal fade" id="viewCertificateModal" tabindex="-1" aria-labelledby="viewCertificateModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewCertificateModalLabel">Certificate</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <div id="certificate_viewer_container"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Close</button>
                    @can(config("permit.print certificate.name"))
                        <button type="button" class="btn btn-primary" id="print_certificate_btn" style="display: none;">
                            <i class="ti tabler-printer me-2"></i>Print
                        </button>
                    @endcan
                </div>
            </div>
        </div>
    </div>
@endsection

@push('page-script')
    <script>
        // This script will be replaced by the new logic in pages-profile.js
    </script>
@endpush
