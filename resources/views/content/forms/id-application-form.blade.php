@php use App\Models\User; @endphp
@extends('layouts/layoutMaster')

@section('title', 'Activity Board')

<!-- Vendor Styles -->
@section('vendor-style')
    @vite([
    'resources/assets/vendor/libs/select2/select2.scss',
    'resources/assets/vendor/libs/sweetalert2/sweetalert2.scss',
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
    'resources/assets/js/forms-id-application-form.js',
    'resources/assets/js/extended-ui-sweetalert2.js',
    ])
@endsection

@section('content')
    {{-- This single block now handles both CREATE and VIEW/EDIT modes --}}
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <form id="id_application_form"
                          action="{{ $submission ? route('forms.update', ['formSlug' => 'id-application-form', 'id' => $submission->id]) : route('forms.store', 'id-application-form') }}"
                          method="POST">
                        @include('content.snippets.form_header')
                        <div class="card-body">

                            @csrf
                            @php
                                // Define the employee object to use, whether creating or viewing
                                $employee = $submission->employee ?? auth()->user();
                            @endphp
                            @if($submission)
                                @method('PUT')
                            @endif

                            {{-- Employee Information --}}
                            <h5 class="mt-3">Employee Information Details</h5>
                            <hr>
                            <div class="mb-3">
                                <label for="name" class="form-label">Name</label>
                                <input type="text" class="form-control" id="name" name="name"
                                       value="{{ $employee->name }}" readonly>
                                {{-- Hidden field for employee_id is crucial for the backend --}}
                                <input type="hidden" name="employee_id" value="{{ $employee->id }}">
                            </div>
                            <div class="mb-3">
                                <label for="job_title" class="form-label">Title / Position</label>
                                <input type="text" class="form-control" id="job_title"
                                       value="{{ $employee->PrimaryRole->name ?? '' }}" readonly>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="sss_number" class="form-label">SSS#</label>
                                    <input type="text" class="form-control" id="sss_number"
                                           value="{{ $employee->sss_number }}" readonly>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="philhealth_number" class="form-label">PhilHealth#</label>
                                    <input type="text" class="form-control" id="philhealth_number"
                                           value="{{ $employee->philhealth_number }}" readonly>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="pagibig_number" class="form-label">Pag-IBIG#</label>
                                    <input type="text" class="form-control" id="pagibig_number"
                                           value="{{ $employee->pagibig_number }}" readonly>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="birth_date" class="form-label">Birth Date</label>
                                    <input type="text" class="form-control" id="birthdate"
                                           value="{{ $employee->birthdate ? $employee->birthdate->format('M d, Y') : '' }}" readonly>
                                </div>
                            </div>

                            {{-- Emergency Contact --}}
                            <h5 class="mt-4">Person to notify in case of emergency</h5>
                            <hr>
                            <div class="mb-3">
                                <label for="emergency_contact_name" class="form-label">Name</label>
                                <input type="text" class="form-control" id="emergency_contact_name" name="emergency_contact_name"
                                       value="{{ $submission->emergency_contact_name ?? old('emergency_contact_name') }}">
                                <div class="invalid-feedback error-text"></div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="emergency_contact_relation" class="form-label">Relation</label>
                                    <input type="text" class="form-control" id="emergency_contact_relation" name="emergency_contact_relation"
                                           value="{{ $submission->emergency_contact_relation ?? old('emergency_contact_relation') }}">
                                    <div class="invalid-feedback error-text"></div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="emergency_contact_number" class="form-label">Contact No.</label>
                                    <input type="tel" class="form-control" id="emergency_contact_number" name="emergency_contact_number"
                                           value="{{ $submission->emergency_contact_number ?? old('emergency_contact_number') }}">
                                    <div class="invalid-feedback error-text"></div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="emergency_contact_address" class="form-label">Address</label>
                                <textarea class="form-control" id="emergency_contact_address" name="emergency_contact_address" rows="2"
                                >{{ $submission->emergency_contact_address ?? old('emergency_contact_address') }}</textarea>
                                <div class="invalid-feedback error-text"></div>
                            </div>

                            {{-- HR Processing Section - Only show when viewing a submission --}}
                            @if($submission)
                                @can(config("permit.edit id application form.name"))
                                    <hr class="my-4">
                                    <h5 class="mt-4">HR Processing Section</h5>
                                    <p class="text-muted small">This section is intended for the HR Department to complete.</p>

                                    @php
                                        // Define a permission for who can edit this section
                                        $canProcessForm = auth()->user()->can('process id application form');
                                    @endphp

                                    <table class="table table-bordered text-center">
                                        <thead>
                                        <tr>
                                            <th>Complete Info</th>
                                            <th>With ID Picture</th>
                                            <th>For Filing</th>
                                            <th>Encoded</th>
                                            <th>Card Done</th>
                                            <th>Delivered</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <td><input class="form-check-input" type="checkbox" name="is_info_complete" {{ $submission->is_info_complete ? 'checked' : '' }} {{ !$canProcessForm ? 'disabled' : '' }}></td>
                                            <td><input class="form-check-input" type="checkbox" name="has_id_picture" {{ $submission->has_id_picture ? 'checked' : '' }} {{ !$canProcessForm ? 'disabled' : '' }}></td>
                                            <td><input class="form-check-input" type="checkbox" name="is_for_filing" {{ $submission->is_for_filing ? 'checked' : '' }} {{ !$canProcessForm ? 'disabled' : '' }}></td>
                                            <td><input class="form-check-input" type="checkbox" name="is_encoded" {{ $submission->is_encoded ? 'checked' : '' }} {{ !$canProcessForm ? 'disabled' : '' }}></td>
                                            <td><input class="form-check-input" type="checkbox" name="is_card_done" {{ $submission->is_card_done ? 'checked' : '' }} {{ !$canProcessForm ? 'disabled' : '' }}></td>
                                            <td><input class="form-check-input" type="checkbox" name="is_delivered" {{ $submission->is_delivered ? 'checked' : '' }} {{ !$canProcessForm ? 'disabled' : '' }}></td>
                                        </tr>
                                        </tbody>
                                    </table>

                                    <div class="row mt-4">
                                        <div class="col-md-6">
                                            <label class="form-label">Completed by:</label>
                                            <div class="form-control-plaintext fw-bold">{{ $submission->completedBy->name ?? 'N/A' }}</div>
                                            <small class="text-muted">Printed name & Signature</small>
                                        </div>
                                    </div>
                                @endcan
                            @endif

                            <div class="d-grid">
                                <button type="submit" id="submitBtn" class="btn btn-primary">Submit Application</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
