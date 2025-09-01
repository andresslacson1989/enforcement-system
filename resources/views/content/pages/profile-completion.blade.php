@extends('layouts/blankLayout')

@section('title', 'Complete Your Profile')

<!-- Vendor Styles -->
@section('vendor-style')
    @vite([
    'resources/assets/vendor/libs/select2/select2.scss',
    'resources/assets/vendor/libs/sweetalert2/sweetalert2.scss',
    'resources/assets/vendor/libs/flatpickr/flatpickr.scss',
    ])
@endsection

<!-- Vendor Scripts -->
@section('vendor-script')
    @vite([
    'resources/assets/vendor/libs/jquery/jquery.js',
    'resources/assets/vendor/libs/select2/select2.js',
    'resources/assets/vendor/libs/sweetalert2/sweetalert2.js',
    'resources/assets/vendor/libs/flatpickr/flatpickr.js',
    ])
@endsection

<!-- Page Scripts -->
@section('page-script')
    @vite([
    'resources/assets/js/pages-profile-completion.js',
    ])
@endsection

@section('content')
    <div class="container-tight py-4">
        <div class="card card-md">
            <div class="card-body">
                <h2 class="h2 text-center mb-4">Complete Your Profile</h2>
                <p class="text-muted mb-4">
                    To ensure we have all the necessary information, please complete your profile. This is a one-time process.
                </p>

                @if(session('warning'))
                    <div class="alert alert-warning" role="alert">
                        <div class="d-flex">
                            <div>
                                <h4 class="alert-title">Action Required</h4>
                                <div class="text-muted">{{ session('warning') }}</div>
                            </div>
                        </div>
                    </div>
                @endif

                <form id="profile_completion_form" action="{{ route('profile.completion.submit') }}" method="POST" novalidate>
                    @csrf
                    <h3 class="h4 my-4">Personal Information</h3>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">First Name</label>
                                <input type="text" class="form-control" name="first_name" value="{{ old('first_name', $user->first_name) }}">
                                <div class="invalid-feedback error-text"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Last Name</label>
                                <input type="text" class="form-control" name="last_name" value="{{ old('last_name', $user->last_name) }}">
                                <div class="invalid-feedback error-text"></div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Phone Number</label>
                                <input type="text" class="form-control" name="phone_number" value="{{ old('phone_number', $user->phone_number) }}">
                                <div class="invalid-feedback error-text"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Birth Date</label>
                                <input type="text" class="form-control flatpickr-date" name="birthdate" value="{{ old('birthdate', $user->birthdate ? $user->birthdate->format('Y-m-d') : '') }}">
                                <div class="invalid-feedback error-text"></div>
                            </div>
                        </div>
                    </div>

                    <h3 class="h4 my-4">Address</h3>
                    <div class="mb-3">
                        <label class="form-label">Street Address</label>
                        <input type="text" class="form-control" name="street" value="{{ old('street', $user->street) }}">
                        <div class="invalid-feedback error-text"></div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">City</label>
                                <input type="text" class="form-control" name="city" value="{{ old('city', $user->city) }}">
                                <div class="invalid-feedback error-text"></div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Province</label>
                                <input type="text" class="form-control" name="province" value="{{ old('province', $user->province) }}">
                                <div class="invalid-feedback error-text"></div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="mb-3">
                                <label class="form-label">ZIP Code</label>
                                <input type="text" class="form-control" name="zip_code" value="{{ old('zip_code', $user->zip_code) }}">
                                <div class="invalid-feedback error-text"></div>
                            </div>
                        </div>
                    </div>

                    <h3 class="h4 my-4">Government IDs</h3>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">SSS Number</label>
                                <input type="text" class="form-control" name="sss_number" value="{{ old('sss_number', $user->sss_number) }}">
                                <div class="invalid-feedback error-text"></div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">PhilHealth Number</label>
                                <input type="text" class="form-control" name="philhealth_number" value="{{ old('philhealth_number', $user->philhealth_number) }}">
                                <div class="invalid-feedback error-text"></div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Pag-IBIG Number</label>
                                <input type="text" class="form-control" name="pagibig_number" value="{{ old('pagibig_number', $user->pagibig_number) }}">
                                <div class="invalid-feedback error-text"></div>
                            </div>
                        </div>
                    </div>

                    <div class="form-footer mt-4">
                        <button type="submit" class="btn btn-primary w-100">Save and Continue</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
