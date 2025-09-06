@php
    use App\Enums\LeaveType;
    use App\Models\User;
@endphp

@extends('layouts/layoutMaster')

@section('title', 'Personnel Leave Application Form')

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
    // We can create a dedicated JS file for this form later if needed
    'resources/assets/js/forms-personnel-leave-application.js',
    ])
@endsection

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    @include('content.snippets.form_header')
                    <div class="card-body mt-5">
                        <form id="{{ strtolower(str_replace(' ', '-', $form_name) )}}" method="POST"
                              @can(config("permit.fill ".strtolower($form_name).".name"))  action="{{ route('forms.store', strtolower(str_replace(' ', '-', $form_name)) ) }}" @endcan>
                            @csrf
                            {{-- Leave Type --}}
                            <div class="mb-3">
                                <label for="leave_type" class="form-label">Type of Leave</label>
                                <select class="form-control select2" id="leave_type" name="leave_type" required {{ $submission ? 'disabled' : '' }}>
                                    {{-- We dynamically create options from our Enum. This is clean and powerful! --}}
                                    @foreach(LeaveType::cases() as $type)
                                        <option value="{{ $type->value }}" {{ ($submission && $form->leave_type == $type->value) || old('leave_type') == $type->value ? 'selected' : '' }}>
                                            {{ $type->label() }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('leave_type')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Leave Duration --}}
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="start_date" class="form-label">From</label>
                                    <input type="date" class="form-control" id="start_date" name="start_date" value="{{ $submission ? $form->start_date : old('start_date') }}"
                                           required $submission>
                                    @error('start_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="end_date" class="form-label">To</label>
                                    <input type="date" class="form-control" id="end_date" name="end_date" value="{{ $submission ? $form->end_date : old('end_date') }}"
                                           required $submission>
                                    @error('end_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            {{-- Purpose --}}
                            <div class="mb-3">
                                <label for="purpose" class="form-label">Purpose</label>
                                <textarea class="form-control" id="purpose" name="purpose" rows="3"
                                          required $submission>{{ $submission ? $form->purpose : old('purpose') }}</textarea>
                                @error('purpose')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Reliever --}}
                            <div class="mb-3">
                                <label for="reliever_id" class="form-label">Reliever Name</label>
                                <select class="form-control select2" id="reliever_id" name="reliever_id" {{ $submission ? 'disabled' : '' }} required>
                                    <option value="">Select a reliever</option>
                                    @if(isset($relievers))
                                        @foreach($relievers as $reliever)
                                            <option value="{{ $reliever->id }}"
                                                {{ ($submission && $form->reliever_id == $reliever->id) || old('reliever_id') == $reliever->id ? 'selected' : '' }}>
                                                {{ $reliever->name }}
                                            </option>
                                        @endforeach
                                    @elseif($submission && $form->reliever)
                                        <option value="{{ $form->reliever->id }}" selected>{{ $form->reliever->name }}</option>
                                    @endif
                                    <option value="2">Test Reliever</option>
                                </select>
                                @error('reliever_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <hr>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">Submit Form</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
