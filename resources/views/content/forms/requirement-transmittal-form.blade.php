@php use App\Models\User;use Carbon\Carbon; @endphp
@extends('layouts/layoutMaster')

@section('title', 'Recruitment Transmittal Form')

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
    'resources/assets/js/forms-requirement-transmittal-form.js',
    'resources/assets/js/extended-ui-sweetalert2.js',
    ])
@endsection

@section('content')

    @if($submission)
        <div class="row">
            <form @can(config("permit.edit ".$form_name.".name")) action="/forms/update/{{ str_replace(' ', '-', strtolower($submission->name)) }}/{{ $submission->id }}" method="put" id="requirement_transmittal_form" @endcan>
                @csrf
                <input type="hidden" value="{{ $submission->id }}" id="form_id" name="form_id">
                <div class="col-12">
                    <div class="card">
                        @include('content.snippets.form_header')
                        <div class="card-body pt-6">
                            <div class="row">
                                <div class="col-lg-9 mx-auto">
                                    <!-- 1. Employee Info -->
                                    <div class="row d-flex justify-content-around">
                                        <div class="col">
                                            <h5 class="mb-4">Employee Info</h5>
                                        </div>
                                        <div class="col">
                                            <!-- Display the submission date -->
                                            <h5 class="mb-4 text-end">{{ Carbon::parse($submission->created_at)->format('F d, Y') }}</h5>
                                        </div>
                                    </div>
                                    <div class="row g-6">
                                        <div class="col-md-12">
                                            <label class="form-label" for="employee_name">Full Name</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control" value="{{ $employee->first_name }}" placeholder="First Name" id="first_name" name="first_name" />
                                                <input type="text" class="form-control" value="{{ $employee->middle_name }}" placeholder="Middle Name" id="middle_name" name="middle_name" />
                                                <input type="text" class="form-control" value="{{ $employee->last_name }}" placeholder="Last Name" id="last_name" name="last_name" />
                                                <input type="text" class="form-control" value="{{ $employee->suffix ?? '' }}" placeholder="Suffix" id="suffix" name="suffix" />
                                                <select class="form-select" name="gender" id="gender">
                                                    <option value="male" @if($employee->gender == 'male') selected @endif>Male</option>
                                                    <option value="female" @if($employee->gender == 'female') selected @endif>Female</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <label class="form-label" for="employee_name">Address</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control" value="{{ $employee->street }}" placeholder="Street" id="street" name="street" />
                                                <input type="text" class="form-control" value="{{ $employee->city }}" placeholder="City" id="city" name="city" />
                                                <input type="text" class="form-control" value="{{ $employee->province }}" placeholder="Province" id="province" name="province" />
                                                <input type="text" class="form-control" value="{{ $employee->zip_code }}" placeholder="Zip Code" id="zip_code" name="zip_code" />

                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label" for="email">Email</label>
                                            <input class="form-control" type="email" aria-label="email" aria-describedby="email@example.com" value="{{ $employee->email }}" id="email" name="email" />
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label" for="phone_number">Phone No</label>
                                            <input class="form-control" type="number" aria-label="phone-number" aria-describedby="09xx xxxx xxx" value="{{ $employee->phone_number }}" id="phone_number" name="phone_number" />
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label" for="employee_number">Employee No</label>
                                            <input class="form-control" type="text" aria-label="ID1234" aria-describedby="ID1234" value="{{ $employee->employee_number }}" id="employee_number" name="employee_number" />
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label" for="detachment_id">Deployment</label>
                                            <select id="detachment_id"
                                                    name="detachment_id"
                                                    class="form-select select2 w-100">
                                                <option value="" selected disabled>Choose an item</option>
                                                @forelse($detachments as $item)

                                                    <option value="{{ $item->id }}" @if($employee->detachment_id == $item->id) selected @endif>{{ $item->name }}</option>
                                                @empty
                                                @endforelse
                                            </select>
                                        </div>
                                    </div>
                                    <hr />
                                    <div class="row">
                                        <div class="col">
                                            <h5 class="mb-4">Requirements</h5>
                                        </div>
                                    </div>
                                    <div class="row g-6">
                                        <div class="col-md-12">
                                            <table class="table table-bordered table-sm">
                                                <thead>
                                                <tr class="text-center">
                                                    <th>Requirements</th>
                                                    <th>Expiration</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @foreach(config('form-requirements.requirement-transmittal-form') as $index => $requirement)
                                                    @php
                                                        $base_name = $requirement['name'];
                                                        $original_name = 'original_' . $base_name;
                                                        $photocopy_name = 'photocopy_' . $base_name;
                                                        $exp_date_name = 'exp_' . $base_name;
                                                    @endphp
                                                    <tr>
                                                        <td>
                                                            <div class="row mb-2">
                                                                <div class="col-12 fw-bold">
                                                                    {{ $index + 1 }}. {{ $requirement['label'] }}
                                                                </div>
                                                            </div>
                                                            @if(isset($requirement['sub_options']))
                                                                @foreach($requirement['sub_options'] as $sub_name => $sub_label)
                                                                    <input class="form-check-input ms-5" type="checkbox" id="{{ $sub_name }}" name="{{ $sub_name }}" @if($submission->$sub_name ?? false) checked @endif>
                                                                    <label class="me-2" for="{{ $sub_name }}">{{ $sub_label }}</label>
                                                                @endforeach
                                                                <br>
                                                            @endif

                                                            <input class="form-check-input ms-5" type="checkbox" id="{{ $original_name }}" name="{{ $original_name }}" @if($submission->$original_name ?? false) checked @endif>
                                                            <label class="me-2" for="{{ $original_name }}">Original</label>

                                                            <input class="form-check-input ms-5" type="checkbox" id="{{ $photocopy_name }}" name="{{ $photocopy_name }}" @if($submission->$photocopy_name ?? false) checked @endif>
                                                            <label class="me-2" for="{{ $photocopy_name }}">Photocopy</label>
                                                        </td>
                                                        <td>
                                                            @if($requirement['has_expiration'])
                                                                <input type="date" placeholder="Month day, Year" class="form-control exp_date text-center" id="{{ $exp_date_name }}" name="{{ $exp_date_name }}"
                                                                       value="{{ $submission->$exp_date_name ?? '' }}" />
                                                            @else
                                                                <div class="bg-light" style="height: 38px;"></div>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-6 text-center mt-5 mb-5">
                                        <p>Submitted By:</p>
                                        <!-- Populate the submitted by section with the submission's user and submission date -->
                                        <h5 class="mb-0 fw-bolder">{{ $employee->first_name }} {{ $employee->last_name }} {{ $employee->suffix ?? '' }}</h5>
                                        <p>{{ ucwords($employee->getRoleNames()[0]) }} <br> {{ Carbon::parse($submission->created_at)->format('F d, Y') }}</p>
                                    </div>
                                    <div class="col-6 text-center mt-5 mb-5">
                                        <p>Released By:</p>
                                        <!-- Populate the submitted by section with the submission's user and submission date -->
                                        <h5 class="mb-0 fw-bolder border-bottom border-2 border-gray w-50 m-auto mb-1 mt-5"></h5>
                                        <p>HR Department <br> Date: <span class="w-px-200 border-bottom border-gray border-2 text-center">&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;</span>
                                    </div>
                                    <div class="col-6 text-center mt-5">
                                        <p>Received By:</p>
                                        <!-- Populate the submitted by section with the submission's user and submission date -->
                                        <h5 class="mb-0 fw-bolder">{{ $user->first_name ?? ''}} {{ $user->last_name ?? ''}} {{ $user->suffix ?? '' }}</h5>
                                        <p>{{ ucwords($user->getRoleNames()[0] ?? '') }} <br> {{ Carbon::parse($submission->created_at)->format('F d, Y') }}</p>
                                    </div>
                                    <div class="col-6 text-center mt-5">
                                        <p>Received By:</p>
                                        <!-- Populate the submitted by section with the submission's user and submission date -->
                                        <h5 class="mb-0 fw-bolder">{{ $employee->first_name }} {{ $employee->last_name }} {{ $employee->suffix ?? '' }}</h5>
                                        <p>{{ ucwords($employee->getRoleNames()[0]) }} <br> Date: <span class="w-px-200 border-bottom border-gray border-2 text-center">&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;</span>
                                        </p>
                                    </div>
                                    @can(config("permit.edit requirement transmittal form.name"))
                                        <div class="d-grid d-md-flex justify-content-md-end gap-2 mb-5">
                                            <button type="submit" class="btn btn-primary">Update Form</button>
                                        </div>
                                    @endcan
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="row">
            <form action="/form/requirement-transmittal-form/approve" id="approval_form" method="patch">
                @csrf
                <input type="hidden" id="form_id" value="{{ $submission->id }}">
                <input type="hidden" id="form_type" value="{{ $submission->name }}">
                <div class="card">
                    <div class="card-body pt-6">
                        <div class="row g-6">
                            <div class="col-md-12">
                                <div class="col">
                                    <h5>REMARKS</h5>
                                </div>
                                <div class="row">
                                    <div class="col-md mb-md-0 mb-2">
                                        <div class="form-check custom-option custom-option-basic">
                                            <label class="form-check-label custom-option-content" for="complete_requirements">
                                                <input class="form-check-input" type="checkbox" id="complete_requirements" name="complete_requirements" @if($submission->complete_requirements ?? false) checked @endif />
                                                <span class="custom-option-header">
                                    <span class="h6 mb-0">Completed Requirements</span>
                                  </span>
                                                <span class="custom-option-body">
                                    <small class="option-text">Employee have satisfied the requirements.</small>
                                  </span>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md mb-md-0 mb-2">
                                        <div class="form-check custom-option custom-option-basic">
                                            <label class="form-check-label custom-option-content" for="qualified_for_loan">
                                                <input class="form-check-input" type="checkbox" id="qualified_for_loan" name="qualified_for_loan" @if($submission->qualified_for_loan ?? false) checked @endif />
                                                <span class="custom-option-header">
                                    <span class="h6 mb-0">Qualified for ESIAI Loan</span>
                                  </span>
                                                <span class="custom-option-body">
                                    <small>Employee can request ESIAI Loan</small>
                                  </span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @can(config("permit.edit requirement transmittal form.name"))
                                <div class="d-grid d-md-flex justify-content-md-end gap-2 mb-5">
                                    <button type="submit" class="btn btn-primary" id="approve_button">Update Remarks</button>
                                </div>
                            @endcan
                        </div>
                    </div>
                </div>
            </form>
        </div>

    @else
        <div class="row">
            <form action="{{ route('forms.store','requirement-transmittal-form') }}" method="post" id="requirement_transmittal_form">
                @csrf
                <div class="col-12">
                    <div class="card">
                        @include('content.snippets.form_header')
                        <div class="card-body pt-6">
                            <div class="row">
                                <div class="col-lg-9 mx-auto">
                                    <!-- 1. Delivery Address -->
                                    <div class="row d-flex justify-content-around">
                                        <div class="col">
                                            <h5 class="mb-4">Employee Info</h5>
                                        </div>
                                        <div class="col">
                                            <h5 class="mb-4 text-end">{{ today()->format('F d, Y') }}</h5>
                                        </div>
                                    </div>
                                    <div class="row g-6">
                                        <div class="col-md-12">
                                            <label class="form-label" for="employee_name">Full Name & Gender</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control" value="" placeholder="First Name" id="first_name" name="first_name" />
                                                <input type="text" class="form-control" value="" placeholder="Middle Name" id="middle_name" name="middle_name" />
                                                <input type="text" class="form-control" value="" placeholder="Last Name" id="last_name" name="last_name" />
                                                <input type="text" class="form-control" value="" placeholder="Suffix" id="suffix" name="suffix" />
                                                <select class="form-select" name="gender" id="gender">
                                                    <option value="male">Male</option>
                                                    <option value="female">Female</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <label class="form-label" for="employee_name">Address</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control" value="" placeholder="Street" id="street" name="street" />
                                                <input type="text" class="form-control" value="" placeholder="City" id="city" name="city" />
                                                <input type="text" class="form-control" value="" placeholder="Province" id="province" name="province" />
                                                <input type="text" class="form-control" value="" placeholder="Zip Code" id="zip_code" name="zip_code" />
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label" for="email">Email</label>
                                            <input class="form-control" type="email" aria-label="email" aria-describedby="email@example.com" value="" id="email" name="email" />
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label" for="phone_number">Phone No</label>
                                            <input class="form-control" type="number" aria-label="phone-number" aria-describedby="09xx xxxx xxx" value="" id="phone_number" name="phone_number" />
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label" for="employee_number">Employee No</label>
                                            <input class="form-control" type="text" aria-label="ID1234" aria-describedby="ID1234" value="" id="employee_number" name="employee_number" />
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label" for="detachment_id">Deployment</label>
                                            <select id="detachment_id"
                                                    name="detachment_id"
                                                    class="form-select select2 w-100">
                                                <option value="" selected disabled>Choose an item</option>
                                                @forelse($detachments as $item)
                                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                                @empty
                                                @endforelse
                                            </select>
                                        </div>
                                    </div>
                                    <hr />
                                    <div class="row">
                                        <div class="col">
                                            <h5 class="mb-4">Requirements</h5>
                                        </div>
                                    </div>
                                    <div class="row g-6">
                                        <div class="col-md-12">
                                            <table class="table table-bordered table-sm">
                                                <thead>
                                                <tr class="text-center">
                                                    <th>Requirements</th>
                                                    <th>Expiration</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @foreach(config('form-requirements.requirement-transmittal-form') as $index => $requirement)
                                                    @php
                                                        $base_name = $requirement['name'];
                                                        $original_name = 'original_' . $base_name;
                                                        $photocopy_name = 'photocopy_' . $base_name;
                                                        $exp_date_name = 'exp_' . $base_name;
                                                    @endphp
                                                    <tr>
                                                        <td>
                                                            <div class="row mb-2">
                                                                <div class="col-12 fw-bold">
                                                                    {{ $index + 1 }}. {{ $requirement['label'] }}
                                                                </div>
                                                            </div>
                                                            @if(isset($requirement['sub_options']))
                                                                @foreach($requirement['sub_options'] as $sub_name => $sub_label)
                                                                    <input class="form-check-input ms-5" type="checkbox" id="{{ $sub_name }}" name="{{ $sub_name }}">
                                                                    <label class="me-2" for="{{ $sub_name }}">{{ $sub_label }}</label>
                                                                @endforeach
                                                                <br>
                                                            @endif

                                                            <input class="form-check-input ms-5" type="checkbox" id="{{ $original_name }}" name="{{ $original_name }}">
                                                            <label class="me-2" for="{{ $original_name }}">Original</label>

                                                            <input class="form-check-input ms-5" type="checkbox" id="{{ $photocopy_name }}" name="{{ $photocopy_name }}">
                                                            <label class="me-2" for="{{ $photocopy_name }}">Photocopy</label>
                                                        </td>
                                                        <td>
                                                            @if($requirement['has_expiration'])
                                                                <input type="date" placeholder="Month day, Year" class="form-control exp_date text-center" id="{{ $exp_date_name }}" name="{{ $exp_date_name }}" />
                                                            @else
                                                                <div class="bg-light" style="height: 38px;"></div>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="row g-6 mt-5">
                                        {{--                                        <div class="col-md-12">
                                                                                    <div class="alert alert-danger" role="alert">
                                                                                        <i class="icon-base ti tabler-alert-square-rounded icon-md me-1"></i>
                                                                                        This document is a system-generated form. By submitting this form, you acknowledge and agree that all relevant information, including your employee ID, will be recorded for processing and
                                                                                        record-keeping purposes.
                                                                                    </div>
                                                                                </div>--}}
                                        <div class="d-grid d-md-flex justify-content-md-end gap-2 mb-5">
                                            <button type="submit" class="btn btn-primary">Submit Form</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    @endif
@endsection
